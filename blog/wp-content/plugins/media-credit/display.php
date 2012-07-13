<?php

function display_author_media($author_id, $sidebar = true, $limit = 10, $link_without_parent = false, $header = "<h3>Recent Media</h3>", $exclude_unattached = true) {
	$media = author_media($author_id, $limit, $exclude_unattached);
	if (!$media)
		return;

	$id = "id = " . ($sidebar ? "recent-media-sidebar" : "recent-media-inline");
	$container = "div";

	echo "<div $id>$header";
	foreach ($media as $post) {
		setup_postdata($post);
		// If media is attached to a post, link to the parent post. Otherwise, link to attachment page itself.
		if ($post->post_parent > 0 || !$link_without_parent)
			$image = wp_get_attachment_image($post->ID, 'thumbnail');
		else
			$image = wp_get_attachment_link($post->ID, 'thumbnail', true);
		$image = preg_replace('/title=".*"/', '', $image); // remove title attribute from image
		$link = $post->post_parent > 0 ? "<a href='" . get_permalink($post->post_parent) . "' title='" . get_the_title($post->post_parent) . "'>$image</a>" : $image;
		echo "<$container class='author-media' id='attachment-$post->ID'>$link</$container>";
	}
	echo "</div>";
}

function author_media_and_posts($id, $include_posts = true, $limit = 0, $exclude_unattached = true) {
	global $wpdb;
	$posts_query = $attached = $date_query = $limit_query = "";
	
	if ($include_posts)
		$posts_query = "OR (post_type = 'post'
					AND post_parent = '0'
					AND post_status = 'publish')";
	$posts_query .= ")";

	if ($exclude_unattached)
		$attached = " AND post_parent != '0'";
	$attached .= ") ";

	$options = get_option( MEDIA_CREDIT_OPTION );
	$start_date = $options['install_date'];
	if ($start_date)
		$date_query = " AND post_date >= '$start_date'";

	if ($limit > 0)
		$limit_query = " LIMIT $limit";
	
	$results = $wpdb->get_results( $wpdb->prepare( "
			SELECT *
			FROM $wpdb->posts
			WHERE post_author = %d" . $date_query . "
				AND ( (post_type = 'attachment' " .
					$attached .
					$posts_query . "		
				AND ID NOT IN (
					SELECT post_id
					FROM $wpdb->postmeta
					WHERE meta_key = '" . MEDIA_CREDIT_POSTMETA_KEY . "'
				)
			GROUP BY ID
			ORDER BY post_date DESC" . $limit_query,
			$id ) );
	
	return $results;
}

function author_media($id, $limit = 0, $exclude_unattached = true) {
	return author_media_and_posts($id, false, $limit, $exclude_unattached);
}
			
?>
