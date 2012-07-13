<?php
/**
 * @package WordPress
 * @subpackage BuddyPress
 * @sub-subpackage BP Xtra Signup
 * @author Boris Glumpler
 * @copyright 2010, ShabuShabu Webdesign
 * @link http://shabushabu.eu/bp-xtra-signup
 * @license http://www.opensource.org/licenses/gpl-2.0.php GPL License
 */
 
class BPXS_Options
{
	var $price_url;
	var $filepath;
	
	/**
	 * Constructor
	 * @since 1.0
	 */
    function __construct()
	{
		global $bpxs;
		
	    $this->filepath = admin_url() . 'admin.php?page=' . $_GET['page'];
		$this->price_url = $bpxs->home_url . 'prices.php';
        
		// only process if $_POST vars are available
		if( ! empty( $_POST ) )
			$this->processor();
    }

	/**
	 * Process any $_POST variables
	 * @since 1.0
	 */
	function processor()
	{
		global $bpxs;
		
		if ( isset( $_POST['update_bpxs'] ) )
		{	
			check_admin_referer( 'bpxs_settings' );
			
			$error = false;
			$message = '';

			if( $_POST['newsletter'] == true )
			{
				if( empty( $_POST['mcapi_list_id'] ) || empty( $_POST['mcapi_key'] ) )
				{
					$message .= __( 'You need to enter a Mailchimp List ID and an API Key.', 'bpxs' );
					$error = true;
				}
						
				if( ! empty( $_POST['email_recipient'] ) )
				{
					if( ! is_email( $_POST['email_recipient'] ) )
					{
						$message .= __( 'You need to enter a valid email address.', 'bpxs' );
						$error = true;
					}
				}
			}
			
			if( $_POST['date_of_birth'] == true )
			{
				if( empty( $_POST['dob_field'] ) )
				{
						$message .= __( 'You need to specify a date of birth profile field.', 'bpxs' );
						$error = true;
				}
				
				if( empty( $_POST['dob_age'] ) || $_POST['dob_age'] == '----' )
				{
						$message .= __( 'You need to specify an age.', 'bpxs' );
						$error = true;
				}
			}
			
			// proceed if there is no error
			if( ! $error )
			{
				if( $_POST['page_options'] )	
					$options = explode( ',', stripslashes( $_POST['page_options'] ) );
					
				if( $options )
				{
					foreach( $options as $option )
					{
						$option = trim( $option );
						$value = trim( $_POST[$option] );
						
						// make sure all boolean values get saved as such
						if( in_array( $option, array( 'tos', 'newsletter', 'email_confirmation', 'u_availability', 'psw_strength', 'date_of_birth', 'email_check' ) ) )
							$bpxs->options->{$option} = (bool)$value;
						else	
							$bpxs->options->{$option} = $value;
					}
				}
				// Save options
				update_option( 'bpxs_options', $bpxs->options );
				
				BPXS_Admin_Loader::show_message( __( 'Update Successfully', 'bpxs' ) );
			}
			// or show any errors
			else
			{
				BPXS_Admin_Loader::show_error( $message );
			}
		}
		do_action( 'bpxs_update_options_page' );
	}
	
	/**
	 * Render the page content
	 * @since 1.0
	 */
	function controller()
	{
        // get list of tabs
        $tabs = $this->tabs_order();
		?>
        
		<script type="text/javascript">
		jQuery(document).ready(function(){
			jQuery('#slider').tabs({ fxFade: true, fxSpeed: 'fast' });
		});
        </script>
	
        <div id="slider" class="wrap">
        
            <ul id="tabs">
			<?php    
            foreach( $tabs as $tab_key => $tab_name )
               echo "\n\t\t<li><a href='#$tab_key'>$tab_name</a></li>";
            ?>
            </ul>
            
            <?php    
            foreach( $tabs as $tab_key => $tab_name )
			{
                echo "\n\t<div id='$tab_key'>\n";
				
                // Looks for the internal class function, otherwise enable a hook for plugins
                if( method_exists( $this, "tab_$tab_key" ) )
                    call_user_func( array( &$this , "tab_$tab_key" ) );
                else
                    do_action( 'bpxs_tab_content_' . $tab_key );
					
                echo "\n\t</div>";
            } 
            ?>
        </div>
        <?php
	}
	
