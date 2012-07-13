<?php

/**
 * BuddyPress - Members Directory
 *
 * @package BuddyPress
 * @subpackage bp-default
 */

?>

<?php get_header( 'buddypress' ); ?>

	<?php do_action( 'bp_before_directory_members_page' ); ?>

	<section id="content">
	
		<?php do_action( 'bp_before_directory_members' ); ?>

			<?php do_action( 'bp_before_directory_members_content' ); ?>

			<?php locate_template( array( 'members/members-loop.php' ), true ); ?>

			<?php do_action( 'bp_directory_members_content' ); ?>

			<?php wp_nonce_field( 'directory_members', '_wpnonce-member-filter' ); ?>

			<?php do_action( 'bp_after_directory_members_content' ); ?>

		<?php do_action( 'bp_after_directory_members' ); ?>

	</section><!-- #content -->

	<?php do_action( 'bp_after_directory_members_page' ); ?>

<?php //get_sidebar( 'buddypress' ); ?>
<?php get_footer( 'buddypress' ); ?>
