<?php

/**
 * BuddyPress - Users Header
 *
 * @package BuddyPress
 * @subpackage bp-default
 */

?>

<?php do_action( 'bp_before_member_header' ); ?>

<div id="item-header-avatar" class="column alpha">

	<?php bp_member_avatar('type=full&width=220&height=220'); ?>
	
</div><!-- #item-header-avatar -->

<div id="item-header-content" class="columns seven omega clearfix row">
	<div class="row">
		<div class="clearfix">
        	<h2 class="column alpha">
        		<a href="<?php bp_displayed_user_link(); ?>"><?php bp_displayed_user_fullname(); ?></a>
        	</h2>
        	<div id="item-buttons" class="column">
        
        		<?php do_action( 'bp_member_header_actions' ); ?>
        		
        	</div><!-- #item-buttons -->
    	</div>
    	<div class="item-header-details">
        	<div class="user-nicename">@<?php bp_displayed_user_username(); ?></div>
        	<div class="country"><?php gg_member_location(bp_get_profile_field_data( 'field=Country' )); ?></div>
        	<div>Focus: <?php gg_member_focus_group(bp_get_profile_field_data('field=focus group')); ?></div>
    	</div>
    	<?php do_action( 'bp_before_member_header_meta' ); ?>
	</div>
	<div id="item-meta" class="row">

		<?php if ( bp_is_active( 'activity' ) && (!bp_is_my_profile())) : ?>

			<blockquote id="latest-update">
				
				<?php echo gg_get_activity_latest_update( bp_displayed_user_id() ); ?>

			</blockquote>

		<?php endif; ?>
		
		<?php
		
		do_action( 'bp_before_member_activity_post_form' );

        if ( is_user_logged_in() && bp_is_my_profile() && ( !bp_current_action() || bp_is_current_action( 'just-me' ) ) )
        	locate_template( array( 'activity/post-form-input.php'), true );
        
        do_action( 'bp_after_member_activity_post_form' );
        
       ?>

		<?php
		/***
		 * If you'd like to show specific profile fields here use:
		 * bp_profile_field_data( 'field=About Me' ); -- Pass the name of the field
		 */
		 //do_action( 'bp_profile_header_meta' );
		 
		 ?>
		 <div class="item-header-details">
		 	<h3>My digital footprint</h3>
		 	<div><?php gg_get_digital_footprint(bp_displayed_user_id()); ?></div>
    	</div>

	</div><!-- #item-meta -->
	<br class="clearfix" />
</div><!-- #item-header-content -->
<div>

</div>
<?php do_action( 'bp_after_member_header' ); ?>

<?php do_action( 'template_notices' ); ?>