<?php if(is_admin() && is_user_logged_in() && current_user_can('install_plugins')  ) {
    $c = new ShareAndFollow();
    $c->cache_warning_check();
    if (isset($_POST['update_share-and-follow']) && isset($_POST['cnonce']) ) {
        if(wp_verify_nonce($_POST['cnonce'],'checkcss')){
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
    // check file access for the CSS file
    if($c->_options['add_css'] == 'true'){
            $dir = plugin_dir_path( __FILE__ );
            $pd = WP_PLUGIN_DIR;
           foreach (array('print','stylesheet') as $name) {
                    if($name == 'print' && $c->_options['print_support'] == 'false') {}else{
                        $fp = @fopen($pd."/share-and-follow/css/".$name.".css",'w');
                        $perms = substr(sprintf('%o', fileperms(trailingslashit($dir) . "/css/" . $name . ".css"  )), -3);

                        if($fp === false){
                            $advice = ($perms != 664) ?  "First try <strong>664</strong> to enable group write access." : "" ;
                            echo "<div class='error'><h4>Major Problem </h4><p>The stylesheet file <strong>" . $pd . "/share-and-follow/css/".$name.".css </strong> cannot be written to. It currently has the settings of <strong>".$perms."</strong>.  Please change the it so that it can be written to. " . $advice . ". Check with your hosting company if you continue to have problems as to what settings you should use and how to make the correct changes. Alternativly use inline styles.<p><p><strong>Under no circumstance should you ever make the settings 777, it will open the file to hackers! Go back to your host and ask for more support on how to make this change</strong></p></div>";
                        }
                        else {
                            fclose($fp);
                            }
                    }
           }

    }

    // show admin page for CSS
    ?>
<div class="wrap">
    <?php $c->showUsSupport(); ?>
<div class="std">
       <form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
           <?php wp_nonce_field('checkcss','cnonce'); ?>
           <input type="hidden" name="cssid" id="cssid" value="<?php echo ($c->_options['cssid']+1);?>" />
           
             <h1><?php _e('CSS and style Configuration', 'share-and-follow') ?></h1>
             <h3><?php _e('Use external CSS?', 'share-and-follow') ?></h3>
             <p><?php _e('You have the choice to use an external stylesheet or a style section in the head of the HTML.  If you are using caching and need an ultra fast site, say NO as it will reduce the number of connections.  As it generates the style setion in the head on-the-fly it is slower to do it this way without using caching.  If you need to know, the plugin uses the following external CSS file', 'share-and-follow') ?> <strong>/wp-content/plugins/share-and-follow/css/stylesheet.css</strong>.</p>
                <p><label for="add_css_yes"><input type="radio" id="add_css_yes" name="add_css" value="true" <?php if ($c->_options['add_css'] == "true") {_e('checked="checked"', "shareAndcss");} ?> /><?php _e('Yes', 'share-and-follow') ?></label>&nbsp;&nbsp;&nbsp;&nbsp;
                    <label for="add_css_no"><input type="radio" id="add_css_no" name="add_css" value="false" <?php if ($c->_options['add_css'] == "false") {_e('checked="checked"', "shareAndcss");} ?>/><?php _e('No', 'share-and-follow') ?></label></p>
                <p><?php _e('be careful as it reloads the CSS dynamically evey time there is a change to the admin screen', 'share-and-follow') ?></p>
                <h3><?php _e('Add your own CSS', 'share-and-follow') ?></h3>
                    <textarea cols="20" rows="10" style="width:100%"  name="extra_css" ><?php echo stripslashes($c->_options['extra_css']) ?></textarea>
                <h3><?php _e('Add theme support', 'share-and-follow') ?></h3>
                <p><?php _e('Wordpress has many themes, slowly over time we will be adding more and more CSS packs to support those themes. For now we have a selection of the top ones. <em>Please note</em> that Kubric/Default will work for most themes no matter what the name, as they have been based on this theme originally.', 'share-and-follow') ?></p>
                <select  name="theme_support" id="theme_support" style="width:12em">
                    <option value="none" <?php if ("none" == $c->_options['theme_support']) {echo 'selected="selected"';} ?>>none</option>
                    <option value="default" <?php if ("default" == $c->_options['theme_support']) {echo 'selected="selected"';} ?>>Kubric/Default</option>
                    <option value="choco" <?php if ("choco" == $c->_options['theme_support']) {echo 'selected="selected"';} ?>>ChocoTheme</option>
                    <option value="arjuna" <?php if ("arjuna" == $c->_options['theme_support']) {echo 'selected="selected"';} ?>>Arjuna X</option>
                    <option value="intrepidity" <?php if ("intrepidity" == $c->_options['theme_support']) {echo 'selected="selected"';} ?>>Intrepidity</option>
                    <option value="dojo" <?php if ("dojo" == $c->_options['theme_support']) {echo 'selected="selected"';} ?>>Dojo</option>
                    <option value="thesis" <?php if ("thesis" == $c->_options['theme_support']) {echo 'selected="selected"';} ?>>Thesis</option>
                    <option value="tribune" <?php if ("tribune" == $c->_options['theme_support']) {echo 'selected="selected"';} ?>>Tribune</option>
                    <option value="mymag" <?php if ("mymag" == $c->_options['theme_support']) {echo 'selected="selected"';} ?>>MyMag</option>
                    <option value="frugal" <?php if ("frugal" == $c->_options['theme_support']) {echo 'selected="selected"';} ?>>Frugal</option>
                </select>
                <h3><?php _e('Page Print Support', 'share-and-follow') ?></h3>
                <h3><?php _e('Do you want to load a print CSS file?', 'share-and-follow') ?></h3>
                <p><?php _e('If you already have your own Print CSS file, then it is best to set this to NO, otherwise feel free to use the Share and Follow one.  If you need to find the CSS file it is in <br />', 'share-and-follow') ?> <strong>/wp-content/plugins/share-and-follow/css/print.css</strong>.</p>
                <p><label for="print_support_yes"><input type="radio" id="print_support_yes" name="print_support" value="true" <?php if ($c->_options['print_support'] == "true") {_e('checked="checked"', "shareAndcss");} ?> /><?php _e('Yes', 'share-and-follow') ?></label>&nbsp;&nbsp;&nbsp;&nbsp;
                    <label for="print_support_no"><input type="radio" id="print_support_no" name="print_support" value="false" <?php if ($c->_options['print_support'] == "false") {_e('checked="checked"', "shareAndcss");} ?>/><?php _e('No', 'share-and-follow') ?></label></p>
                <p><?php _e('be careful as it reloads the CSS dynamically evey time there is a change to the admin screen', 'share-and-follow') ?></p>
                <p><?php _e('Printing a page is different to reading it from the screen. There are many things that do not need to be there on a printed page, such as the menu or navigation.  Use the entry box to provide a comment list of CSS selectors to help control how your printouts look.  By default a few have been added to help you.', 'share-and-follow') ?></p>
                    <input type="text" name="css_print_excludes" value="<?php echo $c->_options['css_print_excludes']; ?>" style="width:80%"/>
                <br />
                <h3><?php _e('Add your own Print CSS ', 'share-and-follow') ?></h3>
                    <textarea cols="20" rows="10" style="width:100%"  name="extra_print_css" ><?php echo stripslashes($c->_options['extra_print_css']) ?></textarea>
                    <input type="submit" name="update_share-and-follow" value="<?php _e('Update Settings', 'share-and-follow') ?>" />
                <br />
       </form>
            </div>

</div>


    <?php
}else{
  // trying to direct access the file.  most likely will not show this message, but fail badly!
  die('hacking?');
}   ?>
