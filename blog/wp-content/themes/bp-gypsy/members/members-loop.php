<?php

/**
 * BuddyPress - Members Loop
 *
 * Querystring is set via AJAX in _inc/ajax.php - bp_dtheme_object_filter()
 *
 * @package BuddyPress
 * @subpackage bp-default
 */

?>

<?php do_action( 'bp_before_members_loop' ); ?>

<?php if ( bp_has_members( bp_ajax_querystring( 'members' ) ) ) : ?>

	<?php do_action( 'bp_before_directory_members_list' ); ?>
	
	<ul id="members-list" class="item-list clearfix" role="main">

	<?php while ( bp_members() ) : bp_the_member(); ?>

		<li class="columns four omega">
			<a href="<?php echo bp_member_permalink(); ?>" class="member-info">
    			
    			<h3 class="item-title">
    				<?php bp_member_name(); ?>
    			</h3>
    			
    			<div class="item-avatar member-avatar">
    				
    				<?php bp_member_avatar('type=full&width=220&height=220'); ?>
    				
    				<div class="item hidden">
    
        				<div><label>Name:</label> <?php bp_member_profile_data( 'field=NAME' ); ?></div>
        				<div><label>Location:</label><?php bp_member_profile_data( 'field=Country' ); ?></div>
        				<div><label>Age:</label><?php bp_member_profile_data( 'field=Age Range' ); ?></div>
        				<div><label>About Me:</label><?php bp_member_profile_data( 'field=You in 700 characters (That\'s 5 Tweets!)' ); ?></div>
        				
        				<div class="action">
        
        				    <?php do_action( 'bp_directory_members_actions' ); ?>
        
        				</div>
        				
        			</div>
    			</div>
    			
			</a>
		</li>

	<?php endwhile; ?>

	</ul>

	<?php do_action( 'bp_after_directory_members_list' ); ?>

	<?php bp_member_hidden_fields(); ?>

<?php else: ?>

	<div id="message" class="info">
		<p><?php _e( "Sorry, no members were found.", 'buddypress' ); ?></p>
	</div>

<?php endif; ?>

<?php do_action( 'bp_after_members_loop' ); ?>
