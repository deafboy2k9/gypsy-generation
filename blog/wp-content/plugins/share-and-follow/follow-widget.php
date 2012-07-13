<?php
class Follow_Widget  extends WP_Widget {
    function Follow_Widget() {
        /* Widget settings. */
        $widget_ops = array( 'classname' => 'follow_links', 'description' => 'Most common follow links widget' );
        /* Widget control settings. */
        $control_ops = array( 'width' => 500, 'height' => 350, 'id_base' => 'follow-widget' );
        /* Create the widget. */
        $this->WP_Widget( 'follow-widget', 'Follow Widget', $widget_ops, $control_ops );
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

             $c = new ShareAndFollow();
            $args = array(
                'add_follow_text' => "false",
                'size' => $instance['size'],                                    'list_style' => $instance['style'],
                'direction' => $instance['direction'],                          'css_images' => $instance['css_images'],
                'sidebar_tab'=>'followwrap',                                    'follow_rss'=>$instance['follow_rss'],
                'rss_text'=>$instance['rss_text'],                              'rss_link'=>$c->_options['rss_link']
            );
                
                            $args2 = array();
                            foreach ($c->_allSites as $item => $value){
                                if(strstr($value['service'],"follow") && !empty($c->_options[$item.'_link'])){
                                    $args2[]=$item;
                                }
                            }
                            $args2[]='rss';
                foreach ($args2 as $item){
                $args[$item.'_text'] = $instance[$item.'_text'];
                $args['follow_'.$item] = $instance[$item];
                $args[$item.'_link']=$c->_options[$item.'_link'];
                        }
           
        follow_links($args);
        /* After widget (defined by themes). */
        echo $after_widget;
    }

    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        /* Strip tags (if needed) and update the widget settings. */
        $instance['title'] = strip_tags( $new_instance['title'] );		$instance['size'] = $new_instance['size'];
        $instance['style'] = $new_instance['style'];                            $instance['direction'] = $new_instance['direction'];
        $instance['follow_rss']=$new_instance['rss'];                           $instance['rss_text']=$new_instance['rss_text'];
        $instance['css_images'] = $new_instance['css_images'];

