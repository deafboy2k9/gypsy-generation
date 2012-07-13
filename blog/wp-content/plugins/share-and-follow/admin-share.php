<?php if(is_admin() && is_user_logged_in() && current_user_can('install_plugins')  ) {
    $c = new ShareAndFollow();
    $c->cache_warning_check();
    if (isset($_POST['update_share-and-follow']) && isset($_POST['snonce']) ) {
        if(wp_verify_nonce($_POST['snonce'],'checkshare')){
            // save options

            // loop through defaults to get all possible keys
            foreach($c->_defaults as $key => $value){
                // check if key is set
                if(isset($_POST[$key])){
                    // update the options variable
                    $c->_options[$key] = $_POST[$key];
                }
            }
            // update the settings using the options variable
            $c->update_plugin_options();
            ?>
                <div class="updated"><p><strong><?php _e("Settings Updated.", "share-and-follow");?></strong></p></div>
            <?php
        }else{
            // trying to save without a good nonce
            wp_die('hacking?');
        }

    }

    

?>

            <div class="wrap">
            <?php $c->showUsSupport(); ?>
                      <p>
                    <?php _e('On this page you can configure the share icons.  All you need to do is choose the ones you want people to use, along with the text you want them to see as a popup.','share-and-follow'); ?>

                </p>
                                <p>
                    <?php _e('Adding items here will also make them available in the widget for following.','share-and-follow'); ?>

                </p>


                <br/><br/><br/><br/>
            <div class="std">
                <form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
                <?php wp_nonce_field('checkshare','snonce'); ?>
           
           
                <h1><?php _e('Share Icons Setup','share-and-follow'); ?></h1>
                <h3><?php _e('Allow Share Icons to be added to the End of a Post?','share-and-follow'); ?></h3>
                <input type="hidden" name="cssid" id="cssid" value="<?php echo ($c->_options['cssid']+1);?>" />
                <p><?php _e('Selecting &quot;No&quot; will disable the content from being added into the end of a post.','share-and-follow'); ?></p>
                <p><label for="devloungeAddContent_yes"><input type="radio" id="devloungeAddContent_yes" name="add_content" value="true" <?php if ($c->_options['add_content'] == "true") {echo "checked=\"checked\"";} ?> /> <?php _e('Yes','share-and-follow'); ?></label>&nbsp;&nbsp;&nbsp;&nbsp;
                <label for="devloungeAddContent_no"><input type="radio" id="devloungeAddContent_no" name="add_content" value="false" <?php if ($c->_options['add_content'] == "false") {echo "checked=\"checked\"";} ?>/> <?php _e('No','share-and-follow'); ?></label></p>
                <h3><?php _e('Where to show the share icons','share-and-follow'); ?></h3>
                <p><?php _e('Choose where on your site the share icons will be automatically added','share-and-follow'); ?></p>
                <?php $args = array ('wp_page'=>__('pages','share-and-follow'), 'wp_post'=>__('posts','share-and-follow'), 'wp_author'=>__ ('author pages','share-and-follow'), 'wp_home'=>__('home page','share-and-follow'), 'wp_archive'=>__('tags, archive or catagory page','share-and-follow'),  ); ?>
                <?php foreach($args as $key=>$value){
                    ?>
                <input type="hidden" value="no" name="<?php echo $key; ?>" />
                <input type="checkbox" <?php if ( 'yes' == $c->_options[$key] ) {echo "checked=\"checked\"";} ?> name="<?php echo $key; ?>" value="yes" id="<?php echo $key; ?>"/><label for="<?php echo $key; ?>"><?php echo $value; ?></label><br />
                    <?php
                } ?>
                <label><?php _e('exclude these IDs :','share-and-follow'); ?></label><input type="text" name="excluded_share_pages" value="<?php echo $c->_options['excluded_share_pages']; ?>">
                <p><?php _e('exclude pages or posts by entering IDs as a comma separated list. i.e. 1, 2, 3, 4   (ideal for About and Contact page)','share-and-follow'); ?></p>
                <ul style="padding:0;margin:0">
                        <li style="float:left;width:50%;padding:0;margin:0">
                            <h3><label for="size"><?php _e('Size of Icons on Posts','share-and-follow'); ?></label></h3>
                            <select  name="size" id="size" style="width:12em">
                                <?php $args = array ('16','24','32','48','60') ?>
                                <?php foreach ($args as $sizeToShow) {?>
                                    <option value="<?php echo $sizeToShow; ?>" <?php if ($sizeToShow == $c->_options['size']) {echo 'selected="selected"';} ?>><?php echo $sizeToShow; ?>x<?php echo $sizeToShow; ?></option>
                                <?php } ?>
                            </select>
                        </li>
                         <li style="padding:1px 0 5px 0 ;margin:0 0 0 50%">
                            <h3><label for="list_style"><?php _e('Share icons display style','share-and-follow'); ?></label></h3>
                            <select id="list_style" name="list_style" style="width:12em">
                                    <option <?php if ( 'icon_text' == $c->_options['list_style'] ) echo 'selected="selected"'; ?> value="icon_text"><?php _e('Icon and Text','share-and-follow'); ?></option>
                                    <option <?php if ( 'text_only' == $c->_options['list_style'] ) echo 'selected="selected"'; ?> value="text_only"><?php _e('Text only','share-and-follow'); ?> </option>
                                    <option <?php if ( 'iconOnly' == $c->_options['list_style'] ) echo 'selected="selected"'; ?> value="iconOnly"><?php _e('Icon only','share-and-follow'); ?> </option>
                            </select>
                         </li>
                </ul>
                <ul style="padding:0;margin:0;clear:left;">
                        <li style="float:left;width:50%;padding:0;margin:0">
                            <h3><?php _e('Use CSS single images or Image Sprites?','share-and-follow'); ?></h3>
                            <p><label for="css_images_yes"><input type="radio" id="css_images_yes" name="css_images" value="yes" <?php if ($c->_options['css_images'] == "yes") {echo "checked=\"checked\"";} ?> /> <?php _e('CSS Single image','share-and-follow'); ?></label><br />
                            <label for="css_images_no"><input type="radio" id="css_images_no" name="css_images" value="no" <?php if ($c->_options['css_images'] == "no") {echo "checked=\"checked\"";;} ?>/> <?php _e('CSS Sprites(default)','share-and-follow'); ?></label></p>
                            <p><?php _e('CSS sprites are massivly faster. CSS Single Images can be easily replaced with your own images','share-and-follow'); ?></p>
                         </li>
                         <li style="padding:1px 0 5px 0 ;margin:0 0 0 50%">
                            <h3><label for="spacing"><?php _e('Icon spacing (in px)','share-and-follow'); ?> </label></h3>
                            <select  name="spacing" id="spacing" style="width:12em">
                                <?php for ( $counter = 0; $counter <= 10; $counter++) {?>
                                        <option value="<?php echo $counter; ?>" <?php if ($counter == $c->_options['spacing']) {echo 'selected="selected"';} ?>><?php echo $counter ?></option>
                                <?php } ?>
                            </select>
                            <h3><label for="top_padding"><?php _e('Padding above icons (in px)','share-and-follow'); ?> </label></h3>
                            <select  name="top_padding" id="top_padding" style="width:12em">
                                <?php for ( $counter = 0; $counter <= 40; $counter += 5) {?>
                                        <option value="<?php echo $counter; ?>" <?php if ($counter == $c->_options['top_padding']) {echo 'selected="selected"';} ?>><?php echo $counter ?></option>
                                <?php } ?>
                            </select>
                         </li>
                </ul>

                <ul style="padding:0;margin:0;clear:left;">
                        <li style="float:left;width:50%;padding:0;margin:0;">
                            <h3><?php _e('Show heading prefix','share-and-follow'); ?></h3>
                            <label for="share_yes"><input type="radio" id="share_yes" name="share" value="yes" <?php if ($c->_options['share'] == "yes") {echo "checked=\"checked\"";} ?> /> <?php _e('Yes','share-and-follow'); ?></label>&nbsp;&nbsp;&nbsp;&nbsp;
                            <label for="share_no"><input type="radio" id="share_no" name="share" value="no" <?php if ($c->_options['share'] == "no" || empty($c->_options['share']) ) {echo "checked=\"checked\"";} ?>/> <?php _e('No','share-and-follow'); ?></label>
                        </li>
                        <li style="padding:1px 0 5px 0 ;margin:0 0 0 50%">
                            <h3><?php _e('Heading text','share-and-follow'); ?></h3>
                                <label for="share_text"><?php _e('Share text ','share-and-follow'); ?></label> <input type="text" name="share_text" value="<?php echo $c->_options['share_text']; ?>" id="share_text"/>
                        </li>
                </ul>
                <h3><?php _e('Share Links to display','share-and-follow'); ?></h3>
                <p><?php _e('The popup text uses two case sensitive keywords, they are <strong>BLOG</strong> &amp; <strong>TITLE</strong>. The word <strong>BLOG</strong> is replaced with the word "post" or "blog", and the word <strong>TITLE</strong> is automatically replaced with the full title of your blog or post. ','share-and-follow'); ?></p>
                <table>
                    <thead>
                        <tr>
                            <th style="width:12em"><?php _e('Show','share-and-follow'); ?></th><th><?php _e('Link text','share-and-follow'); ?></th><th><?php _e('Popup text','share-and-follow'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php // setup sites to show automatically
                        $args = array();
                        foreach ($c->_allSites as $item => $value){
                            if($item == 'email' || $item == 'post_rss' || $item == 'bookmark' || $item == 'print'|| $item=='rss'){}
                            else{
                                if(strstr($value['service'],"share")){
                                    $args[]=$item;
                                }
                            }
                        }
                        sort($args);

                        $args[]='post_rss';
                        $args[]='print';
                        $args[]='bookmark';
                        ?>
                        <?php foreach ($args as $siteToShow) {?>
                        <tr>
                            <td>
                                <?php if ($siteToShow != 'post_rss'){?>
                                <img src="<?php echo WP_PLUGIN_URL; ?>/share-and-follow/images/blank.gif" height="16px" width="16px" alt="<?php echo $siteToShow; ?>" style="background:transparent url(<?php echo WP_PLUGIN_URL; ?>/share-and-follow/default/16/sprite-feb-16.png) no-repeat <?php echo str_replace(" ", "px ",$c->_allSites[$siteToShow]['sprites']['16']) ?>px" />
                                <?php } elseif ($siteToShow == 'post_rss'){?>
                                <img src="<?php echo WP_PLUGIN_URL; ?>/share-and-follow/images/blank.gif" height="16px" width="16px" alt="<?php echo $siteToShow; ?>" style="background:transparent url(<?php echo WP_PLUGIN_URL; ?>/share-and-follow/default/16/sprite-feb-16.png) no-repeat <?php echo str_replace(" ", "px ",$c->_allSites['rss']['sprites']['16']) ?>px" />
                                <?php } ?>
                                <input type="hidden" name="<?php echo $siteToShow; ?>" value="no" id="<?php echo $siteToShow; ?>">
                                <input type="checkbox" <?php if ( 'yes' == $c->_options[$siteToShow] ) {echo "checked=\"checked\"";} ?> name="<?php echo $siteToShow; ?>" value="yes" id="<?php echo $siteToShow; ?>"><label for="<?php echo $siteToShow; ?>"><?php echo str_replace("_", " ",$c->getRightTitle($siteToShow)) ; ?></label></td>
                            <td><input type="text" name="<?php echo $siteToShow; ?>_share_text" id="<?php echo $siteToShow; ?>_share_text" value="<?php echo stripslashes  ($c->_options[$siteToShow.'_share_text']);?>" style="width:200px"/></td>
                            <td><input type="text" name="<?php echo $siteToShow; ?>_popup_text" id="<?php echo $siteToShow; ?>_popup_text" value="<?php echo stripslashes  ($c->_options[$siteToShow.'_popup_text']);?>" style="width:200px"/></td>
                        </tr>
                        <?php } ?>
                        <tr>
                            <td><img src="<?php echo WP_PLUGIN_URL; ?>/share-and-follow/default/16/email.png" height="16px" width="16px" alt="post email" />
                                <input type="hidden" value="no" name="email" />
                                <input type="checkbox" <?php if ( 'yes' == $c->_options['email'] ) {echo "checked=\"checked\"";} ?> name="email" value="yes" id="_email"><label for="email">email</label></td>
                            <td><input type="text" name="email_share_text" id="email_share_text" value="<?php echo $c->_options['email_share_text'];?>" style="width:200px"/>

                            </td>
                            <td><input type="text" name="email_popup_text" id="email_popup_text" value="<?php echo stripslashes  ($c->_options['email_popup_text']);?>" style="width:200px"/></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td colspan="2"><h4><?php _e('What it says in the email message','share-and-follow'); ?></h4>
                                <textarea name="email_body_text"  id="email_body_text"  style="width:350px" rows="3" cols="20" ><?php echo stripslashes($c->_options['email_body_text']);?></textarea></td>
                        </tr>
                    </tbody>
                </table>
                <ul style="padding:0;margin:0;clear:left;">
                  <li style="">

                <h3><label for="add_short"><?php _e('Setup short URLs for twitter','share-and-follow'); ?></label></h3>
                <?php if (function_exists('json_decode')){ ?>
                <table class="like">
                <tr><td><label for="bit_ly"><?php _e('Bit.ly user name','share-and-follow'); ?></label></td><td><input type="text" name="bit_ly" value="<?php echo $c->_options['bit_ly'];  ?>" style="width:200px" id="bit_ly"/></td>
                </tr><tr><td><label for="bit_ly_code"><?php _e('Bit.ly API Code','share-and-follow'); ?></label></td><td><input type="text" name="bit_ly_code" value="<?php echo $c->_options['bit_ly_code']; ?>" style="width:200px" id="bit_ly_code"/></td>
                </tr><tr><td><label for="bit_ly_domain"><?php _e('Bit.ly Pro Domain (optional) ','share-and-follow'); ?></label></td><td><input type="text" name="bit_ly_domain" value="<?php echo $c->_options['bit_ly_domain']; ?>" style="width:200px" id="bit_ly_domain"/><br /><small><?php _e("Bit.ly pro domain only without 'http://', i.e. short.ie", 'share-and-follow'); ?></small></td></tr>
                </table>
                <p><?php _e('Setting up a <a href="http://bit.ly/a/sign_up">bit.ly account</a> and entering the details above, the system will automatically make URLs short for sharing on twitter.','share-and-follow'); ?></p><br />

                <?php }
                else {_e("Sorry, but you can't have short URLs until you upgrade your version of PHP to support JSON.  This means you need version PHP 5.2.0 or above to be installed on your server, please talk to your hosting company about it. ", 'share-and-follow');}
                ?>
                <h3><?php _e('What a tweets look like','share-and-follow'); ?></h3>
                <h4>Tweet Message</h4>
                 <input type="radio" <?php if ( 'clean' == $c->_options['twitter_text'] ) {echo "checked=\"checked\"";} ?> name="twitter_text" value="clean" ><label for="twitter_text"><?php _e('Just the URL','share-and-follow'); ?></label><br />
                 <input type="radio" <?php if ( 'title' == $c->_options['twitter_text'] ) {echo "checked=\"checked\"";} ?> name="twitter_text" value="title" ><label for="twitter_text"><?php _e('Title of the post or page and the URL','share-and-follow'); ?></label><br />
                 <b><?php _e('Default message to prefix the URL', 'share-and-follow') ?></b><br />
                 <input type="text" name="twitter_text_default" style="width:200px" value="<?php echo $c->_options['twitter_text_default']; ?>" />
                 <p><?php _e('Entering a message in here will make that the default for all tweets. If you add the custom field of "twitter_text" in a post, the value will be a unique message','share-and-follow'); ?></p>
                 <h4><?php _e('Tweet Suffix','share-and-follow'); ?> <small><?php _e('what it says after the tweet','share-and-follow'); ?></small></h4>
                 <input type="text" name="twitter_text_suffix" style="width:200px"  value="<?php echo $c->_options['twitter_text_suffix']; ?>" />
                 <p><?php _e('Entering a message in here will make that the default for all tweets. The ideal way to use this is for adding "VIA @twittername" to the end of tweets automatically.  If you add the custom field of "twitter_suffix" in a post, the value will be a unique suffix for the tweet','share-and-follow'); ?></p>
                    </li>
                </ul>

                 <input type="submit" name="update_share-and-follow" value="<?php _e('Update Settings', 'share-and-follow') ?>" />
                </form>
                 </div>

            </div>



<?php 
}else{
    wp_die("hacking?");
}