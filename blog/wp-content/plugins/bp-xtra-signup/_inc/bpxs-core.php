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

require( BPXS_ABSPATH .'_inc/bpxs-js-css.php' );

/**
* Add custom userdata from register.php
* @since 1.0
*/
function bpxs_add_to_signup( $usermeta )
{
	global $bpxs;
	
	if( $bpxs->options->tos == true )
		$usermeta['accept_tos'] = $_POST['accept_tos'];

	if( $bpxs->options->newsletter == true )
	{
		if( isset( $_POST['newsletter'] ) )
			$usermeta['newsletter'] = $_POST['newsletter'];
	}
	
	return $usermeta;
}
add_filter( 'bp_signup_usermeta', 'bpxs_add_to_signup' );

/**
* Perform checks for custom registration data
* @since 1.0
*/
function bpxs_check_additional_signup()
{
	global $bp, $bpxs;
	
	if( $bpxs->options->tos == true )
	{
		if( $_POST['accept_tos'] != 'agreed'  )
			$bp->signup->errors['accept_tos'] = stripcslashes( $bpxs->options->tos_error_text );
	}
	
	if( $bpxs->options->date_of_birth == true && ! empty( $bpxs->options->dob_field ) )
	{
		$dob = $_POST['field_'. $bpxs->options->dob_field .'_year'] .'-'. bpxs_get_month_number( $_POST['field_'. $bpxs->options->dob_field .'_month'] ) .'-'. $_POST['field_'. $bpxs->options->dob_field .'_day'];

		if( ! bpxs_check_dob( $dob ) )
			$bp->signup->errors['field_' . $bpxs->options->dob_field] = sprintf( __( 'You need to be at least %d years old to join this network.', 'bpxs' ), $bpxs->options->dob_age );
	}
}
add_action( 'bp_signup_validate', 'bpxs_check_additional_signup' );

/**
* Update usermeta with custom registration data
* @since 1.0
*/
function bpxs_user_activate_fields( $user_id, $user_login, $user_password, $user_email, $usermeta )
{
	global $bpxs;
	
	if( $bpxs->options->tos == true )
		update_usermeta( $user_id, 'accept_tos', $usermeta['accept_tos'] );

	if( $bpxs->options->newsletter == true )
		update_usermeta( $user_id, 'newsletter', $usermeta['newsletter'] );
	
	return $user_id;
}
add_action( 'bp_core_signup_user', 'bpxs_user_activate_fields', 5, 5 );

/**
* Subscribes a user to our newsletter if checked
* @since 4.0
*/
function bpxs_subscribe_newsletter( $user_id, $user_login, $user_password, $user_email, $usermeta )
{
	global $bpxs;
	
	if( $bpxs->options->newsletter == true )
	{
		if( $usermeta['newsletter'] == 'agreed' )
		{
			require_once( BPXS_ABSPATH . '_inc/MCAPI.class.php' );
		
			$api = new MCAPI( $bpxs->options->mcapi_key );
			
			$u = get_userdata( $user_id );
			
			$merge_vars = array( 'FNAME' => $u->nickname, 'LNAME'=> '' );
			
			$retval = $api->listSubscribe( $bpxs->options->mcapi_list_id, $u->user_email, $merge_vars );
			
			if( $api->errorCode && ! empty( $bpxs->options->email_recipient ) )
			{
				$body = stripslashes( $bpxs->options->email_message );
				$body = str_replace( '{ERROR_CODE}', $api->errorCode, $body );
				$body = str_replace( '{ERROR_MESSAGE}', $api->errorMessage, $body );
				$body = str_replace( '{USER_ID}', $user_id, $body );
				$body = str_replace( '{USER_NAME}', $auth->nickname, $body );
				$body = str_replace( '{USER_EMAIL}', $auth->user_email, $body );
	
				wp_mail( $bpxs->options->email_recipient, $bpxs->options->email_subject, $body );
			}
		}
		else
			update_usermeta( $user_id, 'newsletter', 'declined' );
	}
}
add_action( 'bp_core_signup_user', 'bpxs_subscribe_newsletter', 5, 5 );

