<?php
/*
Plugin Name: Better Recent Posts Widget
Plugin URI: http://pippinsplugins.com/better-recent-posts-widget
Description: Provides a better recent posts widget, including thumbnails, category, and number options
Version: 1.1.4
Author: Pippin Williamson
Author URI: http://pippinsplugins.com
*/


/**
 * Recent Posts Widget Class
 */
class pippin_recent_posts extends WP_Widget {


    /** constructor */
    function pippin_recent_posts() {
        parent::WP_Widget(false, $name = 'Better Recent Posts');	
    }

    /** @see WP_Widget::widget */
    function widget($args, $instance) {	
        extract( $args );
		global $posttypes;
        $title 			= apply_filters('widget_title', $instance['title']);
        $cat 			= apply_filters('widget_title', $instance['cat']);
        $number 		= apply_filters('widget_title', $instance['number']);
        $offset 		= apply_filters('widget_title', $instance['offset']);
        $thumbnail_size = apply_filters('widget_title', $instance['thumbnail_size']);
        $thumbnail 		= $instance['thumbnail'];
        $posttype 		= $instance['posttype'];
        ?>
              <?php echo $before_widget; ?>
                  <?php if ( $title )
                        echo $before_title . $title . $after_title; ?>
							<ul class="no-bullets">
							<?php
								global $post;
								$tmp_post = $post;
								
								// get the category IDs and place them in an array
								
								$args = 'numberposts=' . $number . '&offset=' . $offset . '&post_type=' . $posttype . '&cat=' . $cat;
								$myposts = get_posts( $args );
								foreach( $myposts as $post ) : setup_postdata($post); ?>
									<li <?php if(!empty($thumbnail_size)) { $size = $thumbnail_size + 8; echo 'style="height: ' . $size . 'px;"'; } ?> >
										<?php if($thumbnail == true) { ?>
											<a href="<?php the_permalink(); ?>" style="float: left; margin: 0 5px 0 0;"><?php the_post_thumbnail(array($thumbnail_size));?></a>
										<?php } ?>
										<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a><br/>
								
									</li>
								<?php endforeach; ?>
								<?php $post = $tmp_post; ?>
							</ul>
              <?php echo $after_widget; ?>
        <?php
    }

    /** @see WP_Widget::update */
    function update($new_instance, $old_instance) {		
		global $posttypes;
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['cat'] = strip_tags($new_instance['cat']);
		$instance['number'] = strip_tags($new_instance['number']);
		$instance['offset'] = strip_tags($new_instance['offset']);
		$instance['thumbnail_size'] = strip_tags($new_instance['thumbnail_size']);
		$instance['thumbnail'] = $new_instance['thumbnail'];
		$instance['posttype'] = $new_instance['posttype'];
        return $instance;
    }

    /** @see WP_Widget::form */
    function form($instance) {	

		$posttypes = get_post_types('', 'objects');
	
        $title = esc_attr($instance['title']);
        $cat = esc_attr($instance['cat']);
        $number = esc_attr($instance['number']);
        $offset = esc_attr($instance['offset']);
        $thumbnail_size = esc_attr($instance['thumbnail_size']);
        $thumbnail = esc_attr($instance['thumbnail']);
		$posttype	= esc_attr($instance['posttype']);
        ?>
         <p>
          <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> 
          <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
		<p>
          <label for="<?php echo $this->get_field_id('cat'); ?>"><?php _e('Category IDs, separated by commas'); ?></label> 
          <input class="widefat" id="<?php echo $this->get_field_id('cat'); ?>" name="<?php echo $this->get_field_name('cat'); ?>" type="text" value="<?php echo $cat; ?>" />
        </p>
		<p>
          <label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number to Show:'); ?></label> 
          <input class="widefat" id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" />
        </p>
		<p>
          <label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Offset (the number of posts to skip):'); ?></label> 
          <input class="widefat" id="<?php echo $this->get_field_id('offset'); ?>" name="<?php echo $this->get_field_name('offset'); ?>" type="text" value="<?php echo $offset; ?>" />
        </p>
		<p>
          <input id="<?php echo $this->get_field_id('thumbnail'); ?>" name="<?php echo $this->get_field_name('thumbnail'); ?>" type="checkbox" value="1" <?php checked( '1', $thumbnail ); ?>/>
          <label for="<?php echo $this->get_field_id('thumbnail'); ?>"><?php _e('Display thumbnails?'); ?></label> 
        </p>
		<p>
          <label for="<?php echo $this->get_field_id('thumbnail_size'); ?>"><?php _e('Size of the thumbnails, e.g. <em>80</em> = 80px x 80px'); ?></label> 
          <input class="widefat" id="<?php echo $this->get_field_id('thumbnail_size'); ?>" name="<?php echo $this->get_field_name('thumbnail_size'); ?>" type="text" value="<?php echo $thumbnail_size; ?>" />
        </p>
		<p>	
			<label for="<?php echo $this->get_field_id('posttype'); ?>"><?php _e('Choose the Post Type to display'); ?></label> 
			<select name="<?php echo $this->get_field_name('posttype'); ?>" id="<?php echo $this->get_field_id('posttype'); ?>" class="widefat">
				<?php
				foreach ($posttypes as $option) {
					echo '<option value="' . $option->name . '" id="' . $option->name . '"', $posttype == $option->name ? ' selected="selected"' : '', '>', $option->name, '</option>';
				}
				?>
			</select>		
		</p>
        <?php 
    }


} // class utopian_recent_posts
// register Recent Posts widget
add_action('widgets_init', create_function('', 'return register_widget("pippin_recent_posts");'));

?>