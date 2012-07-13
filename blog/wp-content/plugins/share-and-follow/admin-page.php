<?php

$c = new ShareAndFollow();
$c->cache_warning_check();


if (is_user_logged_in() && is_admin() ){

     if (isset($_POST['update_share-and-follow']) && isset($_POST['cnonce']) ) {
        if(! wp_verify_nonce($_POST['cnonce'],'checkcdn')){
                        wp_die('hacking??');}

     foreach($c->_defaults as $key => $value){
                // check if key is set
                if(isset($_POST[$key])){
                    // update the options variable
                    $c->_options[$key] = $_POST[$key];
                }
            }
    update_option($c->_adminOptionsName, $c->_options);
    delete_transient($c->_adminOptionsName);


    if (function_exists('json_decode')){
                        $key = '';
                        
                        if ( !empty($_POST['cdn-key']) && $_POST['cdn-key'] != $c->_options['cdn-key'] ){
                            delete_transient('cdnsets');
                            $c->_options['cdn-key'] = $_POST['cdn-key'];
                        }

                        if ($c->_options['cdn-key']==''){delete_transient('cdnsets');}
                        else if (strlen($c->_options['cdn-key']) <> 40){
                            echo "<div class='error'><p>It looks like you have put in an incorrect CDN API key. Check if you have put extra spaces in it</p></div>";
                            delete_transient('cdnsets');
                        }
                        else {
                            $result = get_transient('cdnsets');
                            if($this->_options['cdn']['status_txt'] == 'FAIL' || $result === false ){
                                require_once('RemoteConnector.php');
                                $url = "http://api.share-and-follow.com/v1/getSets2.php?url=".trailingslashit(get_bloginfo('url'))."&challange=".md5(trailingslashit(get_bloginfo('url')).$this->_options['cdn-key']);
                                $control = new Pos_RemoteConnector($url);
                                $result = $control->__toString();
                                if ($result != false){
                                    set_transient('cdnsets',$result,60*60*24);
                                }
                            }


                        $replies = json_decode($result, true);
                    
                        if ($replies['status_txt']=='FAIL'){
                            $c->_options['cdn']['status_txt']='FAIL';
                            $c->update_plugin_options();                            
                            echo "<div class='error'>The following error has happened : ".$replies['data']."</div>";
                        }
                        else {
                        $c->_options['cdn'] = json_decode($result, true); // jason format
                        $c->_options['cdn']['status_txt']='OK';
                        $c->update_plugin_options();  
                        }
                    }
                }

?>
<div class="updated"><p><strong><?php _e("Settings Updated.", "share-and-follow");?></strong></p></div>
<?php } ?>
                
<div class="wrap" >
        <?php $c->showUsSupport(); ?>
        <div style="margin-right:440px;min-height:200px">
        <h2><?php _e('Share and Follow Administration','share-and-follow'); ?></h2>
        <p><?php _e('Here you can setup the CDN for additional icon sets and speed increase to your site.','share-and-follow'); ?></p>
        <p><?php _e('More','share-and-follow'); ?> <a href="http://share-and-follow.com/wordpress-plugin/" target="_blank"><?php _e('documentation','share-and-follow'); ?></a> <?php _e(' on how to use this plugin and it&#39;s options &#40;Share Widget, Follow Widget, Share on Posts, Shortcode in Post, Follow Tab, Theme Tags&#41;','share-and-follow'); ?></p>
        </div>
<?php global $current_user;
get_currentuserinfo();
if ( $c->_options['cdn']['status_txt']=='FAIL'|| $c->_options['cdn-key'] == '' || (strlen($c->_options['cdn-key'])<>40 ) ){
?>
        <script>
	jQuery(document).ready(function(){
		jQuery( "#tabs" ).tabs();
	});
	</script>
        <h1><?php _e('Helpful Videos','share-and-follow'); ?></h1>
        <div id="tabs">
	<ul>
		<li><a href="#tabs-1"><?php _e('Adding the CDN and Extra Icon Sets','share-and-follow'); ?></a></li>
		<li><a href="#tabs-2"><?php _e('Overview video of Share and Follow','share-and-follow'); ?></a></li>
	</ul>
	<div id="tabs-1">
            <div style="float:left;margin-right:20px">
<?php $c->show_video("http://player.vimeo.com/video/16185599", "325", "580") ?>

            </div>
<h2><?php _e('Choose your yearly subscription','share-and-follow'); ?></h2>
<?php $c->show_paypal_subscribe(); ?>
<p>Subscriptions are optional.  Subscribing will make your site faster, and give you more icon sets to choose from.</p>


            <div style="clear:both"></div>
	</div>
	<div id="tabs-2">
            <div style="float:left;margin-right:20px">
                <?php $c->show_video("http://player.vimeo.com/video/15507608", "325", "580") ?>		
                </div>
                <h2><?php _e('How to setup Share and Follow','share-and-follow'); ?></h2>
                <p><?php _e('An overview of many of the features of share and follow.','share-and-follow'); ?></p>
                        <div style="clear:both"></div> 
	</div>
</div>
<?php } ?>
        <form method="post" action="admin.php?page=share-and-follow-menu">
            <?php wp_nonce_field('checkcdn','cnonce'); ?>
            <?php  if (function_exists('json_decode')){ ?>
            <?php // echo $details; ?>
            
                
             <?php if ($c->_options['cdn']['status_txt']=='FAIL'|| $c->_options['cdn-key'] == '' || (strlen($c->_options['cdn-key'])<>40 ) ) { ?>
            <div class="cdn-setup" style="max-width:1250px;">
                <h1><?php _e('Setup the CDN API key','share-and-follow'); ?></h1>
                <div style="margin:0 20px 20px 20px">
                     <h3><?php _e('Add extra icon sets via optional CDN subscription','share-and-follow'); ?></h3>
                     <p><?php _e('A CDN is the fastest way to get these images to readers of your site. Subscribe to the CDN and get lots of  new icon sets. <a href="http://www.share-and-follow.com/cdn-subscription/">read more about our CDN</a>, or watch the video above.','share-and-follow'); ?></p>
                     <label for="cdn-key">CDN API Key</label><input type="text" name="cdn-key" id="cdn-key"  style="width:30em" value="<?php echo ($c->_options['cdn-key']);?>" /><input type="submit" name="update_share-and-follow" value="<?php _e('Update Settings', 'share-and-follow') ?>" />
                </div>
                <?php } else { ?>
             <div id="tabs-open">
                 <h1><?php _e('Helpful Video','share-and-follow'); ?></h1>
            <div style="float:left;margin-right:20px">
		<?php $c->show_video("http://player.vimeo.com/video/15507608", "325", "580") ?>	
                </div>
                <h2><?php _e('How to setup Share and Follow','share-and-follow'); ?></h2>
                <p><?php _e('An overview of many of the features of share and follow.','share-and-follow'); ?></p>
                        <div style="clear:both"></div> 
	</div>
                <div class="cdn-setup" style="max-width:1250px;">
                     <h1><?php _e('Choose the icon set','share-and-follow'); ?></h1>
                    <div class="cdn-rounded">
                        <label for="cdn-key"><?php _e('your CDN API Key','share-and-follow'); ?></label><input style="width:30em" type="text" name="cdn-key" id="cdn-key" value="<?php echo ($c->_options['cdn-key']);?>" />
                     
                     <div class="imageholder">
                       <ul class="cdn-listing">
                      <?php
                      if (!empty($c->_options['cdn']['data']['sets']['icons'])){
                      foreach ($c->_options['cdn']['data']['sets']['icons'] as $item ){?>
                     <li><img src="<?php  echo WP_PLUGIN_URL ?>/share-and-follow/images/blank.gif" height="128" width="128" alt="<?php echo $item['location']; ?> set" style="background-image:url(<?php  echo $c->_options['cdn']['data']['sets']['overview'] ?><?php echo $item['position']; ?>.png);background-repeat: no-repeat" />
                     <label for="<?php echo $item['location']; ?>_set"><input type="radio" id="<?php echo $item['location']; ?>_set" name="icon_set" value="<?php echo $item['location']; ?>" <?php if ($c->_options['icon_set'] == $item['location'] || $c->_options['icon_set'] == "" ) {echo'checked';} ?>/><br /><?php echo $item['name']; ?></label></li>
                     <?php }} 
                 ?>
             </ul>
              </div>
             <div style="clear:both;"></div>
             <input type="submit" name="update_share-and-follow" value="<?php _e('Update Settings', 'share-and-follow') ?>" />
             </div>
             <?php } ?>
                </div>
         <?php } ?>
            

        <div style="clear:both;"></div>
         
   
          
    </form>
        <div class="submit">
        </div>
</div>


    
<?php  } ?>