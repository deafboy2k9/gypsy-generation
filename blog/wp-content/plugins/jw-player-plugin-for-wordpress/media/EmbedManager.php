<?php
/**
 * @file Definition of the JW Playlist Manager.
 */

global $jw_query;
global $p_items;

/**
 * This file contains the necessary methods for rendering the Playlist Manager
 * tab in the WordPress media popup.  The code is largely borrowed from the
 * WordPress Gallery with necessary modifications for managing playlists and
 * showing all uploaded media.
 * @global string $redir_tab Global reference to the tab to redirect to on
 * submit.
 * @global string $type Global reference to the type of content being managed.
 * @param undefined $errors List of any errors encountered.
 */
function media_jwplayer_insert_form($errors) {
  global $redir_tab, $type, $jw_query, $p_items;

  $args = array(
    'post_parent' => null,
    'posts_per_page' => 10,
    'paged' => isset($_GET['paged']) ? $_GET['paged'] : 1,
    'post_status' => 'inherit',
    'post_type' => 'attachment',
    'orderby' => 'title ASC, ID',
    'order' => 'DESC'
  );
  $jw_query = new WP_Query($args);

  $redir_tab = 'jwplayer';
  media_upload_header();

  $post_id = intval($_REQUEST['post_id']);
  $form_action_url = admin_url("media-upload.php?type=$type&tab=jwplayer&post_id=$post_id");
  $form_action_url = apply_filters('media_upload_form_url', $form_action_url, $type);
  $playlists = jwplayer_get_playlists();

  $new_playlist_id = -1;
  if (isset($_POST[LONGTAIL_KEY . "playlist_create"]) || isset($_POST["save"])) {
    $post_title = $_POST[LONGTAIL_KEY . "playlist_name"];
    $new_playlist = array();
    $new_playlist["post_title"] = $post_title;
    $new_playlist["post_type"] = "jw_playlist";
    $new_playlist["post_status"] = null;
    $new_playlist["post_parent"] = null;
    if (isset($_POST["save"])) {
      $new_playlist_id = isset($_POST[LONGTAIL_KEY . "playlist_select"]) ? $_POST[LONGTAIL_KEY . "playlist_select"] : $playlists[0]->ID;
    } else {
      $new_playlist_id = wp_insert_post($new_playlist);
      $playlists = jwplayer_get_playlists();
    }
    $current_playlist = $new_playlist_id;
  } else if (isset($_POST["delete"])) {
    wp_delete_post($_POST[LONGTAIL_KEY . "playlist_select"]);
    $playlists = jwplayer_get_playlists();
    $current_playlist = $playlists[0]->ID;
  }

  if (!isset($current_playlist)) {
    if (isset($_POST[LONGTAIL_KEY . "playlist_select"])) {
      $current_playlist = $_POST[LONGTAIL_KEY . "playlist_select"];
    } else if (isset($_GET["playlist"])) {
      $current_playlist = $_GET["playlist"];
    } else {
      $current_playlist = $playlists[0]->ID;
    }
  }

  if (isset($_GET["p_items"])) {
    $p_items = json_decode(str_replace("\\", "", $_GET["p_items"]));
  } else if (isset($_POST["playlist_items"]) && $_POST["old_playlist"] == $current_playlist) {
    $p_items = json_decode(str_replace("\\", "", $_POST["playlist_items"]));
  } else {
    $p_items = explode(",", get_post_meta($current_playlist, LONGTAIL_KEY. "playlist_items", true));
  }

  update_post_meta($new_playlist_id, LONGTAIL_KEY . "playlist_items", implode(",", $p_items));

  echo '<link rel="stylesheet" href="'. WP_PLUGIN_URL . '/' . plugin_basename( dirname(dirname(__FILE__)) ).'/' . 'css/playlist.css" type="text/css" media="print, projection, screen" />'."\n";
  echo '<script type=text/javascript src="' . WP_PLUGIN_URL . '/' . plugin_basename( dirname(__FILE__) ).'/' . 'js/playlist.js"></script>'."\n"
?>

<script type="text/javascript">

  jQuery(function($){
    var desc = false;
    var preloaded = $(".media-item.preloaded");
    if ( preloaded.length > 0 ) {
      preloaded.each(function(){prepareMediaItem({id:this.id.replace(/[^0-9]/g, '')},'');});
      updateMediaForm();
    }
    var playlistPreloaded = $(".playlist-item.preloaded");
    if ( playlistPreloaded.length > 0 ) {
      playlistPreloaded.each(function(){preparePlaylistItem({id:this.id.replace(/[^0-9]/g, '')},'');});
      updatePlaylistForm();
    }
    preloaded.removeClass("open");
    $(".startclosed").hide();
    $('#playlist-items').sortable( {
			items: 'div.playlist-item',
			placeholder: 'sorthelper',
			axis: 'y',
			distance: 2,
			handle: 'div.filename',
			stop: function(e, ui) {
				// When an update has occurred, adjust the order for each item
        var item_list = document.getElementById("playlist_items");
        var p_items = new Array();
        var old_p_items =  eval('(' + item_list.value + ')');
        if (old_p_items[0] == "") {old_p_items = new Array();}
				var all = $('#playlist-items').sortable('toArray'), len = all.length;
				$.each(all, function(i, id) {
					var order = desc ? (len - i) : (1 + i);
					$('#' + id + ' .menu_order input').val(order);
          p_items.push(id.replace("playlist-item-", ""));
				});
        update_page_numbers(p_items, old_p_items);
        document.getElementById("playlist_items").value = dump(p_items);
			}
		} );
  });

  function insertPlaylist() {
    var s;
    var playlist_dropdown = document.getElementById("<?php echo LONGTAIL_KEY . "playlist_select"; ?>");
    var player_dropdown = document.getElementById("<?php echo LONGTAIL_KEY . "player_select"; ?>");
    s = "[jwplayer ";
    if (player_dropdown.value != "Default") {
      s += "config=\"" + player_dropdown.value + "\" ";
    }
    s += "playlistid=\"" + playlist_dropdown.value + "\"]";
    getJWWin().send_to_editor(s);
    return;
  }

  function getJWWin() {
    return window.dialogArguments || opener || parent || top;
  }

  function deletePlaylistHandler() {
    return confirm("Are you sure wish to delete the Playlist?");
  }

  function createPlaylistHandler() {
    var playlistName = document.forms[0]["<?php echo LONGTAIL_KEY . "playlist_name"; ?>"];
    if (playlistName.value == "") {
      alert("Your playlist must have a valid name.");
      return false;
    }
    return true;
  }

  function updatePlaylist(object) {
    var item_list = document.getElementById("playlist_items");
    var p_items = eval('(' + item_list.value + ')');
    if (p_items[0] == "") {p_items = new Array();}
    var old_p_items =  eval('(' + item_list.value + ')');
    if (old_p_items[0] == "") {old_p_items = new Array();}
    var playlist_check = object.id.indexOf("playlist_") > -1;
    var attachment_id = "";
    attachment_id = playlist_check ? object.id.replace("playlist_", "") : object.id;
    attachment_id = attachment_id.replace("attachments[", "").replace("][enabled]", "");
    if (object != null && object.checked === true) {
      p_items.push(attachment_id);
      update_checks(attachment_id, playlist_check, true);
    } else {
      var i = 0;
      for (i=0; i < p_items.length; i++) {
        if (p_items[i] == attachment_id) {
          break;
        }
      }
      update_checks(attachment_id, playlist_check, false);
      p_items.splice(i, 1);
    }
    update_page_numbers(p_items, old_p_items);
    item_list.value = dump(p_items);
  }

  function update_page_numbers(p_items, old_p_items) {
    var pages = jQuery(".page-numbers");
    var j = 0;
    for (j = 0; j < pages.length; j++) {
      var page = pages[j];
      if (page.href) {
        page.href = page.href.replace(encodeURI("&p_items=" + dump(old_p_items)), "");
        page.href = page.href + encodeURI("&p_items=" + dump(p_items));
      }
    }
  }

  function update_checks(attachment_id, playlist_check, new_state) {
    if (playlist_check) {
      var targetBox = document.getElementById("attachments[" + attachment_id + "][enabled]");
      if (targetBox != null) {
        targetBox.checked = new_state;
      }
    }
    if (new_state) {
      var itemToAdd = jQuery("#media-item-" + attachment_id).clone();
      itemToAdd[0].id = itemToAdd[0].id.replace("media", "playlist");
      itemToAdd[0].className = itemToAdd[0].className.replace("media", "playlist");
      var input = itemToAdd.children(".menu_order").children(".menu_order_input")[0];
      input.id = "playlist_" + input.id
      input.name = "playlist_" + input.name;
      if (input != null) input.checked = true;
      itemToAdd.appendTo("#playlist-items");
    } else {
      jQuery("#playlist-item-" + attachment_id).empty().remove();
    }
  }

</script>

<form enctype="multipart/form-data" method="post" action="<?php echo esc_attr($form_action_url); ?>" class="media-upload-form validate" id="playlist-form" style="width: 626px;">
  <?php wp_nonce_field('media-form'); ?>
  <?php //media_upload_form( $errors ); ?>
  <div class="tablenav" style="width: 626px;">
    <?php
      $page_links = paginate_links( array(
        'base' => add_query_arg( 'paged', '%#%' ),
        'format' => '',
        'prev_text' => __('&laquo;'),
        'next_text' => __('&raquo;'),
        'total' => ceil($jw_query->found_posts / 10),
        'current' => isset($_GET['paged']) ? $_GET['paged'] : 1,
        'add_args' => array('playlist' => $current_playlist)
      ));

      if ( $page_links ) { ?>
        <div class='tablenav-pages'>
          <span style="font-size: 13px;"><?php _e("Available Media:"); ?></span>
          <?php echo $page_links; ?>
        </div>
      <?php }?>
    <div class="alignleft actions">
      <div class="hide-if-no-js">
        <?php _e("Playlist:"); ?>
        <select onchange="this.form.submit()" id="<?php echo LONGTAIL_KEY . "playlist_select"; ?>" name="<?php echo LONGTAIL_KEY . "playlist_select"; ?>">
          <?php foreach ($playlists as $playlist_list) { ?>
            <option value="<?php echo $playlist_list->ID; ?>" <?php selected($playlist_list->ID, $current_playlist); ?>>
              <?php echo $playlist_list->post_title; ?>
            </option>
          <?php } ?>
        </select>
        <input type="submit" class="button savebutton" name="save" id="save-all" value="<?php esc_attr_e( 'Save' ); ?>" />
        <input type="submit" class="button savebutton" name="delete" id="delete-all" value="<?php esc_attr_e( 'Delete' ); ?>" onclick="return deletePlaylistHandler()" />
        <input type="hidden" name="post_id" id="post_id" value="<?php echo (int) $post_id; ?>" />
        <input type="hidden" name="type" value="<?php echo esc_attr( $GLOBALS['type'] ); ?>" />
        <input type="hidden" name="tab" value="<?php echo esc_attr( $GLOBALS['tab'] ); ?>" />
        <input type="hidden" id="playlist_items" name="playlist_items" value='<?php echo json_encode($p_items); ?>' />
        <input type="hidden" id="old_playlist" name="old_playlist" value="<?php echo $current_playlist; ?>" />
      </div>
    </div>
  </div>

  <div id="playlist-items" style="width: 300px; float: left;" class="ui-sortable">
    <?php echo get_jw_playlist_items($post_id, $errors, $current_playlist); ?>
  </div>

  <div id="media-items" style="width: 300px; float: right;">
    <?php add_filter('attachment_fields_to_edit', 'media_post_single_attachment_fields_to_edit', 10, 2); ?>
    <?php echo get_playlist_items($post_id, $errors, $current_playlist); ?>
  </div>

  <div class="clear"></div>

  <p class="ml-submit">
    <?php _e("New Playlist:"); ?>
    <input type="text" value="" name="<?php echo LONGTAIL_KEY . "playlist_name"; ?>" />
    <input type="submit" class="button savebutton" style="" name="<?php echo LONGTAIL_KEY . "playlist_create"; ?>" id="<?php echo LONGTAIL_KEY . "playlist_create"; ?>" value="<?php esc_attr_e("Create Playlist"); ?>" onclick="return createPlaylistHandler()" />
  </p>
  <p class="ml-submit" style="padding: 0em 0;">
    <?php _e("Select Player:"); ?>
    <select name="<?php echo LONGTAIL_KEY . "player_select"; ?>" id="<?php echo LONGTAIL_KEY . "player_select"; ?>">
      <option value="Default">Default</option>
      <?php $configs = LongTailFramework::getConfigs(); ?>
      <?php if ($configs) { ?>
        <?php foreach ($configs as $config) { ?>
          <?php if ($config != "New Player") { ?>
            <option value="<?php echo $config; ?>"><?php echo $config; ?></option>
          <?php } ?>
        <?php } ?>
      <?php } ?>
    </select>
    <input type="button" class="button-primary" onmousedown="insertPlaylist();" name="insert-gallery" id="insert-gallery" value="<?php esc_attr_e( 'Insert Playlist' ); ?>" />
  </p>
</form>
<?php
}

