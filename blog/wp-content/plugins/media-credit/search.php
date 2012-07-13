<?php
if ( isset( $_GET['q'] ) ) {
	$dir = "../../..";
	require_once("$dir/wp-config.php");
	require_once("$dir/wp-admin/includes/user.php");
	require_once("$dir/wp-includes/user.php");

	if ($authors = get_editable_authors_by_name( $current_user->id, $_GET['q'], $_GET['limit'] ) ) {
		foreach ( $authors as $author )
			echo "$author->display_name|$author->ID\n";
		
		/* --- For jQuery UI autocomplete:
		foreach ( $authors as $author )
			$results[] = (object) array("id"=>$author->ID, "label"=>$author->display_name, "value"=>$author->display_name);
		echo json_encode($results);
		*/
	}
	echo '';
}

/**
 * Returns the users that are editable by $user_id (normally the current user) and that contain $name within their
 * display name. Important to use this function rather than just selected all users for WPMU bloggers.
 *
 * Basis for this function is proudly stolen from wp-{admin/}includes/user.php :)
 */
function get_editable_authors_by_name( $user_id, $name, $limit ) {
	global $wpdb;
	
	// get_editable_user_ids was deprecated in WordPress 3.1 and moved to a file that does not get included above, so
	// if the function doesn't exist, then we know we're on a site at WP >= 3.1. Let's used some non-deprecated
	// goodness instead.
	if ( function_exists ( 'get_editable_user_ids' ) ) {
		$editable = get_editable_user_ids( $user_id );
	} else {
		$editable = get_users( array(
			'who' => 'authors',
			'include_selected' => true
		) );
	}

	if ( !$editable ) {
		return false;
	} else {
		$editable = join(',', $editable);
		// Prepare autocomplete term for query: add wildcard after, and replace all spaces with wildcards
		$name = str_replace( ' ', '%', $name ) . '%';
		$authors = $wpdb->get_results( $wpdb->prepare( "
			SELECT ID, display_name
			FROM $wpdb->users
			WHERE ID IN ($editable)
				AND upper(display_name) LIKE %s
			ORDER BY display_name
			LIMIT 0, $limit",
			strtoupper($name) ));
	}

	return apply_filters('get_editable_authors_by_name', $authors, $name);
}

?>
