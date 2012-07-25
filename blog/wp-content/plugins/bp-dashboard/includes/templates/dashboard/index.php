<?php

/**
 * BuddyPress - Dashboard Directory
 *
 * @package BuddyPress_Dashboard_Component
 */

?>

<?php get_header( 'buddypress' ); ?>

	<?php do_action( 'bp_before_directory_dashboard_page' ); ?>

	<div id="content">
		<div class="padder">

		<?php do_action( 'bp_before_directory_dashboard' ); ?>

		<form action="" method="post" id="dashboard-directory-form" class="dir-form">

			<h3><?php _e( 'Dashboard Directory', 'bp-dashboard' ); ?></h3>

			<?php do_action( 'bp_before_directory_dashboard_content' ); ?>

			<?php do_action( 'template_notices' ); ?>

			<div class="item-list-tabs no-ajax" role="navigation">
				<ul>
					<li class="selected" id="groups-all"><a href="<?php echo trailingslashit( bp_get_root_domain() . '/' . bp_get_dashboard_root_slug() ); ?>"><?php printf( __( 'All Posts <span>%s</span>', 'buddypress' ), bp_dashboard_get_total_post_count() ); ?></a></li>

					<?php do_action( 'bp_dashboard_directory_dashboard_filter' ); ?>

				</ul>
			</div><!-- .item-list-tabs -->

			<div id="dashboard-dir-list" class="dashboard dir-list">

				<?php bp_core_load_template( 'dashboard/dashboard-home' ); ?>

			</div><!-- #dashboard-dir-list -->

			<?php do_action( 'bp_directory_dashboard_content' ); ?>

			<?php wp_nonce_field( 'directory_dashboard', '_wpnonce-dashboard-filter' ); ?>

			<?php do_action( 'bp_after_directory_dashboard_content' ); ?>

		</form><!-- #dashboard-directory-form -->

		<?php do_action( 'bp_after_directory_dashboard' ); ?>

		</div><!-- .padder -->
	</div><!-- #content -->

	<?php do_action( 'bp_after_directory_dashboard_page' ); ?>

<?php get_sidebar( 'buddypress' ); ?>
<?php get_footer( 'buddypress' ); ?>

