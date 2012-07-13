<?php

/**
 * BuddyPress - Users Activity
 *
 * @package BuddyPress
 * @subpackage bp-default
 */

?>
<?php get_header( 'buddypress' ); ?>
<?php do_action( 'bp_before_member_settings_template' ); ?>
	<section id="content" class="columns thirteen member row">

		<div class="columns eleven">
		
			<div id="item-nav" class="column alpha omega clearfix">
				<div class="item-list-tabs no-ajax" id="object-nav" role="navigation">
					<ul>
						<li>
						    <a href="<?php echo bp_displayed_user_domain()?>/settings/general">
						    General
						    </a>
						</li>
						<li>
						    <a href="<?php echo bp_displayed_user_domain()?>/settings/notifications">
						    Notifications
						    </a>
						</li>
						
						<?php do_action( 'bp_member_options_nav' ); ?>

					</ul>
				</div>
				
			</div><!-- #item-nav -->
		
			<div id="item-body" role="main">
			
				<?php do_action( 'bp_before_member_body' ); ?>

				<?php do_action( 'bp_after_member_body' ); ?>
			
			</div>
		
		</div>
			
	</section><!-- #content -->
<?php do_action( 'bp_after_member_settings_template' ); ?>
<?php get_sidebar( 'buddypress' ); ?>
<?php get_footer( 'buddypress' ); ?>
