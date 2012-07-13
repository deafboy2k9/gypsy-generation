<?php if(is_admin() && is_user_logged_in() && current_user_can('install_plugins')  ) {
    $c = new ShareAndFollow();
    $c->cache_warning_check();
    if (isset($_POST['update_share-and-follow']) && isset($_POST['fnonce']) ) {
        if(wp_verify_nonce($_POST['fnonce'],'checkfollow')){
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
                <p>
                    <?php _e('On this page you can configure the follow icons.  All you need to do is put in the URL of the site that you want people to follow you on, along with the text you want them to see as a popup.','share-and-follow'); ?>

                </p>
                <p>
                    <?php _e('You can also choose where on the page you see the follow tab, top, bottom, left, right.','share-and-follow'); ?>

                </p>

                                <p>
                    <?php _e('Adding items here will also make them available in the widget for following.','share-and-follow'); ?>

                </p>

                <br/><br/><br/><br/>
            <div class="std">
                <form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
                <?php wp_nonce_field('checkfollow','fnonce'); ?>
                <input type="hidden" name="cssid" id="cssid" value="<?php echo ($c->_options['cssid']+1);?>" />
                <h1><?php _e('Follow Side/Top/Bottom Tab setup','share-and-follow'); ?></h1>
                <h3><?php _e('Show the Follow Tab on Screen','share-and-follow'); ?></h3>
                <p><?php _e('Selecting "No" will disable the content from being added into to your website.','share-and-follow'); ?></p>
                <p><label for="add_follow_yes"><input type="radio" id="add_follow_yes" name="add_follow" value="true" <?php if ($c->_options['add_follow'] == "true") {echo "checked=\"checked\"";} ?> /><?php _e('Yes','share-and-follow'); ?></label>&nbsp;&nbsp;&nbsp;&nbsp;
                <label for="add_follow_no"><input type="radio" id="add_follow_no" name="add_follow" value="false" <?php if ($c->_options['add_follow'] == "false") {echo "checked=\"checked\"";} ?>/><?php _e('No','share-and-follow'); ?></label></p>
                <ul style="padding:0;margin:0">
                    <li style="padding:0;margin:0;clear:left;">
                         <h3><?php _e('Where to show the follow tab','share-and-follow'); ?></h3>
                         <p><?php _e('Choose where on your site the follow icons will be automatically added','share-and-follow'); ?></p>
                        <?php $args = array ('follow_page'=>__('pages','share-and-follow'), 'follow_post'=>__('posts','share-and-follow'), 'follow_author'=>__ ('author pages','share-and-follow'), 'follow_home'=>__('home page','share-and-follow'), 'follow_archive'=>__('tags, archive or catagory page','share-and-follow'), ); ?>
                        <?php foreach($args as $key=>$value){
                            ?>
                        <input type="hidden" value="no" name="<?php echo $key; ?>" />
                        <input type="checkbox" <?php if ( 'yes' == $c->_options[$key] ) {echo "checked=\"checked\"";} ?> name="<?php echo $key; ?>" value="yes" id="<?php echo $key; ?>"/><label for="<?php echo $key; ?>"><?php echo $value; ?></label><br />
                            <?php
                        } ?>
                        <label><?php _e('exclude these IDs :','share-and-follow'); ?></label><input type="text" name="excluded_follow_pages" value="<?php echo $c->_options['excluded_follow_pages']; ?>">
                        <p><?php _e('exclude pages or posts by entering IDs as a comma separated list. i.e. 1, 2, 3, 4   (ideal for homepage when you have a splash page, or part of your buy process.)','share-and-follow'); ?></p>

                        </li>
                    <li style="float:left;width:50%;padding:0;margin:0">
                        <h3><?php _e('Background Color','share-and-follow'); ?></h3>
                        <div id="colorSelector"></div>

                               #<input type="text" name="background_color" id="background_color" value="<?php echo $c->_options['background_color'];?>"/><br />
                               <input type="hidden" name="background_transparent" value="no" />
                               <input type="checkbox" <?php if ( 'yes' == $c->_options['background_transparent'] ) {echo "checked=\"checked\"";} ?> name="background_transparent" value="yes" id="background_transparent"> <label for="background_transparent"><?php _e('Transparent','share-and-follow'); ?></label></li>
                    <li  style="padding:1px 0 5px 0 ;margin:0 0 0 50%">
                        <h3><?php _e('Border Color','share-and-follow'); ?></h3>

                               #<input type="text" name="border_color" id="border_color" value="<?php echo $c->_options['border_color'];?>"/> <br />
                                <input type="hidden" name="border_transparent" value="no" />
                               <input type="checkbox" <?php if ( 'yes' == $c->_options['border_transparent'] ) {echo "checked=\"checked\"";} ?> name="border_transparent" value="yes" id="border_transparent"><label for="border_transparent"><?php _e('No Border','share-and-follow'); ?></label></li>
                </ul>
                <p style="padding:0;margin:0;font-size:small;clear:left;"><?php _e('for example <b>#f60</b> is entered as <b>f60</b> or <b>#ff6600</b> becomes <b>ff6600</b>, clicking <b>Transparent</b> will make the tab have no color and <b>no border</b> will set the border to disapear','share-and-follow'); ?>.</p>
                 <ul style="padding:0;margin:0">
                        <li style="float:left;width:50%;padding:0;margin:0">
                            <h3><label for="follow_location"><?php _e('Follow Tab Location','share-and-follow'); ?> </label></h3>
                            <select  name="follow_location" id="follow_location" style="width:12em">
                            <option value="right" <?php if ("right" == $c->_options['follow_location'] ) {echo 'selected="selected"';} ?>  ><?php _e('Right','share-and-follow'); ?></option>
                            <option value="left" <?php if ("left" == $c->_options['follow_location']) {echo 'selected="selected"';} ?>><?php _e('Left','share-and-follow'); ?></option>
                            <option value="bottom" <?php if ("bottom" == $c->_options['follow_location']) {echo 'selected="selected"';} ?>><?php _e('Bottom','share-and-follow'); ?></option>
                            <option value="top" <?php if ("top" == $c->_options['follow_location']) {echo 'selected="selected"';} ?>><?php _e('Top','share-and-follow'); ?></option>
                            </select>
                        </li>
                        <li  style="padding:1px 0 5px 0 ;margin:0 0 0 50%">
                            <h3><?php _e('Prefix with a heading','share-and-follow'); ?></h3>
                            <p><label for="add_follow_text_yes"><input type="radio" id="add_follow_text_yes" name="add_follow_text" value="true" <?php if ($c->_options['add_follow_text'] == "true") {echo "checked=\"checked\"";} ?> /><?php _e('Yes','share-and-follow'); ?></label>&nbsp;&nbsp;&nbsp;&nbsp;
                                <label for="add_follow_text_no"><input type="radio" id="add_follow_text_no" name="add_follow_text" value="false" <?php if ($c->_options['add_follow_text'] == "false") {echo "checked=\"checked\"";} ?>/><?php _e('No','share-and-follow'); ?></label></p>
                        </li>

                        <li style="padding:0;margin:0;width:48%;float:left;">
                            <h3><?php _e('Use CSS single images or Image Sprites?','share-and-follow'); ?></h3>
                            <p><label for="css_follow_images_yes"><input type="radio" id="css_follow_images_yes" name="css_follow_images" value="yes" <?php if ($c->_options['css_follow_images'] == "yes") {echo "checked=\"checked\"";} ?> /> <?php _e('CSS Single image','share-and-follow'); ?></label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br />
                            <label for="css_follow_images_no"><input type="radio" id="css_follow_images_no" name="css_follow_images" value="no" <?php if ($c->_options['css_follow_images'] == "no") {echo "checked=\"checked\"";} ?>/> <?php _e('CSS Sprites(default)','share-and-follow'); ?></label></p>
                            <p><?php _e('CSS sprites are massivly faster when dealing with many icons, but are very wasteful if you are just using 1 to 4 icons. Use same size as Share icons or Single Images for faster respose times with less icons. ','share-and-follow'); ?></small></p>
                         </li>
                         <li  style="padding:1px 0 5px 0 ;margin:0 0 0 50%">
                           <h3><label for='width_of_page_minimum'><?php _e('Minimum width of screen','share-and-follow'); ?> </label></h3>
                            <input type="text" name='width_of_page_minimum' id='width_of_page_minimum' value="<?php echo $c->_options['width_of_page_minimum']; ?>" />px
                            <p><?php _e('If you set this, it will use a bit of javascript to remove the Right or Left tab from the screen when the screen size is the same or smaller that the entered number.','share-and-follow'); ?></p>
                         </li>
                 </ul>
                    <ul style="padding:0;margin:0">
                        <li style="float:left;width:50%;padding:0;margin:0;clear:left;">
                            <h3><label for="follow_color"><?php _e('Follow Word Color','share-and-follow'); ?></label></h3>
                                <select  name="follow_color" id="follow_color" style="width:12em">
                                    
                                    <option value="f00" <?php if ("f00" == $c->_options['follow_color']){echo 'selected="selected"';} ?>><?php _e('Red','share-and-follow'); ?></option>
                                    <option value="f0f" <?php if ("f0f" == $c->_options['follow_color']){echo 'selected="selected"';} ?>><?php _e('Pink','share-and-follow'); ?></option>
                                    <option value="00f" <?php if ("00f" == $c->_options['follow_color']){echo 'selected="selected"';} ?>><?php _e('Blue','share-and-follow'); ?></option>
                                    <option value="fff" <?php if ("fff" == $c->_options['follow_color']){echo 'selected="selected"';} ?>><?php _e('White','share-and-follow'); ?></option>
                                    <option value="ccc" <?php if ("ccc" == $c->_options['follow_color']){echo 'selected="selected"';} ?>>#ccc</option>
                                    <option value="999" <?php if ("999" == $c->_options['follow_color']){echo 'selected="selected"';} ?>>#999</option>
                                    <option value="666" <?php if ("666" == $c->_options['follow_color']){echo 'selected="selected"';} ?>>#666</option>
                                    <option value="333" <?php if ("333" == $c->_options['follow_color']){echo 'selected="selected"';} ?>>#333</option>
                                    <option value="000" <?php if ("000" == $c->_options['follow_color']){echo 'selected="selected"';} ?>><?php _e('Black','share-and-follow'); ?></option>
                                </select>
                        </li>
                        <li style="padding:1px 0 5px 0 ;margin:15px 0 0 50%;">
                            <h3><?php _e('Follow Heading text replacement','share-and-follow'); ?></h3>
                            <p><label for="word_value"><?php _e('On screen text','share-and-follow'); ?></label> <input type="text" name="word_text" id="word_text" value="<?php echo $c->_options['word_text'];?>" style="width:150px"/></p>
                            <p><?php _e('Replacement Word','share-and-follow'); ?> <select  name="word_value" id="word_value" style="width:12em">
                                    <optgroup label="<?php _e('English words','share-and-follow'); ?>">
                                        <?php $eng = array ('follow'=>'follow','followme'=>'follow me','followus'=>'follow us','connect'=>'connect',
                                                            'communicate'=>'communicate','join'=>'join','network'=>'network','review'=>'review',) ?>
                                        <?php foreach ($eng as $key => $value) {?>
                                            <option value="<?php echo $key; ?>"  <?php if ($key == $c->_options['word_value'] ) {echo 'selected="selected"';} ?>><?php echo $value; ?></option>
                                        <?php } ?>
                                    </optgroup>
                                    <optgroup label="<?php _e('Dutch words','share-and-follow'); ?>">
                                        <?php $nld = array ('aansluiten'=>'aansluiten','deelnemen'=>'deelnemen','mededeling'=>'mededeling','overzichten'=>'overzichten','toevoegen'=>'toevoegen',
                                                            'verbinden'=>'verbinden','volgen'=>'volgen','volgmij'=>'volg mij','volgons'=>'volg ons','volgonze'=>'volg onze',); ?>
                                        <?php foreach ($nld as $key => $value) {?>
                                            <option value="<?php echo $key; ?>"  <?php if ($key == $c->_options['word_value'] ) {echo 'selected="selected"';} ?>><?php echo $value; ?></option>
                                        <?php } ?>
                                    </optgroup>
                                    <optgroup label="<?php _e('French words','share-and-follow'); ?>">
                                        <?php $fr = array ('ajouter'=>'ajouter','seconnecter'=>'se connecter','publications'=>'publications','rejoindre'=>'rejoindre','reseau'=>'r&eacute;seau','suivre'=>'suivre',); ?>
                                        <?php foreach ($fr as $key => $value) {?>
                                            <option value="<?php echo $key; ?>"  <?php if ($key == $c->_options['word_value'] ) {echo 'selected="selected"';} ?>><?php echo $value; ?></option>
                                        <?php } ?>
                                    </optgroup>
                                    <optgroup label="<?php _e('Portuguese words','share-and-follow'); ?>">
                                        <?php $pt = array ('conectar'=>'conectar','comunicar'=>'comunicar','juntar'=>'juntar','rede'=>'rede','resenhas'=>'resenhas','seguir'=>'seguir',
                                                            'sigame'=>'siga-me','siganos'=>'siga-nos',); ?>
                                        <?php foreach ($pt as $key => $value) {?>
                                            <option value="<?php echo $key; ?>"  <?php if ($key == $c->_options['word_value'] ) {echo 'selected="selected"';} ?>><?php echo $value; ?></option>
                                        <?php } ?>
                                    </optgroup>
                                    <optgroup label="<?php _e('Spanish words','share-and-follow'); ?>">
                                        <?php $es = array ('seguir'=>'seguir'); ?>
                                        <?php foreach ($es as $key => $value) {?>
                                            <option value="<?php echo $key; ?>"  <?php if ($key == $c->_options['word_value'] ) {echo 'selected="selected"';} ?>><?php echo $value; ?></option>
                                        <?php } ?>
                                    </optgroup>
                                </select>
                                </p>
                        </li>
                    </ul>
                    <p><?php _e('The text replacement only applies to the side tabs.  It has been made that way to give virtical text, but does limit the word choices.  Use a top or bottom tab to show the text that you want, not the replacement text.','share-and-follow')?></p>
                    <ul style="padding:0;margin:0">
                        <li style="float:left;width:50%;padding:0;margin:0">
                           <h3><label for="follow_list_style"><?php _e('Follow icons display style','share-and-follow'); ?></label></h3>
                            <select id="follow_list_style" name="follow_list_style" style="width:12em">
                                    <?php if ( $c->_options['follow_location'] == 'bottom'||$c->_options['follow_location'] == 'top'){?><option <?php if ( 'icon_text' == $c->_options['follow_list_style'] ) echo 'selected="selected"'; ?> value="icon_text"><?php _e('Icon and Text','share-and-follow'); ?></option><?php } ?>
                                    <?php if ( $c->_options['follow_location'] == 'bottom'||$c->_options['follow_location'] == 'top'){?><option <?php if ( 'text_only' == $c->_options['follow_list_style'] ) echo 'selected="selected"'; ?> value="text_only"><?php _e('Text only','share-and-follow'); ?> </option><?php } ?>
                                    <option <?php if ( 'text_replace' == $c->_options['follow_list_style'] ) echo 'selected="selected"'; ?> value="text_replace"><?php _e('Text replacement','share-and-follow'); ?></option>
                                    <option <?php if ( 'iconOnly' == $c->_options['follow_list_style'] ) echo 'selected="selected"'; ?> value="iconOnly"><?php _e('Icon only','share-and-follow'); ?> </option>
                            </select>
                       </li>
                        <li style="padding:1px 0 5px 0 ;margin:0 0 0 50%">
                            <h3><label for="tab_size"><?php _e('Size of Follow Icons on tab','share-and-follow'); ?></label></h3>
                            <select  name="tab_size" id="tab_size" style="width:12em">
                                <?php $args = array ('16','24','32','48','60') ?>
                                <?php foreach ($args as $sizeToShow) {?>
                                    <option value="<?php echo $sizeToShow; ?>" <?php if ($sizeToShow == $c->_options['tab_size']) {echo 'selected="selected"';} ?>><?php echo $sizeToShow; ?>x<?php echo $sizeToShow; ?></option>
                                <?php } ?>
                            </select>
                        </li>
                    </ul>


                    <ul style="padding:0;margin:0">
                        <li style="float:left;width:50%;padding:0;margin:0">
                           <h3><label for="follow_list_spacing"><?php _e('Follow tab icons spacing','share-and-follow'); ?></label></h3>
                            <select id="follow_list_spacing" name="follow_list_spacing" style="width:12em">
                                <?php   for ( $counter = 0 ; $counter <= 15; $counter += 1) { ?>
                                    <option value="<?php echo $counter; ?>" <?php if ($counter == $c->_options['follow_list_spacing']) {echo 'selected="selected"';} ?>><?php echo $counter; ?>px</option>
                                <?php } ?>
                            </select>
                       </li>
                        <li style="padding:1px 0 5px 0 ;margin:0 0 0 50%">
                            <h3><label for="distance_from_top"><?php _e('Side tab distance from top of screen','share-and-follow'); ?></label></h3>
                            <select  name="distance_from_top" id="distance_from_top" style="width:12em">
                                <?php for ( $counter = 0; $counter <= 200; $counter += 10) { ?>
                                    <option value="<?php echo $counter; ?>" <?php if ($counter == $c->_options['distance_from_top']) {echo 'selected="selected"';} ?>><?php echo $counter?>px</option>
                                <?php } ?>
                            </select>
                        </li>
                    </ul>



                       <h3 id="enterlinks"><?php _e('Follow Links  to display in the follow tab (also needed for follow widget)','share-and-follow'); ?></h3>
                       <table style="width:100%">
                           <thead><tr><th style="width:12em"><?php _e('Show','share-and-follow'); ?></th><th><?php _e('Link Text','share-and-follow'); ?></th><th ><?php _e('Link destination','share-and-follow'); ?></th></tr></thead>
                           <tbody>
                            <?php // setup sites to show automatically ?>

                            <?php
                            

                            $args = array();
                            foreach ($c->_allSites as $item => $value){
                                if(strstr($value['service'],"follow")){
                                    if ($item!='rss'){
                                    $args[]=$item;
                                    }
                                }
                            }

                            ?>
                            <?php // loop through showing all items after sorting it to be alphabetic ?>
                            <?php sort($args); ?>
                            <?php foreach ($args as $siteToShow) {?>
                               <tr>
                                   <td><img src="<?php echo WP_PLUGIN_URL; ?>/share-and-follow/images/blank.gif" height="16px" width="16px" alt="<?php echo $siteToShow; ?>" style="background:transparent url(<?php echo WP_PLUGIN_URL; ?>/share-and-follow/default/16/sprite-feb-16.png) no-repeat <?php echo str_replace(" ", "px ",$c->_allSites[$siteToShow]['sprites']['16']) ?>px" />
                                    <input type="hidden" name="follow_<?php echo $siteToShow; ?>" value="no" id="follow_<?php echo $siteToShow; ?>">
                                    <input type="checkbox" <?php if ( 'yes' == $c->_options['follow_'.$siteToShow] ) {echo "checked=\"checked\"";} ?> name="follow_<?php echo $siteToShow; ?>" value="yes" id="follow_<?php echo $siteToShow; ?>"><label for="follow_<?php echo $siteToShow; ?>"><?php echo str_replace("_", " ",$siteToShow) ; ?></label>
                                   </td>
                                   <td><input type="text" name="<?php echo $siteToShow; ?>_link_text" id="<?php echo $siteToShow; ?>_link_text" value="<?php echo stripslashes  ($c->_options[$siteToShow.'_link_text']);?>" style="width:95%"/></td>
                                   <td><input type="text" name="<?php echo $siteToShow; ?>_link" id="<?php echo $siteToShow; ?>_link" value="<?php echo $c->_options[$siteToShow.'_link'];?>" style="width:100%"/><td>
                               </tr>
                            <?php } ?>
                                <tr>
                                   <td><img src="<?php echo WP_PLUGIN_URL; ?>/share-and-follow/default/16/rss.png" height="16px" width="16px" alt="rss" />
                                   <input type="hidden" value="no" name="follow_rss" />
                                   <input type="checkbox" <?php if ( 'yes' == $c->_options['follow_rss'] ) {echo "checked=\"checked\"";} ?> name="follow_rss" value="yes" id="follow_rss"><label for="follow_rss">rss</label></td>
                                   <td><input type="text" name="rss_link_text" id="rss_link_text" value="<?php echo stripslashes  ($c->_options['rss_link_text']);?>" style="width:95%"/></td>
                                   <td>
                                      <label for="rss_style_rss"><input type="radio" id="rss_style_rss" name="rss_style" value="rss_url" <?php if ($c->_options['rss_style'] == "rss_url") {_e('checked="checked"', "shareAndcss");} ?> /><?php _e('rss', 'share-and-follow') ?></label>&nbsp;&nbsp;
                                      <label for="rss_style_rss2"><input type="radio" id="rss_style_rss2" name="rss_style" value="rss2_url" <?php if ($c->_options['rss_style'] == "rss2_url") {_e('checked="checked"', "shareAndcss");} ?>/><?php _e('rss2', 'share-and-follow') ?></label>&nbsp;&nbsp;
                                      <label for="rss_style_atom"><input type="radio" id="rss_style_atom" name="rss_style" value="atom_url" <?php if ($c->_options['rss_style'] == "atom_url") {_e('checked="checked"', "shareAndcss");} ?>/><?php _e('atom', 'share-and-follow') ?></label>
                                   </td>
                               </tr>
                           </tbody>
                       </table>
                       <p><b><em><?php _e('Important', 'share-and-follow') ?>:</em></b> <?php _e('Always put in the full URL with http:// at the beginning when entering a Link Destination.', 'share-and-follow') ?> </p>

                    <input type="submit" name="update_share-and-follow" value="<?php _e('Update Settings', 'share-and-follow') ?>" />
            </form>
                </div>
            </div>

 <?php


}else{
    wp_die("hacking?");
}