<?php
/**
 * Handles all Admin access functionality.
 */
class Wdcab_AdminPages {

	function Wdcab_AdminPages () { $this->__construct(); }

	function __construct () {

	}

	/**
	 * Main entry point.
	 *
	 * @static
	 */
	function serve () {
		$me = new Wdcab_AdminPages;
		$me->add_hooks();
	}

	function create_admin_menu_entry () {
		if (@$_POST && isset($_POST['option_page'])) {
			$changed = false;
			if ('wdcab_options' == @$_POST['option_page']) {
				if (isset($_POST['wdcab']['links']['_last_'])) {
					$last = $_POST['wdcab']['links']['_last_'];
					unset($_POST['wdcab']['links']['_last_']);
					if (@$last['url'] && @$last['title']) $_POST['wdcab']['links'][] = $last;
				}
				if (isset($_POST['wdcab']['links'])) {
					$_POST['wdcab']['links'] = array_filter($_POST['wdcab']['links']);
				}
				update_site_option('wdcab', $_POST['wdcab']);
				$changed = true;
			}

			if ($changed) {
				$goback = add_query_arg('settings-updated', 'true',  wp_get_referer());
				wp_redirect($goback);
				die;
			}
		}
		$page = is_multisite() ? 'settings.php' : 'options-general.php';
		$perms = is_multisite() ? 'manage_network_options' : 'manage_options';
		add_submenu_page($page, __('Custom Admin Bar', 'wdcab'), __('Custom Admin Bar', 'wdcab'), $perms, 'wdcab', array($this, 'create_admin_page'));
	}

	function register_settings () {
		global $wp_version;
		$version = preg_replace('/-.*$/', '', $wp_version);
		$form = new Wdcab_AdminFormRenderer;

		register_setting('wdcab', 'wdcab');
		add_settings_section('wdcab_settings', __('Settings', 'wdcab'), create_function('', ''), 'wdcab_options');
		add_settings_field('wdcab_enable', __('Enable Custom entry', 'wdcab'), array($form, 'create_enabled_box'), 'wdcab_options', 'wdcab_settings');
		add_settings_field('wdcab_title', __('Entry title <br /><small>(text or image)</small>', 'wdcab'), array($form, 'create_title_box'), 'wdcab_options', 'wdcab_settings');
		add_settings_field('wdcab_title_link', __('Title link leads to', 'wdcab'), array($form, 'create_title_link_box'), 'wdcab_options', 'wdcab_settings');
		add_settings_field('wdcab_add_step', __('Add new link', 'wdcab'), array($form, 'create_add_link_box'), 'wdcab_options', 'wdcab_settings');
		add_settings_field('wdcab_links', __('Configure Links', 'wdcab'), array($form, 'create_links_box'), 'wdcab_options', 'wdcab_settings');
		if (version_compare($version, '3.3', '>=')) {
			add_settings_field('wdcab_disable', __('Disable WordPress menu items', 'wdcab'), array($form, 'create_disable_box'), 'wdcab_options', 'wdcab_settings');
		}
	}

	function create_admin_page () {
		include(WDCAB_PLUGIN_BASE_DIR . '/lib/forms/plugin_settings.php');
	}

	function js_print_scripts () {
		if (!isset($_GET['page']) || 'wdcab' != $_GET['page']) return false;
		wp_enqueue_script( array("jquery", "jquery-ui-core", "jquery-ui-sortable", 'jquery-ui-dialog') );
	}

	function css_print_styles () {
		if (!isset($_GET['page']) || 'wdcab' != $_GET['page']) return false;
		wp_enqueue_style('jquery-ui-dialog', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
	}



	function add_hooks () {
		add_action('admin_init', array($this, 'register_settings'));
		$hook = is_multisite() ? 'network_admin_menu' : 'admin_menu';
		add_action($hook, array($this, 'create_admin_menu_entry'));

		add_action('admin_print_scripts', array($this, 'js_print_scripts'));
		add_action('admin_print_styles', array($this, 'css_print_styles'));

	}
}