	/**
	 * Create array for tabs and add a filter for other plugins to inject more tabs
	 * @since 1.0
	 */
    function tabs_order()
	{     
    	$tabs = array();
    	
    	$tabs['settings'] = __( 'Settings', 'bpxs' );
    	$tabs['help'] 	  = __( 'Help', 'bpxs' );
    	$tabs['donate']   = __( 'Donate', 'bpxs' );
    	
    	$tabs = apply_filters( 'bpxs_settings_tabs', $tabs );
    
    	return $tabs;
    }

	/**
	 * Content of the General Options tab
	 * @since 1.0
	 */
    function tab_settings()
	{
        global $bpxs, $wpdb, $bp;
		
		$field_ids = $wpdb->get_results( $wpdb->prepare( "SELECT id, name FROM {$bp->profile->table_name_fields} WHERE parent_id = 0" ) );
		$field_ids = array_merge( array(''), (array)$field_ids );
    	?>
		<script type="text/javascript">
		jQuery(document).ready(function() {
			jQuery('.postbox.close-me').addClass('closed');			
        	jQuery('.postbox h3').click( function() {
				jQuery(this).parent('.postbox').toggleClass('closed');
			});
			jQuery('#show_all').click(function() {
				if( jQuery(this).is(':checked') ){
					jQuery('.postbox').removeClass('closed');
				} else {
					jQuery('.postbox').addClass('closed');
				}
			});
		});
        </script>
        <h2><?php _e( 'Settings','bpxs' ); ?></h2>

        <form name="general" method="post" action="<?php echo $this->filepath ?>" >
        
        	<label id="show_all_label" for="show_all"><input type="checkbox" id="show_all" name="show_all" value="1" /> <?php _e( 'Click to open all option boxes.', 'bpxs' ) ?></label>
        
            <?php wp_nonce_field( 'bpxs_settings' ) ?>
            <input type="hidden" name="page_options" value="tos,signup_title,tos_text,tos_error_text,mcapi_list_id,mcapi_key,newsletter_text,newsletter,email_recipient,email_subject,email_message,email_confirmation,u_availability,psw_strength,date_of_birth,dob_field,dob_age,email_check" />
            <div id="poststuff" class="meta-box-sortables">
                <div class="postbox close-me">
					<div class="handlediv" title="<?php _e( 'Click to toggle', 'bpxs' ) ?>"><br /></div>
                    <h3><?php _e( 'Terms of Service', 'bpxs' ) ?></h3>
                    <div class="inside">
                        <table id="bpxs-tos" class="form-table">
                        <tr>
                            <th><?php _e( 'Activate TOS', 'bpxs' ) ?></th>
                            <td>
                            <select id="bpgs_tos" name="tos">
                                <option <?php selected( true, $bpxs->options->tos ) ?> value="1"><?php _e( 'Yes', 'bpgs' ) ?></option>
                                <option <?php selected( false, $bpxs->options->tos ) ?> value="0"><?php _e( 'No', 'bpgs' ) ?></option>
                            </select> <small>(<?php _e( 'Show the TOS checkbox on the registration screen?', 'bpxs' ); ?>)</small></td>
                        </tr>
                        <tr>
                            <th><?php _e( 'Signup Title', 'bpxs' ) ?></th>
                            <td>
                                <input type="text" class="large-text" id="bpxs_signup_title" name="signup_title" value="<?php echo stripslashes( $bpxs->options->signup_title ); ?>" /><br />
                                <small>(<?php _e( 'The header title of the extra signup options.', 'bpxs' ); ?>)</small>
                            </td>
                        </tr>
                        <tr>
                            <th><?php _e( 'Checkbox Text', 'bpxs' ) ?></th>
                            <td>
                                <input type="text" class="large-text" id="bpxs_tos_text" name="tos_text" value="<?php echo htmlentities( stripslashes( $bpxs->options->tos_text ) ); ?>" /><br />
                                <small>(<?php _e( 'Will be displayed next to the checkbox a new user needs to check.', 'bpxs' ); ?>)</small>
                            </td>
                        </tr>
                        <tr>
                            <th><?php _e( 'Error Text', 'bpxs' ) ?></th>
                            <td>
                                <input type="text" class="large-text" id="bpxs_tos_error_text" name="tos_error_text" value="<?php echo stripslashes( $bpxs->options->tos_error_text ); ?>" /><br />
                                <small>(<?php _e( 'The text that appears when a user does not check the TOS checkbox.', 'bpxs' ); ?>)</small>
                            </td>
                        </tr>
                        </table>
                    </div>
                </div>
                <div class="postbox close-me">
					<div class="handlediv" title="<?php _e( 'Click to toggle', 'bpxs' ) ?>"><br /></div>
                    <h3><?php _e( 'Mailchimp Integration', 'bpxs' ) ?></h3>
                    <div class="inside">
                        <table id="bpxs-newsletter" class="form-table">
                        <tr>
                            <th><?php _e( 'Activate Newsletter', 'bpxs' ) ?></th>
                            <td>
                            <select id="bpxs_newsletter" name="newsletter">
                                <option <?php selected( true, $bpxs->options->newsletter ) ?> value="1"><?php _e( 'Yes', 'bpxs' ) ?></option>
                                <option <?php selected( false, $bpxs->options->newsletter ) ?> value="0"><?php _e( 'No', 'bpxs' ) ?></option>
                            </select> <small>(<?php _e( 'Only activate the newsletter if you have a Mailchimp account.', 'bpxs' ); ?>)</small></td>
                        </tr>
                        <tr>
                            <th><?php _e( 'Newsletter Text', 'bpxs' ) ?></th>
                            <td>
                                <input type="text" class="large-text" id="bpxs_newsletter_text" name="newsletter_text" value="<?php echo stripslashes( $bpxs->options->newsletter_text ); ?>" /><br />
                                <small>(<?php _e( 'The text next to the checkbox on the registration page.', 'bpxs' ); ?>)</small>
                            </td>
                        </tr>
                        <tr>
                            <th><?php _e( 'API Key', 'bpxs' ) ?></th>
                            <td>
                                <input type="text" class="large-text" id="bpxs_mcapi_key" name="mcapi_key" value="<?php echo stripslashes( $bpxs->options->mcapi_key ); ?>" /><br />
                                <small>(<?php _e( 'Your Mailchimp API key.', 'bpxs' ); ?>)</small>
                            </td>
                        </tr>
                        <tr>
                            <th><?php _e( 'List ID', 'bpxs' ) ?></th>
                            <td>
                                <input type="text" class="large-text" id="bpxs_mcapi_list_id" name="mcapi_list_id" value="<?php echo stripslashes( $bpxs->options->mcapi_list_id ); ?>" /><br />
                                <small>(<?php _e( 'The list ID you want new users to subscribe to.', 'bpxs' ); ?>)</small>
                            </td>
                        </tr>
                        <tr>
                            <th><?php _e( 'Email Recipient', 'bpxs' ) ?></th>
                            <td>
                                <input type="text" class="large-text" id="bpxs_email_recipient" name="email_recipient" value="<?php echo stripslashes( $bpxs->options->email_recipient ); ?>" /><br />
                                <small>(<?php _e( 'Enter an email address you want newsletter signup errors sent to.', 'bpxs' ); ?>)</small>
                            </td>
                        </tr>
                        <tr>
                            <th><?php _e( 'Email Subject', 'bpxs' ) ?></th>
                            <td>
                                <input type="text" class="large-text" id="bpxs_email_subject" name="email_subject" value="<?php echo stripslashes( $bpxs->options->email_subject ); ?>" /><br />
                                <small>(<?php _e( 'The subject of the error message.', 'bpxs' ); ?>)</small>
                            </td>
                        </tr>
                        <tr>
                            <th><?php _e( 'Email Message', 'bpxs' ) ?></th>
                            <td>
                                <textarea rows="8" class="large-text" id="bpxs_email_message" name="email_message"><?php echo stripslashes( $bpxs->options->email_message ); ?></textarea><br />
                                <small>(<?php _e( 'The body of the error message.', 'bpxs' ); ?>)</small>
                            </td>
                        </tr>
                        </table>
                    </div>
                </div>
                <div class="postbox close-me">
					<div class="handlediv" title="<?php _e( 'Click to toggle', 'bpxs' ) ?>"><br /></div>
                    <h3><?php _e( 'Email Confirmation', 'bpxs' ) ?></h3>
                    <div class="inside">
                        <table id="bpxs-email-confirmation" class="form-table">
                        <tr>
                            <th><?php _e( 'Activate Email Confirmation', 'bpxs' ) ?></th>
                            <td>
                            <select id="bpxs_email_confirmation" name="email_confirmation">
                                <option <?php selected( true, $bpxs->options->email_confirmation ) ?> value="1"><?php _e( 'Yes', 'bpxs' ) ?></option>
                                <option <?php selected( false, $bpxs->options->email_confirmation ) ?> value="0"><?php _e( 'No', 'bpxs' ) ?></option>
                            </select> <small>(<?php _e( 'Select yes if you want your users to confirm their email address.', 'bpxs' ); ?>)</small></td>
                        </tr>
                        </table>
                    </div>
                </div>
                <div class="postbox close-me">
					<div class="handlediv" title="<?php _e( 'Click to toggle', 'bpxs' ) ?>"><br /></div>
                    <h3><?php _e( 'Ajax Username Availability', 'bpxs' ) ?></h3>
                    <div class="inside">
                        <table id="bpxs-u-availability" class="form-table">
                        <tr>
                            <th><?php _e( 'Activate Username Check', 'bpxs' ) ?></th>
                            <td>
                            <select id="bpxs_u_availability" name="u_availability">
                                <option <?php selected( true, $bpxs->options->u_availability ) ?> value="1"><?php _e( 'Yes', 'bpxs' ) ?></option>
                                <option <?php selected( false, $bpxs->options->u_availability ) ?> value="0"><?php _e( 'No', 'bpxs' ) ?></option>
                            </select> <small>(<?php _e( 'Select yes if you want to activate the ajax username availability check.', 'bpxs' ); ?>)</small></td>
                        </tr>
                        </table>
                    </div>
                </div>
                <div class="postbox close-me">
					<div class="handlediv" title="<?php _e( 'Click to toggle', 'bpxs' ) ?>"><br /></div>
                    <h3><?php _e( 'Ajax Email Check', 'bpxs' ) ?></h3>
                    <div class="inside">
                        <table id="bpxs-email-check" class="form-table">
                        <tr>
                            <th><?php _e( 'Activate Email Check', 'bpxs' ) ?></th>
                            <td>
                            <select id="bpxs_email_check" name="email_check">
                                <option <?php selected( true, $bpxs->options->email_check ) ?> value="1"><?php _e( 'Yes', 'bpxs' ) ?></option>
                                <option <?php selected( false, $bpxs->options->email_check ) ?> value="0"><?php _e( 'No', 'bpxs' ) ?></option>
                            </select> <small>(<?php _e( 'Select yes if you want to activate the ajax email check.', 'bpxs' ); ?>)</small></td>
                        </tr>
                        </table>
                    </div>
                </div>
                <div class="postbox close-me">
					<div class="handlediv" title="<?php _e( 'Click to toggle', 'bpxs' ) ?>"><br /></div>
                    <h3><?php _e( 'Password Strength Meter', 'bpxs' ) ?></h3>
                    <div class="inside">
                        <table id="bpxs-psw-strength" class="form-table">
                        <tr>
                            <th><?php _e( 'Activate Password Strength Meter', 'bpxs' ) ?></th>
                            <td>
                            <select id="bpxs_psw_strength" name="psw_strength">
                                <option <?php selected( true, $bpxs->options->psw_strength ) ?> value="1"><?php _e( 'Yes', 'bpxs' ) ?></option>
                                <option <?php selected( false, $bpxs->options->psw_strength ) ?> value="0"><?php _e( 'No', 'bpxs' ) ?></option>
                            </select> <small>(<?php _e( 'Select yes if you want to activate the password strength meter.', 'bpxs' ); ?>)</small></td>
                        </tr>
                        </table>
                    </div>
                </div>
                <div class="postbox close-me">
					<div class="handlediv" title="<?php _e( 'Click to toggle', 'bpxs' ) ?>"><br /></div>
                    <h3><?php _e( 'Date of Birth Check', 'bpxs' ) ?></h3>
                    <div class="inside">
                        <table id="bpxs-dob-check" class="form-table">
                        <tr>
                            <th><?php _e( 'Activate DOB Check', 'bpxs' ) ?></th>
                            <td>
                            <select id="bpxs_date_of_birth" name="date_of_birth">
                                <option <?php selected( true, $bpxs->options->date_of_birth ) ?> value="1"><?php _e( 'Yes', 'bpxs' ) ?></option>
                                <option <?php selected( false, $bpxs->options->date_of_birth ) ?> value="0"><?php _e( 'No', 'bpxs' ) ?></option>
                            </select> <small>(<?php _e( 'Select yes if you want to activate the date of birth check.', 'bpxs' ); ?>)</small></td>
                        </tr>
                        <tr>
                            <th><label for="dob_field"><?php _e( 'DOB Field ID', 'bpxs' ); ?></label></th>
                            <td>
                                <select id="dob_field" name="dob_field">
                                    <?php foreach( $field_ids as $key => $val ) { ?>
                                    <option value="<?php echo $val->id ?>" <?php selected( $val->id, $bpxs->options->dob_field ) ?>><?php echo $val->name ?></option>
                                    <?php } ?>
                                </select> <small>(<?php _e( 'Select the profile field that represents the date of birth.', 'bpxs' ); ?>)</small></td>
                            </td>
                        </tr>
                        <tr>
                            <th><label for="dob_age"><?php _e( 'Pick your age', 'bpxs' ); ?></label></th>
                            <td>
                                <select id="dob_age" name="dob_age">
                                	<option value="">----</option>
                                    <?php for( $i = 10; $i <= 21; $i++ ) { ?>
                                    <option value="<?php echo $i ?>" <?php selected( $i, $bpxs->options->dob_age ) ?>><?php echo $i ?></option>
                                    <?php } ?>
                                </select> <small>(<?php _e( 'Select the age you need your users to be above.', 'bpxs' ); ?>)</small></td>
                            </td>
                        </tr>
                        </table>
                    </div>
                </div>
			</div>      
            <div class="submit"><input type="submit" name="update_bpxs" value="<?php _e( 'Update' ) ;?> &raquo;"/></div>
        </form>
        <?php	
	}

