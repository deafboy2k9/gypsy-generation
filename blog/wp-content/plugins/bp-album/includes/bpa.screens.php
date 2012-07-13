<?php
session_start();
/********************************************************************************
 * Screen Functions
 *
 * Screen functions are the controllers of BuddyPress. They will execute when their
 * specific URL is caught. They will first save or manipulate data using business
 * functions, then pass on the user to a template file.
 */

/**
 * bp_album_screen_picture()
 *
 * Single picture
 * 
 * @version 0.1.8.11
 * @since 0.1.8.0
 */ 
 
 

function bp_album_screen_single() {

	global $bp,$pictures_template;
	
	if ( $bp->current_component == $bp->album->slug && $bp->album->single_slug == $bp->current_action && $pictures_template->picture_count && isset($bp->action_variables[1]) && $bp->album->edit_slug == $bp->action_variables[1]  ) {
	
		do_action( 'bp_album_screen_edit' );

		add_action( 'bp_template_title', 'bp_album_screen_edit_title' );
		add_action( 'bp_template_content', 'bp_album_screen_edit_content' );
	
		bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
		
		return;
	}
	
	do_action( 'bp_album_screen_single' );

	bp_album_query_pictures();
	bp_core_load_template( apply_filters( 'bp_album_template_screen_single', 'album/single' ) );
}

/**
 * bp_album_screen_edit_title()
 *
 * @version 0.1.8.11
 * @since 0.1.8.0
 */ 
function bp_album_screen_edit_title() {
	_e( 'Edit File', 'bp-album' );
}

/**
 * bp_album_screen_edit_content()
 *
 * @version 0.1.8.11
 * @since 0.1.8.0
*/

function bp_album_screen_edit_content() {

	global $bp,$pictures_template;

	if (bp_album_has_pictures() ) :  bp_album_the_picture();
	$limit_info = bp_album_limits_info();

	$priv_str = array(
		0 => __('Public','bp-album'),
		2 => __('Registered members','bp-album'),
		4 => __('Only friends','bp-album'),
		6 => __('Private','bp-album'),
		10 => __('Hidden (admin only)','bp-album')
	);
	
	?>
	<h4><?php _e( 'Edit File', 'bp-album' ) ?></h4>

	<form method="post" enctype="multipart/form-data" name="bp-album-edit-form" id="bp-album-edit-form" class="standard-form">
    <img id="picture-edit-thumb" src='<?php bp_album_picture_thumb_url() ?>'/>
    <p>
	<label><?php _e('File Title *', 'bp-album' ) ?><br />
	
	<?php if(isset($_SESSION['title']) && $_SESSION['title']!='') { ?>
	<input type="text" name="title" id="picture-title" size="100" value="<?php
		echo $_SESSION['title'];
	?>"/>
	<?php } else { ?>
	
	<input type="text" name="title" id="picture-title" size="100" value="<?php
		echo (empty($_POST['title'])) ? bp_album_get_picture_title() : wp_filter_kses($_POST['title']);
	?>"/>
	
	<?php } ?>
	</label>
    </p>
    
     <p>
	<label><?php _e('File Description', 'bp-album' ) ?><br />

	<textarea name="description" id="picture-description" rows="15"cols="40" ><?php
		if(isset($_SESSION['video_description']) && $_SESSION['video_description']!='')
		{
			echo $_SESSION['video_description'];
		}
		else
		{
			echo (empty($_POST['description'])) ? bp_album_get_picture_desc() : wp_filter_kses($_POST['description']);
		}
		?>
		</textarea></label>
    </p>
    
  	<br/><br/>
    <input type="submit" name="submit" id="submit" value="<?php _e( 'Save', 'bp-album' ) ?>"/>

		<?php
		// This is very important, don't leave it out. 
		wp_nonce_field( 'bp-album-edit' );
		?>
	</form>
	<?php else: ?>
		<p><?php _e( "Either this url is not valid or you can't edit this file.", 'bp-album' ) ?></p>
	<?php endif;
}

/**
 * bp_album_screen_pictures()
 *
 * An album page
 * 
 * @version 0.1.8.11
 * @since 0.1.8.0
 */
function bp_album_screen_pictures() {

	do_action( 'bp_album_screen_pictures' );

	bp_album_query_pictures();
	bp_core_load_template( apply_filters( 'bp_album_template_screen_pictures', 'album/pictures' ) );
}

