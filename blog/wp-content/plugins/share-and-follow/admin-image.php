<?php if(is_admin() && is_user_logged_in() && current_user_can('install_plugins')  ) {
    $c = new ShareAndFollow();
    $c->cache_warning_check();
    if (isset($_POST['update_share-and-follow']) && isset($_POST['imagenonce']) ) {
        if(wp_verify_nonce($_POST['imagenonce'],'checkimage')){
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
            ?><div class="updated"><p><strong><?php _e("Settings Updated.", "share-and-follow");?></strong></p></div><?php
        }else{
            // trying to save without a good nonce
            wp_die('hacking?');
        }
    }
?>
            <div class="wrap">
            <?php $c->showUsSupport(); ?>
            <div class="std">
                <form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
                <?php wp_nonce_field('checkimage','imagenonce'); ?>

             <h1 id="share-image"><?php _e('Setup share image', 'share-and-follow') ?></h1>
             <p><?php _e('By setting up a share image, social networks such as Facebook will choose that share image as its primary image to display on screen in news feeds inside facebook. This is especially useful if the theme is made of only image replacement images rather than HTML tag images, as facebook will now have the opportunity to show an image rather than none at all', 'share-and-follow') ?>.</p>
             <h3><?php _e('Show Share Images', 'share-and-follow') ?></h3>
             <p><?php _e('Add the share image metadata to the head section of your web pages.  Saying "no" will remove the functionality', 'share-and-follow') ?>.</p>
             <p><label for="add_image_link_yes"><input type="radio" id="add_image_link_yes" name="add_image_link" value="true" <?php if ($c->_options['add_image_link'] == "true") {_e('checked="checked"', "shareAndcss");} ?> /><?php _e('Yes', 'share-and-follow') ?></label>&nbsp;&nbsp;&nbsp;&nbsp;
                <label for="add_image_link_no"><input type="radio" id="add_image_link_no" name="add_image_link" value="false" <?php if ($c->_options['add_image_link'] == "false") {_e('checked="checked"', "shareAndcss");} ?>/><?php _e('No', 'share-and-follow') ?></label></p>
             <h3><?php _e('Setup Default Gravatar image', 'share-and-follow') ?> <small><a href="http://gravatar.com/" target="_blank">goto gravatar</a></small></h3>
                <?php _e('enter your email address', 'share-and-follow') ?> <input type="email" name="default_email" id="default_email" value="<?php echo $c->_options['default_email'];?>" /><br />
                <?php _e('alternative default image', 'share-and-follow') ?> <input type="text" name="default_email_image" id="default_email_image" value="<?php echo $c->_options['default_email_image'];?>" />
                <p><?php _e('You can choose an alternative image to display rather than the standard gravatar one, if so, enter the URL here including http://, useful for high volume sites.', 'share-and-follow') ?></p>
                <h3><?php _e('Author Settings', 'share-and-follow') ?></h3>
                    <input type="radio" <?php if ( 'default' == $c->_options['author_defaults'] ) {echo "checked=\"checked\"";} ?> name="author_defaults" value="default" ><label for="author_defaults"><?php _e('Use default gravatar','share-and-follow'); ?></label><br />
                    <input type="radio" <?php if ( 'authors' == $c->_options['author_defaults'] ) {echo "checked=\"checked\"";} ?> name="author_defaults" value="authors" ><label for="author_defaults"><?php _e('Use author email to generate gravatar','share-and-follow'); ?></label><br />
                <h3><?php _e('Site Logo setup', 'share-and-follow') ?></h3>
                <?php _e('enter image URL', 'share-and-follow') ?> <input type="text" name="logo_image_url" value="<?php echo $c->_options['logo_image_url']; ?>" />
                <p><?php _e('Include http:// in the URL, make sure that the image is no larger than 130px wide by 110px high.', 'share-and-follow') ?></p>
                <p><?php _e('<strong><em>Important:</em></strong> the system will default to this image if there is not an image in a post or page that it can find.','share-and-follow')?></p>
                <h3><?php _e('Setup image logic: Who, What, Where.', 'share-and-follow') ?></h3>
                <table class="logic">
                    <tr><th style="width:160px"><?php _e('Type of page', 'share-and-follow') ?></th><th><?php _e('Display logic', 'share-and-follow') ?></th></tr>
                    <tr>
                        <td><?php _e('Pages', 'share-and-follow') ?></td>
                        <td> <input type="radio" <?php if ( 'gravatar' == $c->_options['page_img'] ) {echo "checked=\"checked\"";} ?> name="page_img" value="gravatar" ><label for="page_img"><?php _e('Author Gravatar','share-and-follow'); ?></label><br />
                             <input type="radio" <?php if ( 'logo' == $c->_options['page_img'] ) {echo "checked=\"checked\"";} ?> name="page_img" value="logo" ><label for="page_img"><?php _e('Site Logo','share-and-follow'); ?></label><br />
                             <input type="radio" <?php if ( 'postImage' == $c->_options['page_img'] ) {echo "checked=\"checked\"";} ?> name="page_img" value="postImage" ><label for="page_img"><?php _e('Image from page','share-and-follow'); ?></label><br />
                             <h4><?php _e('optional default image', 'share-and-follow') ?></h4>
                             <input type="text" name="page_image_url" value="<?php echo $c->_options['page_image_url']; ?>" />
                             <br /><small><?php _e('Enter full URL including http:// to the image you want to use. Making the field blank will restore the radio button logic', 'share-and-follow') ?></small>
                        </td>
                    </tr>
                    <tr>
                        <td><?php _e('Posts', 'share-and-follow') ?></td>
                        <td> <input type="radio" <?php if ( 'gravatar' == $c->_options['post_img'] ) {echo "checked=\"checked\"";} ?> name="post_img" value="gravatar" ><label for="post_img"><?php _e('Author Gravatar','share-and-follow'); ?></label><br />
                             <input type="radio" <?php if ( 'logo' == $c->_options['post_img'] ) {echo "checked=\"checked\"";} ?> name="post_img" value="logo" ><label for="post_img"><?php _e('Site Logo','share-and-follow'); ?></label><br />
                             <input type="radio" <?php if ( 'postImage' == $c->_options['post_img'] ) {echo "checked=\"checked\"";} ?> name="post_img" value="postImage" ><label for="post_img"><?php _e('Image from post','share-and-follow'); ?></label><br />
                             <h4><?php _e('optional default image', 'share-and-follow') ?></h4>
                             <input type="text" name="post_image_url" value="<?php echo $c->_options['post_image_url']; ?>" />
                             <br /><small><?php _e('Enter full URL including http:// to the image you want to use. Making the field blank will restore the radio button logic', 'share-and-follow') ?></small>
                        </td>
                    </tr>
                    <tr>
                        <td><?php _e('Homepage', 'share-and-follow') ?></td>
                        <td> <input type="radio" <?php if ( 'gravatar' == $c->_options['homepage_img'] ) {echo "checked=\"checked\"";} ?> name="homepage_img" value="gravatar" ><label for="homepage_img"><?php _e('Default Author Gravatar','share-and-follow'); ?></label><br />
                             <input type="radio" <?php if ( 'logo' == $c->_options['homepage_img'] ) {echo "checked=\"checked\"";} ?> name="homepage_img" value="logo" ><label for="homepage_img"><?php _e('Site Logo','share-and-follow'); ?></label><br />
                             <h4><?php _e('optional default image', 'share-and-follow') ?></h4>
                             <input type="text" name="homepage_image_url" value="<?php echo $c->_options['homepage_image_url']; ?>" />
                             <br /><small><?php _e('Enter full URL including http:// to the image you want to use. Making the field blank will restore the radio button logic', 'share-and-follow') ?></small>
                        </td>
                    </tr>
                    <tr>
                        <td><?php _e('Archive pages', 'share-and-follow') ?></td>
                        <td> <input type="radio" <?php if ( 'gravatar' == $c->_options['archive_img'] ) {echo "checked=\"checked\"";} ?> name="archive_img" value="gravatar" ><label for="archive_img"><?php _e('Default Author Gravatar','share-and-follow'); ?></label><br />
                             <input type="radio" <?php if ( 'logo' == $c->_options['archive_img'] ) {echo "checked=\"checked\"";} ?> name="archive_img" value="logo" ><label for="archive_img"><?php _e('Site Logo','share-and-follow'); ?></label><br />
                             <h4><?php _e('optional default image', 'share-and-follow') ?></h4>
                             <input type="text" name="archive_image_url" value="<?php echo $c->_options['archive_image_url']; ?>" />
                             <br /><small><?php _e('Enter full URL including http:// to the image you want to use. Making the field blank will restore the radio button logic', 'share-and-follow') ?></small>
                        </td>
                    </tr>
                </table>
                <p><?php _e('It is possible to have a specific image setup in a post or page, by adding a custom field called "image_src" and setting an image URL uniquely for that page or post', 'share-and-follow') ?></p>
                <input type="submit" name="update_share-and-follow" value="<?php _e('Update Settings', 'share-and-follow') ?>" />

                </form>
            </div>
            </div>
                <?php
}else{
    wp_die("hacking?");
}

