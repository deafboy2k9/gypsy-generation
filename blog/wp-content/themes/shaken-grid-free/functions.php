<?php

add_action( 'after_setup_theme', 'shaken_setup' );

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 *
 * To override shaken_setup() in a child theme, add your own shaken_setup to your child theme's
 * functions.php file.
 *
 * @since 3.0.0
 */
function shaken_setup() {
	
	// RSS Links
	add_theme_support('automatic-feed-links');
	
	// Add support for gallery post thumbnails 
	add_theme_support( 'post-thumbnails');
		set_post_thumbnail_size(480,480, true);
		// For the premium version:
		add_image_size( 'sidebar', 75, 75, true);
		add_image_size( 'col1', 135, 650);
		add_image_size( 'col3', 485, 800);
		add_image_size( 'col4', 660, 800);
	
	// Add support for nav menus
	add_theme_support( 'nav-menus' );
	
	/* Add your nav menus function to the 'init' action hook. */
	add_action( 'init', 'shaken_register_menus' );
	
	/* Add your sidebars function to the 'widgets_init' action hook. */
	add_action( 'widgets_init', 'shaken_register_sidebars' );
	
	// Add featured images to RSS
	add_filter('pre_get_posts','feedFilter');
	
	//pre-set post editing text
	add_filter( 'default_content', 'my_editor_content' );
}

// smart jquery inclusion
if (!is_admin()) {
	wp_enqueue_script('jquery');
}

//function to add some default text to wordpress new post editor
function my_editor_content( $content ) {

	$content = '
[column width="30%" num="1"]
	--> ADD CONTENT HERE <--
[/column]
	
[column width="30%" num="2"]
	--> ADD CONTENT HERE <--
[/column]';

	return $content;
}

function shaken_register_menus(){
	register_nav_menus( array(
			'primary' => __( 'Primary Navigation'),
	));
}

function shaken_register_sidebars(){
	register_sidebar( array (
		'name' => 'Sidebar',
		'id' => 'primary-widget-area',
		'description' => __( 'The main sidebar'),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => "</li>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
}

// -------------- Add featured images to RSS feed --------------
function feedContentFilter($content) {
	$thumbId = get_post_thumbnail_id();
 
	if($thumbId) {
		$img = wp_get_attachment_image_src($thumbId, 'col3');
		$image = '<img src="'. $img[0] .'" alt="" width="'. $img[1] .'" height="'. $img[2] .'" />';
		echo $image;
	}
 
	return $content;
}

function feedFilter($query) {
	if ($query->is_feed) {
		add_filter('the_content', 'feedContentFilter');
		}
	return $query;
}

/**
 * Template for comments and pingbacks.
 *
 * To override this walker in a child theme without modifying the comments template
 * simply create your own twentyeleven_comment(), and that function will be used instead.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 *
 * @since Twenty Eleven 1.0
 */
function twentyeleven_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
	?>
	<li class="post pingback">
		<p><?php _e( 'Pingback:', 'shaken-grid-free' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __( 'Edit', 'shaken-grid-free' ), '<span class="edit-link">', '</span>' ); ?></p>
	<?php
			break;
		default :
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<article id="comment-<?php comment_ID(); ?>" class="comment">
			<footer class="comment-meta">
				<div class="comment-author vcard">
					<?php
						$avatar_size = 68;
						if ( '0' != $comment->comment_parent )
							$avatar_size = 39;

						echo get_avatar( $comment, $avatar_size );

						/* translators: 1: comment author, 2: date and time */
						printf( __( '%1$s on %2$s <span class="says">said:</span>', 'shaken-grid-free' ),
							sprintf( '<span class="fn">%s</span>', get_comment_author() ),
							sprintf( '<a href="%1$s"><time pubdate datetime="%2$s">%3$s</time></a>',
								esc_url( get_comment_link( $comment->comment_ID ) ),
								get_comment_time( 'c' ),
								/* translators: 1: date, 2: time */
								sprintf( __( '%1$s at %2$s', 'shaken-grid-free' ), get_comment_date(), get_comment_time() )
							)
						);
					?>

					<?php edit_comment_link( __( 'Edit', 'shaken-grid-free' ), '<span class="edit-link">', '</span>' ); ?>
				</div><!-- .comment-author .vcard -->

				<?php if ( $comment->comment_approved == '0' ) : ?>
					<em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'shaken-grid-free' ); ?></em>
					<br />
				<?php endif; ?>

			</footer>

			<div class="comment-content"><?php comment_text(); ?></div>

			<div class="reply">
				<?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Reply <span>&darr;</span>', 'twentyeleven' ), 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
			</div><!-- .reply -->
		</article><!-- #comment-## -->

	<?php
			break;
	endswitch;
}

