<?php
/**
 * @package WordPress
 * @subpackage BuddyPress
 * @sub-subpackage Course Info
 * @author Boris Glumpler
 * @copyright 2010, ShabuShabu Webdesign
 * @link http://scubavagabonds.com
 * @license http://www.opensource.org/licenses/gpl-2.0.php GPL License
 */
 
/** Define the server path to wp-config here, if you placed WP-CONTENT outside the classic file structure */

$path  = ''; // It should end with a trailing slash    

/** That's all, stop editing from here **/

if( ! defined( 'WP_LOAD_PATH' ) )
{
	/** classic root path if wp-content and plugins are below wp-config.php */
	$classic_root = dirname( dirname( dirname( dirname( __FILE__ ) ) ) ) .'/' ;
	
	if( file_exists( $classic_root .'wp-load.php' ) )
		define( 'WP_LOAD_PATH', $classic_root);
	else
		if( file_exists( $path .'wp-load.php' ) )
			define( 'WP_LOAD_PATH', $path );
		else
			exit( 'Could not find wp-load.php' );
}

// let's load WordPress
require_once( WP_LOAD_PATH .'wp-load.php' );