/**
 * bp_album_screen_upload()
 *
 * Sets up and displays the screen output for the sub nav item "example/screen-two"
 * 
 * @version 0.1.8.11
 * @since 0.1.8.0
 */
function bp_album_screen_upload() {
    
	global $bp;

	do_action( 'bp_album_screen_upload' );

	add_action( 'bp_template_content', 'bp_album_screen_upload_content' );

	bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
}

/**
 * bp_album_screen_upload_title()
 *
 * @version 0.1.8.11
 * @since 0.1.8.0
 */
function bp_album_screen_upload_title() {
	_e( 'Upload new picture', 'bp-album' );
}

/**
 * bp_album_screen_upload_content()
 *
 * @version 0.1.8.11
 * @since 0.1.8.0
 */
function bp_album_screen_upload_content() {

	global $bp;

	$limit_info = bp_album_limits_info();

	$priv_str = array(
		0 => __('Public','bp-album'),
		2 => __('Registered members','bp-album'),
		4 => __('Only friends','bp-album'),
		6 => __('Private','bp-album'),
	);

	if( ($limit_info['all']['enabled'] == true) && ($limit_info['all']['remaining'] > 0) ):?>

	<h4><?php _e( 'Upload new file', 'bp-album' ) ?></h4>

	<form method="post" enctype="multipart/form-data" name="bp-album-upload-form" id="bp-album-upload-form" class="standard-form">

    <input type="hidden" name="upload" value="<?php echo $bp->album->bp_album_max_upload_size; ?>" />
    <input type="hidden" name="action" value="picture_upload" />
    
	<p>
		<label><?php _e('Title*', 'bp-album' ) ?><br />
		<input type="text" name="video-title" id="video-title" value=''/></label>
    </p>
  
    <p>    
	<label><?php _e('URL*', 'bp-album' ) ?><br/>
	<input type="text" name="upload_url_field" id="upload_url_field" value=''/></label>
    </p>
    
     <b>OR</b>
    
    <p>
	<label>	
	<?php _e('Enter Embed Url*', 'bp-album' ) ?><br/>
	<textarea name="embed_url" id="embed_url" rows="5" cols="5"></textarea></label>
    </p>
    
    <b>OR</b>

    <p>
	<label><?php _e('Select File to Upload*', 'bp-album' ) ?><br/>
	<input type="file" name="file" id="file" value=''/></label>
    </p>    
     <b>Allowed file types .png, .jpg, .gif, .mp3, .m4a, .oga, .mp4, .flv, .mov</b> 
     
     
    <p>
	<label><?php _e('Description', 'bp-album' ) ?><br />
	<textarea name="video-description" id="video-description" rows="15"cols="40" ></textarea>
    </label>
    </p>
        
     <br/>
    <br/>
   
    <input type="submit" name="submit" id="submit" value="<?php _e( 'Submit', 'bp-album' ) ?>"/>

		<?php
		// This is very important, don't leave it out. 
		   wp_nonce_field( 'bp-album-upload' );
		?>
	</form>
	<?php else: ?>
		<p><?php _e( 'You have reached the upload limit, delete some files if you want to upload more', 'bp-album' ) ?></p>
	<?php endif;
}

/********************************************************************************
 * Action Functions
 *
 * Action functions are exactly the same as screen functions, however they do not
 * have a template screen associated with them. Usually they will send the user
 * back to the default screen after execution.
 */

/**
 * bp_album_action_upload()
 *
 * @version 0.1.8.11
 * @since 0.1.8.0
 */
