<?php

/**
 * In this file you should define template tag functions that end users can add to their template
 * files.
 *
 * It's a general practice in WordPress that template tag functions have two versions, one that
 * returns the requested value, and one that echoes the value of the first function. The naming
 * convention is usually something like 'bp_dashboard_get_item_name()' for the function that returns
 * the value, and 'bp_dashboard_item_name()' for the function that echoes.
 */

/**
 * If you want to go a step further, you can create your own custom WordPress loop for your component.
 * By doing this you could output a number of items within a loop, just as you would output a number
 * of blog posts within a standard WordPress loop.
 *
 * The example template class below would allow you do the following in the template file:
 *
 * 	<?php if ( bp_get_dashboard_has_items() ) : ?>
 *
 *		<?php while ( bp_get_dashboard_items() ) : bp_get_dashboard_the_item(); ?>
 *
 *			<p><?php bp_get_dashboard_item_name() ?></p>
 *
 *		<?php endwhile; ?>
 *
 *	<?php else : ?>
 *
 *		<p class="error">No items!</p>
 *
 *	<?php endif; ?>
 *
 * Obviously, you'd want to be more specific than the word 'item'.
 *
 * In our example here, we've used a custom post type for storing and fetching our content. Though
 * the custom post type method is recommended, you can also create custom database tables for this
 * purpose. See bp-dashboard-classes.php for more details.
 *
 */

function bp_dashboard_has_items( $args = '' ) {
	global $bp, $items_template;

	return $items_template->have_posts();
}

function bp_dashboard_the_item() {
	global $items_template;
	return $items_template->query->the_post();
}

function bp_dashboard_item_name() {
	echo bp_dashboard_get_item_name();
}
	/* Always provide a "get" function for each template tag, that will return, not echo. */
	function bp_dashboard_get_item_name() {
		global $items_template;
		echo apply_filters( 'bp_dashboard_get_item_name', $items_template->item->name ); // Example: $items_template->item->name;
	}

/**
 * Echo "Viewing x of y pages"
 *
 * @package BuddyPress_Dashboard_Component
 * @since 1.0
 */
function bp_dashboard_pagination_count() {
	echo bp_dashboard_get_pagination_count();
}
	/**
	 * Return "Viewing x of y pages"
	 *
	 * @package BuddyPress_Dashboard_Component
	 * @since 1.0
	 */
	function bp_dashboard_get_pagination_count() {
		global $items_template;

		$pagination_count = sprintf( __( 'Viewing page %1$s of %2$s', 'bp-dashboard' ), $items_template->query->query_vars['paged'], $items_template->query->max_num_pages );

		return apply_filters( 'bp_dashboard_get_pagination_count', $pagination_count );
	}

/**
 * Echo pagination links
 *
 * @package BuddyPress_Dashboard_Component
 * @since 1.0
 */
function bp_dashboard_item_pagination() {
	echo bp_dashboard_get_item_pagination();
}
	/**
	 * return pagination links
	 *
	 * @package BuddyPress_Dashboard_Component
	 * @since 1.0
	 */
	function bp_dashboard_get_item_pagination() {
		global $items_template;
		return apply_filters( 'bp_dashboard_get_item_pagination', $items_template->pag_links );
	}

/**
 * Echo the dashboard post sender avatar (post author)
 *
 * @package BuddyPress_Dashboard_Component
 * @since 1.0
 */
function bp_dashboard_post_avatar( $args = array() ) {
	echo bp_dashboard_get_post_avatar( $args );
}
	/**
	 * Return the dashboard post sender avatar (the post author)
	 *
	 * @package BuddyPress_Dashboard_Component
	 * @since 1.0
	 *
	 * @param mixed $args Accepts WP style arguments - either a string of URL params, or an array
	 * @return str The HTML for a user avatar
	 */
	function bp_dashboard_get_post_avatar( $args = array() ) {
		$defaults = array(
			'item_id' => get_the_author_meta( 'ID' ),
			'object'  => 'user'
		);

		$r = wp_parse_args( $args, $defaults );

		return bp_core_fetch_avatar( $r );
	}

/**
 * Echo the "title" of the dashboard post
 *
 * @package BuddyPress_Dashboard_Component
 * @since 1.0
 */
function bp_dashboard_post_title() {
	echo bp_dashboard_get_post_title();
}
	/**
	 * Return the "title" of the dashboard-post
	 *
	 * We'll assemble the title out of the available information. This way, we can insert
	 * fancy stuff link links, and secondary avatars.
	 *
	 * @package BuddyPress_Dashboard_Component
	 * @since 1.0
	 */
	function bp_dashboard_get_post_title() {
		// First, set up the high fiver's information
		$poster_link = bp_core_get_userlink( get_the_author_meta( 'ID' ) );

		// Next, get the information for the high five recipient
		$recipient_id    = get_post_meta( get_the_ID(), 'bp_dashboard_recipient_id', true );
		$recipient_link  = bp_core_get_userlink( $recipient_id );

		// Use sprintf() to make a translatable message
		$title 		 = sprintf( __( '%1$s gave %2$s a dashboard post!', 'bp-dashboard' ), $poster_link, $recipient_link );

		return apply_filters( 'bp_dashboard_get_post_title', $title, $poster_link, $recipient_link );
	}

