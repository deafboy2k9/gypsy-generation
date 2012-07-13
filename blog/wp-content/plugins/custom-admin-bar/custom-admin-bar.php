<?php
/*
Plugin Name: Custom Admin Bar
Plugin URI: http://premium.wpmudev.org/project/custom-admin-bar
Description: Adds a custom drop-down entry to your admin bar.
Version: 1.3
Author: Ve Bailovity (Incsub)
Author URI: http://premium.wpmudev.org

Copyright 2009-2011 Incsub (http://incsub.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License (Version 2 - GPLv2) as published by
the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/


define ('WDCAB_PLUGIN_SELF_DIRNAME', basename(dirname(__FILE__)), true);

//Setup proper paths/URLs and load text domains
if (is_multisite() && defined('WPMU_PLUGIN_URL') && defined('WPMU_PLUGIN_DIR') && file_exists(WPMU_PLUGIN_DIR . '/' . basename(__FILE__))) {
	define ('WDCAB_PLUGIN_LOCATION', 'mu-plugins', true);
	define ('WDCAB_PLUGIN_BASE_DIR', WPMU_PLUGIN_DIR, true);
	define ('WDCAB_PLUGIN_URL', WPMU_PLUGIN_URL, true);
	$textdomain_handler = 'load_muplugin_textdomain';
} else if (defined('WP_PLUGIN_URL') && defined('WP_PLUGIN_DIR') && file_exists(WP_PLUGIN_DIR . '/' . WDCAB_PLUGIN_SELF_DIRNAME . '/' . basename(__FILE__))) {
	define ('WDCAB_PLUGIN_LOCATION', 'subfolder-plugins', true);
	define ('WDCAB_PLUGIN_BASE_DIR', WP_PLUGIN_DIR . '/' . WDCAB_PLUGIN_SELF_DIRNAME, true);
	define ('WDCAB_PLUGIN_URL', WP_PLUGIN_URL . '/' . WDCAB_PLUGIN_SELF_DIRNAME, true);
	$textdomain_handler = 'load_plugin_textdomain';
} else if (defined('WP_PLUGIN_URL') && defined('WP_PLUGIN_DIR') && file_exists(WP_PLUGIN_DIR . '/' . basename(__FILE__))) {
	define ('WDCAB_PLUGIN_LOCATION', 'plugins', true);
	define ('WDCAB_PLUGIN_BASE_DIR', WP_PLUGIN_DIR, true);
	define ('WDCAB_PLUGIN_URL', WP_PLUGIN_URL, true);
	$textdomain_handler = 'load_plugin_textdomain';
} else {
	// No textdomain is loaded because we can't determine the plugin location.
	// No point in trying to add textdomain to string and/or localizing it.
	wp_die(__('There was an issue determining where Custom Admin Bar plugin is installed. Please reinstall.'));
}
$textdomain_handler('wdcab', false, WDCAB_PLUGIN_SELF_DIRNAME . '/languages/');

function wdcab_add_to_admin_bar () {
	$opts = get_site_option('wdcab');
	if (!@$opts['enabled']) return false;
	if (!@$opts['title']) return false;
	//if (!@$opts['links'] || !is_array($opts['links'])) return false;

	$title = preg_match('/^https?:/', trim($opts['title'])) ? '<img src="' . trim($opts['title']) . '" />' : trim($opts['title']);

	$link = @$opts['title_link'];
	$allowed = array(
		'network_site_url', 'site_url', 'admin_url'
	);
	if (in_array($link, $allowed)) $link = $link();
	else $link = esc_url($link);

	global $wp_admin_bar;
	$wp_admin_bar->add_menu(array(
		'id' => 'wdcab_root',
		'title' => $title,
		'href' => $link,
	));

	foreach ($opts['links'] as $link) {
		$href = false;
		switch ($link['url_type']) {
			case "admin": $href = admin_url($link['url']); break;
			case "site": $href = site_url($link['url']); break;
			case "external": $href = $link['url']; break;
		}
		if (!$href) continue;
		$wp_admin_bar->add_menu(array(
			'parent' => 'wdcab_root',
			'id' => 'wdcab_' . preg_replace('/[^-a-z0-9]/', '-', strtolower($link['title'])),
			'title' => $link['title'],
			'href' => $href,
		));
	}

}

function wdcab_remove_from_admin_bar () {
	global $wp_version;
	$version = preg_replace('/-.*$/', '', $wp_version);
	if (version_compare($version, '3.3', '>=')) {
		global $wp_admin_bar;
		$opts = get_site_option('wdcab');
		$disabled = is_array(@$opts['disabled_menus']) ? $opts['disabled_menus'] : array();
		foreach ($disabled as $id) {
			$wp_admin_bar->remove_node($id);
		}
	}
}

if (is_admin()) {
	require_once WDCAB_PLUGIN_BASE_DIR . '/lib/class_wdcab_admin_form_renderer.php';
	require_once WDCAB_PLUGIN_BASE_DIR . '/lib/class_wdcab_admin_pages.php';
	Wdcab_AdminPages::serve();
}

add_action('admin_bar_menu', 'wdcab_add_to_admin_bar', 1);
add_action('admin_bar_menu', 'wdcab_remove_from_admin_bar', 999);