function bp_album_action_upload() {
    
	global $bp;
	
	if ( $bp->current_component == $bp->album->slug && $bp->album->upload_slug == $bp->current_action && isset( $_POST['submit'] )) {
	
		check_admin_referer('bp-album-upload');
		
		$error_flag = false;
		$feedback_message = array();

		if( !isset($_POST['privacy']) ){
					
			//$priv_lvl = intval($_POST['privacy']);
			$priv_lvl = 0;

                        switch ($priv_lvl) {
                            case "0": $pic_limit = $bp->album->bp_album_max_priv0_pictures; break;
                            case "1": $pic_limit = $bp->album->bp_album_max_priv1_pictures; break;
                            case "2": $pic_limit = $bp->album->bp_album_max_priv2_pictures; break;
                            case "3": $pic_limit = $bp->album->bp_album_max_priv3_pictures; break;
                            case "4": $pic_limit = $bp->album->bp_album_max_priv4_pictures; break;
                            case "5": $pic_limit = $bp->album->bp_album_max_priv5_pictures; break;
                            case "6": $pic_limit = $bp->album->bp_album_max_priv6_pictures; break;
                            case "7": $pic_limit = $bp->album->bp_album_max_priv7_pictures; break;
                            case "8": $pic_limit = $bp->album->bp_album_max_priv8_pictures; break;
                            case "9": $pic_limit = $bp->album->bp_album_max_priv9_pictures; break;
                            default: $pic_limit = null;
                        }
			$test = bp_album_get_picture_count(array('privacy'=>$priv_lvl));

			if($priv_lvl == 10 ) {
				$pic_limit = is_super_admin() ? false : null;
			}

			/*if( $pic_limit === null){
				$error_flag = true;
				$feedback_message[] = __( 'Privacy option is not correct.', 'bp-album' );	
			}			
			elseif( $pic_limit !== false && ( $pic_limit === 0  || $pic_limit <= bp_album_get_picture_count(array('privacy'=>$priv_lvl)) ) ) {

				$error_flag = true;
				
				switch ($priv_lvl){
					case 0 :
						$feedback_message[] = __( 'You reached the limit for public files.', 'bp-album' ).' '.__( 'Please select another privacy option.', 'bp-album' );
						break;
					case 2 :
						$feedback_message[] = __( 'You reached the limit for files visible to community members.', 'bp-album' ).' '.__( 'Please select another privacy option.', 'bp-album' );
						break;
					case 4 :
						$feedback_message[] = __( 'You reached the limit for files visible to friends.', 'bp-album' ).' '.__( 'Please select another privacy option.', 'bp-album' );
						break;
					case 6 :
						$feedback_message[] = __( 'You reached the limit for private files.', 'bp-album' ).' '.__( 'Please select another privacy option.', 'bp-album' );
						break;
				}
			}*/
		}
		
		$uploadErrors = array(
			0 => __("There was no error, the file uploaded with success", 'bp-album'),
			1 => __("Your image was bigger than the maximum allowed file size of: " . $bp->album->bp_album_max_upload_size . "MB"),
			2 => __("Your image was bigger than the maximum allowed file size of: " . $bp->album->bp_album_max_upload_size . "MB"),
			3 => __("The uploaded file was only partially uploaded", 'bp-album'),
			4 => __("No file was uploaded", 'bp-album'),
			6 => __("Missing a temporary folder", 'bp-album'),
			7 => __("Enter only one field", 'bp-album')
		);
			
			
			if(trim($_REQUEST['video-title'])== "")
			{
				$feedback_message[] = sprintf( __( 'Please enter a Title.', 'bp-album' ), $uploadErrors[7] );
				$error_flag = true;	
			}
			else
			{
					$_SESSION['title'] = $_REQUEST['video-title'];
			}
										
					$_SESSION['video_description'] = $_REQUEST['video-description'];			
			
			if ($_FILES['file']['name']!='' && $_REQUEST['embed_url']!='' && $_REQUEST['upload_url_field']!=''){		
				$feedback_message[] = sprintf( __( 'Please use only one field (from URL, Embed URL or Select file to Upload) at a time.', 'bp-album' ), $uploadErrors[7] );
				$error_flag = true;
			}
			else if ($_FILES['file']['name']=='' && $_REQUEST['embed_url']=='' && $_REQUEST['upload_url_field']==''){		
				$feedback_message[] = sprintf( __( 'Please use at least one field (from URL, Embed URL or Select file to Upload).', 'bp-album' ), $uploadErrors[7] );
				$error_flag = true;
			}
			else if ($_FILES['file']['name']!='' && $_REQUEST['embed_url']!=''){		
				$feedback_message[] = sprintf( __( 'Please use only one field.', 'bp-album' ), $uploadErrors[7] );
				$error_flag = true;
			}
			else if ($_FILES['file']['name']!='' && $_REQUEST['upload_url_field']!=''){		
				$feedback_message[] = sprintf( __( 'Please use only one field.', 'bp-album' ), $uploadErrors[7] );
				$error_flag = true;
			}
			else if ($_REQUEST['upload_url_field']!='' && $_REQUEST['embed_url']!=''){		
				$feedback_message[] = sprintf( __( 'Please use only one field.', 'bp-album' ), $uploadErrors[7] );
				$error_flag = true;
			}
			else if(isset($_REQUEST['upload_url_field']) && $_REQUEST['upload_url_field']!='')
			{
				$url = $_REQUEST['upload_url_field'];

				if(!filter_var($url, FILTER_VALIDATE_URL))
				{
				  $feedback_message[] = sprintf( __( 'Please enter a valid URL.', 'bp-album' ), $uploadErrors[7] );
					$error_flag = true;
				}				
			}
			
			
		/*	
			*****************************commented by jaspreet 30May2012******************************
			
			
		elseif(isset($_REQUEST['embed_url']) && $_REQUEST['embed_url']!='')
			{			
				$emb_url = $_REQUEST['embed_url'];						
			}
			elseif ( isset($_FILES['file']) && $_FILES['file']['tmp_name']!='')
			{				
				$embed_url = $_REQUEST['embed_url'];
			
				if ( $_FILES['file']['error'] ) {	
					$feedback_message[] = sprintf( __( 'Your upload failed, please try again. Error was: %s', 'bp-album' ), $uploadErrors[$_FILES['file']['error']] );
					$error_flag = true;	
				}		
			elseif ( ($_FILES['file']['size'] / (1024 * 1024)) > $bp->album->bp_album_max_upload_size ) {

				$feedback_message[] = sprintf(__( 'The file you tried to upload was too big. Please upload a file less than ' . $bp->album->bp_album_max_upload_size . 'MB', 'bp-album'));
				$error_flag = true;
				
			}
			 Check the file has the correct extension type. Copied from bp_core_check_avatar_type() and modified with /i so that the
			 regex patterns are case insensitive (otherwise .JPG .GIF and .PNG would trigger an error)
			elseif ( (!empty( $_FILES['file']['type'] ) && !preg_match('/(jpe?g|gif|png)$/i', $_FILES['file']['type'] ) ) || !preg_match( '/(jpe?g|gif|png)$/i', $_FILES['file']['name'] ) ) {

				$feedback_message[] = __( 'Please upload only JPG, GIF or PNG image files.', 'bp-album' );
				$error_flag = true;
			}

		}
		else {
			$feedback_message[] = sprintf( __( 'Your upload failed, please try again. Error was: %s', 'bp-album' ), $uploadErrors[4]);
			$error_flag = true;
		
		}
		
		
		*****************************commented by jaspreet 30May2012*******************************/
								
		//by Jaspreet 24 May 2012
								
		if(isset($_REQUEST['upload_url_field']) && $_REQUEST['upload_url_field']!='' && $error_flag == false)	
		{	
			global $current_user;
			global $wpdb;
			
			//generating thumb of url			
			$url = $_REQUEST['upload_url_field'];
			$html = file_get_contents($url);
			$doc = new DOMDocument();
			@$doc->loadHTML($html);
			
			$tags = $doc->getElementsByTagName('img');
			$url_image_array = array();
			$show_valid_image = '';
			$uia = 0;
			foreach ($tags as $tag) {
				$url_image_array[$uia] = $tag->getAttribute('src');
				if(strpos($url_image_array[$uia],'logo') == true)
				{
					$show_valid_image = $url_image_array[$uia];
					break;
				}
				else
				{
					$show_valid_image = $url_image_array[$uia];
				}
				$uia++;
			}
			//generating thum of url end
						
			//check whether image has complete url
			$valid_url = strpos($show_valid_image,"http");
			if($valid_url !== false)
			{}
			else
			{
				$show_valid_image = $_REQUEST['upload_url_field'].'/'.$show_valid_image;
			}
			
			//check whether image has complete url
			
			//making database column
			$uploaded_by = $bp->loggedin_user->domain;
												
			$action_field_table = addslashes("<a href='".$uploaded_by."' title='".$current_user->display_name."'>".$current_user->display_name."</a> Uploaded a new link: ");			
			
			$primary_link = addslashes($_REQUEST['upload_url_field']);	
				
			$content_column = addslashes('<a target="_blank" href="'.$primary_link.'" class="picture-activity-thumb">'.$primary_link.'</a><br/><br/><a target="_blank" href="'.$primary_link.'" class="picture-activity-thumb"><img src="'.$show_valid_image.'" /></a>');
			//making database column end
			
			$insert_embed_data1 = "insert into wp_bp_album(owner_type, owner_id, date_uploaded, title, pic_org_url, pic_org_path, pic_mid_url, pic_mid_path, pic_thumb_url, pic_thumb_path, privacy,description) values('user','".$current_user->ID."','".gmdate( "Y-m-d H:i:s" )."','".$_REQUEST['upload_url_field']."','".$show_valid_image."','".$show_valid_image."','".$show_valid_image."','".$show_valid_image."','".$show_valid_image."','".$show_valid_image."','0','".addslashes($_REQUEST['video-description'])."')";		
					
			$wpdb->query($insert_embed_data1);
			$id = $wpdb->insert_id;
			
											
			$insert_embed_data = "insert into wp_bp_activity (user_id,component,action,content,primary_link,item_id,date_recorded) values('".$current_user->ID."','album','".$action_field_table."','".$content_column."','".$primary_link."','".$id."','".date('Y-m-d h:i:s')."')";
			
			$wpdb->query($insert_embed_data);
										
		}
		else if(isset($_REQUEST['embed_url']) && $_REQUEST['embed_url']!='' && $error_flag == false)	
		{				
			global $current_user;						
			global $wpdb;
			
			$final_embed_url = "";
			
			if(is_numeric(strpos($_REQUEST['embed_url'],"iframe")))
			{
				$final_embed_url = $_REQUEST['embed_url'];
			}
			else if(strpos($_REQUEST['embed_url'],"iframe") == false && is_numeric(strpos($_REQUEST['embed_url'],"youtube")))
			{
				$addree_bar_link = explode("v=",$_REQUEST['embed_url']);
				$address_bar_link1 = explode("&",$addree_bar_link[1]);								
				$final_embed_url = '<iframe width="420" height="315" src="http://www.youtube.com/embed/'.$address_bar_link1[0].'" frameborder="0" allowfullscreen></iframe>';
			}
			else if(strpos($_REQUEST['embed_url'],"iframe") == false && is_numeric(strpos($_REQUEST['embed_url'],"http://youtu.be/")))
			{
				$addree_bar_link = explode("http://youtu.be/",$_REQUEST['embed_url']);							
				$final_embed_url = '<iframe width="420" height="315" src="http://www.youtube.com/embed/'.$addree_bar_link[1].'" frameborder="0" allowfullscreen></iframe>';				
			}
			else if(strpos($_REQUEST['embed_url'],"iframe") == false && is_numeric(strpos($_REQUEST['embed_url'],"soundcloud")))
			{
				$addree_bar_link = explode("/",$_REQUEST['embed_url']);
				$total_parts =  count($addree_bar_link);
								
				$final_embed_url = '<iframe width="100%" height="166" scrolling="no" frameborder="no" src="http://w.soundcloud.com/player/?url=http%3A%2F%2Fapi.soundcloud.com%2Ftracks%2F'.$addree_bar_link[$total_parts-1].'&show_artwork=true"></iframe>';
				
			}
			/*else if(strpos($_REQUEST['embed_url'],"iframe") == false && is_numeric(strpos($_REQUEST['embed_url'],"http://snd.sc/")))
			{
				$addree_bar_link = explode("http://snd.sc/",$_REQUEST['embed_url']);
											
				$final_embed_url = '<iframe width="100%" height="166" scrolling="no" frameborder="no" src="http://w.soundcloud.com/player/?url=http%3A%2F%2Fapi.soundcloud.com%2Ftracks%2F'.$addree_bar_link[1].'&show_artwork=true"></iframe>';
								
			}*/		
			
			$insert_embed_data = "insert into wp_bp_album(owner_type, owner_id, date_uploaded,title,privacy,embed_url,description) values('user','".$current_user->ID."','".gmdate( "Y-m-d H:i:s" )."','".$_REQUEST['video-title']."','0','".$final_embed_url."','".addslashes($_REQUEST['video-description'])."')";		
						
			$wpdb->query($insert_embed_data);
			$id = $wpdb->insert_id;
						
			$album_video_link = addslashes($bp->loggedin_user->domain . $bp->current_component . '/'.$bp->album->single_slug.'/' . $id);			
			
			$activty_head = explode($current_user->display_name,$album_video_link);
						
			$action_field_table = addslashes( "<a href='".$album_video_link."' title='".$current_user->display_name."'>".$current_user->display_name."</a>Uploaded a new Video");
			
			$content_field_table =addslashes( $_REQUEST['embed_url']);
			
			$insert_embed_data1 = "insert into wp_bp_activity (user_id,component,action,content,primary_link,item_id,date_recorded) values('".$current_user->ID."','album','".$action_field_table."','".$content_field_table."','".$album_video_link."','".$id."','".date('Y-m-d h:i:s')."')";
			
			$wpdb->query($insert_embed_data1);
										
		}
		else if(!$error_flag)
		{			
			if(!$error_flag)
			{
				add_filter( 'upload_dir', 'bp_album_upload_dir', 10, 0 );						
				$pic_org = wp_handle_upload( $_FILES['file'],array('action'=>'picture_upload') );	
				if ( !empty( $pic_org['error'] ) ) 
				{
					$feedback_message[] = sprintf( __('Your upload failed, please try again. Error was: %s', 'bp-album' ), $pic_org['error'] );
					$error_flag = true;
				}
			}	
			
			if(!$error_flag){  
	
				if( !is_multisite() ){
					// Some site owners with single-blog installs of WordPress change the path of
					// their upload directory by setting the constant 'BLOGUPLOADDIR'. Handle this
					// for compatibility with legacy sites.
	
					if( defined( 'BLOGUPLOADDIR' ) ){
	
						$abs_path_to_files = str_replace('/files/','/',BLOGUPLOADDIR);
					}
					else {
	
						$abs_path_to_files = ABSPATH;
					}
				}
				else {
	
					// If the install is running in multisite mode, 'BLOGUPLOADDIR' is automatically set by
					// WordPress to something like "C:\xampp\htdocs/wp-content/blogs.dir/1/" even though the
					// actual file is in "C:\xampp\htdocs/wp-content/uploads/", so we need to use ABSPATH	
					$abs_path_to_files = ABSPATH;
				}
				
				$pic_org_path = $pic_org['file'];
				$pic_org_url = str_replace($abs_path_to_files,'/',$pic_org_path);
				
				$pic_org_size = getimagesize( $pic_org_path );
				$pic_org_size = ($pic_org_size[0]>$pic_org_size[1])?$pic_org_size[0]:$pic_org_size[1];
				
				if($pic_org_size <= $bp->album->bp_album_middle_size)
				{	
					$pic_mid_path = $pic_org_path;
					$pic_mid_url = $pic_org_url;
				} 
				else 
				{	
					$pic_mid = wp_create_thumbnail( $pic_org_path, $bp->album->bp_album_middle_size );
					$pic_mid_path = str_replace( '//', '/', $pic_mid );
					$pic_mid_url = str_replace($abs_path_to_files,'/',$pic_mid_path);
	
					if (!$bp->album->bp_album_keep_original)
					{	
						unlink($pic_org_path);
						$pic_org_url=$pic_mid_url;
						$pic_org_path=$pic_mid_path;
					}
				}
	
				if($pic_org_size <= $bp->album->bp_album_thumb_size)
				{
	
					$pic_thumb_path = $pic_org_path;
					$pic_thumb_url = $pic_org_url;
				} 
				else {
	
					$pic_thumb = image_resize( $pic_mid_path, $bp->album->bp_album_thumb_size, $bp->album->bp_album_thumb_size, true);
					$pic_thumb_path = str_replace( '//', '/', $pic_thumb );
					$pic_thumb_url = str_replace($abs_path_to_files,'/',$pic_thumb);
				}
	
				$owner_type = 'user';
				$owner_id = $bp->loggedin_user->id;
				$date_uploaded =  gmdate( "Y-m-d H:i:s" );
				$title = $_FILES['file']['name'];
				$description = ' ';
				$privacy = $priv_lvl;
	
				
				$id=bp_album_add_picture($owner_type,$owner_id,$title,$description,$priv_lvl,$date_uploaded,$pic_org_url,$pic_org_path,$pic_mid_url,$pic_mid_path,$pic_thumb_url,$pic_thumb_path,$embed_url);
				
						if($id)
							$feedback_message[] = __('File uploaded. Now you can edit the files details.', 'bp-album');
						else {
							$error_flag = true;
							$feedback_message[] = __('There were problems saving the files details.', 'bp-album');
				}
			}						
		}
		
		//By Jaspreet 24 May2012				
		
		if ($error_flag){
			bp_core_add_message( implode('&nbsp;', $feedback_message ),'error');
		} 
		else 
		{
			bp_core_add_message(implode('&nbsp;',$feedback_message),'success');
			
			if(isset($_REQUEST['upload_url_field']) && $_REQUEST['upload_url_field']!='')			
			{
				global $current_user;
				/*echo bloginfo('wpurl').'/members/'.$current_user->display_name.'/activity';
				die;
				bp_core_redirect(bloginfo('wpurl').'/members/'.$current_user->display_name.'/activity');*/
				header("location:http://".$_SERVER['HTTP_HOST']."/blog/members/".$current_user->display_name."/activity");
			}
			else if(trim($_REQUEST['embed_url']) == '')
			{			
				bp_core_redirect( $bp->loggedin_user->domain . $bp->current_component . '/'.$bp->album->single_slug.'/' . $id.'/'.$bp->album->edit_slug.'/');
			}
			else
			{
				bp_core_redirect($bp->loggedin_user->domain . $bp->current_component . '/'.$bp->album->single_slug.'/' . $id);
			}	
			die;
		}		
	}
	
}
add_action('bp_actions','bp_album_action_upload',3);
add_action('wp','bp_album_action_upload',3);

