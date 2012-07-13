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

	<div class="wrap">
		<div id="grid">
			<div id="sort">
				<?php if ( bp_has_members( bp_ajax_querystring( 'members' ) ) ) : ?>
					<?php while ( bp_members() ) : bp_the_member(); ?>
						<div class="box">
							<div class="name_container"><span class="member_name"><?php bp_member_name() ?></span></div>
							<a class="member_avatar" href="<?php echo bp_core_fetch_avatar(array('item_id'=>bp_get_member_user_id(), 'object' => 'user', 'type' => 'full' , 'html' => false, 'no_grav' => true)); ?>">
								<?php bp_member_avatar('type=full&width=240&height=240'); ?>
								<div class="profile_info" style="display:none;">
									<span style="display:block;">Name: <?php bp_member_profile_data( 'field=NAME' ); ?></span>
									<span style="display:block;">Location: <?php bp_member_profile_data( 'field=LOCATION' ); ?></span>
									<span style="display:block;">Age: <?php bp_member_profile_data( 'field=AGE' ); ?></span>
									<span style="display:block;">Why You Here: <?php bp_member_profile_data( 'field=WHY YOU HERE?' ); ?></span>
									<span style="display:block;">About Me: <?php bp_member_profile_data( 'field=YOU IN 140 CHARACTERS' ); ?></span>
								</div>
							</a>
							<?php if ( is_user_logged_in() ) : ?>
								<div class="action" style="display:none;">
									<?php if( get_current_user_id() != bp_get_member_user_id() ) : ?>
									<div class="generic-button">
										<a href="<?php bp_member_permalink(); ?>">View Profile</a>
									</div>
									<?php endif; ?>
									<?php bp_member_add_friend_button(); ?>
								</div>
							<?php endif; ?>
						</div>
					<?php endwhile; ?>
				<?php else: ?>
					<div id="message" class="info">
						<p><?php _e( "Sorry, no members were found.", 'buddypress' ); ?></p>
					</div>
				<?php endif; ?>
			</div><!-- #sort --> 
		</div><!-- #grid -->
	</div><!-- .wrap -->

	<?php do_action( 'bp_after_directory_members_page' ); ?>

<?php //get_sidebar( 'buddypress' ); ?>
<?php get_footer( 'buddypress' ); ?>
