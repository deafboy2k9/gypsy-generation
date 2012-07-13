<?php
class Share_Widget  extends WP_Widget {
        function Share_Widget() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'share_links', 'description' => 'Most common share links widget' );
		/* Widget control settings. */
		$control_ops = array( 'width' => 550, 'height' => 350, 'id_base' => 'share-widget' );
		/* Create the widget. */
		$this->WP_Widget( 'share-widget', 'Share Widget', $widget_ops, $control_ops );
	}

        function widget( $args, $instance ) {
		extract( $args );
		/* User-selected settings. */
		$title = apply_filters('widget_title', $instance['title'] );
		/* Before widget (defined by themes). */
		echo $before_widget;
		/* Title of widget (before and after defined by themes). */
		if ( $title )
			echo $before_title . $title . $after_title;
		/* Display name from widget settings. */
                
                if(is_front_page()||is_category() || is_archive() || is_tag() || is_month() || is_404() || is_search()) {
                         $thePostID = 0;
                   }
                   else{
                        global $wp_query;
                        $thePostID = $wp_query->post->ID;
                   }

                  $c = new ShareAndFollow();
                  $adminOptionsName = "ShareAndFollowAdminOptions";
                  $c->_options = get_option($adminOptionsName);
                    $args = array(
                            'page_id' => $thePostID,                                        'heading' => "2",
                            'share'=>'',                                                    'css_images' => $instance['css_images'],
                            'size' => $instance['size'],                                    'list_style' => $instance['style'],
                            'direction' => $instance['direction'],                          'echo'=>'0',
                            'email_body_text' => $instance['email_body_text'],
                        );
                      
                $c->_allSites = ShareAndFollow::get_sites();
                            $args2 = array();
                            foreach ($c->_allSites as $item => $value){
                                if(strstr($value['service'],"share") ){
                                  if ($item != 'rss'){
                                    $args2[]=$item;
                                  }
                                }
                            }
                foreach ($args2 as $item){
                 $args[$item] = $instance[$item];
                 $args[$item.'_share_text'] = $instance[$item.'_share_text'];
                 $args[$item.'_popup_text'] = $instance[$item.'_popup_text'];
                }
                                
		social_links($args);
                // print_r($args);
		/*
                 *  After widget (defined by themes). */
		echo $after_widget;
	}


	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		/* Strip tags (if needed) and update the widget settings. */
		$instance['title'] = strip_tags( $new_instance['title'] );                   $instance['size'] = $new_instance['size'];
                $instance['style'] = $new_instance['style'];                                 $instance['direction'] = $new_instance['direction'];
                $instance['css_images'] = $new_instance['css_images'];                       $instance['email_body_text'] = $new_instance['email_body_text'];
                //
                // setup icons sizes in a option
                 
                $c->_allSites = ShareAndFollow::get_sites();
                    $args2 = array();
                    foreach ($c->_allSites as $item => $value){
                        if(strstr($value['service'],"share") ){
                          if ($item != 'rss'){
                            $args2[]=$item;
                          }
                        }
                    }
                foreach ($args2 as $item){
                $instance[$item] = $new_instance[$item];
                $instance[$item.'_share_text'] = $new_instance[$item.'_share_text'];
                $instance[$item.'_popup_text'] = $new_instance[$item.'_popup_text'];
                }

            return $instance;
	}

        function form( $instance ) {

		/* Set up some default widget settings. */
               $c = new ShareAndFollow();
               $default =array('title'=>'Share this blog');
               $args2 = array();
                    foreach ($c->_allSites as $item => $value){
                        if(strstr($value['service'],"share") ){
                          if ($item != 'rss'){
                            $args2[]=$item;
                          }
                        }
                    }
                foreach ($args2 as $item){
                $default[$item] = $c->_options[$item];
                $default[$item.'_share_text'] = $c->_options[$item.'_share_text'];
                $default[$item.'_popup_text'] = $c->_options[$item.'_popup_text'];
                }
                $default['email_body_text'] = $c->_options['email_body_text'];
                $default['css_images']= 'yes';
                $default['size']='16';
                $default['style']='';
                $default['direction'] = 'down';
                
		$instance = wp_parse_args( (array) $instance, $default );?>
                <input id="<?php echo $this->get_field_id( 'email_body_text' ); ?>" name="<?php echo $this->get_field_name( 'email_body_text' ); ?>"  type="hidden" value="<?php echo $instance['email_body_text']; ?>"/>
                <p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:','share-and-follow'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%" />
		</p>
                <p>
                    <?php _e('CSS images?','share-and-follow'); ?>
                    <label><input type="radio" name="<?php echo $this->get_field_name( 'css_images' ); ?>" value="yes" <?php if ( 'yes' == $instance['css_images'] ) echo 'checked'; ?> /> <?php _e('Single Images','share-and-follow'); ?></label>&nbsp;&nbsp;&nbsp;&nbsp;
                    <label><input type="radio" name="<?php echo $this->get_field_name( 'css_images' ); ?>" value="no" <?php if ( 'no' == $instance['css_images'] ) echo 'checked'; ?> /> <?php _e('Sprites','share-and-follow'); ?></label>
                    <br /><small><?php _e('consider your users/readers.  If you use all the same icon sizes and sprites it will load quickest, as your users maybe on a slow mobile phone or iPad user.  If you use large size icons, consider using single images to reduce the page load time.','share-and-follow'); ?></small>

                </p>

                <p>
			<label for="<?php echo $this->get_field_id( 'size' ); ?>"><?php _e('Size:','share-and-follow'); ?></label><br />
			<select id="<?php echo $this->get_field_id( 'size' ); ?>" name="<?php echo $this->get_field_name( 'size' ); ?>" class="widefat" style="width:250px;">
				<option <?php if ( '16' == $instance['size'] ) echo 'selected="selected"'; ?> value="16" >16x16 px</option>
				<option <?php if ( '24' == $instance['size'] ) echo 'selected="selected"'; ?> value="24">24x24 px</option>
                                <option <?php if ( '32' == $instance['size'] ) echo 'selected="selected"'; ?> value="32">32x32 px</option>
                                <option <?php if ( '48' == $instance['size'] ) echo 'selected="selected"'; ?> value="48">48x48 px</option>
                                <option <?php if ( '60' == $instance['size'] ) echo 'selected="selected"'; ?> value="60">60x60 px</option>
			</select>
		</p>


                <p>
			<label for="<?php echo $this->get_field_id( 'style' ); ?>"><?php _e('Style:','share-and-follow'); ?></label><br />
			<select id="<?php echo $this->get_field_id( 'style' ); ?>" name="<?php echo $this->get_field_name( 'style' ); ?>" class="widefat" style="width:250px">
				<option <?php if ( 'icon_text' == $instance['style'] ) echo 'selected="selected"'; ?> value="icon_text">Icon and Text</option>
				<option <?php if ( 'text_only' == $instance['style'] ) echo 'selected="selected"'; ?> value="text_only">Text only </option>
                                <option <?php if ( 'iconOnly' == $instance['style'] ) echo 'selected="selected"'; ?> value="iconOnly">Icon only </option>
			</select>
		</p>
                <p>
			<label for="<?php echo $this->get_field_id( 'direction' ); ?>"><?php _e('Direction:','share-and-follow'); ?></label><br />
			<select id="<?php echo $this->get_field_id( 'direction' ); ?>" name="<?php echo $this->get_field_name( 'direction' ); ?>" class="widefat" style="width:250px">
				<option  value="down"><?php _e('list','share-and-follow'); ?></option>
				<option <?php if ( 'row' == $instance['direction'] ) echo 'selected="selected"'; ?>value="row"><?php _e('row','share-and-follow'); ?></option>
			</select>
		</p>
                <b><?php _e('icons to display','share-and-follow'); ?></b>
                <table>
                        <thead>
                                <tr>
                                    <th style="width:150px"><?php _e('Show','share-and-follow'); ?></th><th style="width:190px"><?php _e('Link text','share-and-follow'); ?></th><th style="width:190px"><?php _e('Popup text','share-and-follow'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php // setup sites to show in widget options ?>
                                <?php

                            $args = array();
                            foreach ($c->_allSites as $item => $value){
                                if(strstr($value['service'],"share") ){
                                  if ($item != 'rss'){
                                    $args[]=$item;
                                  }
                                }
                            }
                            sort($args);
                            ?>
                                <?php // $args = array('facebook','twitter', 'stumble','google_buzz','yahoo_buzz','digg', 'delicious','reddit', 'hyves','linkedin','orkut', 'myspace','mixx','nujij','sphinn','technorati','tumblr', 'vkontakte', 'xing' , 'bookmark', 'email','print' ); ?>
                                <?php // loop through showing all items ?>
                                <?php foreach ($args as $siteToShow) { ?>
                                        <tr>
                                            <td style="vertical-align: top !important;"><img src="<?php echo WP_PLUGIN_URL; ?>/share-and-follow/images/blank.gif" height="16px" width="16px" alt="<?php echo $siteToShow; ?>"  style="border-spacing:0;margin:0;padding:0;background:transparent url(<?php echo WP_PLUGIN_URL; ?>/share-and-follow/default/16/sprite-16.png) no-repeat <?php echo str_replace(" ", "px ",$c->_allSites[$siteToShow]['sprites']['16']) ?>px" /> <input type="checkbox" <?php if ( 'yes' == $instance[$siteToShow] ) echo 'checked'; ?> name="<?php echo $this->get_field_name( $siteToShow ); ?>" value="yes" id="<?php echo $this->get_field_id( $siteToShow ); ?>"><label for="<?php echo $this->get_field_id( $siteToShow ); ?>"> <?php echo str_replace("_", " ",$siteToShow) ; ?></label></td>
                                            <td style="vertical-align: top !important;"><input type="text" name="<?php echo $this->get_field_name( $siteToShow.'_share_text' ); ?>" id="<?php echo $this->get_field_id( $siteToShow.'_share_text' ); ?>" style="width:100%"  value="<?php echo stripslashes( $instance[$siteToShow.'_share_text']); ?>" ></td>
                                            <td style="vertical-align: top !important;"><input type="text" name="<?php echo $this->get_field_name( $siteToShow.'_popup_text' ); ?>" id="<?php echo $this->get_field_id( $siteToShow.'_popup_text' ); ?>" style="width:100%"  value="<?php echo stripslashes( $instance[$siteToShow.'_popup_text']); ?>" ></td>
                                        </tr>
                                <?php } ?>
                            </tbody>
                </table>
                <p><?php _e('the words <strong>BLOG</strong> &amp; <strong>TITLE</strong> are keywords that will be replaced.  BLOG is replaced with the word "post" or "blog" and the word TITLE will be replaced with full title of the post','share-and-follow'); ?></p>
                <?php
	}
}


?>