/**
 * bp_album_upload_dir() 
 *
 * @version 0.1.8.11
 * @since 0.1.8.0
 */
function bp_album_upload_dir() {
    
	global $bp;

	$user_id = $bp->loggedin_user->id;
	
	$dir = BP_ALBUM_UPLOAD_PATH;

	$siteurl = trailingslashit( get_blog_option( 1, 'siteurl' ) );
	$url = str_replace(ABSPATH,$siteurl,$dir);
	
	$bdir = $dir;
	$burl = $url;
	
	$subdir = '/' . $user_id;
	
	$dir .= $subdir;
	$url .= $subdir;

	if ( !file_exists( $dir ) )
		@wp_mkdir_p( $dir );

	return apply_filters( 'bp_album_upload_dir', array( 'path' => $dir, 'url' => $url, 'subdir' => $subdir, 'basedir' => $bdir, 'baseurl' => $burl, 'error' => false ) );
}

/**
 * bp_album_action_edit()
 *
 * @version 0.1.8.11
 * @since 0.1.8.0
 */
function bp_album_action_edit() {
	
	global $bp,$pictures_template;
	
	if ( $bp->current_component == $bp->album->slug && $bp->album->single_slug == $bp->current_action && $pictures_template->picture_count && isset($bp->action_variables[1]) && $bp->album->edit_slug == $bp->action_variables[1] &&  isset( $_POST['submit'] )) {
	
		check_admin_referer('bp-album-edit');
		
		$error_flag = false;
		$feedback_message = array();
		
		$id = $pictures_template->pictures[0]->id;
		
		if(empty($_POST['title'])){
			$error_flag = true;
			$feedback_message[] = __( 'Picture Title can not be blank.', 'bp-album' );
		}

		if( $bp->album->bp_album_require_description && empty($_POST['description'])){
			$error_flag = true;
			$feedback_message[] = __( 'Picture Description can not be blank.', 'bp-album' );
		}
		
		if( !isset($_POST['privacy']) ){
			/*$error_flag = true;
			$feedback_message[] = __( 'Please select a privacy option.', 'bp-album' );	
		}
		else {
			$priv_lvl = intval($_POST['privacy']);*/
			$priv_lvl = 0;

                        switch ($priv_lvl) {
                            case "0": $pic_limit = $bp->album->bp_album_max_priv0_pictures; break;
                            case "1": $pic_limit = $bp->album->bp_album_max_priv1_pictures; break;
                            case "2": $pic_limit = $bp->album->bp_album_max_priv2_pictures; break;
                            case "3": $pic_limit = $bp->album->bp_album_max_priv3_pictures; break;
                            case "4": $pic_limit = $bp->album->bp_album_max_priv4_pictures; break;
                            case "5": $pic_limit = $bp->album->bp_album_max_priv5_pictures; break;
                            case "6": $pic_limit = $bp->album->bp_album_max_priv6_pictures; break;
                            case "7": $pic_limit = $bp->album->bp_album_max_priv7_pictures; break;
                            case "8": $pic_limit = $bp->album->bp_album_max_priv8_pictures; break;
                            case "9": $pic_limit = $bp->album->bp_album_max_priv9_pictures; break;
                            default: $pic_limit = null;
                        }


			if($priv_lvl == 10 )
				$pic_limit = is_super_admin() ? false : null;
			/*if( $pic_limit === null){
				$error_flag = true;
				$feedback_message[] = __( 'Privacy option is not correct.', 'bp-album' );	
			}
			elseif( $pic_limit !== false && $priv_lvl !== $pictures_template->pictures[0]->privacy && ( $pic_limit === 0|| $pic_limit <= bp_album_get_picture_count(array('privacy'=>$priv_lvl)) ) ){
				$error_flag = true;
				switch ($priv_lvl){
					case 0 :
						$feedback_message[] = __( 'You reached the limit for public pictures.', 'bp-album' ).' '.__( 'Please select another privacy option.', 'bp-album' );
						break;
					case 2 :
						$feedback_message[] = __( 'You reached the limit for pictures visible to community members.', 'bp-album' ).' '.__( 'Please select another privacy option.', 'bp-album' );
						break;
					case 4 :
						$feedback_message[] = __( 'You reached the limit for pictures visible to friends.', 'bp-album' ).' '.__( 'Please select another privacy option.', 'bp-album' );
						break;
					case 6 :
						$feedback_message[] = __( 'You reached the limit for private pictures.', 'bp-album' ).' '.__( 'Please select another privacy option.', 'bp-album' );
						break;
				}
			}*/
		}

		/*if(bp_is_active('activity') && $bp->album->bp_album_enable_comments)
			if(!isset($_POST['enable_comments']) || ($_POST['enable_comments']!= 0 && $_POST['enable_comments']!= 1)){
				$error_flag = true;
				$feedback_message[] = __( 'Comments option is not correct.', 'bp-album' );
			}
		else
			$_POST['enable_comments']==0;*/
			

		if( !$error_flag ){

			// WordPress adds an escape character "\" to some special values in INPUT FIELDS (test's becomes test\'s), so we have to strip
			// the escape characters, and then run the data through *proper* filters to prevent SQL injection, XSS, and various other attacks.

			//if( bp_album_edit_picture($id,stripslashes($_POST['title']),stripslashes($_POST['description']),$priv_lvl,$_POST['enable_comments']) ){
			if( bp_album_edit_picture($id,stripslashes($_POST['title']),stripslashes($_POST['description']),$priv_lvl,1) ){
				$feedback_message[] = __('File details saved.', 'bp-album');
			}else{
				$error_flag = true;
				$feedback_message[] = __('There were problems saving file details.', 'bp-album');
			}
		}
		if ($error_flag){
			bp_core_add_message( implode('&nbsp;', $feedback_message ),'error');
		} 
		else {
			bp_core_add_message( implode('&nbsp;', $feedback_message ),'success' );			
			bp_core_redirect( $bp->displayed_user->domain . $bp->album->slug . '/'.$bp->album->single_slug.'/' . $id.'/');
			die;
		}
		
	}
	
}
add_action('bp_actions','bp_album_action_edit',3);
add_action('wp','bp_album_action_edit',3);

