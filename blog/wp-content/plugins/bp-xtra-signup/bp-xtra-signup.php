<?php
/*
Plugin Name: BP Xtra Signup
Plugin URI: http://shabushabu.eu/
Description: This plugin lets you add a Terms of Service checkbox and, optionally, a Mailchimp signup checkbox to your BuddyPress registration page. Additionally, an ajax username availability check, a password strength meter, email check and date of birth check can be activated.
Author: Boris Glumpler
Version: 1.6
Author URI: http://shabushabu.eu/
Site Wide Only: true

Copyright 2010 by ShabuShabu Webdesign

****************************************************************************

This script is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

****************************************************************************
*/

class BPXS_Loader
{
	/**
	 * The plugin version
	 */
	var $version = '1.6';
	
	/**
	 * Minimum required WP version
	 */
	var $min_wp = '3.0';
	
	/**
	 * Minimum required BP version
	 */
	var $min_bp = '1.2';
	
	/**
	 * Minimum required PHP version
	 */
	var $min_php = '5.0.0';
	
	/**
	 * Plugin creator link
	 */
	var $home_url = 'http://shabushabu.eu/';
	
	/**
	 * Name of the plugin folder
	 */
	var $plugin_name;
	
	/**
	 * Holds the admin page
	 */
	var $bpxs_admin;
	
	/**
	 * All our options
	 */
	var $options;
	
	/**
	 * PHP4 constructor
	 * @since 1.0
	 */
	function bpxs_loader()
	{
		$this->__construct();
	}
	
	/**
	 * PHP5 constructor
	 * @since 1.0
	 */
	function __construct()
	{
		$this->constants();
		$this->translate();

		// Stop the plugin if we missed the requirements
		if( ! $this->check_requirements() )
			return;

		$this->plugin_name = plugin_basename( __FILE__ );
		$this->globals();
		$this->dependencies();

		// activate and uninstall hooks
		register_activation_hook( $this->plugin_name, array( &$this, 'activate' ) );
		register_uninstall_hook( $this->plugin_name, array( &$this, 'uninstall' ) );

		// load BP related files only if BP is present
		if( defined( 'BP_VERSION' ) )
			$this->start();
		else
			add_action( 'bp_init', array( &$this, 'start' ) );
			
		add_filter( 'plugin_row_meta', array( &$this, 'add_links' ), 10, 2 );
	}

	/**
	 * Load all BP related files
	 * @since 1.0
	 */
	function start()
	{
		// Stop the plugin if we don't have the correct BP version or the options haven't been set up yet
		if( ! $this->check_plugin() )
			return;

		require_once( dirname( __FILE__ ) . '/_inc/bpxs-core.php');
	}

	/**
	 * Check for required wp version
	 * @since 1.0
	 */
	function check_requirements()
	{		
		global $wp_version;
		
		if( version_compare( $wp_version, $this->min_wp, '>=' ) == false )
		{
			add_action( 'admin_notices', create_function( '', 'global $bpxs; printf(\'<div id="message" class="error"><p><strong>\' . __(\'BP Xtra Signup works only under WordPress %s or higher. <a href="%supdate-core.php">Upgrade now</a>!\', "bpxs" ) . \'</strong></p></div>\', $bpxs->min_wp, admin_url() );' ) );
			$error = true;
		}
		
		if( version_compare( PHP_VERSION, $this->min_php, '>=' ) == false )
		{
			add_action( 'admin_notices', create_function( '', 'global $bpxs; printf(\'<div id="message" class="error"><p><strong>\' . __(\'BP Xtra Signup works only under PHP %s or higher. Please ask your hosting company for support!\', "bpxs" ) . \'</strong></p></div>\', $bpxs->min_php );'	) );
			$error = true;
		}
		
		return ( ! $error ) ? true : false;
	}
	
	/**
	 * Check for correct setup of options
	 * @since 1.0
	 */
	function check_plugin()
	{
		if( version_compare( BP_VERSION, $this->min_bp, '>=' ) == false )
		{
			add_action( 'admin_notices', create_function( '', 'global $bpxs; printf(\'<div id="message" class="error"><p><strong>\' . __(\'BP Xtra Signup works only under BuddyPress %s or higher. <a href="%supdate-core.php">Upgrade now</a>!\', "bpxs" ) . \'</strong></p></div>\', $bpxs->min_bp, admin_url() );'	) );
			$error = true;
		}
		
		return ( ! $error ) ? true : false;
	}

	/**
	 * Load the languages
	 * @since 1.0
	 */
	function translate()
	{
		if( file_exists( BPXS_ABSPATH . 'languages/bpxs-' . get_locale() . '.mo' ) )
			load_textdomain( 'bpxs', BPXS_ABSPATH . 'languages/bpxs-' . get_locale() . '.mo' );
	}

	/**
	 * Declare our options
	 * @since 1.0
	 */
	function globals()
	{
		if( $options = get_option( 'bpxs_options' ) )
		{
			foreach( $options as $key => $var )
				$this->options->{$key} = $var;
		}
	}
	
	/**
	 * Include all dependent files
	 * @since 1.0
	 */
	function dependencies()
	{
		if( is_admin() )
		{
			require_once( dirname( __FILE__ ) . '/admin/bpxs-admin.php');
			$this->bpxs_admin = new BPXS_Admin_Loader();
		}
	}
	
	/**
	 * Declare all constants
	 * @since 1.0
	 */
	function constants()
	{
		define( 'BPXS_VERSION', $this->version );
		define( 'BPXS_FOLDER', plugin_basename( dirname( __FILE__ ) ) );
		define( 'BPXS_ABSPATH', trailingslashit( str_replace("\\","/", WP_PLUGIN_DIR . '/' . BPXS_FOLDER ) ) );
		define( 'BPXS_URLPATH', trailingslashit( WP_PLUGIN_URL . '/' . BPXS_FOLDER ) );
	}
	
	/**
	 * Activate the plugin
	 * @since 1.0
	 */
	function activate()
	{
		include_once( dirname( __FILE__ ) .'/admin/bpxs-install.php' );
		bpxs_install();
	}

	/**
	 * Delete all options
	 * @since 1.0
	 */
	function uninstall()
	{
		include_once( dirname( __FILE__ ) .'/admin/bpxs-install.php' );
		bpxs_uninstall();
	}

	/**
	 * Add some links to plugin setup page
	 * @since 1.0
	 */
	function add_links( $links, $file )
	{
		if( $file == $this->plugin_name )
		{
			$links[] = '<a href="'. $this->home_url .'forums/">' . __( 'Support Forums', 'bpxs' ) . '</a>';
			$links[] = '<a href="'. $this->home_url .'donation/">' . __( 'Donate', 'bpxs' ) . '</a>';
		}
		
		return $links;
	}
}
// get the show on the road
$bpxs = new BPXS_Loader();
global $bpxs;
?>