/**
 * Add newsletter and TOS to register page
 * @since 1.0
 */
function bpxs_add_to_registration()
{
	global $bpxs;

	?>
    <div id="tos" class="register-section">
        <h4><?php echo stripslashes( $bpxs->options->signup_title ) ?></h4>
        
        <?php if( $bpxs->options->tos == true ) { ?>
			<?php do_action( 'bp_accept_tos_errors' ) ?>
            <label><input type="checkbox" name="accept_tos" id="accept_tos" value="agreed" /> <?php echo stripslashes( $bpxs->options->tos_text ) ?></label>
		<?php } ?>

        <?php if( $bpxs->options->newsletter == true ) { ?>
            <label><input type="checkbox" name="newsletter" id="newsletter" value="agreed" /> <?php echo stripslashes( $bpxs->options->newsletter_text ) ?></label>
		<?php } ?>
    </div>
    <?php
}
add_action( 'bp_before_registration_submit_buttons', 'bpxs_add_to_registration' );

/**
 * Add email confirmation to register page
 * Code by Francesco Laffi
 * http://flweb.it/2010/05/add-confirm-email-field-in-buddypress-signup-page/
 * @since 1.2
 */
function bpxs_add_email_confirm()
{
	global $bpxs;
	
	if( $bpxs->options->email_confirmation == true )
	{
		$email = empty( $_POST['signup_email_first'] ) ? '' : $_POST['signup_email_first'];
		
		do_action( 'bp_signup_email_first_errors' ); ?>
		
		<input type="text" name="signup_email_first" id="signup_email_first" value="<?php echo $email ?>" />
        
		<label><?php _e( 'Confirm Email (required)', 'bpxs' ); ?></label>
		<?php do_action( 'bp_signup_email_second_errors' );
	}
}
add_action( 'bp_signup_email_errors', 'bpxs_add_email_confirm', 20 );

/**
 * Check email confirmation
 * Code by Francesco Laffi
 * http://flweb.it/2010/05/add-confirm-email-field-in-buddypress-signup-page/
 * @since 1.2
 */
function bpxs_check_email_confirm()
{
	global $bp, $bpxs;

	if( $bpxs->options->email_confirmation == true )
	{
		// unset any existing errors
		unset( $bp->signup->errors['signup_email'] );
	
		//check if email address is correct and set an error message for the first field if any
		$account_details = bp_core_validate_user_signup( $_POST['signup_username'], $_POST['signup_email_first'] );
		
		if( ! empty( $account_details['errors']->errors['user_email'] ) )
			$bp->signup->errors['signup_email_first'] = $account_details['errors']->errors['user_email'][0];
	
		if( ! empty( $_POST['signup_email_first'] ) )
		{
			if( empty( $_POST['signup_email'] ) )
				$bp->signup->errors['signup_email_second'] = __( 'Please make sure you enter your email twice', 'bpxs' );
				
			elseif( $_POST['signup_email'] != $_POST['signup_email_first'] )
				$bp->signup->errors['signup_email_second'] = __( 'The emails you entered do not match.', 'bpxs' );
		}
	}
}
add_action('bp_signup_validate', 'bpxs_check_email_confirm');

/**
 * Check username availability
 * Code mostly by Brajesh Singh
 * http://buddydev.com/buddypress/creating-a-buddypress-wordpress-username-availability-checker-for-your-site/
 * @since 1.3
 */
function bpxs_check_username()
{
	global $bpxs;
	
	if( $bpxs->options->u_availability == true )
	{
		if( ! empty( $_POST['user_name'] ) )
		{
			$user_name = sanitize_user( $_POST['user_name'] );
			
			if( bpxs_username_exists( $user_name ) )
			{
				$new_name = bpxs_next_username( $user_name );
				$msg = array( 'code' => 'taken', 'message' => sprintf( __( 'This usename is taken, please choose another one, e.g. %s', 'bpxs' ), $new_name ) );
			}
		
			if( empty( $msg ) )
			{
				$check = bpxs_validate_username( $user_name );
				
				if( empty( $check ) )
					$msg = array( 'code' => 'success', 'message' => __( 'This username is available.', 'bpxs' ) );
				else
					$msg = array( 'code' => 'error', 'message' => $check );
			}
		}
		else
			$msg = array( 'code' => 'error', 'message' => __( 'You have to chose a username.', 'bpxs' ) );
			
		$msg = apply_filters( 'bpxs_custom_message_filter', $msg );

		echo json_encode( $msg );
	}
}
add_action( 'wp_ajax_check_username', 'bpxs_check_username' );