/**
 * bp_album_action_delete()
 *
 * @version 0.1.8.11
 * @since 0.1.8.0
 */
function bp_album_action_delete() {

	global $bp,$pictures_template;
	
	if ( $bp->current_component == $bp->album->slug && $bp->album->single_slug == $bp->current_action && $pictures_template->picture_count && isset($bp->action_variables[1]) && $bp->album->delete_slug == $bp->action_variables[1] ) {
		check_admin_referer('bp-album-delete-pic');
		
				
		if(!$pictures_template->picture_count){
			bp_core_add_message( __( 'This url is not valid.', 'bp-album' ), 'error' );
			return;
		}
		else{
			
			if ( !bp_is_my_profile() && !current_user_can(level_10) ) {
				bp_core_add_message( __( 'You don\'t have permission to delete this picture', 'bp-album' ), 'error' );
			}
			elseif (bp_album_delete_picture($pictures_template->pictures[0]->id)){
				bp_core_add_message( __( 'File deleted.', 'bp-album' ), 'success' );
				bp_core_redirect( $bp->displayed_user->domain . $bp->album->slug . '/'. $bp->album->pictures_slug .'/');
				die;
			}
			else{
				bp_core_add_message( __( 'There were problems deleting the file.', 'bp-album' ), 'error' );
			}
		}
		bp_core_redirect( $bp->displayed_user->domain . $bp->album->slug . '/'. $bp->album->single_slug .'/'.$pictures_template->pictures[0]->id. '/');
		die;
	}
}
add_action('bp_actions','bp_album_action_delete',3);
add_action('wp','bp_album_action_delete',3);

/**
 * bp_album_screen_all_images()
 * 
 * Displays sitewide featured content block
 *
 * @version 0.1.8.11
 * @since 0.1.8.0
 */
function bp_album_screen_all_images() {

        global $bp;

        bp_album_query_pictures();
	bp_album_load_subtemplate( apply_filters( 'bp_album_screen_all_images', 'album/all-images' ), false );
}
add_action('bp_album_all_images','bp_album_screen_all_images',3);

?>