//  -------------- Theme Options Pages --------------
$themename = "Shaken Grid";  
$shortname = "soy";

$categories = get_categories('hide_empty=0&orderby=name');  
$wp_cats = array();  
foreach ($categories as $category_list ) {  
       $wp_cats[$category_list->cat_ID] = $category_list->cat_name;  
}  
array_unshift($wp_cats, "Choose a category");


// --------------- Theme Options ---------------------------------------------------------------//
$options = array (  
   
array( "name" => $themename." Options",  
    "type" => "title"),  

// --------------- Style Options --------------- //
array( "name" => "Styles",  
    "type" => "section"),  
array( "type" => "open"),  
            
array( "name" => "Custom Favicon",  
    "desc" => "A favicon is a 16x16 pixel icon that represents your site; paste the URL to a .ico image that you want to use as the image",  
    "id" => $shortname."_favicon",  
    "type" => "text",  
    "std" => get_bloginfo('url') ."/favicon.ico"),
	
array( "name" => "Stylesheet",  
    "desc" => "The color (white or black) of your website",  
    "id" => $shortname."_style",  
	"options" => array("white" => "white","black" => "black"),
    "type" => "select"),
      
array( "type" => "close"), 

// --------------- Social Network Links --------------- //
array( "name" => "General",  
    "type" => "section"),  

array( "type" => "open"),  

array( "name" => "Stat Tracking",  
    "desc" => "Enter the stat tracking code here to add to the end of the HTML",  
    "id" => $shortname."_stats",  
    "type" => "textarea",  
    "std" => ""),   
      
array( "type" => "close")

   
); 
// --------------- End Theme Options ---------------------------------------------------------------//

function mytheme_add_admin() {  
   
global $themename, $shortname, $options;  
   
if ( $_GET['page'] == basename(__FILE__) ) {  
   
    if ( 'save' == $_REQUEST['action'] ) {  
   
        foreach ($options as $value) {  
        update_option( $value['id'], $_REQUEST[ $value['id'] ] ); }  
   
foreach ($options as $value) {  
    if( isset( $_REQUEST[ $value['id'] ] ) ) { update_option( $value['id'], $_REQUEST[ $value['id'] ]  ); } else { delete_option( $value['id'] ); } }  
   
    header("Location: admin.php?page=functions.php&saved=true");  
die;  
   
}   
else if( 'reset' == $_REQUEST['action'] ) {  
   
    foreach ($options as $value) {  
        delete_option( $value['id'] ); }  
   
    header("Location: admin.php?page=functions.php&reset=true");  
die;  
   
}  
}  
   
add_menu_page($themename, $themename, 'administrator', basename(__FILE__), 'mytheme_admin');  
}  
  
function mytheme_add_init() {  
$file_dir=get_template_directory_uri(); 
wp_enqueue_style("functions", $file_dir."/functions/functions.css", false, "1.0", "all");  
wp_enqueue_script("rm_script", $file_dir."/functions/rm_script.js", false, "1.0");  
} 

