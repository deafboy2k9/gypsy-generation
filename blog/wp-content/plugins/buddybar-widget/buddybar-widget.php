<?php

/* Hooks & Filters */

add_action( 'widgets_init', 'slush_buddybar_widget_fn' );

/* Queues the included stylesheet for the BuddyBar Widget */

wp_register_style( 'bbw-style', '/wp-content/plugins/buddybar-widget/buddybar-style.css' );
wp_enqueue_style( 'bbw-style' );



/***** BuddyBar Widget *****/

/* Creates the widget itself */

if ( !class_exists('Slushman_BuddyBar_Widget') ) {
	class Slushman_BuddyBar_Widget extends WP_Widget {
	
		function Slushman_BuddyBar_Widget() {
			$widget_ops = array( 'classname' => 'slushman-buddybar-widget', 'description' => __( 'BuddyBar Widget', 'slushman-buddybar-widget' ) );
			$this->WP_Widget( 'buddybar_widget', __( 'BuddyBar Widget' ), $widget_ops);
		}
		
		function widget( $args, $instance ) {
			extract( $args );
			
			echo $before_widget;
			
			$title = empty( $instance['title'] ) ? '' : apply_filters( 'widget_title', $instance['title'] );
			if ( !empty( $title ) ) { echo $before_title . $title . $after_title; };
			
			if ( is_user_logged_in() ) :
				do_action( 'bp_before_sidebar_me' ); ?>
				<div id="sidebar-me">


					<ul id="bbw-avatar">
						<li><?php bp_loggedin_user_avatar( 'type=full' ); ?></li>
					</ul>
					<ul id="bbw-profile">
					<span class="bbw-user-link"><a href="<?php bp_loggedin_user_link(); ?>"><?php bp_loggedin_user_fullname(); ?></a></span>
<br>
						<li class="bbw-module-link"><a href="<?php echo bp_loggedin_user_domain() . BP_XPROFILE_SLUG ?>/edit">Edit Profile</a></li>
<br>
						<li class="bbw-module-link"><a href="<?php echo bp_loggedin_user_domain() . BP_XPROFILE_SLUG ?>/change-avatar">Change Picture</a></li>
<br>
						<li class="bbw-module-link"><a href="<?php echo bp_loggedin_user_domain() ?>settings/general">Change Email / Password</a></li>
<br>
<br>
						<li class="bbw-module-link"><a href="<?php echo wp_logout_url( get_permalink() ); ?>" rel="nofollow" class="button logout" title="<?php _e( 'Log Out' ); ?>"><?php _e( 'Log Out' ); ?></a></li>
<br
					</ul>
				<?php if ( bp_is_active( 'activity' ) ) : ?>
				<ul id="bbw-activity">
					<li class="bbw-module-title"><a href="<?php echo bp_loggedin_user_domain() . BP_ACTIVITY_SLUG ?>">Activity</a></li>
					<li class="bbw-module-link"><a href="<?php echo bp_loggedin_user_domain() . BP_ACTIVITY_SLUG ?>/just-me">Personal</a></li>
					<li class="bbw-module-link"><a href="<?php echo bp_loggedin_user_domain() . BP_ACTIVITY_SLUG ?>/favorites">Favorites</a></li>
					<li class="bbw-module-link"><a href="<?php echo bp_loggedin_user_domain() . BP_ACTIVITY_SLUG ?>/mentions">Mentions</a></li>
				</ul>
				<?php endif;
				if ( bp_is_active( 'messages' ) ) : ?>
				<ul id="bbw-messages">
					<li class="bbw-module-title"><a href="<?php echo bp_loggedin_user_domain() . BP_MESSAGES_SLUG ?>">Messages</a></li>
					<li class="bbw-module-link"><a href="<?php echo bp_loggedin_user_domain() . BP_MESSAGES_SLUG ?>">Inbox<?php if ( bp_message_thread_has_unread() ) : echo " (" . messages_get_unread_count() . ")"; endif; ?></a></li>
					<li class="bbw-module-link"><a href="<?php echo bp_loggedin_user_domain() . BP_MESSAGES_SLUG ?>/sentbox">Sent</a></li>
					<li class="bbw-module-link"><a href="<?php echo bp_loggedin_user_domain() . BP_MESSAGES_SLUG ?>/compose">Compose</a></li>
					<li class="bbw-module-link"><a href="<?php echo bp_loggedin_user_domain() . BP_MESSAGES_SLUG ?>/notices">Notices</a></li>
				</ul>
				<?php endif;
				if ( bp_is_active( 'friends' ) ) : ?>
				<ul id="bbw-friends">
					<li class="bbw-module-title"><a href="<?php echo bp_loggedin_user_domain() . BP_FRIENDS_SLUG ?>">Friends</a></li>
					<li class="bbw-module-link"><a href="<?php echo bp_loggedin_user_domain() . BP_FRIENDS_SLUG ?>/my-friends">My Friends</a></li>
					<li class="bbw-module-link"><a href="<?php echo bp_loggedin_user_domain() . BP_FRIENDS_SLUG ?>/requests">Requests</a></li>
				</ul>
				<?php endif;
				if ( bp_is_active( 'groups' ) ) : ?>
				<ul id="bbw-groups">
					<li class="bbw-module-title"><a href="<?php echo bp_loggedin_user_domain() . BP_GROUPS_SLUG ?>">Groups</a></li>
					<li class="bbw-module-link"><a href="<?php echo bp_loggedin_user_domain() . BP_GROUPS_SLUG ?>/my-groups">My Groups</a></li>
					<li class="bbw-module-link"><a href="<?php echo bp_loggedin_user_domain() . BP_GROUPS_SLUG ?>/invites">Invites</a></li>
				</ul>
				<?php endif;
				
				do_action( 'bp_sidebar_me' ); ?>
				</div>
				<?php do_action( 'bp_after_sidebar_me' );
			
			/***** If the user is not logged in, show the log form and account creation link *****/
			
			else :
			
				do_action( 'bp_before_sidebar_login_form' ); ?>
				
				<p id="login-text">
					<?php _e( 'To edit your profile, please log in.  ', 'buddypress' ); ?> 
				</p>
				
				<form name="login-form" id="sidebar-login-form" class="standard-form" action="<?php echo site_url( 'wp-login.php', 'login-post' ); ?>" method="post">
					<label><?php _e( 'Username', 'buddypress' ); ?><br />
					<input type="text" name="log" id="side-user-login" class="input" value="<?php echo attribute_escape( stripslashes( $user_login ) ); ?>" /></label>
					
					<label><?php _e( 'Password', 'buddypress' ); ?><br />
					<input type="password" name="pwd" id="sidebar-user-pass" class="input" value="" /></label>
					
					<p class=""><label><input name="rememberme" type="checkbox" id="sidebar-rememberme" value="forever" /><?php _e( 'Remember Me', 'buddypress' ); ?></label></p>
					
					<?php do_action( 'bp_sidebar_login_form' ); ?>
					<input type="submit" name="wp-submit" id="sidebar-wp-submit" value="<?php _e( 'Log In' ); ?>" tabindex="100" />
					<input type="hidden" name="testcookie" value="1" />
				</form><br />
				<?php if ( bp_get_signup_allowed() ) :
						printf( __( '<a href="%s" title="Create an account">Create an account</a> to log in.', 'buddypress' ), site_url( BP_REGISTER_SLUG . '/' ) );
					endif; ?>
				
				<?php do_action( 'bp_after_sidebar_login_form' );
			endif;
			
			echo $after_widget;
		}
		
		/* Updates the widget */
		
		function update( $new_instance, $old_instance ) {
			$instance = $old_instance;
			$instance['title'] = strip_tags( $new_instance['title']);
			return $instance;
		}
		
		/* Creates the widget options form */
		
		function form( $instance ) {
			$defaults = array( 'title' => 'BuddyPress' );
			$instance = wp_parse_args( ( array )$instance,$defaults);
			$title = esc_attr( $instance['title'] );
			?>
				<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title' ); ?>: <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></label></p>
			<?php
		}
	
	} // End of class Slushman_BuddyBar_Widget
} // End of 'Does the Slushman_BuddyBar_Widget class already exist?'

function slush_buddybar_widget_fn() {
	register_widget( 'Slushman_BuddyBar_Widget' );
}

?>