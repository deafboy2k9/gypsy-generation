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

/**
 * Setup the default options
 * @since 1.0
 */
function bpxs_install()
{
	global $bpxs;
	
	$options = get_option( 'bpxs_options' );
	
	if( ! $options )
	{
		$bpxs->options = new stdClass;
		$bpxs->options->tos					= true;
		$bpxs->options->email_confirmation	= false;
		$bpxs->options->newsletter			= false;
		$bpxs->options->psw_strength		= true;
		$bpxs->options->u_availability		= true;
		$bpxs->options->email_check			= true;
		$bpxs->options->date_of_birth		= true;
		$bpxs->options->dob_field			= '';
		$bpxs->options->dob_age				= 13;
		$bpxs->options->mcapi_list_id		= '';
		$bpxs->options->mcapi_key			= '';
		$bpxs->options->signup_title		= __( "Don't forget", 'bpxs' );
		$bpxs->options->newsletter_text		= __( 'Check this box if you want to subscribe to our newsletter.', 'bpxs' );
		$bpxs->options->tos_text			= __( 'Please make sure to read our <a href="">Terms of Service</a> and then check this box (required).', 'bpxs' );
		$bpxs->options->tos_error_text		= __( 'You have to check our Terms Of Service to continue.', 'bpxs' );
		$bpxs->options->email_recipient		= get_option( 'admin_email' );
		$bpxs->options->email_subject		= __( 'Unsuccessful newsletter signup', 'bpxs' );
		$bpxs->options->email_message		= __( 'Hello,
	
there has been an error with a new subscriber to your newsletter! Check the error and send a message to the user if signup to the newsletter has been unsuccessful.

Unable to load listSubscribe()!

UserID: {USER_ID}
Name: {USER_NAME}
Email: {USER_EMAIL}

Code: {ERROR_CODE}
Msg: {ERROR_MESSAGE}', 'bpxs' );
	
		// write to the database
		update_option( 'bpxs_options', $bpxs->options );
	}
}

/**
 * Delete all options and database tables
 * @since 1.0
 */
function bpxs_uninstall()
{
	delete_option( 'bpxs_options' );
}
?>