function mytheme_admin() {  
   
global $themename, $shortname, $options;  
$i=0;  
   
if ( $_REQUEST['saved'] ) echo '<div id="message" class="updated fade"><p><strong>'.$themename.' settings saved.</strong></p></div>';  
if ( $_REQUEST['reset'] ) echo '<div id="message" class="updated fade"><p><strong>'.$themename.' settings reset.</strong></p></div>';  
   
?>  
<div class="wrap rm_wrap">  
<h2><?php echo $themename; ?> Settings</h2>  
   
<div class="rm_opts">  
<form method="post">
<?php foreach ($options as $value) {  
switch ( $value['type'] ) {  
   
case "open":  
?>  
   
<?php break;  
   
case "close":  
?>  
   
</div>  
</div>  
<br />  
  
   
<?php break;  
   
case "title":  
?>
<p class="premium-msg">A premium version of Shaken Grid, packed with additional features is available! <br />
<strong><a href="http://shakenandstirredweb.com/themes/shaken-grid" target="_blank">Check it out and purchase today &raquo;</a></strong></p>  
  
   
<?php break;  
   
case 'text':  
?>  
  
<div class="rm_input rm_text">  
    <label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label>  
    <input name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="<?php echo $value['type']; ?>" value="<?php if ( get_settings( $value['id'] ) != "") { echo stripslashes(get_settings( $value['id'])  ); } else { echo $value['std']; } ?>" />  
 <small><?php echo $value['desc']; ?></small><div class="clearfix"></div>  
   
 </div>  
<?php  
break;  
   
case 'textarea':  
?>  
  
<div class="rm_input rm_textarea">  
    <label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label>  
    <textarea name="<?php echo $value['id']; ?>" type="<?php echo $value['type']; ?>" cols="" rows=""><?php if ( get_settings( $value['id'] ) != "") { echo stripslashes(get_settings( $value['id']) ); } else { echo $value['std']; } ?></textarea>  
 <small><?php echo $value['desc']; ?></small><div class="clearfix"></div>  
   
 </div>  
    
<?php  
break;  
   
case 'select':  
?>  
  
<div class="rm_input rm_select">  
    <label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label>  
      
<select name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>">  
<?php foreach ($value['options'] as $option) { ?>  
        <option <?php if (get_settings( $value['id'] ) == $option) { echo 'selected="selected"'; } ?>><?php echo $option; ?></option><?php } ?>  
</select>  
  
    <small><?php echo $value['desc']; ?></small><div class="clearfix"></div>  
</div>  
<?php  
break;  
   
case "checkbox":  
?>  
  
<div class="rm_input rm_checkbox">  
    <label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label>  
      
<?php if(get_option($value['id'])){ $checked = "checked=\"checked\""; }else{ $checked = "";} ?>  
<input type="checkbox" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" value="true" <?php echo $checked; ?> />  
  
  
    <small><?php echo $value['desc']; ?></small><div class="clearfix"></div>  
 </div>  
<?php break; 

case "section":  
  
$i++; 

?>
  
<div class="rm_section">  
<div class="rm_title"><h3><img src="<?php bloginfo('template_directory')?>/functions/images/trans.png" class="inactive" alt="""><?php echo $value['name']; ?></h3><span class="submit"><input name="save<?php echo $i; ?>" type="submit" value="Save changes" />  
</span><div class="clearfix"></div></div>  
<div class="rm_options">  
  
   
<?php break;  
   
}  
}  
?>  
   
<input type="hidden" name="action" value="save" />  
</form>  
<form method="post">  
<p class="submit">  
<input name="reset" type="submit" value="Reset" />  
<input type="hidden" name="action" value="reset" />  
</p> 

</form>  
<p><strong>Enjoying this <em>free</em> theme? Please donate so more themes like this one can be released for free.</strong>
    <form action="https://www.paypal.com/cgi-bin/webscr" method="post" id="donate-btn">
    <input type="hidden" name="cmd" value="_s-xclick">
    <input type="hidden" name="hosted_button_id" value="JVAP9GC39L2BU">
    <input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
    <img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
    </form>
</p>
<p><a href="http://shakenandstirredweb.com">A Shaken &amp; Stirred product</a></p>
 </div>   
   
  
<?php  
}  
  
?>
<?php  
add_action('admin_init', 'mytheme_add_init');  
add_action('admin_menu', 'mytheme_add_admin');  

/*
 * Additional hooks & filters 
 * to add extra profile field types 
 * to BP
 */

//Add country select list
function bdp_add_new_xprofile_field_type($field_types)
{
    $country_select_type = array('country');
    $field_types = array_merge($field_types, $country_select_type);
    
    return $field_types;
}

//Add the filter announcing to XProfile that we have a new field type
add_filter('xprofile_field_types', 'bdp_add_new_xprofile_field_type');

//Function to add new admin field type
function bpd_admin_render_new_xprofile_field_type($field, $echo = true){

    ob_start();
    switch ($field->type)
    {
        case 'country':
        
            global $wpdb;
            $sql = 'select code, name from gg_country order by name asc';
            
            $countries = $wpdb->get_results($sql);
            
     ?>
            <select id="<?php bp_the_profile_field_input_name() ?>" name="<?php bp_the_profile_field_input_name() ?>">
     			<option value="0">--Please select one--</option>
     <?php
            foreach($countries as $country)
            {
      ?>
                <option value="<?php echo $country->code; ?>"><?php echo $country->name; ?></option>
      <?php
            }
            
      ?>
            </select>
            
      <?php
            break;
        
        case 'links':    
            
       ?>
       	<input type="text" name="portfolio_links" id="portfolio_links" />
       <?php      
            
            break;
        case 'group':
       ?>
       	<input type="text" name="group" id="group" />
       <?php 
            break;
        default:
            
       ?> Unknown field type
        
       <?php        
            break;
    }
    
    $output = ob_get_contents();
    ob_end_clean();
   
    if($echo)
    {
        echo $output;
        
    }else{
        
        return $output;
    }
    
}

add_filter( 'xprofile_admin_field', 'bpd_admin_render_new_xprofile_field_type' );

function bpd_edit_render_new_xprofile_field($echo = true){
    if(empty ($echo)){
        $echo = true;
    }
   
    $type = bp_get_the_profile_field_type();
    
    switch ($type)
    {
        case 'country':
        
            global $wpdb;
            $sql = 'select code, name from gg_country order by name asc';
            
            $countries = $wpdb->get_results($sql);
            
     ?>
     		<label for="<?php bp_the_profile_field_input_name(); ?>"><?php bp_the_profile_field_name(); ?> <?php if ( bp_get_the_profile_field_is_required() ) : ?><?php _e( '(required)', 'buddypress' ); ?><?php endif; ?></label>
            <select id="<?php bp_the_profile_field_input_name() ?>" name="<?php bp_the_profile_field_input_name() ?>">
     			<option value="0">--Please select one--</option>
     <?php
            foreach($countries as $country)
            {
      ?>
                <option value="<?php echo $country->code; ?>"><?php echo $country->name; ?></option>
      <?php
            }
            
      ?>
            </select>
            
      <?php
            break;
        case 'group':
            $i = 0;
			if ( bp_has_groups('type=alphabetical&per_page=99999') ) : while ( bp_groups() ) : bp_the_group();
			
			    if ( bp_get_group_status() == ('public')) { 
	   ?>			<li class="reg_groups_item">
						<input type="radio" id="field_reg_groups_<?php echo $i; ?>" name="<?php bp_the_profile_field_input_name(); ?>" value="<?php bp_group_id(); ?>" /> <?php bp_group_name(); ?>
					</li>
		<?php   }
		    
		        $i++;
		    endwhile;
		    else:
		?>
				<p class="reg_groups_none">No selections are available at this time.</p>
		<?php 
		    endif;
		    break;
        case 'links':
            
      ?>
      <label for="<?php bp_the_profile_field_input_name(); ?>"><?php bp_the_profile_field_name(); ?> <?php if ( bp_get_the_profile_field_is_required() ) : ?><?php _e( '(required)', 'buddypress' ); ?><?php endif; ?></label>
      <div id="xprofile_links_container">
      	<ol>
      		
      		<?php for($i = 0; $i < 5; $i++){ ?>
      		<li>
      			<input type="text" id="portfolio_links_<?php echo $i; ?>" name="<?php bp_the_profile_field_input_name(); ?>[]" />
      			<select id="portfolio_links_type_<?php echo $i; ?>" name="<?php bp_the_profile_field_input_name(); ?>_type[]" >
      				<option value="0">--Select One--</option>
      				<option value="portfolio">Portfolio</option>
      				<option value="blog">Blog</option>
      				<option value="facebook">Facebook page</option>
      				<option value="twitter">Twitter</option>
      				<option value="youtube">YouTube account</option>
      				<option value="soundcloud">Soundcloud account</option>
      				<option value="bandcamp">Bandcamp</option>
      				<option value="linkedln">Linkenln profile</option>
      				<option value="tumblr">Tumblr</option>
      				<option value="other">Other</option>
      			</select>
      		</li>
      		<?php } ?>
      	</ol>
      </div>
      <?php      
            
            break;
        
    }
    
    $output = ob_get_contents();
    ob_end_clean();
   
    if($echo)
    {
        echo $output;
        
    }else{
        
        return $output;
    }
    
}

add_action( 'bp_custom_profile_edit_fields', 'bpd_edit_render_new_xprofile_field' );

// Override default action hook in order to support images
function bpd_override_xprofile_screen_edit_profile(){
    
}

add_action( 'bp_actions', 'bpd_override_xprofile_screen_edit_profile', 10 );

//Create profile_edit handler
function bpd_screen_edit_profile(){

}

function bpd_profile_upload_dir( $upload_dir ) {

}

//add_filter( 'bp_registration_needs_activation', '__return_false' );


function lwd_check_additional_signup()
{
    global $bp, $bps;
    
    //Check that the password is at least 8 characters
    if((strlen($_POST['signup_password']) < 8) || (strlen($_POST['signup_password']) > 12))
    {
        $bp->signup->errors['signup_password'] = 'Please enter a password between 8 to 12 characters';
    }
    
    //Check that the country is selected
    if(!$_POST['country'])
    {
        $bp->signup->errors['country'] = 'Please select a country';
    }
    
    //Check that the info about a user is less than 700 characters
    if($_POST['field_65'] && (strlen($_POST['field_65']) > 700))
    {
        $bp->signup->errors['field_65'] = 'Please ensure that you enter 700 characters';
    }
 
}

//add_action( 'bp_signup_validate', 'lwd_check_additional_signup' );

function bpd_load_js() {
     wp_enqueue_script( 'bpd-js', get_template_directory_uri() . '/js/xprofile-fields.js',
							array( 'jquery' ), '1.0' );
}

add_action( 'wp_print_scripts', 'bpd_load_js' );

function gg_handle_registration_avatar() {
            global $wpdb, $bp;
            $bp->avatar_admin->step = 'upload-image'; 
 	 
 	        /* If user has uploaded a new avatar */ 
 	        if ( !empty( $_FILES ) ) { 
 	 
 	                /* Check the nonce */ 
 	                check_admin_referer( 'bp_avatar_upload' ); 
 	 
 	                $bp->signup->step = 'completed-confirmation'; 
 	 
 	                if ( is_multisite() ) { 
 	                    
 	                        /* Get the activation key */ 
 	                        if ( !$bp->signup->key = $wpdb->get_var( $wpdb->prepare( "SELECT activation_key FROM {$wpdb->signups} WHERE user_login = %s AND user_email = %s", $_POST[ 'signup_username' ], $_POST[ 'signup_email' ] ) ) ) {
 	                             
 	                                bp_core_add_message( __( 'There was a problem uploading your avatar, please try uploading it again', 'buddypress' ) );
 	                                 
 	                        } else { 
 	                                /* Hash the key to create the upload folder (added security so people don't sniff the activation key) */ 
 	                                $bp->signup->avatar_dir = wp_hash( $bp->signup->key ); 
 	                        } 
 	                        
 	                } else { 
 	                        $user_id = bp_core_get_userid( $_POST['signup_username'] ); 
 	                        $bp->signup->avatar_dir = wp_hash( $user_id ); 
 	                } 
 	                 
 	                      
 	                /* Pass the file to the avatar upload handler */ 
 	                if ( bp_core_avatar_handle_upload( $_FILES, 'bp_core_signup_avatar_upload_dir' ) ) { 

 	                        $bp->avatar_admin->step = 'crop-image'; 
 	                        /* Make sure we include the jQuery jCrop file for image cropping */ 
 	                        add_action( 'wp_print_scripts', 'bp_core_add_jquery_cropper' ); 
 	                } 
 	        } 
 	        
 	        /* If the image cropping is done, crop the image and save a full/thumb version */ 
 	        if ( isset( $_POST['avatar-crop-submit'] ) ) { 
 	 
 	                
 	                /* Check the nonce */ 
 	                check_admin_referer( 'bp_avatar_cropstore' ); 
 	 
 	                /* Reset the avatar step so we can show the upload form again if needed */ 
 	                $bp->signup->step = 'completed-confirmation'; 
 	                $bp->avatar_admin->step = 'upload-image'; 
 	 
 	                if ( !bp_core_avatar_handle_crop( array( 'original_file' => $_POST['image_src'], 'crop_x' => $_POST['x'], 'crop_y' => $_POST['y'], 'crop_w' => $_POST['w'], 'crop_h' => $_POST['h'] ) ) ) 
 	                        bp_core_add_message( __( 'There was a problem cropping your avatar, please try uploading it again', 'buddypress' ), 'error' ); 
 	                else 
 	                        bp_core_add_message( __( 'Your new avatar was uploaded successfully', 'buddypress' ) ); 
 	        } 

    
}

add_action( 'bp_core_screen_signup', 'gg_handle_registration_avatar' );