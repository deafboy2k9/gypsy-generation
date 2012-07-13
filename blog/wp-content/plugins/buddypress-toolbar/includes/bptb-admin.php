<?php
/**
 * Helper functions for the admin - plugin links and help tabs.
 *
 * @package    BuddyPress Toolbar
 * @subpackage Admin
 * @author     David Decker - DECKERWEB
 * @copyright  Copyright 2012, David Decker - DECKERWEB
 * @license    http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link       http://genesisthemes.de/en/wp-plugins/buddypress-toolbar/
 * @link       http://twitter.com/#!/deckerweb
 *
 * @since 1.0
 * @version 1.1
 */

add_filter( 'plugin_row_meta', 'ddw_bptb_plugin_links', 10, 2 );
/**
 * Add various support links to plugin page
 *
 * @since 1.0
 *
 * @param  $bptb_links
 * @param  $bptb_file
 * @return strings plugin links
 */
function ddw_bptb_plugin_links( $bptb_links, $bptb_file ) {

	if ( ! current_user_can( 'install_plugins' ) )
		return $bptb_links;

	if ( $bptb_file == BPTB_PLUGIN_BASEDIR . '/buddypress-toolbar.php' ) {
		$bptb_links[] = '<a href="http://wordpress.org/extend/plugins/buddypress-toolbar/faq/" target="_new" title="' . __( 'FAQ', 'buddypress-toolbar' ) . '">' . __( 'FAQ', 'buddypress-toolbar' ) . '</a>';
		$bptb_links[] = '<a href="http://wordpress.org/tags/buddypress-toolbar?forum_id=10" target="_new" title="' . __( 'Support', 'buddypress-toolbar' ) . '">' . __( 'Support', 'buddypress-toolbar' ) . '</a>';
		$bptb_links[] = '<a href="' . __( 'http://genesisthemes.de/en/donate/', 'buddypress-toolbar' ) . '" target="_new" title="' . __( 'Donate', 'buddypress-toolbar' ) . '">' . __( 'Donate', 'buddypress-toolbar' ) . '</a>';
	}

	return $bptb_links;

}  // end of function ddw_bptb_plugin_links
