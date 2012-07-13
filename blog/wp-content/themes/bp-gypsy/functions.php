<?php

if ( !function_exists( 'bp_dtheme_enqueue_styles' ) ) :
function bp_dtheme_enqueue_styles() {
 
    // You should bump this version when changes are made to bust cache
    $version = '20120624';
 
    // Register stylesheet of bp-dusk child theme
    wp_register_style( 'bp-gypsy', get_stylesheet_directory_uri() . '/style.css', array(), $version );
 
    // Enqueue stylesheet of bp-gypsy child theme
    wp_enqueue_style( 'bp-gypsy' );
    
    $version = '20120624';
    
    wp_deregister_script( 'jquery' );
	wp_register_script('jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js');
	wp_enqueue_script('jquery');
    
    //Register the global plugings
    wp_register_script('plugins-js', get_stylesheet_directory_uri() . '/javascripts/plugins.js', array('jquery'), $version);

	// Enqueue the global JS - Ajax will not work without it
	wp_enqueue_script( 'plugins-js' );
	
	// Enqueue the global JS - Ajax will not work without it
	wp_register_script( 'script-js', get_stylesheet_directory_uri() . '/javascripts/script.js', array( 'jquery' ), $version );
	
	wp_enqueue_script('script-js');
	
	wp_dequeue_style ( 'bp-admin-bar');
	wp_dequeue_style ( 'bp-admin-bar-rtl');
}
add_action( 'wp_enqueue_scripts', 'bp_dtheme_enqueue_styles' , 1 );
endif;

function remove_admin_bar_links() {
	global $wp_admin_bar;
	$wp_admin_bar->remove_menu('wp-logo');
	$wp_admin_bar->remove_menu('updates');
}

//add_action( 'wp_before_admin_bar_render', 'remove_admin_bar_links' );


function gg_get_activity_latest_update( $user_id = 0 ) {
    
	global $bp;

	if ( !$user_id )
		$user_id = $bp->displayed_user->id;

	if ( bp_core_is_user_spammer( $user_id ) || bp_core_is_user_deleted( $user_id ) )
		return false;

	if ( !$update = bp_get_user_meta( $user_id, 'bp_latest_update', true ) )
		return false;

	$latest_update = ' <a href="' . bp_get_root_domain() . '/' . bp_get_activity_root_slug() . '/p/' . $update['id'] . '/"> ' . apply_filters( 'bp_get_activity_latest_update_excerpt', trim( strip_tags( bp_create_excerpt( $update['content'], 358 ) ) ) ) . '</a>';

	return apply_filters( 'bp_get_activity_latest_update', $latest_update  );
}

function gg_custom_profile_tabs()
{
    global $bp;
    
    $bp->bp_nav['profile'] = false;
    $bp->bp_nav['activity']['name'] = 'Local feed';
    $bp->bp_nav['friends']['name'] = 'Network';
    $bp->bp_nav['groups']['name'] = 'Groups';
    $bp->bp_nav['blogs'] = false;
    $bp->bp_nav['messages'] = false;
    
    if(bp_is_my_profile())
    {
        $bp->bp_nav['settings']['name'] = 'My settings';
        
    }else {
        $bp->bp_nav['settings'] = false;
    }
    
    $bp->bp_nav['personal'] = false;
   
}

add_action( 'bp_setup_nav', 'gg_custom_profile_tabs');

function gg_custom_options_tabs()
{
    global $bp;
    
    //$bp->bp_options_nav['activity']['just-me']['friends'] = false;
}

add_action( 'bp_setup_nav', 'gg_custom_options_tabs');

function gg_member_location($countryCode)
{
    global $wpdb;
    $sql = 'select CONCAT_WS(", ", name, code) from gg_country where code = "'.$countryCode.'"';
    $location = $wpdb->get_var($wpdb->prepare($sql));

    echo $location;
}

function gg_member_focus_group($groupId)
{
    $group = groups_get_group( array( 'group_id' => $groupId ) );
    echo $group->name;        
}

function gg_member_footprint()
{
        
}

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
     wp_enqueue_script( 'bpd-js', get_stylesheet_directory_uri() . '/javascripts/xprofile-fields.js',
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

function gg_get_digital_footprint($userId)
{
    $options = array_filter(xprofile_get_field_data('Your digital footprint', $userId));
    
    echo '<ul>';
    foreach($options as $option)
    {
      echo '<li>';
      echo '<a href="'.$option.'" target="_blank">'.$option.'</a>';
      echo '</li>';
    }
    echo '</ul>';
}

function gg_get_my_profile_url()
{
    global $bp;
    
    echo $bp->loggedin_user->domain;
}

function gg_bp_adminbar_notifications_menu() {
	global $bp;

	if ( !is_user_logged_in() )
		return false;

	echo '<li id="bp-adminbar-notifications-menu"><a href="' . $bp->loggedin_user->domain . '">';
	_e( 'Notifications', 'buddypress' );

	if ( $notifications = bp_core_get_notifications_for_user( $bp->loggedin_user->id ) ) { ?>
		<span><?php echo count($notifications) ?></span>
	<?php
	}
	
	
	echo '</a>';
	echo '<ul>';

	if ( $notifications ) { ?>
		<?php $counter = 0; ?>
		<?php for ( $i = 0; $i < count($notifications); $i++ ) { ?>
			<?php $alt = ( 0 == $counter % 2 ) ? ' class="alt"' : ''; ?>
			<li<?php echo $alt ?>><?php echo $notifications[$i] ?></li>
			<?php $counter++; ?>
		<?php } ?>
	<?php } else { ?>
		<li><a href="<?php echo $bp->loggedin_user->domain ?>"><?php _e( 'No new notifications.', 'buddypress' ); ?></a></li>
	<?php
	}

	echo '</ul>';
	echo '</li>';
}


function gg_notifications_menu() {
		global $bp;

		if ( !is_user_logged_in() )
			return false;

		echo '<li id="bp-adminbar-notifications-menu"><a href="' . $bp->loggedin_user->domain . '">';
		
		if ( $notifications = bp_core_get_notifications_for_user( $bp->loggedin_user->id ) ) { ?>
			<span><?php echo (string) count($notifications) ?></span>
		<?php
		}

		echo '</a>';
		echo '<ul>';

		if ( $notifications ) { ?>
			<?php $counter = 0; ?>
			<?php for ( $i = 0; $i < count($notifications); $i++ ) { ?>
				<?php $alt = ( 0 == $counter % 2 ) ? ' class="alt"' : ''; ?>
				<li<?php echo $alt ?>><?php echo $notifications[$i] ?></li>
				<?php $counter++; ?>
			<?php } ?>
		<?php } else { ?>
			<li>
				<a href="<?php echo $bp->loggedin_user->domain ?>">0</a>
			</li>
		<?php
		}

		echo '</ul>';
		echo '</li>';
}

function gg_notifications_count()
{
    global $bp;
    echo '<li id="bp-adminbar-notifications-menu"><a href="' . $bp->loggedin_user->domain . '">';
    echo (string) count(bp_core_get_notifications_for_user($bp->loggedin_user_id));
    echo '</a></li>';
}

remove_action( 'bp_adminbar_menus', 'bp_adminbar_notifications_menu', 8 );