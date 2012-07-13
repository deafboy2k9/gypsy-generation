<?php
/*
Plugin Name: Real Time Twitter
Plugin URI: http://rollybueno.info/real-time-twitter/
Description: Real Time Twitter display your tweets on your sidebar in REAL TIME. It will retrieve tweets based on time specified without page reload.
Version: 1.0.1
Author: rollybueno
Author URI: http://rollybueno.info
License: A "Slug" license name e.g. GPL2

   Copyright 2012 Rolly G. Bueno Jr.

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
    
*/

function real_time_twitter_script()
{    	
    	/**
	 * jquery.jtweetsanywhere-1.3.1.min.js
	 * v 1.3.1.min
	*/
    	wp_enqueue_script( 'jquery.jtweetsanywhere-1.3.1', plugins_url( '/js/jquery.jtweetsanywhere-1.3.1.min.js',__FILE__ ), array(), '1.3.1' );
    	
    	/**
	 * jtweetsanywhere-de-1.3.1.min.js
	 * v 1.3.1.min
	*/
    	wp_enqueue_script( 'jtweetsanywhere-de-1.3.1', plugins_url( '/js/jtweetsanywhere-de-1.3.1.min.js',__FILE__ ), array(), '1.3.1'  );
    	
    	/**
	 * twitter anywhere.js platform
	*/
    	wp_enqueue_script( 'anywhere', 'http://platform.twitter.com/anywhere.js?id=KmxsGWdbeYa1gHOIS9AjA&v=1', array(), '' );
    	
    	/**
	 * jslidery
	 * v 0.1.0
	*/
    	wp_enqueue_script( 'jquery.jslidery-0.1.0', plugins_url( '/js/jquery.jslidery-0.1.0.js',__FILE__ ), array(), '0.1.0' );
}
add_action('wp_enqueue_scripts', 'real_time_twitter_script');

function real_time_twitter_style()
{
	
        /**
	 * jquery.jtweetsanywhere-1.3.1
	 * v 1.3.1
	*/
	wp_register_style( 'jquery.jtweetsanywhere', plugins_url( '/css/jquery.jtweetsanywhere-1.3.1.css', __FILE__ ), array(), '1.3.1' );
        wp_enqueue_style( 'jquery.jtweetsanywhere' );
}
add_action( 'wp_enqueue_scripts', 'real_time_twitter_style' );

class real_time_twitter extends WP_Widget 
{
	/** constructor */
	function __construct() {
		parent::__construct( 
			'real_time_twitter', /* Base ID */ 
			'Real Time Twitter', /* Name */ 
			array( 'description' => 'Real Time Twitter display your tweets on your sidebar in REAL TIME. It will retrieve tweets based on time specified without page reload.' ) 
		);
	}
	/** @see WP_Widget::widget */
	function widget( $args, $instance ) {
	
		extract( $args );
		
		$title = $instance['page_title'];
			
		echo $before_widget;
		echo $before_title . $title . $after_title;		
		
		echo '<div id="realTimeTwitter">';
		echo '</div>';	
		?>
			<script type="text/javascript">
			jQuery(function($){
				$('#realTimeTwitter').jTweetsAnywhere({
				    username: '<?php echo $instance[ 'username' ]; ?>',
				    count: '<?php echo $instance[ 'tweets_number' ]; ?>',
				    showTweetFeed: {
				    	showProfileImages: <?php echo $instance[ 'profile_image' ]; ?>,
        				showUserScreenNames: <?php echo $instance[ 'screen_name' ]; ?>,
				    	expandHovercards: <?php echo $instance[ 'hover_cards' ]; ?>,
				    	autoConformToTwitterStyleguide: true,
				        showTimestamp: {
				            refreshInterval: <?php echo $instance[ 'interval' ]; ?>
				        },
				        autorefresh: {
				            mode: '<?php echo $instance[ 'refresh_mode' ]; ?>',
				            interval: 30
				        },
				        paging: { mode: '<?php echo $instance[ 'paging_mode' ]; ?>' }
				    },
				    showFollowButton: <?php echo $instance[ 'follow_button' ]; ?>
				});
			});
			</script>
		
		<?php	
		echo $after_widget;
	}
	