/**
 * Check if a username exists already
 * @since 1.3.1
 */
function bpxs_username_exists( $user_name )
{
	include_once( ABSPATH. WPINC . '/registration.php' );
		
	if( username_exists( $user_name ) )
		return true;
		
	elseif( defined( 'WP_ALLOW_MULTISITE' ) )
	{
		global $wpdb;
		
		if( $user = $wpdb->get_row( $wpdb->prepare("SELECT * FROM {$wpdb->signups} WHERE user_login = %s", $user_name ) ) )
			return true;
	}
	
	return false;
}

/**
 * Look for the next available username
 * @since 1.3.1
 */
function bpxs_next_username( $user_name )
{
	$new_name = $user_name . '2';
	$check = bpxs_username_exists( $new_name );

	if( $check )
	{
		$suffix = 3;
		do {
			$alt_user_name = $user_name . $suffix;
			$check = bpxs_username_exists( $user_name . $suffix );
			$suffix++;
		} while ( $check );
		
		$new_name = $alt_user_name;
	}

	return apply_filters( 'bpxs_next_available_username', $new_name );
}

/**
 * Validate a username
 * Code by Brajesh Singh
 * http://buddydev.com/buddypress/creating-a-buddypress-wordpress-username-availability-checker-for-your-site/
 * @since 1.3
 */
function bpxs_validate_username( $user_name )
{
	global $wpdb;

	$maybe = array();
	preg_match( "/[a-z0-9]+/", $user_name, $maybe );

	$db_illegal_names = get_option( 'illegal_names' );
	$filtered_illegal_names = apply_filters( 'bp_core_illegal_usernames', array( 'www', 'web', 'root', 'admin', 'main', 'invite', 'administrator', BP_GROUPS_SLUG, BP_MEMBERS_SLUG, BP_FORUMS_SLUG, BP_BLOGS_SLUG, BP_REGISTER_SLUG, BP_ACTIVATION_SLUG ) );

	$illegal_names = array_merge( (array)$db_illegal_names, (array)$filtered_illegal_names );

	if( ! validate_username( $user_name ) || $user_name != $maybe[0] )
		$error = __( 'Only lowercase letters and numbers allowed.', 'bpxs' );
		
	if( in_array( $user_name, (array)$illegal_names ) )
		$error = __( 'This username is reserved. Please chose another one.', 'bpxs' );

	if( strlen( $user_name ) < 4 )
		$error =  __( 'Username must be at least 4 characters.', 'bpxs' ) ;

	if( strpos( ' ' . $user_name, '_' ) != false )
		$error = __( 'Usernames must not contain the character "_"!', 'bpxs' ) ;

	$match = array();
	preg_match( '/[0-9]*/', $user_name, $match );

	if( $match[0] == $user_name )
		$error = __( 'Usernames must contain letters too!', 'bpxs' ) ;

	$error = apply_filters( 'bpxs_custom_error', $error, $user_name );
	
	return $error;
}

/**
 * Add js translation strings for password strength meter
 * @since 1.3
 */