        $c = new ShareAndFollow();

        
        $args2 = array();
                    foreach ($c->_allSites as $item => $value){
                        if(strstr($value['service'],"follow") ){
                            $args2[]=$item;
                        }
                    }
        foreach ($args2 as $item){
            $instance[$item] = $new_instance[$item];
            $instance[$item.'_text'] = $new_instance[$item.'_text'];
        }
        return $instance;
    }

    function form( $instance ) {
        /* Set up some default widget settings. */
        $defaults = array(
           'title' => '',                   'size'=>'16',           'style'=>'',                     'direction' => 'down',
           'facebook'=>'',               'flickr'=>'',        'stumble'=>'',                'twitter'=>'',
           'youtube'=>'',                'linkedin'=>'',      'google_buzz'=>'',               'newsletter'=>'',
           'yahoo_buzz'=>'',                'vimeo'=>'',            'soundcloud'=>'',                'dailymotion'=>'',
           'gowalla'=>'',                   'coconex'=>'',          'plaxo'=>'',                     'xing'=>'',
           'vkontakte'=>'',                 'hyves'=>'',            'orkut'=>'',                     'myspace'=>'yes',
           'phat'=>'',                      'yelp'=>'',             'rss'=>'yes',                    'tumblr'=>'',
           'xfire'=>'',                     'lastfm'=>'',           'css_images'=>'yes',             'foursquare'=>'',
           'digg'=>'',                      'bandcamp'=>'',         'sphinn'=>'',                    'itunes'=>'',
           'blogger'=>'',
           'feedburner'=>'',                'feedburner_text'=>__('Stay updated','share-and-follow'),
           'delicious'=>'',                 'moddb'=>'',            'imdb'=>'',                      'deviantart'=>'',
           'picasa'=>'',                    'slideshare'=>'',       'slideshare_text'=>__('See my presentations','share-and-follow'),
           'soundcloud_text'=>__('Listen to my music','share-and-follow'),           'vimeo_text'=>__('Watch my videos','share-and-follow'),
           'dailymotion_text'=>__('Tune to my channel','share-and-follow'),          'lastfm_text'=>__('Check my tunes','share-and-follow'),
           'facebook_text'=>__('Become a Fan','share-and-follow'),                   'foursquare_text'=>__('Follow me on FourSquare','share-and-follow'),
           'flickr_text'=>__('See my photos','share-and-follow'),                    'stumble_text'=>__('Follow my Stumbles','share-and-follow'),
           'linkedin_text'=>__('Connect with me','share-and-follow'),                'twitter_text'=>__('Tweet with me','share-and-follow'),
           'youtube_text'=>__('Subscribe to my Channel','share-and-follow'),         'hyves_text'=>__('Become Hyves friends','share-and-follow'),
           'orkut_text'=>__('Become Orkut friends','share-and-follow'),              'myspace_text'=>__('Become MySpace follower','share-and-follow'),
           'yelp_text'=>__('Read my reviews','share-and-follow'),                    'tumblr_text'=>__('Tumblr. me','share-and-follow'),
           'xfire_text'=>__('Go on a mission with me','share-and-follow'),           'yahoo_buzz_text'=>__('Connect with me','share-and-follow'),
           'google_buzz_text'=>__('Join the conversation','share-and-follow'),       'newsletter_text'=>__('Join our newsletter','share-and-follow'),
           'rss_text'=>__('RSS feed','share-and-follow'),                            'plaxo_text'=>__('Join my address book','share-and-follow'),
           'coconex_text'=>__('Connect with me','share-and-follow'),                 'gowalla_text'=>__('Follow my actions','share-and-follow'),
           'xing_text'=>__('Connect with us','share-and-follow'),                    'vkontakte_text'=>__('Become Friends','share-and-follow'),
           'digg_text'=>__('Digg my stuff','share-and-follow'),                      'sphinn_text'=>__('Read my posts','share-and-follow'),
           'itunes_text'=>__('Listen to me','share-and-follow'),                     'deviantart_text'=>__('See my artwork','share-and-follow'),
            'moddb_text'=>__('Gamer? my mods','share-and-follow'),                  'picasa_text'=>__('See my pictures','share-and-follow'),
            'bandcamp_text'=>__('Listen to the band','share-and-follow'),           'imdb_text'=>__('Read my reviews','share-and-follow'),
            'delicious_text'=>__('See what I share','share-and-follow'),'posterous_text'=>__('See my phone feed','share-and-follow'),
            'ya_text'=>__('Connect with me','share-and-follow'),'ya'=>'',		    'posterous'=>'',
            'email_text'=>__('Sign up for emails','share-and-follow'),'email'=>'',
            'blogger_text'=>__('Read my blog','share-and-follow'),'email'=>'',

             'wordpress'=>'',           'technet'=>'',    'squidoo'=>'',    'plurk'=>'',      'meetup'=>'',
             'getglue'=>'',    'ning'=>'',       'bebo'=>'',       'faves'=>'',      'identica'=>'',

             'wordpress_text'=>__('Me on wordpress','share-and-follow'),
             'technet_text'=>__('My technical items','share-and-follow'),
             'squidoo_text'=>__('Check me on Squidoo','share-and-follow'),
             'plurk_text'=>__('Connect with me on Plurk','share-and-follow'),
             'meetup_text'=>__('Come to the Meeting','share-and-follow'),
             'getglue_text'=>__('Wanna see my stickers?','share-and-follow'),
             'ning_text'=>__('Wanna see my stickers?','share-and-follow'),
             'bebo_text'=>__('Find me on Bebo','share-and-follow'),
             'faves_text'=>__('See my Faves','share-and-follow'),
             'identica_text'=>__('Me on identi.ca','share-and-follow'),

                       );
        $instance = wp_parse_args( (array) $instance, $defaults ); ?>
        <?php $c = new ShareAndFollow();  ?>
        <?php //admin pannel ?>

        <?php //title ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:','share-and-follow'); ?></label>
            <input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
        </p>
        <p>
            <?php _e('CSS images?','share-and-follow'); ?>
            <label><input type="radio" name="<?php echo $this->get_field_name( 'css_images' ); ?>" value="yes" <?php if ( 'yes' == $instance['css_images'] ) echo 'checked'; ?> /> <?php _e('Single Images','share-and-follow'); ?></label>&nbsp;&nbsp;&nbsp;&nbsp;
            <label><input type="radio" name="<?php echo $this->get_field_name( 'css_images' ); ?>" value="no" <?php if ( 'no' == $instance['css_images'] ) echo 'checked'; ?> /> <?php _e('Sprites','share-and-follow'); ?></label>
            <br /><small><?php _e('consider your users/readers.  If you are planning to make the icons 60px, its quicker to send single images when dealing with only 3 or 4 icons, if you plan to have 7 or more then sprites will most likely be faster. Consider the mobile phone and iPad user.','share-and-follow'); ?></small>
        </p>
        <?php //Size of icons ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'size' ); ?>"><?php _e('Size:','share-and-follow'); ?></label>
            <select id="<?php echo $this->get_field_id( 'size' ); ?>" name="<?php echo $this->get_field_name( 'size' ); ?>" class="widefat" style="width:100%;">
                    <option <?php if ( '16' == $instance['size'] ) echo 'selected="selected"'; ?> value="16" >16x16 px</option>
                    <option <?php if ( '24' == $instance['size'] ) echo 'selected="selected"'; ?> value="24">24x24 px</option>
                    <option <?php if ( '32' == $instance['size'] ) echo 'selected="selected"'; ?> value="32">32x32 px</option>
                    <option <?php if ( '48' == $instance['size'] ) echo 'selected="selected"'; ?> value="48">48x48 px</option>
                    <option <?php if ( '60' == $instance['size'] ) echo 'selected="selected"'; ?> value="60">60x60 px</option>
            </select>
        </p>
        <?php //Display Style ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'style' ); ?>"><?php _e('Style:','share-and-follow'); ?></label>
            <select id="<?php echo $this->get_field_id( 'style' ); ?>" name="<?php echo $this->get_field_name( 'style' ); ?>" class="widefat" style="width:100%;">
                    <option <?php if ( 'icon_text' == $instance['style'] ) echo 'selected="selected"'; ?> value="icon_text"><?php _e('Icon and Text','share-and-follow'); ?></option>
                    <option <?php if ( 'text_only' == $instance['style'] ) echo 'selected="selected"'; ?> value="text_only"><?php _e('Text only','share-and-follow'); ?> </option>
                    <option <?php if ( 'iconOnly' == $instance['style'] ) echo 'selected="selected"'; ?> value="iconOnly"><?php _e('Icon only','share-and-follow'); ?></option>
            </select>
        </p>
        <?php //Display direction ?>
        <p>
                <label for="<?php echo $this->get_field_id( 'direction' ); ?>"><?php _e('Share Icons display direction:','share-and-follow'); ?></label>
                <select id="<?php echo $this->get_field_id( 'direction' ); ?>" name="<?php echo $this->get_field_name( 'direction' ); ?>" class="widefat" direction="width:100%;">
                        <option  value="down"><?php _e('list','share-and-follow'); ?></option>
                        <option <?php if ( 'row' == $instance['direction'] ) echo 'selected="selected"'; ?>value="row"><?php _e('row','share-and-follow'); ?></option>
                </select>
        </p>
        <?php //Icons to  display and the text to show  ?>
        <b><?php _e('icons to display','share-and-follow'); ?></b>
         <table>
            <thead>
                <tr>
                    <th style="width:150px"><?php _e('Show','share-and-follow'); ?></th><th  style="width:200px"><?php _e('Link text','share-and-follow'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php // setup sites to show in widget options 

                            $args2 = array();
                            foreach ($c->_allSites as $item => $value){
                                if(strstr($value['service'],"follow") && !empty($c->_options[$item.'_link'])){
                                    $args2[]=$item;
                                }
                            }
                            $args2[]='rss';
                foreach ($args2 as $siteToShow) { ?>
                <tr>
                    <td><img src="<?php echo WP_PLUGIN_URL; ?>/share-and-follow/images/blank.gif" height="16px" width="16px" alt="<?php echo $siteToShow; ?>"  style="border-spacing:0;margin:0;padding:0;background:transparent url(<?php echo WP_PLUGIN_URL; ?>/share-and-follow/default/16/sprite-16.png) no-repeat <?php echo str_replace(" ", "px ",$c->_allSites[$siteToShow]['sprites']['16']) ?>px" />  <input type="checkbox" <?php if ( 'yes' == $instance[$siteToShow] ) echo 'checked'; ?> name="<?php echo $this->get_field_name( $siteToShow ); ?>" value="yes" id="<?php echo $this->get_field_id( $siteToShow ); ?>"><label for="<?php echo $this->get_field_id( $siteToShow ); ?>"> <?php echo str_replace("_", " ",$siteToShow) ; ?></label></td>
                    <td><input type="text" name="<?php echo $this->get_field_name( $siteToShow.'_text' ); ?>" id="<?php echo $this->get_field_id( $siteToShow.'_text' ); ?>" style="width:100%" value="<?php echo stripslashes($instance[$siteToShow.'_text']); ?>" ></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        <p><?php _e('<b><em>important</em> :</b>  Nothing will display here until you have added the link in the admin screen. Add your follow links in to the' ,'share-and-follow'); ?> <a href="admin.php?page=share-and-follow-following#enterlinks"><?php _e('admin page','share-and-follow'); ?></a>.</p>
        <?php
    }
}
?>
