<?php

/**
 *
 * @package BuddyPress_Dashboard_Component
 * @since 1.0
 */

?>

<?php do_action( 'bp_before_dashboard_loop' ); ?>

<?php //if ( bp_dashboard_has_items( bp_ajax_querystring( 'dashboard' ) ) ) : ?>
<?php //global $items_template; var_dump( $items_template ) ?>
	<div id="pag-top" class="pagination">

		<div class="pag-count" id="dashboard-dir-count-top">

			<?php //bp_dashboard_pagination_count(); ?>

		</div>

		<div class="pagination-links" id="dashboard-dir-pag-top">

			<?php //bp_dashboard_item_pagination(); ?>

		</div>

	</div>

	<?php do_action( 'bp_before_directory_dashboard_list' ); ?>

	<ul id="example-list" class="item-list" role="main">

	<?php //while ( bp_dashboard_has_items() ) : bp_dashboard_the_item(); ?>

		<li>
			<div class="item-avatar">
				<?php //bp_dashboard_sender_avatar( 'type=thumb&width=50&height=50' ); ?>
			</div>

			<div class="item">
				<div class="item-title"><?php //bp_dashboard_post_title() ?></div>

				<?php do_action( 'bp_directory_dashboard_item' ); ?>

			</div>

			<div class="clear"></div>
		</li>

	<?php //endwhile; ?>

	</ul>

	<?php do_action( 'bp_after_directory_dashboard_list' ); ?>

	<div id="pag-bottom" class="pagination">

		<div class="pag-count" id="dashboard-dir-count-bottom">

			<?php //bp_dashboard_pagination_count(); ?>

		</div>

		<div class="pagination-links" id="dashboard-dir-pag-bottom">

			<?php //bp_dashboard_item_pagination(); ?>

		</div>

	</div>

<?php //else: ?>

	<div id="message" class="info">
		<p><?php _e( 'There were no posts found.', 'buddypress' ); ?></p>
	</div>

<?php //endif; ?>

<?php do_action( 'bp_after_dashboard_loop' ); ?>