	/** @see WP_Widget::update */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		
		$instance[ 'page_title' ] = strip_tags($new_instance['page_title']);
		$instance[ 'username' ] = strip_tags($new_instance['username']);
		$instance[ 'refresh_mode' ] = strip_tags($new_instance['refresh_mode']);
		$instance[ 'tweets_number' ] = strip_tags($new_instance['tweets_number']);
		$instance[ 'interval' ] = strip_tags($new_instance['interval']);
		$instance[ 'follow_button' ] = strip_tags($new_instance['follow_button']);
		$instance[ 'profile_image' ] = strip_tags($new_instance['profile_image']);
		$instance[ 'screen_name' ] = strip_tags($new_instance['screen_name']);
		$instance[ 'hover_cards' ] = strip_tags($new_instance['hover_cards']);
		$instance[ 'paging_mode' ] = strip_tags($new_instance['paging_mode']);
		
		return $instance;
	}
	
	/** @see WP_Widget::form */
	function form( $instance ) {
		if ( $instance ):
			$page_title = esc_attr( $instance[ 'page_title' ] );
			$username =  esc_attr( $instance[ 'username' ] );
			$refresh_mode = esc_attr( $instance[ 'refresh_mode' ] );
			$tweets_number =  esc_attr( $instance[ 'tweets_number' ] );
			$interval =  esc_attr( $instance[ 'interval' ] );
			$follow_button =  esc_attr( $instance[ 'follow_button' ] );
			$profile_image =  esc_attr( $instance[ 'profile_image' ] );
			$screen_name =  esc_attr( $instance[ 'screen_name' ] );
			$hover_cards =  esc_attr( $instance[ 'hover_cards' ] );
			$paging_mode =  esc_attr( $instance[ 'paging_mode' ] );
		else:
			$page_title = __( 'Real Time Twitter', 'text_domain' );
			$username = __( 'twitter', 'text_domain' );
			$refresh_mode = __( 'none', 'text_domain' );
			$tweets_number = __( '5', 'text_domain' );
			$interval = __( '10', 'text_domain' );
			$follow_button = __( 'false', 'text_domain' );
			$profile_image = __( 'true', 'text_domain' );
			$screen_name = __( 'true', 'text_domain' );
			$hover_cards = __( 'true', 'text_domain' );
			$paging_mode = __( 'none', 'text_domain' );
		endif;
		
		?>	
			<p>
			<label for="<?php echo $this->get_field_id('page_title'); ?>"><?php _e('Widget Title:'); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id('page_title'); ?>" name="<?php echo $this->get_field_name('page_title'); ?>" type="text" value="<?php echo $page_title; ?>" />
			</p>
		
			<p>
			<label for="<?php echo $this->get_field_id('username'); ?>"><?php _e('Twitter Username:'); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id('username'); ?>" name="<?php echo $this->get_field_name('username'); ?>" type="text" value="<?php echo $username; ?>" />
			</p>
			
			<p>
			<label for="<?php echo $this->get_field_id('refresh_mode'); ?>"><?php _e('Auto Refresh Mode:'); ?></label> 
			<select class="widefat" id="<?php echo $this->get_field_id('refresh_mode'); ?>" name="<?php echo $this->get_field_name('refresh_mode'); ?>">	
			 <option value ="none" <?php selected( $instance[ 'refresh_mode' ], "none" ); ?>>None</option>
			 <option value ="auto-insert" <?php selected( $instance[ 'refresh_mode' ], "auto-insert" ); ?>>Auto Insert</option>
			 <option value ="trigger-insert" <?php selected( $instance[ 'refresh_mode' ], "trigger-insert" ); ?>>Trigger Insert</option>
			</select>
			</p>
			
			<p>
			<label for="<?php echo $this->get_field_id('tweets_number'); ?>"><?php _e('Number of Tweets:'); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id('tweets_number'); ?>" name="<?php echo $this->get_field_name('tweets_number'); ?>" type="text" value="<?php echo $tweets_number; ?>" />
			</p>
			
			<p>
			<label for="<?php echo $this->get_field_id('interval'); ?>"><?php _e('Refresh Interval:'); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id('interval'); ?>" name="<?php echo $this->get_field_name('interval'); ?>" type="text" value="<?php echo $interval; ?>" />
			</p>
			
			<p>
			<label for="<?php echo $this->get_field_id('follow_button'); ?>"><?php _e('Show Follow Button:'); ?></label> 
			<select class="widefat" id="<?php echo $this->get_field_id('follow_button'); ?>" name="<?php echo $this->get_field_name('follow_button'); ?>">	
			 <option value ="true" <?php selected( $instance[ 'follow_button' ], "true" ); ?>>Yes</option>
			 <option value ="false" <?php selected( $instance[ 'follow_button' ], "false" ); ?>>No</option>
			</select>
			</p>
			
			<p>
			<label for="<?php echo $this->get_field_id('profile_image'); ?>"><?php _e('Show Profile Image:'); ?></label> 
			<select class="widefat" id="<?php echo $this->get_field_id('profile_image'); ?>" name="<?php echo $this->get_field_name('profile_image'); ?>">	
			 <option value ="true" <?php selected( $instance[ 'profile_image' ], "true" ); ?>>Yes</option>
			 <option value ="false" <?php selected( $instance[ 'profile_image' ], "false" ); ?>>No</option>
			</select>
			</p>
			
			<p>
			<label for="<?php echo $this->get_field_id('screen_name'); ?>"><?php _e('Show Screen Name:'); ?></label> 
			<select class="widefat" id="<?php echo $this->get_field_id('screen_name'); ?>" name="<?php echo $this->get_field_name('screen_name'); ?>">	
			 <option value ="true" <?php selected( $instance[ 'screen_name' ], "true" ); ?>>Yes</option>
			 <option value ="false" <?php selected( $instance[ 'screen_name' ], "false" ); ?>>No</option>
			</select>
			</p>
			
			<p>
			<label for="<?php echo $this->get_field_id('hover_cards'); ?>"><?php _e('Expand Hover Cards:'); ?></label> 
			<select class="widefat" id="<?php echo $this->get_field_id('hover_cards'); ?>" name="<?php echo $this->get_field_name('hover_cards'); ?>">	
			 <option value ="true" <?php selected( $instance[ 'hover_cards' ], "true" ); ?>>Yes</option>
			 <option value ="false" <?php selected( $instance[ 'hover_cards' ], "false" ); ?>>No</option>
			</select>
			</p>	
			
			<p>
			<label for="<?php echo $this->get_field_id('paging_mode'); ?>"><?php _e('Paging Mode:'); ?></label> 
			<select class="widefat" id="<?php echo $this->get_field_id('paging_mode'); ?>" name="<?php echo $this->get_field_name('paging_mode'); ?>">	
			 <option value ="none" <?php selected( $instance[ 'paging_mode' ], "true" ); ?>>None</option>
			 <option value ="more" <?php selected( $instance[ 'paging_mode' ], "more" ); ?>>More</option>
			 <option value ="prev-next" <?php selected( $instance[ 'paging_mode' ], "prev-next" ); ?>>Prev-Next</option>
			 <option value ="endless-scroll" <?php selected( $instance[ 'paging_mode' ], "endless-scroll" ); ?>>Endless Scroll</option>
			</select>
			</p>			
			
		<?php
	}

}
add_action( 'widgets_init', create_function( '', 'register_widget("real_time_twitter");' ) );


function real_time_twitter_footer()
{

}

add_action( 'wp_footer', 'real_time_twitter_footer' );
?>