	/**
	 * Content of the Help tab
	 * @since 1.0
	 */
    function tab_help()
	{
		global $jb;

		$shabu = get_option( 'shabu_software' );
			
		$diff = strtotime( date( 'Y-m-d H:i:s' ) ) - strtotime( $shabu['time'] );
		// one ping per day
		$api_time_seconds = 86400;
	
		if( ! $shabu || $diff >= $api_time_seconds )
		{
			if( ! class_exists( 'WP_Http' ) )
				include_once( ABSPATH . WPINC. '/class-http.php' );
			
			$request = new WP_Http;
			$http = $request->request( $this->price_url );
			
			if( is_array( $http ) )
			{
				$result = unserialize( $http['body'] );
				
				$shabu_array = array( 'time' => date( 'Y-m-d H:i:s' ), 'info' => $result );
				
				update_option( 'shabu_software', $shabu_array );
			}
			else
				$http_error = true;
		}
		else
			$result = $shabu['info'];

		if( ! $http_error )
		{
			?>
			<h2><?php _e( 'Help', 'jobs' ); ?></h2>
			
			<p><?php printf( __( 'To receive support for this plugin, please <a href="%s">register</a> on <a href="%s">ShabuShabu.eu</a>.', 'jobs' ), $jb->home_url . 'membership-options/', $jb->home_url ); ?></p>
			<p><?php _e( 'Registration is free. Support, however, will only be provided within the support group of this plugin, for which we charge a small monthly subscription fee.', 'jobs' ); ?></p>
	
			<table id="jb-prices" class="widefat">
				<thead>
				<tr>
					<th class="manage-column" scope="col"><?php _e('Description', 'jobs' ); ?></th>
					<th class="manage-column" scope="col"><?php _e('Price', 'jobs' ); ?></th>
				</tr>
				</thead>
				<?php foreach( $result['prices'] as $desc => $price )
				{
					 $alt = $this->alternate( 'odd', 'even' );
					?>
					<tr class="<?php echo $alt; ?>">
						<td><?php echo $desc ?></td>
						<td>EUR <?php echo $price ?></td>
					</tr>
					<?php
				}
				?>
			</table>
			
			<p><?php _e( 'Below you find a list of all our available and planned plugins and themes. Items that have a price tag attached come with 3 months free support.', 'jobs' ); ?></p>
	
			<h3><?php _e( 'Plugins', 'jobs' ) ?></h3>      
			<table class="widefat" cellspacing="0">
				<thead>
				<tr>
					<th class="manage-column" scope="col"><?php _e('Name', 'jobs' ); ?></th>
					<th class="manage-column" scope="col"><?php _e('Description', 'jobs' ); ?></th>
					<th class="manage-column" scope="col"><?php _e('Release Date', 'jobs' ); ?></th>
					<th class="manage-column" scope="col"><?php _e('Price', 'jobs' ); ?></th>
				</tr>
				</thead>
			
				<?php foreach( $result['plugins'] as $plugin )
				{
					 $alt = $this->alternate( 'odd', 'even' );
					?>
					<tr class="<?php echo $alt; ?>">
						<td>
						<?php if( ! empty( $plugin['url'] ) ) { ?>
							<a href="<?php echo $plugin['url']; ?>"><?php echo $plugin['name'] . ' ' . $plugin['version'] ; ?></a>
						<?php } else { ?>
							<?php echo $plugin['name'] . ' ' . $plugin['version'] ; ?>
						<?php } ?>
						</td>
						<td><?php echo $plugin['desc']; ?></td>
						<td><?php echo $plugin['release']; ?></td>
						<td><?php echo $plugin['cost']; ?></td>
					</tr>
				<?php } ?>
			</table>
            
            
			<?php if( $result['themes'] ) : ?>
            
			<h3><?php _e( 'Themes', 'jobs' ) ?></h3>      
			<table class="widefat" cellspacing="0">
				<thead>
				<tr>
					<th class="manage-column" scope="col"><?php _e('Name', 'jobs' ); ?></th>
					<th class="manage-column" scope="col"><?php _e('Description', 'jobs' ); ?></th>
					<th class="manage-column" scope="col"><?php _e('Release Date', 'jobs' ); ?></th>
					<th class="manage-column" scope="col"><?php _e('Price', 'jobs' ); ?></th>
				</tr>
				</thead>
			
				<?php foreach( $result['themes'] as $theme )
				{
					 $alt = $this->alternate( 'odd', 'even' );
					?>
					<tr class="<?php echo $alt; ?>">
						<td>
						<?php if( ! empty( $theme['url'] ) ) { ?>
							<a href="<?php echo $theme['url']; ?>"><?php echo $theme['name'] . ' ' . $theme['version'] ; ?></a>
						<?php } else { ?>
							<?php echo $theme['name'] . ' ' . $theme['version'] ; ?>
						<?php } ?>
						</td>
						<td><?php echo $theme['desc']; ?></td>
						<td><?php echo $theme['release']; ?></td>
						<td><?php echo $theme['cost']; ?></td>
					</tr>
				<?php } ?>
			</table>
            
			<?php endif;
		}
		else
		{
			echo '<h2>'. __( 'Help', 'jobs' ) .'</h2>';
			echo '<p>'. __( 'Information could not be retrieved.', 'jobs' ) .'</p>';
		}
	}