function get_jw_playlist_items($post_id, $errors, $current_playlist) {
  global $p_items;

  $playlist_items = get_children(array( 'post_parent' => null, 'post_type' => 'attachment', 'orderby' => 'menu_order ASC, ID', 'order' => 'DESC'));

  $attachments = array();
  foreach ($p_items as $p_item) {
    $attachments[$p_item] = isset($playlist_items[$p_item]) ? $playlist_items[$p_item] : "";
  }

  $output = '';
	foreach ( (array) $attachments as $id => $attachment ) {
		if ( !empty($attachment) && $attachment->post_status == 'trash' )
			continue;
		if ( $item = get_jw_playlist_item( $id, array( 'errors' => isset($errors[$id]) ? $errors[$id] : null), $current_playlist, "playlist_"))
			$output .= "\n<div id='playlist-item-$id' class='playlist-item child-of-$attachment->post_parent preloaded'><div id='playlist-upload-error-$id'></div><div class='filename'></div>$item\n</div>";
	}

	return $output;
}

/**
 * Get the list of playlist items to be displayed.  This list consists of
 * all media attachments in the system.
 * @param int $post_id The id of the current post.
 * @param undefined $errors List of any errors.
 * @param int $current_playlist The currently selected playlist.
 * @return string The HTML to render the playlist items.
 */
