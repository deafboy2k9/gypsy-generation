<?php if(is_admin() && is_user_logged_in() && current_user_can('install_plugins')  ) {
    $c = new ShareAndFollow();
    $c->cache_warning_check();
    if (isset($_POST['update_share-and-follow']) && isset($_POST['inonce']) ) {
        if(wp_verify_nonce($_POST['inonce'],'checkinteractive')){
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
                <?php wp_nonce_field('checkinteractive','inonce'); ?>
                <input type="hidden" name="cssid" id="cssid" value="<?php echo ($c->_options['cssid']+1);?>" />
    
                <h1><?php _e('Interactive Share Buttons','share-and-follow'); ?></h1>
                <h3><?php _e('Facebook Like button','share-and-follow'); ?></h3>
                <table class="like">
                    <tr>
                        <th><label for="like_locations"><?php _e('Choose the locations where you want to show the like button', 'share-and-follow') ?></label></th>
                        <td><?php $args =array('like_topleft','like_topright','like_bottom'); ?>
                            <?php foreach ($args as $locations) { ?>
                            <input type="hidden" name="<?php echo $locations; ?>" value="no" id="<?php echo $locations; ?>">
                            <input type="checkbox" <?php if ( 'yes' == $c->_options[$locations] ) {echo "checked=\"checked\"";} ?> name="<?php echo $locations; ?>" value="yes" id="<?php echo $locations; ?>"><label for="<?php echo $locations; ?>"><?php echo str_replace("like_", "",$c->getRightTitle($locations)) ; ?></label><br />
                            <?php } ?>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="like_style"><?php _e('Like button style', 'share-and-follow') ?></label></th>
                        <td>
                            <select  name="like_style" id="like_style" style="width:200px">
                            <?php $args =array('standard','box_count','button_count'); ?>
                            <?php foreach ($args as $buttonStyles) { ?>
                                    <option value="<?php echo $buttonStyles; ?>" <?php if ($buttonStyles == $c->_options['like_style']){echo 'selected="selected"';} ?>><?php echo $buttonStyles; ?></option>
                            <?php } ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <label for="like_width"><?php _e('Like button width', 'share-and-follow') ?></label>
                        </th>
                        <td>
                            <input type="text" name="like_width" id="like_width" value="<?php echo stripslashes  ($c->_options['like_width']);?>" style="width:200px"/>
                        </td>
                    </tr>
                     <tr>
                        <th>
                            <label for="like_faces"><?php _e('Show faces of people who like the page or post', 'share-and-follow') ?></label>
                        </th>
                        <td>
                           <input type="hidden" name="like_faces" value="false" id="like_faces">
                           <input type="checkbox" <?php if ( 'true' == $c->_options['like_faces'] ) {echo "checked=\"checked\"";} ?> name="like_faces" value="true" id="<?php echo $locations; ?>">
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <label for="like_verb"><?php _e('Like or Recommend?', 'share-and-follow') ?></label>
                        </th>
                        <td>
                           <select  name="like_verb" id="like_verb" style="width:200px">
                            <?php $args =array('like','recommend',); ?>
                            <?php foreach ($args as $verb) { ?>
                                    <option value="<?php echo $verb; ?>" <?php if ($verb == $c->_options['like_verb']){echo 'selected="selected"';} ?>><?php echo $verb; ?></option>
                            <?php } ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <label for="like_color"><?php _e('Like or Recommend?', 'share-and-follow') ?></label>
                        </th>
                        <td>
                           <select  name="like_color" id="like_color" style="width:200px">
                            <?php $args =array('light', 'dark',); ?>
                            <?php foreach ($args as $color) { ?>
                                    <option value="<?php echo $color; ?>" <?php if ($color == $c->_options['like_color']){echo 'selected="selected"';} ?>><?php echo $color; ?></option>
                            <?php } ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <label for="like_font"><?php _e('Button font', 'share-and-follow') ?></label>
                        </th>
                        <td>
                           <select  name="like_font" id="like_font" style="width:200px">
                            <?php $args =array('arial', 'lucida grande', 'segoe ui', 'tahoma', 'trebuchet ms', 'verdana'); ?>
                            <?php foreach ($args as $font) { ?>
                                    <option value="<?php echo $font; ?>" <?php if ($font == $c->_options['like_font']){echo 'selected="selected"';} ?>><?php echo $font; ?></option>
                            <?php } ?>
                            </select>
                        </td>
                    </tr>

                </table>
                  <h3><?php _e('Twitter Retweet button','share-and-follow'); ?></h3>
                <table class="like">
                    <tr>
                        <th><label for="tweet_locations"><?php _e('Choose the locations where you want to show the like button', 'share-and-follow') ?></label></th>
                        <td><?php $args =array('tweet_topleft','tweet_topright','tweet_bottom'); ?>
                            <?php foreach ($args as $locations) { ?>
                            <input type="hidden" name="<?php echo $locations; ?>" value="no" id="<?php echo $locations; ?>">
                            <input type="checkbox" <?php if ( 'yes' == $c->_options[$locations] ) {echo "checked=\"checked\"";} ?> name="<?php echo $locations; ?>" value="yes" id="<?php echo $locations; ?>"><label for="<?php echo $locations; ?>"><?php echo str_replace("tweet_", "",$c->getRightTitle($locations)) ; ?></label><br />
                            <?php } ?>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="tweet_width"><?php _e('Width of the tweet button', 'share-and-follow') ?></label></th>
                        <td> <input type="text" name="tweet_width" id="tweet_width" value="<?php echo stripslashes ($c->_options['tweet_width']);?>" style="width:200px"/>
                        </td>
                    </tr>
                     <tr>
                        <th>
                            <label for="tweet_style"><?php _e('Button style', 'share-and-follow') ?></label>
                        </th>
                        <td>
                           <select  name="tweet_style" id="tweet_style" style="width:200px">
                            <?php $args =array('vertical', 'horizontal', 'none'); ?>
                            <?php foreach ($args as $style) { ?>
                                    <option value="<?php echo $style; ?>" <?php if ($style == $c->_options['tweet_style']){echo 'selected="selected"';} ?>><?php echo $style; ?></option>
                            <?php } ?>
                            </select>
                        </td>
                    </tr>
                     <tr>
                        <th><label for="tweet_via"><?php _e('Via setting, enter your twitter name (optional)', 'share-and-follow') ?></label></th>
                        <td> <input type="text" name="tweet_via" id="tweet_via" value="<?php echo stripslashes ($c->_options['tweet_via']);?>" style="width:200px"/><br />
                        </td>
                    </tr>
                </table>


                  <h3><?php _e('Stumble Upon button','share-and-follow'); ?></h3>
                <table class="like">
                    <tr>
                        <th><label for="stumble_locations"><?php _e('Choose the locations where you want to show the like button', 'share-and-follow') ?></label></th>
                        <td><?php $args =array('stumble_topleft','stumble_topright','stumble_bottom'); ?>
                            <?php foreach ($args as $locations) { ?>
                            <input type="hidden" name="<?php echo $locations; ?>" value="no" id="<?php echo $locations; ?>">
                            <input type="checkbox" <?php if ( 'yes' == $c->_options[$locations] ) {echo "checked=\"checked\"";} ?> name="<?php echo $locations; ?>" value="yes" id="<?php echo $locations; ?>"><label for="<?php echo $locations; ?>"><?php echo str_replace("stumble_", "",$c->getRightTitle($locations)) ; ?></label><br />
                            <?php } ?>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <label for="stumble_style"><?php _e('Button style', 'share-and-follow') ?></label>
                        </th>
                        <td>
                           <select  name="stumble_style" id="stumble_style" style="width:200px">
                            <?php $args =array('vertical count' =>'5', 'horizontal count rounded'=>'2', 'horizontal count square'=>'1', 'horizontal count borderless' =>'3'); ?>
                            <?php foreach ($args as $style => $value) { ?>
                                    <option value="<?php echo $value; ?>" <?php if ($value == $c->_options['stumble_style']){echo 'selected="selected"';} ?>><?php echo $style; ?></option>
                            <?php } ?>
                            </select>
                        </td>
                    </tr>
                </table>

                  <h3>Google plus button</h3>

                  <table class="like">
                    <tr>
                        <th><label for="googleplus_locations"><?php _e('Choose the locations where you want to show the google plus', 'share-and-follow') ?></label></th>
                        <td><?php $args =array('googleplus_topleft','googleplus_topright','googleplus_bottom'); ?>
                            <?php foreach ($args as $locations) { ?>
                            <input type="hidden" name="<?php echo $locations; ?>" value="no" id="<?php echo $locations; ?>">
                            <input type="checkbox" <?php if ( 'yes' == $c->_options[$locations] ) {echo "checked=\"checked\"";} ?> name="<?php echo $locations; ?>" value="yes" id="<?php echo $locations; ?>"><label for="<?php echo $locations; ?>"><?php echo str_replace("googleplus_", "",$locations) ; ?></label><br />
                            <?php } ?>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <label for="googleplus_size"><?php _e('Button size', 'share-and-follow') ?></label>
                        </th>
                        <td>
                           <select  name="googleplus_size" id="googleplus_size" style="width:200px">
                            <?php $args =array('small (15px)' =>'small', 'Standard (24px)'=>'', 'Medium (20px)'=>'medium', 'Tall (60px)' =>'tall'); ?>
                            <?php foreach ($args as $style => $value) { ?>
                                    <option value="<?php echo $value; ?>" <?php if ($value == $c->_options['googleplus_size']){echo 'selected="selected"';} ?>><?php echo $style; ?></option>
                            <?php } ?>
                            </select>
                        </td>
                    </tr>
                                        <tr>
                        <th>
                            <label for="googleplus_style"><?php _e('Button style', 'share-and-follow') ?></label>
                        </th>
                        <td>
                           <select  name="googleplus_style" id="googleplus_style" style="width:200px">
                            <?php $args =array('none' =>'', 'inline'=>'inline', 'bubble'=>'bubble'); ?>
                            <?php foreach ($args as $style => $value) { ?>
                                    <option value="<?php echo $value; ?>" <?php if ($value == $c->_options['googleplus_style']){echo 'selected="selected"';} ?>><?php echo $style; ?></option>
                            <?php } ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                    <th><label for="googleplus_lang">language</label></th>
                    <td>
                        <select id="googleplus_lang"  name="googleplus_lang" style="width:200px">
             <option value="ar">Arabic - العربية</option>
             <option value="bg">Bulgarian - български</option>
             <option value="ca">Catalan - català</option>
             <option value="zh-CN">Chinese (Simplified) - 中文 &rlm;（簡体）</option>
             <option value="zh-TW">Chinese (Traditional) - 中文 &rlm;（繁體）</option>
             <option value="hr">Croatian - hrvatski</option>
             <option value="cs">Czech - čeština</option>
             <option value="da">Danish - dansk</option>
             <option value="nl">Dutch - Nederlands</option>
             <option selected="selected" value="en-US">English (US) - English &rlm;(US)</option>
             <option value="en-GB">English (UK) - English &rlm;(UK)</option>
             <option value="et">Estonian - eesti</option>
             <option value="fil">Filipino - Filipino</option>
             <option value="fi">Finnish - suomi</option>
             <option value="fr">French - français</option>
             <option value="de">German - Deutsch</option>
             <option value="el">Greek - Ελληνικά</option>
             <option value="iw">Hebrew - עברית</option>
             <option value="hi">Hindi - हिन्दी</option>
             <option value="hu">Hungarian - magyar</option>
             <option value="id">Indonesian - Bahasa Indonesia</option>
             <option value="it">Italian - italiano</option>
             <option value="ja">Japanese - 日本語</option>
             <option value="ko">Korean - 한국어</option>
             <option value="lv">Latvian - latviešu</option>
             <option value="lt">Lithuanian - lietuvių</option>
             <option value="ms">Malay - Bahasa Melayu</option>
             <option value="no">Norwegian - norsk</option>
             <option value="fa">Persian - فارسی</option>
             <option value="pl">Polish - polski</option>
             <option value="pt-BR">Portuguese (Brazil) - português &rlm;(Brasil)</option>
             <option value="pt-PT">Portuguese (Portugal) - Português &rlm;(Portugal)</option>
             <option value="ro">Romanian - română</option>
             <option value="ru">Russian - русский</option>
             <option value="sr">Serbian - српски</option>
             <option value="sv">Swedish - svenska</option>
             <option value="sk">Slovak - slovenský</option>
             <option value="sl">Slovenian - slovenščina</option>
             <option value="es">Spanish - español</option>
               <option value="es-419">Spanish (Latin America) - español &rlm;(Latinoamérica y el Caribe)</option>
             <option value="th">Thai - ไทย</option>
             <option value="tr">Turkish - Türkçe</option>
             <option value="uk">Ukrainian - українська</option>
             <option value="vi">Vietnamese - Tiếng Việt</option>
          </select>
                    </td>


                    </tr>
                </table>


                   <h3>Pinterest button</h3>

                  <table class="like">
                    <tr>
                        <th><label for="pinterest_locations"><?php _e('Choose the locations where you want to show the pinterest button', 'share-and-follow') ?></label></th>
                        <td><?php $args =array('pinterest_topleft','pinterest_topright','pinterest_bottom'); ?>
                            <?php foreach ($args as $locations) { ?>
                            <input type="hidden" name="<?php echo $locations; ?>" value="no" id="<?php echo $locations; ?>">
                            <input type="checkbox" <?php if ( 'yes' == $c->_options[$locations] ) {echo "checked=\"checked\"";} ?> name="<?php echo $locations; ?>" value="yes" id="<?php echo $locations; ?>"><label for="<?php echo $locations; ?>"><?php echo str_replace("pinterest_", "",$locations) ; ?></label><br />
                            <?php } ?>
                        </td>
                    </tr>
                                        <tr>
                        <th>
                            <label for="pinterest_style"><?php _e('Button style', 'share-and-follow') ?></label>
                        </th>
                        <td>
                           <select  name="pinterest_style" id="pinterest_style" style="width:200px">
                            <?php $args =array('none' =>'no count', 'vertical'=>'vertical count', 'horizontal'=>'horizontal count'); ?>
                            <?php foreach ($args as $value => $style) { ?>
                                    <option value="<?php echo $value; ?>" <?php if ($value == $c->_options['pinterest_style']){echo 'selected="selected"';} ?>><?php echo $style; ?></option>
                            <?php } ?>
                            </select>
                        </td>
                    </tr>

                      <tr>
                        <th><label for="pinterest_images"><?php _e('Show the pinterest icons on images for per image sharing', 'share-and-follow') ?></label><br /><small>Works automatically for all images added to the wordpress media library, or those given the class of .pin-it</small></th>
                        <td> <select  name="pinterest_images" id="pinterest_images" style="width:200px">
                            <?php $args =array('yes','no'); ?>
                            <?php foreach ($args as $value) { ?>
                                    <option value="<?php echo $value; ?>" <?php if ($value == $c->_options['pinterest_images']){echo 'selected="selected"';} ?>><?php echo $value; ?></option>
                            <?php } ?>
                            </select>
                        </td>
                    </tr>

                                          <tr>
                        <th><label for="pins_to_add"><?php _e('Show on all images or those with the class of .pin-it only', 'share-and-follow') ?></label></th>
                        <td> <select  name="pins_to_add" id="pins_to_add" style="width:200px">
                            <?php $args =array('all','class'); ?>
                            <?php foreach ($args as $value) { ?>
                                    <option value="<?php echo $value; ?>" <?php if ($value == $c->_options['pins_to_add']){echo 'selected="selected"';} ?>><?php echo $value; ?></option>
                            <?php } ?>
                            </select>
                        </td>
                    </tr>
                  </table>
           


            <input type="submit" name="update_share-and-follow" value="<?php _e('Update Settings', 'share-and-follow') ?>" />
                </form>
            </div>
            </div>


                    <?php
}else{wp_die("hacking?");}