	/**
	 * Content of the Donate tab
	 * @since 1.0
	 */
    function tab_donate()
	{
		?>
        <h2><?php _e( 'Donate', 'bpxs' ); ?></h2>
        
        <p><?php _e( 'We spend a lot of time and effort on implementing new features and on the maintenance of this plugin, so if you feel generous and have a few bucks to spare, then please consider to donate.', 'bpxs' ); ?></p>
        <p><?php _e( 'Click on the button below and you will be redirected to the PayPal site where you can make a safe donation', 'bpxs' ); ?></p>
        <p>
            <form action="https://www.paypal.com/cgi-bin/webscr" method="post" >
                <input type="hidden" name="cmd" value="_xclick"/><input type="hidden" name="business" value="mail@shabushabu-webbdesign.com"/>
                <input type="hidden" name="item_name" value="<?php _e('BP Xtra Signup @ http://shabushabu.eu','bpxs'); ?>"/>
                <input type="hidden" name="no_shipping" value="1"/><input type="hidden" name="return" value="http://shabushabu.eu/" />
                <input type="hidden" name="cancel_return" value="http://shabushabu.eu/"/>
                <input type="hidden" name="lc" value="US" /> 
                <input type="hidden" name="currency_code" value="USD"/>
                <input type="hidden" name="tax" value="0"/>
                <input type="hidden" name="bn" value="PP-DonationsBF"/>
                <input type="image" src="https://www.paypal.com/en_US/i/btn/x-click-but21.gif" name="submit" alt="<?php _e( 'Make payments with PayPal - it\'s fast, free and secure!', 'bpxs' ); ?>" style="border: none;"/>
            </form>
        </p>
        <p><?php _e( 'Thank you and all the best!' ,'bpxs' ); ?><br />ShabuShabu Webdesign Team</p>
        <?php
	}

	/**
	 * alternate between anything
	 * @since 1.0
	 */
	function alternate()
	{
		static $i = 0;
		$args = func_get_args();
	
		return $args[$i++ % (func_num_args())];
	}
}
?>