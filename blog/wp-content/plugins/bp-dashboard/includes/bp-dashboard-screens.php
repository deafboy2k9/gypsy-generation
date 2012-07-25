<?php
/**
 * If your component uses a top-level directory, this function will catch the requests and load
 * the index page.
 *
 * @package BuddyPress_Template_Pack
 * @since 1.0
 */
function bp_dashboard_directory_setup() {
	if ( bp_is_dashboard_component() && !bp_current_action() && !bp_current_item() ) {
		// This wrapper function sets the $bp->is_directory flag to true, which help other
		// content to display content properly on your directory.
		bp_update_is_directory( true, 'dashboard' );

		// Add an action so that plugins can add content or modify behavior
		do_action( 'bp_dashboard_directory_setup' );

		bp_core_load_template( apply_filters( 'dashboard_directory_template', 'dashboard/index' ) );
	}
}
add_action( 'bp_screens', 'bp_dashboard_directory_setup' );

/**
 * bp_dashboard_home()
 *
 * Sets up and displays the screen output for the sub nav item "dashboard/dashboard-home"
 */
function bp_dashboard_home() {
	global $bp;

	/**
	 * There are three global variables that you should know about and you will
	 * find yourself using often.
	 *
	 * $bp->current_component (string)
	 * This will tell you the current component the user is viewing.
	 *
	 * Example: If the user was on the page http://example.org/members/andy/groups/my-groups
	 *          $bp->current_component would equal 'groups'.
	 *
	 * $bp->current_action (string)
	 * This will tell you the current action the user is carrying out within a component.
	 *
	 * Example: If the user was on the page: http://example.org/members/andy/groups/leave/34
	 *          $bp->current_action would equal 'leave'.
	 *
	 * $bp->action_variables (array)
	 * This will tell you which action variables are set for a specific action
	 *
	 * Example: If the user was on the page: http://example.org/members/andy/groups/join/34
	 *          $bp->action_variables would equal array( '34' );
	 *
	 * There are three handy functions you can use for these purposes:
	 *   bp_is_current_component()
	 *   bp_is_current_action()
	 *   bp_is_action_variable()
	 */

	/* Add a do action here, so your component can be extended by others. */
	do_action( 'bp_dashboard_home' );

	/****
	 * Displaying Content
	 */

	/****
	 * OPTION 1:
	 * You've got a few options for displaying content. Your first option is to bundle template files
	 * with your plugin that will be used to output content.
	 *
	 * In an earlier function bp_dashboard_load_template_filter() we set up a filter on the core BP template
	 * loading function that will make it first look in the plugin directory for template files.
	 * If it doesn't find any matching templates it will look in the active theme directory.
	 *
	 * This example component comes bundled with a template for screen one, so we can load that
	 * template to display what we need. If you copied this template from the plugin into your theme
	 * then it would load that one instead. This allows users to override templates in their theme.
	 */

	/* This is going to look in wp-content/plugins/[plugin-name]/includes/templates/ first */
	bp_core_load_template( apply_filters( 'bp_dashboard_template_dashboard_home', 'dashboard/dashboard-home' ) );

	/****
	 * OPTION 2 (NOT USED FOR THIS SCREEN):
	 * If your component is simple, and you just want to insert some HTML into the user's active theme
	 * then you can use the bundle plugin template.
	 *
	 * There are two actions you need to hook into. One for the title, and one for the content.
	 * The functions you hook these into should simply output the content you want to display on the
	 * page.
	 *
	 * The follow lines are commented out because we are not using this method for this screen.
	 * You'd want to remove the OPTION 1 parts above and uncomment these lines if you want to use
	 * this option instead.
	 *
	 * Generally, this method of adding content is preferred, as it makes your plugin
	 * work better with a wider variety of themes.
 	 */

//	add_action( 'bp_template_title', 'bp_dashboard_home_title' );
//	add_action( 'bp_template_content', 'bp_dashboard_home_content' );

//	bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
} //end bp_dashboard_home()
	/***
	 * The second argument of each of the above add_action() calls is a function that will
	 * display the corresponding information. The functions are presented below:
	 */
	function bp_dashboard_home_title() {
		_e( 'Dashboard-Home', 'bp-dashboard' );
	}

	function bp_dashboard_home_content() {
		global $bp;

		/**
		 * For security reasons, we MUST use the wp_nonce_url() function on any actions.
		 * This will stop naughty people from tricking users into performing actions without their
		 * knowledge or intent.
		 */
		//$send_link = wp_nonce_url( $bp->displayed_user->domain . $bp->current_component . '/dashboard-home/send-post', 'bp_dashboard_send_post' );
?>
		<h4><?php _e( 'Welcome to DASHBOARD Home', 'bp-dashboard' ) ?></h4>
<?php 
	}//end bp_dashboard_home_content()