/**
 * Is this page part of the Dashboard component?
 *
 * Having a special function just for this purpose makes our code more readable elsewhere, and also
 * allows us to place filter 'bp_is_dashboard_component' for other components to interact with.
 *
 * @package BuddyPress_Dashboard_Component
 * @since 1.0
 *
 * @uses bp_is_current_component()
 * @uses apply_filters() to allow this value to be filtered
 * @return bool True if it's the dashboard component, false otherwise
 */
function bp_is_dashboard_component() {
	$is_dashboard_component = bp_is_current_component( 'dashboard' );

	return apply_filters( 'bp_is_dashboard_component', $is_dashboard_component );
}

/**
 * Echo the component's slug
 *
 * @package BuddyPress_Dashboard_Component
 * @since 1.0
 */
function bp_dashboard_slug() {
	echo bp_get_dashboard_slug();
}
	/**
	 * Return the component's slug
	 *
	 * Having a template function for this purpose is not absolutely necessary, but it helps to
	 * avoid too-frequent direct calls to the $bp global.
	 *
	 * @package BuddyPress_Dashboard_Component
	 * @since 1.0
	 *
	 * @uses apply_filters() Filter 'bp_get_dashboard_slug' to change the output
	 * @return str $dashboard_slug The slug from $bp->dashboard->slug, if it exists
	 */
	function bp_get_dashboard_slug() {
		global $bp;

		// Avoid PHP warnings, in case the value is not set for some reason
		$dashboard_slug = isset( $bp->dashboard->slug ) ? $bp->dashboard->slug : '';

		return apply_filters( 'bp_get_dashboard_slug', $dashboard_slug );
	}

/**
 * Echo the component's root slug
 *
 * @package BuddyPress_Dashboard_Component
 * @since 1.0
 */
function bp_dashboard_root_slug() {
	echo bp_get_dashboard_root_slug();
}
	/**
	 * Return the component's root slug
	 *
	 * Having a template function for this purpose is not absolutely necessary, but it helps to
	 * avoid too-frequent direct calls to the $bp global.
	 *
	 * @package BuddyPress_Dashboard_Component
	 * @since 1.0
	 *
	 * @uses apply_filters() Filter 'bp_get_dashboard_root_slug' to change the output
	 * @return str $dashboard_root_slug The slug from $bp->dashboard->root_slug, if it exists
	 */
	function bp_get_dashboard_root_slug() {
		global $bp;

		// Avoid PHP warnings, in case the value is not set for some reason
		$dashboard_root_slug = isset( $bp->dashboard->root_slug ) ? $bp->dashboard->root_slug : '';

		return apply_filters( 'bp_get_dashboard_root_slug', $dashboard_root_slug );
	}

/**
 * Echo the total of all dashboard posts across the site
 *
 * @package BuddyPress_Dashboard_Component
 * @since 1.0
 */
function bp_dashboard_total_post_count() {
	echo bp_dashboard_get_total_post_count();
}
	/**
	 * Return the total of all dashboard posts across the site
	 *
	 * The most straightforward way to get a post count is to run a WP_Query. In your own plugin
	 * you might consider storing data like this with update_option(), incrementing each time
	 * a new item is published.
	 *
	 * @package BuddyPress_Dashboard_Component
	 * @since 1.0
	 *
	 * @return int
	 */
	function bp_dashboard_get_total_post_count() {
		$posts = new BP_Dashboard_Posts();
		$posts->get();

		return apply_filters( 'bp_dashboard_get_total_post_count', $posts->query->found_posts, $posts );
	}

/**
 * Echo the total of all posts given to a particular user
 *
 * @package BuddyPress_Dashboard_Component
 * @since 1.0
 */
function bp_dashboard_total_post_count_for_user( $user_id = false ) {
	echo bp_dashboard_get_total_post_count_for_user( $user_id = false );
}
	/**
	 * Return the total of all posts given to a particular user
	 *
	 * The most straightforward way to get a post count is to run a WP_Query. In your own plugin
	 * you might consider storing data like this with update_option(), incrementing each time
	 * a new item is published.
	 *
	 * @package BuddyPress_Dashboard_Component
	 * @since 1.0
	 *
	 * @return int
	 */
	function bp_dashboard_get_total_post_count_for_user( $user_id = false ) {
		// If no explicit user id is passed, fall back on the loggedin user
		if ( !$user_id ) {
			$user_id = bp_loggedin_user_id();
		}

		if ( !$user_id ) {
			return 0;
		}

		$posts = new BP_Dashboard_Posts();
		$posts->get( array( 'recipient_id' => $user_id ) );

		return apply_filters( 'bp_dashboard_get_total_post_count', $posts->query->found_posts, $posts );
	}

?>