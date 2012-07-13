<?php
if(is_admin() && is_user_logged_in() && current_user_can('install_plugins')  ) {
    $c = new ShareAndFollow();
    $c->cache_warning_check();
    if (isset($_POST['reset_share-and-follow']) && isset($_POST['rnonce']) ) {

        if(wp_verify_nonce($_POST['rnonce'],'checkreset')){ 
            
        $c->_options = $c->return_defaults();
        $c->update_plugin_options();

        
           ?> <div class="updated"><p><strong><?php _e("Settings Reset.", "share-and-follow");?></strong></p></div>
           <p><?php _e("All setings have now been reset to installation defaults, your widget settings have not been effected.  You will need to re-add your follow links. <a href='admin.php?page=share-and-follow-menu'>Return to the adminstration page</a> to make the changes", "share-and-follow");?></p>
           <?php

        }else{
            wp_die('hacking?');
        }


        }
        else{
            
            ?>
           <div class="wrap">
               <?php $c->showUsSupport(); ?>
                 <div class="std">
                            <form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
                            <?php wp_nonce_field('checkreset','rnonce'); ?>
                            <h1 id="reset-settings"><?php _e('Reset settings?', 'share-and-follow') ?></h1>
                            <p><?php _e('Want to reset the settings of Share and Follow? click the reset button below to restore the installation defaults.', 'share-and-follow') ?></p>
                            <p><?php _e('<em><b>important:</b></em> this will remove all of your settings.  You might want to go through each of the menu items and save that page so you have a listing of your settings before pressing the reset button', 'share-and-follow') ?></p>
                            <input type="submit" name="reset_share-and-follow" value="<?php _e('Reset Settings', 'share-and-follow') ?>" />
                            </form>
                 </div>
           </div>
<?php }
}

?>