function bp_dashboard_screen_two() {
	global $bp;

	/**
	 * There are three global variables that you should know about and you will
	 * find yourself using often.
	 *
	 * $bp->current_component (string)
	 * This will tell you the current component the user is viewing.
	 *
	 * Example: If the user was on the page http://example.org/members/andy/groups/my-groups
	 *          $bp->current_component would equal 'groups'.
	 *
	 * $bp->current_action (string)
	 * This will tell you the current action the user is carrying out within a component.
	 *
	 * Example: If the user was on the page: http://example.org/members/andy/groups/leave/34
	 *          $bp->current_action would equal 'leave'.
	 *
	 * $bp->action_variables (array)
	 * This will tell you which action variables are set for a specific action
	 *
	 * Example: If the user was on the page: http://example.org/members/andy/groups/join/34
	 *          $bp->action_variables would equal array( '34' );
	 *
	 * There are three handy functions you can use for these purposes:
	 *   bp_is_current_component()
	 *   bp_is_current_action()
	 *   bp_is_action_variable()
	 */

	/* Add a do action here, so your component can be extended by others. */
	do_action( 'bp_dashboard_screen_two' );

	/****
	 * Displaying Content
	 */

	/****
	 * OPTION 1:
	 * You've got a few options for displaying content. Your first option is to bundle template files
	 * with your plugin that will be used to output content.
	 *
	 * In an earlier function bp_example_load_template_filter() we set up a filter on the core BP template
	 * loading function that will make it first look in the plugin directory for template files.
	 * If it doesn't find any matching templates it will look in the active theme directory.
	 *
	 * This example component comes bundled with a template for screen one, so we can load that
	 * template to display what we need. If you copied this template from the plugin into your theme
	 * then it would load that one instead. This allows users to override templates in their theme.
	 */

	/* This is going to look in wp-content/plugins/[plugin-name]/includes/templates/ first */
	bp_core_load_template( apply_filters( 'bp_dashboard_template_screen_two', 'dashboard/screen-two' ) );

	/****
	 * OPTION 2 (NOT USED FOR THIS SCREEN):
	 * If your component is simple, and you just want to insert some HTML into the user's active theme
	 * then you can use the bundle plugin template.
	 *
	 * There are two actions you need to hook into. One for the title, and one for the content.
	 * The functions you hook these into should simply output the content you want to display on the
	 * page.
	 *
	 * The follow lines are commented out because we are not using this method for this screen.
	 * You'd want to remove the OPTION 1 parts above and uncomment these lines if you want to use
	 * this option instead.
	 *
	 * Generally, this method of adding content is preferred, as it makes your plugin
	 * work better with a wider variety of themes.
 	 */

//	add_action( 'bp_template_title', 'bp_dashboard_home_title' );
//	add_action( 'bp_template_content', 'bp_dashboard_home_content' );

//	bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
}//end bp_dashboard_screen_two
	/***
	 * The second argument of each of the above add_action() calls is a function that will
	 * display the corresponding information. The functions are presented below:
	 */
	function bp_dashboard_screen_two_title() {
		_e( 'Screen Two', 'bp-dashboard' );
	}

	function bp_dashboard_screen_two_content() {
		global $bp;

		/**
		 * For security reasons, we MUST use the wp_nonce_url() function on any actions.
		 * This will stop naughty people from tricking users into performing actions without their
		 * knowledge or intent.
		 */
		//$send_link = wp_nonce_url( $bp->displayed_user->domain . $bp->current_component . '/dashboard/send-post', 'bp_dashboard_send_post' );
?>
		<h4><?php _e( 'Welcome to DASHBOARD Screen two', 'bp-dashboard' ) ?></h4>
<?php 
	}//end bp_dashboard_screen_two_content()