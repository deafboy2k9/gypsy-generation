<?php
 
if (is_user_logged_in() && is_admin() ){
                       
    function getRightImage($value){
        switch($value){
        case "yahoo_buzz":
            $result = "yahoobuzz";
            break;
        case "stumble":
            $result = "stumbleupon";
            break;
        case "post_rss":
            $result = "rss";
            break;
        default:
            $result = $value;
      }
        echo $result;
  }
    function getRightTitle($value){
        switch($value){
        case "post_rss":
            $result = "rss comment feed";
            break;
        default:
            $result = $value;
      }
        return $result;
  }
$devOptions = $this->getAdminOptions();
                        //  get post variables
                        // array = name
                        //
                   
            if (isset($_POST['update_share-and-follow'])) {//save option changes

     $adminSettings = array('background_color','border_color','follow_location','follow_color','spacing','size','add_content','add_follow',
                        'add_css', 'list_style', 'follow_list_style','excluded_share_pages','css_images','extra_print_css','extra_css','add_image_link',
                        'default_email','default_email_image','author_defaults','logo_image_url','homepage_img','homepage_image_url','archive_img','archive_image_url',
                        'page_image_url','post_image_url','page_img','post_img','background_transparent','border_transparent','tab_size','share','share_text','cssid',
                        'word_text', 'add_follow_text', 'word_value','email','email_share_text','email_body_text','email_popup_text','print_share_text','print','css_print_excludes',
                        'theme_support','print_popup_text','rss_link_text','follow_rss','distance_from_top', 'follow_list_spacing','wp_page','wp_post', 'wp_author',
                        'wp_home','wp_archive', 'bit_ly_code','bit_ly','twitter_text', 'twitter_text_default','twitter_text_suffix','width_of_page_minimum',
                        'wpsc_top_of_products_page', 'wpsc_product_before_description', 'wpsc_product_addon_after_descr','css_follow_images',
                        'excluded_follow_pages','follow_page','follow_post','follow_archive','follow_home','follow_author','cdn-key','icon_set'
                        );

         foreach ($adminSettings as $item){
            $devOptions[$item] = $_POST[$item];
        }


//
//  check for follow sites. 
//
        global $allSites;
        foreach ($allSites as $item => $value){
            if(strstr($value['service'], "follow")){
               $devOptions['follow_'.$item] = $_POST['follow_'.$item];
               $devOptions[$item.'_link'] = $_POST[$item.'_link'];
               $devOptions[$item.'_link_text'] = $_POST[$item.'_link_text'];

            }
        }

        foreach ($allSites as $item => $value){
            if($item=='email'|| $item == 'rss'){}
            else{
            if(strstr($value['service'],"share")){
               $devOptions[$item] = $_POST[$item];
               $devOptions[$item.'_share_text'] = $_POST[$item.'_share_text'];
               $devOptions[$item.'_popup_text'] = $_POST[$item.'_popup_text'];
            }
          }
        }

                if (isset($_POST['devloungeContent'])) {$devOptions['content'] = apply_filters('content_save_pre', $_POST['devloungeContent']);}
                update_option($this->adminOptionsName, $devOptions);?>
                <div class="updated"><p><strong><?php _e("Settings Updated.", "shareAndFollow");?></strong></p></div>
                <?php } ?>
                
<div class="wrap" >
        <div style="margin-right:440px;min-height:220px">
        <h2><?php _e('Share and Follow Administration','share-and-follow'); ?></h2>
        <p><?php _e('Here you can administer either the Follow Tab, or the Share Links on a Post.  If you want to admin the sidebar widget,  you need to goto the ','share-and-follow'); ?><a href="widgets.php"><?php _e('widgets section.','share-and-follow'); ?></a></p> <p><?php _e(' However for the Follow widget to work with anything except RSS you will have to fill out the details below in the ','share-and-follow'); ?><a href="#enterlinks"><?php _e('follow section','share-and-follow'); ?></a></p>
        <p><?php _e('More','share-and-follow'); ?> <a href="http://share-and-follow.com/wordpress-plugin/" target="_blank"><?php _e('documentation','share-and-follow'); ?></a> <?php _e(' on how to use this plugin and it&#39;s options &#40;Share Widget, Follow Widget, Share on Posts, Shortcode in Post, Follow Tab, Theme Tags&#41;','share-and-follow'); ?></p>
        
        </div>

<style>
td img {vertical-align:bottom;}
th {text-align:left;}
td {vertical-align:top}
div.rounded {-moz-border-radius:15px;-webkit-border-radius:15px;padding:0 20px 20px 20px;background-color:white;border:solid 1px #333}
div.rounded table{border-collapse:collapse;}
div.rounded table thead tr th {padding:.2em .4em}
div.rounded table tbody tr td {}
table.logic {border:solid 1px #ccc;margin-bottom:20px}
table.logic tr th {padding:.2em .4em}
table.logic tr td {border:solid 1px #ccc;border-width: 1px 0 0 0;padding:.2em .4em}
table.logic tr td h4 {margin:0;}
ul.cdn-listing li {float:left; width:120px;min-height:120px;text-align:center;}
ul.cdn-listing li img {margin:0 10px}
.cdn-setup {position:relative;}
.not-the-cdn {padding:10px;width:120px;border-top-left-radius:15px;border-bottom-left-radius:15px;-moz-border-radius-bottomleft:15px;-moz-border-radius-topleft:15px;-webkit-border-top-left-radius:15px;-webkit-border-bottom-left-radius:15px;border:solid 1px #999; position:absolute}
.the-cdn {margin-left: 142px;padding-left:10px;border-top-right-radius:15px;border-bottom-right-radius:15px;-moz-border-radius-bottomright:15px;-moz-border-radius-topright:15px;-webkit-border-top-right-radius:15px;-webkit-border-bottom-right-radius:15px;border:solid 1px #666; }
.the-cdn-approved {border-radius:15px;-moz-border-radius:15px;-webkit-border-radius:15px;border:solid 1px #000;}
</style>
    <?php ShareAndFollow::getCDNsets();?>
     <form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
     <input type="submit" name="reset_share-and-follow" value="<?php _e('Reset Settings', 'share-and-follow') ?>" />
     </form>
     </div>
<?php  } ?>