function get_playlist_items($post_id, $errors, $current_playlist) {
  global $jw_query;

  $playlist_items = array();
  while($jw_query->have_posts()) {
    $post = $jw_query->next_post();
    $playlist_items[$post->ID] = $post;
  }

  $playlist_item_ids = explode(",", get_post_meta($current_playlist, LONGTAIL_KEY. "playlist_items", true));
  $attachments = array();

  foreach ($playlist_items as $playlist_item) {
    $attachments[$playlist_item->ID] = $playlist_item;
  }

	$output = '';
	foreach ( (array) $attachments as $id => $attachment ) {
		if ( $attachment->post_status == 'trash' )
			continue;
		if ( $item = get_jw_playlist_item( $id, array( 'errors' => isset($errors[$id]) ? $errors[$id] : null), $current_playlist))
			$output .= "\n<div id='media-item-$id' class='media-item child-of-$attachment->post_parent preloaded'><div id='media-upload-error-$id'></div><div class='filename'></div>$item\n</div>";
	}

	return $output;
}

/**
 * Retrieves a sepecific playlist item.  In this case it is a media attachment.
 * @global string $redir_tab The tab to redirect to.
 * @param int $attachment_id The id of the attachment we are retrieving.
 * @param array $args Any additional arguments for query the database.
 * @param int $current_playlist The currently selected playlist.
 * @return string The HTML representing the playlist item.
 */
