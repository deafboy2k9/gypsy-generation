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

require_once( '../bpxs-config.php' );

global $bpxs;

if( $bpxs->options->u_availability == true )
	$load[] = 'availability.js';
	
if( $bpxs->options->psw_strength == true )
	$load[] = 'strengthmeter.js';

if( $bpxs->options->email_check == true )
	$load[] = 'email-check.js';
	
if( $bpxs->options->email_confirmation == true )
	$load[] = 'email-compare.js';

ob_start( "ob_gzhandler" );

header( 'Content-type: text/javascript' );
header( "Cache-Control: public" );
header( 'Expires: '. gmdate( 'D, d M Y H:i:s', time() + 86400 ) . 'GMT' ); 

foreach( (array)$load as $file )
{
	include( dirname( __FILE__ ) .'/'. $file );
	echo "\t";
}
?>