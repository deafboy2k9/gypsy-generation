<?php if(is_admin() && is_user_logged_in() && current_user_can('install_plugins')  ) {
    $c = new ShareAndFollow();
    $c->cache_warning_check();
    if (isset($_POST['update_share-and-follow']) && isset($_POST['pnonce']) ) {
        if(wp_verify_nonce($_POST['pnonce'],'checkplugins')){
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
                <?php wp_nonce_field('checkplugins','pnonce'); ?>
                <input type="hidden" name="cssid" id="cssid" value="<?php echo ($c->_options['cssid']+1);?>" />

        
            <h1><?php _e('Plugin Support', 'share-and-follow') ?></h1>
            <p><?php _e('Some plugins and optional extras for wordpress have different ways of working than the normal wordpress way.  To get over this the makers of these plugins make Hooks and Functions for programmers to use.  Slowly over time we will add more plugin support for plugins that actually have hooks that we can connect to.  Otherwise we will offer support via just a template tag', 'share-and-follow') ?></p>
            <h3><?php _e('WP e-Commerce support', 'share-and-follow') ?></h3>
            <h3><?php _e('The location where the share icons show', 'share-and-follow') ?></h3>
            <?php $args = array ('wpsc_top_of_products_page'=>__('Top of products page','share-and-follow'), 'wpsc_product_before_description'=>__('Before description','share-and-follow'), 'wpsc_product_addon_after_descr'=>__ ('After Description','share-and-follow'), ); ?>
                <?php foreach($args as $key=>$value){
                    ?>
                <input type="hidden" value="no" name="<?php echo $key; ?>" />
                <input type="checkbox" <?php if ( 'yes' == $c->_options[$key] ) {echo "checked=\"checked\"";} ?> name="<?php echo $key; ?>" value="yes" id="<?php echo $key; ?>"/><label for="<?php echo $key; ?>"><?php echo $value; ?></label><br />
                    <?php
                } ?>
                <br />
            <h3><?php _e('The location where the interactive icons show', 'share-and-follow') ?></h3>
            <h4>facebook Like button</h4>
            <?php $args = array ('like_wpsc_top_of_products_page'=>__('Top of products page','share-and-follow'), 'like_wpsc_product_before_description'=>__('Before description','share-and-follow'), 'like_wpsc_product_addon_after_descr'=>__ ('After Description','share-and-follow'), ); ?>
                <?php foreach($args as $key=>$value){
                    ?>
                <input type="hidden" value="no" name="<?php echo $key; ?>" />
                <input type="checkbox" <?php if ( 'yes' == $c->_options[$key] ) {echo "checked=\"checked\"";} ?> name="<?php echo $key; ?>" value="yes" id="<?php echo $key; ?>"/><label for="<?php echo $key; ?>"><?php echo $value; ?></label><br />
                    <?php
                } ?>
                <br />
                <h4>Twitter Retweet</h4>
                <?php $args = array ('tweet_wpsc_top_of_products_page'=>__('Top of products page','share-and-follow'), 'tweet_wpsc_product_before_description'=>__('Before description','share-and-follow'), 'tweet_wpsc_product_addon_after_descr'=>__ ('After Description','share-and-follow'), ); ?>
                <?php foreach($args as $key=>$value){
                    ?>
                <input type="hidden" value="no" name="<?php echo $key; ?>" />
                <input type="checkbox" <?php if ( 'yes' == $c->_options[$key] ) {echo "checked=\"checked\"";} ?> name="<?php echo $key; ?>" value="yes" id="<?php echo $key; ?>"/><label for="<?php echo $key; ?>"><?php echo $value; ?></label><br />
                    <?php
                } ?>
                <h4>Stumble Upon Count Button</h4>
                <?php $args = array ('stumble_wpsc_top_of_products_page'=>__('Top of products page','share-and-follow'), 'stumble_wpsc_product_before_description'=>__('Before description','share-and-follow'), 'stumble_wpsc_product_addon_after_descr'=>__ ('After Description','share-and-follow'), ); ?>
                <?php foreach($args as $key=>$value){
                    ?>
                <input type="hidden" value="no" name="<?php echo $key; ?>" />
                <input type="checkbox" <?php if ( 'yes' == $c->_options[$key] ) {echo "checked=\"checked\"";} ?> name="<?php echo $key; ?>" value="yes" id="<?php echo $key; ?>"/><label for="<?php echo $key; ?>"><?php echo $value; ?></label><br />
                    <?php
                } ?>

                <input type="submit" name="update_share-and-follow" value="<?php _e('Update Settings', 'share-and-follow') ?>" />
                </form>
            </div>
            </div>
            <?php
}else{
    wp_die("hacking?");
}