function bpxs_add_js_l10n()
{
	global $bpxs;
	
	if( $bpxs->options->psw_strength == true )
	{
		?>
		<script type='text/javascript'>
		/* <![CDATA[ */
		var pwsL10n = {
			empty: "<?php echo esc_js( __( 'Strength indicator', 'bpxs' ) ); ?>",
			short: "<?php echo esc_js( __( 'Very weak', 'bpxs' ) ); ?>",
			bad: "<?php echo esc_js( __( 'Weak', 'bpxs' ) ); ?>",
			good: "<?php echo esc_js( _x( 'Medium', 'password strength', 'bpxs' ) ); ?>",
			strong: "<?php echo esc_js( __( 'Strong', 'bpxs' ) ); ?>",
			mismatch: "<?php echo esc_js( __( 'Mismatch', 'bpxs' ) ); ?>",
			title: "<?php echo esc_js( __( 'Strength indicator', 'bpxs' ) ); ?>",
			desc: "<?php echo esc_js( __( 'Hint: The password should be at least seven characters long. To make it stronger, use upper and lower case letters, numbers and symbols like ! " ? $ % ^ & ).', 'bpxs' ) ); ?>"
		};
		/* ]]> */
		</script>
		<?php
	}
	
	if( $bpxs->options->email_check == true )
	{
		?>
		<script type='text/javascript'>
		/* <![CDATA[ */
		<?php if( $bpxs->options->email_confirmation == true ) : ?>
		var signup_mail_field = 'input#signup_email_first';
		var compare_error = "<?php echo esc_js( __( "The emails don't match!", 'bpxs' ) ); ?>";
		<?php else : ?>
		var signup_mail_field = 'input#signup_email';
		<?php endif; ?>
		/* ]]> */
		</script>
		<?php
	}

	if( $bpxs->options->email_confirmation == true )
	{
		?>
		<script type='text/javascript'>
		/* <![CDATA[ */
		var compare_error = "<?php echo esc_js( __( "The emails don't match!", 'bpxs' ) ); ?>";
		/* ]]> */
		</script>
		<?php
	}
}
add_action( 'bp_after_registration_submit_buttons', 'bpxs_add_js_l10n' );

/**
 * Check the D.O.B.
 * @since 1.4
 */
function bpxs_check_dob( $dob )
{
	global $bpxs;
	
    list( $y, $m, $d ) = explode( "-", $dob );
    $age = ( date( "md" ) < $m . $d ) ? date( "Y" ) - $y - 1 : date( "Y" ) - $y;
	
	if( $age >= $bpxs->options->dob_age )
		return true;
		
	return false;
}

/**
 * Transform month names into their numbers
 * @since 1.4
 */
function bpxs_get_month_number( $name )
{
	$names = array(
		1  => __( 'January', 'bpxs' ),
		2  => __( 'February', 'bpxs' ),
		3  => __( 'March', 'bpxs' ),
		4  => __( 'April', 'bpxs' ),
		5  => __( 'May', 'bpxs' ),
		6  => __( 'June', 'bpxs' ),
		7  => __( 'July', 'bpxs' ),
		8  => __( 'August', 'bpxs' ),
		9  => __( 'September', 'bpxs' ),
		10 => __( 'October', 'bpxs' ),
		11 => __( 'November', 'bpxs' ),
		12 => __( 'December', 'bpxs' )
	);
	
	return array_search( $name, $names ); 	
}

/**
 * Check for existing email
 * @since 1.5
 */
function bpxs_check_useremail()
{
	global $bpxs;
	
	if( $bpxs->options->email_check == true )
	{
		include_once( ABSPATH. WPINC . '/registration.php' );
		
		if( ! empty( $_POST['email'] ) )
		{
			if( ! is_email( $_POST['email'] ) )
				$msg = array( 'code' => 'error', 'message' => __( 'Please enter a valid email address.', 'bpxs' ) );
		
			if( email_exists( $_POST['email'] ) )
				$msg = array( 'code' => 'error', 'message' => __( 'Sorry, that email address is already in use!', 'bpxs' ) );
				
			if( ! $msg )
				$msg = array( 'code' => 'success', 'message' => __( 'This email address is valid.', 'bpxs' ) );
		}
		else
			$msg = array( 'code' => 'error', 'message' => __( 'You have to enter an email address.', 'bpxs' ) );
			
		$msg = apply_filters( 'bpxs_custom_email_message_filter', $msg );

		echo json_encode( $msg );
	}
}
add_action( 'wp_ajax_check_email', 'bpxs_check_useremail' );
?>