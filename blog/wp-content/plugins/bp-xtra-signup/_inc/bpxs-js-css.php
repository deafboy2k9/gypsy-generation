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

class BPXS_JS_CSS
{
	function __construct()
	{
		if( ! is_admin() )
		{
			add_action( 'wp_print_scripts', array( &$this, 'load_scripts' ) );
			add_action( 'wp_print_styles', array( &$this, 'load_styles' ) );
		}
	}

	function load_styles()
	{
		if( bp_is_register_page() )
			wp_enqueue_style( 'bpxs-register-css', BPXS_URLPATH .'css/style.css' );
	}
	
	function load_scripts()
	{
		global $bpxs;
		
		if( bp_is_register_page() )
			if( $bpxs->options->u_availability == true || $bpxs->options->psw_strength == true || $bpxs->options->email_check == true )
				wp_enqueue_script( 'bpxs-js', BPXS_URLPATH .'js/js.php', array( 'jquery' ), '1.0', true );
	}
}
$bpxs_js_css = new BPXS_JS_CSS();
?>