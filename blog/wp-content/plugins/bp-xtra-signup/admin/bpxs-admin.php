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
 
class BPXS_Admin_Loader
{
	/**
	 * Constructor
	 * @since 1.0
	 */
	function __construct()
	{
		add_action( 'admin_menu', array( &$this, 'add_menu' ), 20 );
		add_action( 'admin_print_scripts', array( &$this, 'load_scripts' ) );
		add_action( 'admin_print_styles', array( &$this, 'load_styles' ) );
		add_filter( 'contextual_help', array( &$this, 'show_help' ), 10, 2 );
	}

	/**
	 * Add the options page
	 * @since 1.0
	 */
	function add_menu()
	{
		add_submenu_page( 'bp-general-settings', __( 'BP Xtra Signup', 'bpxs' ), __( 'BP Xtra Signup', 'bpxs' ), 'manage_options', BPXS_FOLDER, array( &$this, 'show_menu' ) );
	}

	/**
	 * Display the options page
	 * @since 1.0
	 */
	function show_menu()
	{
		global $bpxs;
		
		include_once( dirname( __FILE__ ). '/bpxs-settings.php' );
		$bpxs->options_page = new BPXS_Options();
		$bpxs->options_page->controller();
	}

	/**
	 * Load necessary scripts
	 * @since 1.0
	 */
	function load_scripts()
	{
		// no need to go on if it's not a plugin page
		if( ! isset( $_GET['page'] ) )
			return;

		if( $_GET['page'] == BPXS_FOLDER ) 
		{
			wp_enqueue_script( 'jquery-ui-tabs' );
		}
	}		
	
	/**
	 * Load necessary styles
	 * @since 1.0
	 */
	function load_styles()
	{
		// no need to go on if it's not a plugin page
		if( ! isset( $_GET['page'] ) )
			return;

		if( $_GET['page'] == BPXS_FOLDER ) 
		{
			wp_enqueue_style( 'bpxstabs', BPXS_URLPATH .'admin/css/jquery.ui.tabs.css', false, '1.0', 'screen' );
			wp_enqueue_style( 'bpxsadmin', BPXS_URLPATH .'admin/css/bpxs-admin.css', false, '1.0', 'screen' );
		}
	}
	
	/**
	 * Add some helpful links
	 * @since 1.0
	 */
	function show_help( $help, $screen_id )
	{
		global $bpxs;
			
		if( $screen_id == 'buddypress_page_'. BPXS_FOLDER )
		{
			$help  = '<h5>' . __( 'Get help for BP Xtra Signup', 'bpxs' ) . '</h5>';
			$help .= '<div class="metabox-prefs">';
			$help .= '<a href="'. $bpxs->home_url .'forums/">' . __( 'Support Forums', 'bpxs' ) . '</a><br />';
			$help .= '<a href="'. $bpxs->home_url .'donation/">' . __( 'Donate', 'bpxs' ) . '</a><br />';
			$help .= '</div>';
			
			return $help;
		}
	}

	/**
	 * Show a success message
	 * @since 1.0
	 */
	function show_message( $message )
	{
		echo '<div class="wrap"><h2></h2><div class="updated fade" id="message"><p>' . $message . '</p></div></div>' . "\n";
	}

	/**
	 * Show an error message
	 * @since 1.0
	 */
	function show_error( $error )
	{
		echo '<div class="wrap"><h2></h2><div class="error" id="error"><p>' . $error . '</p></div></div>' . "\n";
	}
}
?>