function get_jw_playlist_item($attachment_id, $args, $current_playlist, $prefix = "") {
	global $redir_tab, $p_items;

	if ( ( $attachment_id = intval($attachment_id) ) && $thumb_url = wp_get_attachment_image_src( $attachment_id, "thumbnail", true ) )
		$thumb_url = $thumb_url[0];
	else
		return false;

	$default_args = array( 'errors' => null, 'send' => true, 'delete' => true, 'toggle' => true, 'show_title' => true );
	$args = wp_parse_args( $args, $default_args );
	extract( $args, EXTR_SKIP );

	$post = get_post($attachment_id);

	$filename = basename($post->guid);
	$title = esc_attr($post->post_title);

	if ( $_tags = get_the_tags($attachment_id) ) {
		foreach ( $_tags as $tag )
			$tags[] = $tag->name;
		$tags = esc_attr(join(', ', $tags));
	}

	$post_mime_types = get_post_mime_types();
	$keys = array_keys(wp_match_mime_types(array_keys($post_mime_types), $post->post_mime_type));
	$type = array_shift($keys);
	$type_html = "<input type='hidden' id='type-of-$attachment_id' value='" . esc_attr( $type ) . "' />";

	$form_fields = get_attachment_fields_to_edit($post, $errors);

	$display_title = ( !empty( $title ) ) ? $title : $filename; // $title shouldn't ever be empty, but just in case
	$display_title = $show_title ? "<div class='filename new'><span class='title'>" . wp_html_excerpt($display_title, 60) . "</span></div>" : '';

	$gallery = ( (isset($_REQUEST['tab']) && 'gallery' == $_REQUEST['tab']) || (isset($redir_tab) && 'gallery' == $redir_tab) ) ? true : false;
	$order = '';

  $checked = "";
  foreach ($p_items as $playlist_item) {
    if ($playlist_item == $attachment_id) {
      $checked = "checked='true'";
      break;
    }
  }

	foreach ( $form_fields as $key => $val ) {
		if ( 'menu_order' == $key ) {
			if ( true ) {
				$order = '<div class="menu_order">';
        $order .= '<input class="menu_order_input" type="checkbox" id="'. $prefix . 'attachments['.$attachment_id.'][enabled]" name="' . $prefix . 'attachments['.$attachment_id.'][enabled]" value="'.$val['value'].'"'.$checked.' onclick="updatePlaylist(this);" /></div>';
      } else
				$order = '<input type="hidden" name="' . $prefix . 'attachments['.$attachment_id.'][menu_order]" value="'.$val['value'].'" />';

			unset($form_fields['menu_order']);
			break;
		}
	}

	$media_dims = '';
	$meta = wp_get_attachment_metadata($post->ID);
	if ( is_array($meta) && array_key_exists('width', $meta) && array_key_exists('height', $meta) )
		$media_dims .= "<span id='playlist-dims-{$post->ID}'>{$meta['width']}&nbsp;&times;&nbsp;{$meta['height']}</span> ";
	$media_dims = apply_filters('media_meta', $media_dims, $post);

	$image_edit_button = '';
	if ( gd_edit_image_support($post->post_mime_type) ) {
		$nonce = wp_create_nonce("image_editor-$post->ID");
		$image_edit_button = "<input type='button' id='imgedit-open-btn-{$post->ID}' onclick='imageEdit.open($post->ID, \"$nonce\")' class='button' value='" . esc_attr__( 'Edit image' ) . "' /> <img src='images/wpspin_light.gif' class='imgedit-wait-spin' alt='' />";
	}

  $toggle_links = "";
  $item = "
	$type_html
	$toggle_links
	$order
	$display_title
  <table class='slidetoggle describe startclosed'>
		<thead class='media-item-info' id='media-head-$post->ID'>
		<tr>
			<td class='A1B1' id='thumbnail-head-$post->ID' rowspan='5'><img class='thumbnail' src='$thumb_url' alt='' /></td>
			<td><strong>" . __('File name:') . "</strong> $filename</td>
		</tr>
		<tr><td><strong>" . __('File type:') . "</strong> $post->post_mime_type</td></tr>
		<tr><td><strong>" . __('Upload date:') . "</strong> " . mysql2date( get_option('date_format'), $post->post_date ) . "</td></tr>\n";

	if ( !empty($media_dims) )
		$item .= "<tr><td><strong>" . __('Dimensions:') . "</strong> $media_dims</td></tr>\n";

	$item .= "
		<tr><td class='A1B1'>$image_edit_button</td></tr>
		</thead>
		<tbody>
		<tr><td colspan='2' class='imgedit-response' id='imgedit-response-$post->ID'></td></tr>
		<tr><td style='display:none' colspan='2' class='image-editor' id='image-editor-$post->ID'></td></tr>\n";

	$defaults = array(
		'input'      => 'text',
		'required'   => false,
		'value'      => '',
		'extra_rows' => array(),
	);

	$thumbnail = '';
	$calling_post_id = 0;
	if ( isset( $_GET['post_id'] ) )
		$calling_post_id = $_GET['post_id'];
	elseif ( isset( $_POST ) && count( $_POST ) ) // Like for async-upload where $_GET['post_id'] isn't set
		$calling_post_id = $post->post_parent;
	if ( 'image' == $type && $calling_post_id && current_theme_supports( 'post-thumbnails', get_post_type( $calling_post_id ) ) && get_post_thumbnail_id( $calling_post_id ) != $attachment_id )
		$thumbnail = "<a class='wp-post-thumbnail' id='wp-post-thumbnail-" . $attachment_id . "' href='#' onclick='WPSetAsThumbnail(\"$attachment_id\");return false;'>" . esc_html__( "Use as thumbnail" ) . "</a>";

	if ( ( $send || $thumbnail || $delete ) && !isset($form_fields['buttons']) )
		$form_fields['buttons'] = array('tr' => "\t\t<tr class='submit'><td></td><td class='savesend'>$send $thumbnail $delete</td></tr>\n");

	$hidden_fields = array();

  foreach ( $form_fields as $id => $field ) {
		if ( $id{0} == '_' )
			continue;

		if ( !empty($field['tr']) ) {
			$item .= $field['tr'];
			continue;
		}

		$field = array_merge($defaults, $field);
		$name = "attachments[$attachment_id][$id]";

		if ( $field['input'] == 'hidden' ) {
			$hidden_fields[$name] = $field['value'];
			continue;
		}

		$required = $field['required'] ? '<abbr title="required" class="required">*</abbr>' : '';
		$aria_required = $field['required'] ? " aria-required='true' " : '';
		$class  = $id;
		$class .= $field['required'] ? ' form-required' : '';

		$item .= "\t\t<tr class='$class'>\n\t\t\t<th valign='top' scope='row' class='label'><label for='$name'><span class='alignleft'>{$field['label']}</span><span class='alignright'>$required</span><br class='clear' /></label></th>\n\t\t\t<td class='field'>";
		if ( !empty($field[$field['input']]) )
			$item .= $field[$field['input']];
		elseif ( $field['input'] == 'textarea' ) {
			$item .= "<textarea type='text' id='$name' name='$name'" . $aria_required . ">" . esc_html( $field['value'] ) . "</textarea>";
		} else {
			$item .= "<input type='text' class='text' id='$name' name='$name' value='" . esc_attr( $field['value'] ) . "'" . $aria_required . "/>";
		}
		if ( !empty($field['helps']) )
			$item .= "<p class='help'>" . join( "</p>\n<p class='help'>", array_unique((array) $field['helps']) ) . '</p>';
		$item .= "</td>\n\t\t</tr>\n";

		$extra_rows = array();

		if ( !empty($field['errors']) )
			foreach ( array_unique((array) $field['errors']) as $error )
				$extra_rows['error'][] = $error;

		if ( !empty($field['extra_rows']) )
			foreach ( $field['extra_rows'] as $class => $rows )
				foreach ( (array) $rows as $html )
					$extra_rows[$class][] = $html;

		foreach ( $extra_rows as $class => $rows )
			foreach ( $rows as $html )
				$item .= "\t\t<tr><td></td><td class='$class'>$html</td></tr>\n";
	}

	if ( !empty($form_fields['_final']) )
		$item .= "\t\t<tr class='final'><td colspan='2'>{$form_fields['_final']}</td></tr>\n";
	$item .= "\t</tbody>\n";
	$item .= "\t</table>\n";

//  $item .= "<img class='thumbnail hidden' src='$thumb_url' alt='' />";

	foreach ( $hidden_fields as $name => $value )
		$item .= "\t<input type='hidden' name='$name' id='$name' value='" . esc_attr( $value ) . "' />\n";

	if ( $post->post_parent < 1 && isset($_REQUEST['post_id']) ) {
		$parent = (int) $_REQUEST['post_id'];
		$parent_name = $prefix . "attachments[$attachment_id][post_parent]";

		$item .= "\t<input type='hidden' name='$parent_name' id='$parent_name' value='" . $parent . "' />\n";
	}

	return $item;
}

/**
 * Builds the argument array for retrieving the playlist type custom post.
 * @return array The arguments for retrieving the playlists.
 */
function jwplayer_get_playlists() {
  $playlist = array(
    "post_type" => "jw_playlist",
    "post_status" => null,
    "post_parent" => null,
    "nopaging" => true,
  );
  return query_posts($playlist);
}

?>