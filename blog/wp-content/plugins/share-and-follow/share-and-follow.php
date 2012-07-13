<?php
/*
Plugin Name: Share and Follow
Plugin URI: http://share-and-follow.com/wordpress-plugin/
Version: 1.80.3
Author: Andy Killen
Author URI: http://phat-reaction.com
Description: A simple plugin to manage sharing and following for social networking.
Copyright 2010 --> 2012 Andy Killen  (email : andy  [a t ] phat hyphen reaction DOT com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

    please note that under the GNU GPL license only the code is usable,
    the images are not part of the code and therefore under seperate
    copyrights and licensing.

*/

// setup some constants

if (!class_exists("ShareAndFollow")) {
	class ShareAndFollow {
            protected  $adminOptionsName;
            protected $_adminOptionsName;
            public $_options;
            public $_allSites;
            public $_defaults;

            //
                // class constructor
                //
		function __construct() {


                    $this->_adminOptionsName = "ShareAndFollowAdminOptions";
                    $this->adminOptionsName = "ShareAndFollowAdminOptions";
                    $this->_options = false;



                    $this->_allSites = $this->get_sites();
                    $this->_defaults = $this->return_defaults();

                  

                    $this->getAdminOptions();

                    register_activation_hook( __FILE__, array(  &$this, 'activate_plugin' ) );
		}
//	    //
                // function to run when activating the plugin and upgrading as it activates then also
                //
                function activate_plugin(){
                       if (!isset($this->_options['css_follow_images'])||empty($this->_options['css_follow_images']) ){
                           $this->_options['css_follow_images'] = 'yes';
                       }
                       $this->_options['cssid']=1;

                       @$this->update_plugin_settings();

                      //  $this->update_plugin_options();
                }


                protected function update_plugin_settings(){
                    // remove google buzz auto add google + share
                    if(isset($this->_options['google_buzz']) && $this->_options['google_buzz']=='yes'){
                        $this->_options['gplus'] == 'yes';
                    }
                    $to_delete = array('mixx','google_buzz');
                    foreach($to_delete as $delete_me){                        
                            unset($this->_options[$delete_me]);
                            unset($this->_options[$delete_me . '_share_text']);
                            unset($this->_options[$delete_me . '_popup_text']);
                        }
                    

                }


                public function get_sites(){
                  include('allsites.php');
                  return $allSites;

                }



                protected function return_allSites(){
                    return $this->get_sites();
                }

		function stylesheet_loader($name, $media){
                        $myStyleUrl = WP_PLUGIN_URL . "/share-and-follow/css/".$name.".css" ;
                        $myStyleFile = WP_PLUGIN_DIR . "/share-and-follow/css/".$name.".css" ;
                            if ( file_exists($myStyleFile) ) {
                                wp_register_style("share-and-follow-".$name."" , $myStyleUrl,array(),1,"".$media."" );
                                wp_enqueue_style( "share-and-follow-".$name."");
                            }
                    }

		 function stylesheetAutoloader(){
                        $sheets = array('stylesheet'=>'screen',
                                        'print'=>'print',
                                        );
                        foreach ($sheets as $name => $media){
                        $myStyleUrl = WP_PLUGIN_URL . "/share-and-follow/css/".$name.".css" ;
                        $myStyleFile = WP_PLUGIN_DIR . "/share-and-follow/css/".$name.".css" ;
                            if ( file_exists($myStyleFile) ) {
                                wp_register_style("share-and-follow-".$name."" , $myStyleUrl,array(),1,"".$media."" );
                                wp_enqueue_style( "share-and-follow-".$name."");
                            }
                    }
		 }
                //
                // get an image from the wordpress image library to be the share image url
                //
                function getPostImage($postID)
                    {
                        $image_src = $this->findMetaImageURL($postID); // check for existing metadata
                            if (!$image_src){
                            $photos = get_children( array('post_parent' => $postID, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'menu_order ID') );
                            // DOES NOT WORK IF ALL IMAGES ARE JUST HTML NEEDS CMS LIBRARY
                            //
                            if($photos)
                            {
                                    $theImages = array_keys($photos);
                                    $iNum=$theImages[0];
                                    $sThumbUrl = wp_get_attachment_url($iNum);
                            }
                            if(!isset($sThumbUrl) || empty($sThumbUrl)) //default to site image if none there
                            {

                                if (isset($this->_options['logo_image_url'])){$sThumbUrl=$this->_options['logo_image_url'];}

                            // DEFAULTS to author gravatar, then site image, needs to be added
                            //
                            }
                            // return image url
                            return $sThumbUrl ;
                            }
                            else {return $image_src;}
                    }

                    public function update_plugin_options(){
                        update_option($this->_adminOptionsName, $this->_options);
                        delete_transient($this->_adminOptionsName);
                        delete_transient('followbar');
                        if (strlen($this->_options['cdn-key']) <> 40) {
                            delete_transient('cdnrep');
                        }

                    }


                    function findMetaImageURL($post_id){
                    if(function_exists('has_post_thumbnail')){
                        if( has_post_thumbnail( $post_id )){
                            $post_thumbnail_id = get_post_thumbnail_id( $post_id );
                            $image_array = wp_get_attachment_image_src( $post_thumbnail_id );                            
                            $image_src = $image_array[0];
                        }else{
                            $image_src = get_post_meta($post_id, 'image_src', true);
                        }
                    }else{
                       $image_src = get_post_meta($post_id, 'image_src', true);
                    }
                    if (empty($image_src) || !isset($image_src)  || $image_src === false ){return false;}

                    else {return $image_src;}
                }
                //
                //shows follow links on the page top/bottom/left/right
                //
                function show_follow_links(){

                if ($this->_options['add_follow'] == "true") {
                    $include_page = "yes";
                    global $wp_query;
                    $curauth = $wp_query->get_queried_object();
                    if (!empty($this->_options['excluded_follow_pages'])){// exclude pages
                        $arr = explode(",", $this->_options['excluded_follow_pages']);
                        foreach ($arr as $siteValue){
                            if ($siteValue == $curauth->ID){$include_page="";}
                        }
                    }
                    if ( is_page()&&$this->_options['follow_page']=='no'){}
                    elseif ( is_single()&&$this->_options['follow_post']=='no'){}
                    elseif ( is_archive()&&$this->_options['follow_archive']=='no'){}
                    elseif ( is_home()&&$this->_options['follow_home']=='no'){}
                    elseif ( is_author()&&$this->_options['follow_author']=='no'){}
                    elseif ($include_page == ""){}
                    elseif (is_feed()){}
                    else {
                $items =array('spacing','word_value','word_text','add_follow','add_css',
            );
                $args=array('list_style'=>$this->_options['follow_list_style'],  'size'=>$this->_options['tab_size'],
                            'add_content'=>'true', 'spacing'=>$this->_options['spacing'],'follow_location'=>$this->_options['follow_location'],
                            'add_follow_text'=>$this->_options['add_follow_text'],'echo'=>'1',
                            'word_value'=>$this->_options['word_value'],'word_text'=>$this->_options['word_text'],'add_follow'=>$this->_options['add_follow'],'add_css'=>$this->_options['add_css'],'follow_rss'=>$this->_options['follow_rss'],'rss_text'=>$this->_options['rss_link_text'],'css_images'=>$this->_options['css_follow_images'],
                            );

                            foreach ($this->_allSites as $item => $siteValue){
                                if(strstr($siteValue['service'],"follow")){
                                    $args['follow_'.$item] = $this->_options['follow_'.$item];
                                    $args[$item.'_link'] = $this->_options[$item.'_link'];
                                    $args[$item.'_text'] = $this->_options[$item.'_link_text'];
                                }
                   }

                   $follow = get_transient('followbar');

                   if($follow === false){
                        $follow = $this->follow_links($args);  // does the follow links tab from the args above
                        set_transient('followbar',$follow, 60*60*30);
                   }
                   echo $follow;
                 }
               }
            }


            // Everything needs defaults
            //
            //
            function return_defaults(){
                 include('defaults.php');
                 return $defaults;
            }
                //
                //
                // setup defaults for all the options
                //
		 protected function sf_cache_manager($option_name = ShareAndFollow::_optionsName){
                    $value = get_transient( $option_name  );
                    if(false === $value){
                        $value = get_option( $option_name );

                        set_transient( $option_name, $value, 60*60*24 );
                    }

                return $value;
            }



                //
                //
                // setup defaults for all the options
                //
		function getAdminOptions() {
                        $this->_options = $this->sf_cache_manager($this->_adminOptionsName);
                        if($this->_options === false){
                            $shareAdminOptions = $this->_defaults;

                            if (!empty($this->_options)) {
                                    foreach ($this->_options as $key => $option)
                                            $shareAdminOptions[$key] = $option;
                            }
                            $this->_options = $shareAdminOptions;
                            $this->update_plugin_options();

                        }
		}

                function getCSSOptions() {
                        $cssAdminOptions = array('cssid'=>'0','print'=>'','screen'=>'');
			$cssOptions = get_option("ShareAndFollowCSS");
			if (!empty($cssOptions)) {
				foreach ($cssOptions as $key => $option)
					$cssAdminOptions[$key] = $option;
			}
			update_option("ShareAndFollowCSS", $cssAdminOptions);
                        delete_transient("ShareAndFollowCSS");
			return $cssAdminOptions;
		}

                //
                //  check CSS ID to see if it matches the admin screen, update settting for CSS as needed
                //
                function checkCss(){
                    $sheets = array( array('name'=>'stylesheet','id'=>$this->_options['cssid']),
                                     array('name'=>'print','id'=>$this->_options['cssid']),
                                   );


                    foreach ($sheets as $sheet){
                    $pd = WP_PLUGIN_DIR;
                    $fp = fopen(trailingslashit($pd)."share-and-follow/css/".$sheet['name'].".css",'r');
                    $readLevel = fgets($fp, 999);
                    $versionStart = stripos($readLevel, '=')+1;
                    $version = substr($readLevel,$versionStart,6);
                    $version = trim($version);
                    if($sheet['id'] == $version){}
                    else {
                        require_once('create-styles.php'); // loading style maker when needed
                        $option = $this->getCSSOptions( "ShareAndFollowCSS" );
                        $fp = @fopen("$pd/share-and-follow/css/".$sheet['name'].".css",'w');

                        if($fp != false) {
                            switch($sheet['name']){
                                case 'stylesheet':
                                    fwrite($fp, $option['screen'], strlen($option['screen']));
                                    break;
                                case 'print':
                                    fwrite($fp, $option['print'], strlen($option['print']));
                                    break;
                            }
                            fclose($fp);
                        }

                    }
                }
               }
               //
               // add items to head section as needed
               //

               function addHeaderCodeEndBlock () {


                    if (!empty ($this->_options['width_of_page_minimum'])){
                        wp_enqueue_script('jquery');
                        ?>
<script type="text/javascript">
//<![CDATA[
jQuery(document).ready(function() {
    function tabAction(){
      if (jQuery(window).width() <= <?php echo $this->_options['width_of_page_minimum']; ?> ){
          if (jQuery("div#follow").hasClass('display_none')){}
          else {jQuery("div#follow").addClass('display_none')}
      }
      else {
          if (jQuery("div#follow").hasClass('display_none')){jQuery("div#follow").removeClass('display_none')}
      }
    }
    tabAction();
    jQuery(window).resize(function() {
      tabAction();
    });
});
//]]>
</script>
                        <?php
                    }
               }

                function addHeaderCode() {

                    if($this->_options['pinterest_images']=='yes'){
                           wp_enqueue_script('jquery');
                   }

                    if ($this->_options['bookmark']=="yes"){
                        wp_enqueue_script('sf-bookmarks',  plugin_dir_url(__FILE__ ) . "js/browser-check-for-bookmarks.js", array(), 1, true);
                    }

                    if (!empty($this->_options['width_of_page_minimum'])){
                        wp_enqueue_script("jquery");
                    }
                    // do add of inline styles in to head section
                    $options  =  $this->sf_cache_manager( "ShareAndFollowCSS" );


                    if ($this->_options['add_css'] == "false") {
                        require_once('create-styles.php');  // loading style maker
                                echo "<style type='text/css' media='screen' >" . $options['screen'] . "</style>";
                        if ($this->_options['print_support']=='true'){
                                echo "<style type='text/css' media='print' >" . $options['screen'] . "</style>";
                        }
                   }
                   //
                   // do add of css StyleSheets into head section
                   //
                if ($this->_options['add_css'] == "true") {
                    // check sheets and call for new sheets if needed
                    $this->checkCss();
                     ///  add the possibly newly created sheet
                        $sheets['stylesheet'] = 'screen';
                        if ($this->_options['print_support']=='true'){
                            $sheets['print'] = 'print';
                        }
                        foreach ($sheets as $name => $media){
                        $myStyleUrl = WP_PLUGIN_URL . "/share-and-follow/css/".$name.".css" ;
                        $myStyleFile = WP_PLUGIN_DIR . "/share-and-follow/css/".$name.".css" ;
                            if ( file_exists($myStyleFile) ) {
                                wp_register_style("share-and-follow-".$name."" , $myStyleUrl,array(),1,"".$media."" );
                                wp_enqueue_style( "share-and-follow-".$name."");
                            }
                    }
              }
                // add share image url
                if ($this->_options['add_image_link']=="true"){
                    global $wp_query;
                    $curauth = $wp_query->get_queried_object();
                    $default = '';
                    if ( is_page()){
                            if (empty ($this->_options['page_image_url'])) {$share_image_base=$this->_options['page_img'];}
                            else{$share_image_base=$this->_options['page_image_url'];}
                            }
                    elseif ( is_single()){
                            if (empty ($this->_options['post_image_url'])) {$share_image_base=$this->_options['post_img'];}
                            else{$share_image_base=$this->_options['post_image_url'];}
                    }
                    elseif ( is_archive()){
                            if (empty ($this->_options['archive_image_url'])) {$share_image_base=$this->_options['archive_img'];}
                            else{$share_image_base=$this->_options['archive_image_url'];}
                    }
                    elseif ( is_home()){
                            if (empty ($this->_options['homepage_image_url'])) {$share_image_base=$this->_options['homepage_img'];}
                            else{$share_image_base=$this->_options['homepage_image_url'];}
                    }
                    elseif (is_404()){$share_image_base = "no";}
                    elseif (is_search()){$share_image_base = "no";}

                       switch($share_image_base){
                       case "gravatar":
                            if ($this->_options['author_defaults'] == 'authors'){ // generated email
                                $email = get_the_author_meta('user_email', $curauth->post_author);
                            }
                            else { // default email
                                $email = $this->_options['default_email'];
                                if(!empty($this->_options['default_email_image'])){$default = $this->_options['default_email_image'];}
                            }
                            $image_src = $this->doGravatarLink($email,$default).".jpg"; // adds .jpg for extra compatibilty
                        break;
                        case "logo":
                            if (!isset($this->_options['logo_image_url']) || empty($this->_options['logo_image_url']) ){
                                if (!isset($curauth->ID)){
                                    $image_src= "";
                                }
                                else {$image_src = $this->getPostImage($curauth->ID);}
                            }
                            else {$image_src = $this->_options['logo_image_url'];}
                        break;
                        case "postImage":
                            $image_src = $this->getPostImage($curauth->ID);
                            if (empty($image_src)){$image_src = $this->_options['logo_image_url'];}
                        break;
                        case "no":
                            $image_src = $this->_options['logo_image_url'];
                            break;
                        default:
                            $image_src = $share_image_base;
                       }

                    if(!empty($image_src)){
                        echo "<link rel=\"image_src\" href=\"".$image_src."\" /> \n";
                    }
                }
		}
                //
                // is it a post or a blog?
                //
                function pagepost($page_id = 0){
                    if ($page_id==0){$html =__('blog','share-and-follow');}
                    else {$html =__('post','share-and-follow');}
                    return $html;
                }
                //
                // find Current page URI
                //
                 function currentPageURI() {
                 $pageURL = 'http';
                 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
                 $pageURL .= "://";
                 if ($_SERVER["SERVER_PORT"] != "80") {
                  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
                 } else {
                  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
                 }
                 return $pageURL;
                }
                //
                // do the gravatar stuff
                //
                function doGravatarLink ($email, $default = '', $size=110){
                // construct the gravatar url, default to no alt image and 110px square
                $grav_url = "http://www.gravatar.com/avatar/" . md5( strtolower( $email ) ) .
                "?default=" . urlencode( $default ) .
                "&amp;size=" . $size;
                return $grav_url;
                }
                //
                // plugin support for wp-ecommerce
                //
                function plugin_support (){

                    // share buttons
                    if ($this->_options['wpsc_top_of_products_page']=="yes"){add_action('wpsc_top_of_products_page', 'my_wp_ecommerce_share_links' );}
                    if ($this->_options['wpsc_product_before_description']=="yes"){add_action('wpsc_product_before_description', 'my_wp_ecommerce_share_links' );}
                    if ($this->_options['wpsc_product_addon_after_descr']=="yes"){add_action('wpsc_product_addon_after_descr', 'my_wp_ecommerce_share_links' );}

                    // interactive buttons
                    // after description
                    if ($this->_options['like_wpsc_product_addon_after_descr']=="yes" || $this->_options['tweet_wpsc_product_addon_after_descr']=="yes" || $this->_options['stumble_wpsc_product_addon_after_descr']=='yes')
                    {
                    add_action('wpsc_product_addon_after_descr',array($this, 'wp_ecommerce_interactive_links_top'  ));}
                    // before
                    if ($this->_options['like_wpsc_product_before_description']=="yes"||$this->_options['tweet_wpsc_product_before_description']=="yes" || $this->_options['stumble_wpsc_product_before_description']=='yes'){
                    add_action('wpsc_product_before_description',array($this, 'wp_ecommerce_interactive_links_top' ));
                    }
                    // title
                    if ($this->_options['tweet_wpsc_top_of_products_page']=="yes"||$this->_options['like_wpsc_top_of_products_page']=="yes"||$this->_options['stumble_wpsc_top_of_products_page']=="yes"){
                    add_action('wpsc_top_of_products_page',array($this, 'wp_ecommerce_interactive_links_top' ));
                    }
                }


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


                function wp_ecommerce_interactive_links_top(){

                    $tweet=$this->_options['tweet_wpsc_top_of_products_page'];$like=$this->_options['like_wpsc_top_of_products_page'];$stumble=$this->_options['stumble_wpsc_top_of_products_page'];
                    $this->wp_ecommerce_interactive_links($like,$tweet,$stumble);
                }
                function wp_ecommerce_interactive_links_before(){

                    $tweet = $this->_options['tweet_wpsc_product_before_description']; $like=$this->_options['like_wpsc_product_before_description']; $stumble=$this->_options['stumble_wpsc_product_before_description'];
                    $this->wp_ecommerce_interactive_links($like,$tweet,$stumble);
                }
                function wp_ecommerce_interactive_links_after(){

                    $tweet = $this->_options['tweet_wpsc_product_addon_after_descr']; $like=$this->_options['like_wpsc_product_addon_after_descr'];$stumble=$this->_options['stumble_wpsc_product_addon_after_descr'];
                    $this->wp_ecommerce_interactive_links($like,$tweet,$stumble);
                }
                function wp_ecommerce_interactive_links($like = '', $tweet='', $stumble=''){
                    $perma=wpsc_the_product_permalink();
                    $title=wpsc_the_product_title();
                    $buildup='<div style="padding:10px 0">';
                    if($tweet=='yes'){
                    $buildup.= $this->doTweetiFrame('', $perma, $title );
                    }
                    if($like=="yes"){
                    $buildup.= $this->doLikeiFrame('', $perma);
                    }
                    if($stumble=='yes'){
                    $buildup.= $this->doStumbleScript('', $perma );
                    }
                    echo $buildup."</div>";
                }


                function getLikeHeight($style, $face){
                    switch ($style){
                        case 'box_count':
                            return '65';
                            break;
                        case 'standard':

                            if($face=='true'){return '80';}
                            else {
                            return '31';
                            }
                            break;
                        case 'button_count':
                            return '21';
                            break;
                    }
                }
                function getTweetHeight($style){
                    switch ($style){
                        case 'vertical':
                            return '65';
                            break;
                        case 'horizontal':
                            return '21';
                            break;
                        case 'none':
                            return '31';
                            break;
                    }
                }

                function doLikeiFrame($postid,$url='',$style='', $size='', $faces=''){
                    if ($url==''){$url = urlencode(get_permalink($postid));}
                    if ($style==''){$style=$this->_options['like_style'];}
                    if ($faces==''){$faces=$this->_options['like_faces'];}
                    if ($size==''){$size=$this->_options['like_width'];}
                    return "<iframe src='//www.facebook.com/plugins/like.php?href=".$url."&amp;layout=".$style."&amp;show_faces=".$faces."&amp;width=".$size."&amp;action=".$this->_options['like_verb']."&amp;font=".$this->_options['like_font']."&amp;colorscheme=".$this->_options['like_color']."&amp;height=".$this->getLikeHeight($this->_options['like_style'],$this->_options['like_faces'] )."' scrolling='no' frameborder='0' style='border:none; overflow:hidden; width:".$size."px; height:".$this->getLikeHeight($style,$faces )."px;' allowTransparency='true'></iframe>";
                }

                function doTweetiFrame($postid, $url = '', $title = '', $via='', $style='', $size=''){


                    if ($url==''){$url = urlencode(get_permalink($postid));}
                    if ($title==''){$title = get_the_title($postid);}
                    if ($style==''){$style=$this->_options['tweet_style'];}
                    if ($size==''){$size=$this->_options['tweet_width'];}

                    if (!empty($this->_options['tweet_via'])){$via = "&amp;via=".$this->_options['tweet_via'];}
                    return "<iframe allowtransparency='true' frameborder='0' scrolling='no' src='//platform.twitter.com/widgets/tweet_button.html?url=".$url."&amp;text=".$title."&amp;count=".$style."&amp;lang=".WPLANG.$via."' style='width:".$size."px; height:".$this->getTweetHeight($style)."px;' ></iframe>";
                }
                function doStumbleScript($postid, $url = '', $title = '', $via='',$style='', $size=''){
                    add_action('wp_footer', array( &$this, 'stumble_footer_code'), 100);
                    if ($url==''){$url = get_permalink($postid);}
                    if ($style==''){$style=$this->_options['stumble_style'];}
                    return "<su:badge layout='" .$style. "' location='". $url ."'></su:badge>";
                }

                function doGooglePlusButton($postid, $url = '',  $style = '', $size = ''){
                    add_action('wp_footer', array( &$this, 'google_plus_footer_code'), 100);
                    if ($url==''){$url = get_permalink($postid);}
                    if ($style==''){$style=$this->_options['googleplus_style'];}
                    if ($size==''){$size=$this->_options['googleplus_size'];}

                    return "<g:plusone size='" . $size .  "' annotation='" . $style  .  "' href='" . $url  .  "'></g:plusone>";
                }

                function doPinterestScript($postid, $style=''){
                    add_action('wp_footer', array( &$this, 'pinterest_footer_code'), 100);
                    $page_url = urlencode(get_permalink($postid));
                    $image_url = $this->getPostImage($postid);
                    if ($style==''){$style = $this->_options['pinterest_style'];}
                    return "<a href='http://pinterest.com/pin/create/button/?url=" . $page_url . "&media=" . $image_url . "' class='pin-it-button' count-layout='".$style."'>Pin It</a>";
                }

                //
                // add content to the end of posts and pages to make icons show
                //
                function addContent($content = '') {

                    $include_page = "yes";
                    
                    // check if page should be excluded
                    if (!empty($this->_options['excluded_share_pages'])){// exclude pages
                        $arr = explode(",", $this->_options['excluded_share_pages']);
                        foreach ($arr as $siteValue){
                            if ($siteValue == get_the_ID() ){$include_page="";}
                        }
                    }

                    if ( is_page()&&$this->_options['wp_page']=='no'){}
                    elseif ( is_single()&&$this->_options['wp_post']=='no'){}
                    elseif ( is_archive()&&$this->_options['wp_archive']=='no'){}
                    elseif ( is_home()&&$this->_options['wp_home']=='no'){}
                    elseif ( is_author()&&$this->_options['wp_author']=='no'){}
                    elseif ($include_page == ""){}
                    elseif (is_404()){}
                    elseif (is_search()){}
                    elseif (is_feed()){}
                    else {
                    $postid = get_the_ID();
                    if ($this->_options['like_topleft']=='yes'||$this->_options['tweet_topleft']=='yes'||$this->_options['stumble_topleft']=='yes' || $this->_options['googleplus_topleft']=='yes'){
                        $buildspace = '<div style="padding: 0 10px 10px 0" class="interactive_left">';
                        if($this->_options['tweet_topleft']=='yes'){
                                $buildspace .= $this->interactive_holder($this->doTweetiFrame($postid), 'left');
                        }
                        if($this->_options['like_topleft']=='yes'){
                                $buildspace .= $this->interactive_holder($this->doLikeiFrame($postid), 'left');
                        }
                          if($this->_options['stumble_topleft']=='yes'){
                                $buildspace .= $this->interactive_holder($this->doStumbleScript($postid), 'left');
                        }
                        if($this->_options['googleplus_topleft']=='yes'){
                        $google_style = '';
                        if($this->_options['googleplus_size']=='tall' && $this->_options['googleplus_style']=='bubble'){
                            $google_style = 'tall';
                        }

                        $buildspace .= $this->interactive_holder($this->doGooglePlusButton($postid), 'left ' . $google_style);
                        }
                        if($this->_options['pinterest_topleft']=='yes'){
                                $buildspace .= $this->interactive_holder($this->doPinterestScript($postid), 'left');
                        }
                       $content = $buildspace."</div>".$content;
                    }
                 if ($this->_options['like_topright']=='yes'||$this->_options['tweet_topright']=='yes'||$this->_options['stumble_topright']=='yes' || $this->_options['googleplus_topright']=='yes'){
                        $buildspace = '<div style="float:right;padding: 0 0 10px 10px" class="interactive_right">';
                       if($this->_options['tweet_topright']=='yes'){
                                $buildspace .= $this->interactive_holder($this->doTweetiFrame($postid), 'right');
                        }
                       if($this->_options['like_topright']=='yes'){
                                $buildspace .= $this->interactive_holder($this->doLikeiFrame($postid), 'right');
                        }
                       if($this->_options['stumble_topright']=='yes'){
                                $buildspace .=$this->interactive_holder( $this->doStumbleScript($postid), 'right');
                        }
                       if($this->_options['googleplus_topright']=='yes'){
                        $google_style = '';

                        if($this->_options['googleplus_size']=='tall' && $this->_options['googleplus_style']=='bubble'){
                            $google_style = 'tall';
                        }
                        if($this->_options['pinterest_topright']=='yes'){
                                $buildspace .= $this->interactive_holder($this->doPinterestScript($postid), 'left');
                        }

                        $buildspace .= $this->interactive_holder($this->doGooglePlusButton($postid), 'right ' . $google_style );
                        }
                       $content = $buildspace."</div>".$content;
                    }

                        if ($this->_options['add_content'] == "true") {
                            $perma=get_permalink();
                            $title=get_the_title();
                            $postid = get_the_ID();
                            // $twitter_text = $this->get_twitter_text($postid);
                                $args = array('page_id' => $postid,
                                   'heading' => '2',
                                   'list_style'=>$this->_options['list_style'],
                                   'size'=>$this->_options['size'],
                                   'direction' => 'row',
                                   'page_title'=>$title,
                                   'page_link'=>$perma,
                                   'echo'=>'1',
                                   'share'=>$this->_options['share'],
                                   'share_text'=>$this->_options['share_text'],
                                   'email_body_text'=>$this->_options['email_body_text'],
                                   'css_images'=>$this->_options['css_images'],
                                   'email_popup_text'=>$this->_options['email_popup_text'],
                                   'email'=>$this->_options['email'],
                                   'email_share_text'=>$this->_options['email_share_text'],
                                   'post_rss'=>$this->_options['post_rss'],
                                   'post_rss_share_text'=>$this->_options['post_rss_share_text'],
                                   'post_rss_popup_text'=>$this->_options['post_rss_popup_text'],
                                );


                     foreach ($this->_allSites as $item => $siteValue){
                            if($item=='email'|| $item == 'rss'){}
                            else{
                            if(strstr($siteValue['service'],"share")){
                               $args[$item] = $this->_options[$item];
                               $args[$item.'_share_text'] = $this->_options[$item.'_share_text'];
                               $args[$item.'_popup_text'] = $this->_options[$item.'_popup_text'];
                            }
                          }
                        }
                        $content .= "<div class='shareinpost'>";
                        $content .= $this->social_links($args);

                          $content .= "</div>";

                        }
                        if ($this->_options['like_bottom']=='yes'||$this->_options['tweet_bottom']=='yes'||$this->_options['stumble_bottom']=='yes'||$this->_options['googleplus_bottom']=='yes'){
                        $buildspace = '<div style="padding: 10px 0 "  class="interactive_bottom">';

                       if($this->_options['tweet_bottom']=='yes'){
                                $buildspace .= $this->interactive_holder($this->doTweetiFrame($postid), 'left');
                        }
                        if($this->_options['like_bottom']=='yes'){
                                $buildspace .= $this->interactive_holder($this->doLikeiFrame($postid), 'left');
                        }
                        if($this->_options['stumble_bottom']=='yes'){
                                $buildspace .= $this->interactive_holder($this->doStumbleScript($postid), 'left');
                        }
                        if($this->_options['googleplus_bottom']=='yes'){
                            $google_style = '';

                            if($this->_options['googleplus_size']=='tall' && $this->_options['googleplus_style']=='bubble'){
                                $google_style = 'tall';
                            }

                                 $buildspace .= $this->interactive_holder($this->doGooglePlusButton($postid), 'left ' . $google_style);
                        }
                        if($this->_options['pinterest_bottom']=='yes'){
                                $buildspace .= $this->interactive_holder($this->doPinterestScript($postid), 'left');
                        }

                       $content .= $buildspace."</div>";
                    }
                    }
                    return $content;
                }

                function interactive_holder( $button, $location = 'left'){
                    return "<div class='button_holder_" . $location . "'>" . $button . "</div>";
                }

                //
                // get twitter text for putting into tweets in advance
                //
               protected function get_twitter_text($postid){
                    $twitter_text = get_post_meta($postid, 'twitter_text', true);  // beginning of tweet
                    if (empty($twitter_text) || !isset($twitter_text)){

                        if (!empty($this->_options['twitter_text_default'])){
                           $completeTweet =   stripslashes($this->_options['twitter_text_default'])." - ";
                           } // over ride text default
                        else {
                            switch($this->_options['twitter_text']){
                            case "clean":
                                $completeTweet = "";
                                break;
                            case "title":
                               $completeTweet = get_the_title($postid)." - ";
                                break;
                            }
                        }
                    }
                    else {$completeTweet = $twitter_text." - ";}
                   return $completeTweet;
                }

               protected function get_twitter_suffix($postid, $tweet){
                    $twitter_suffix = get_post_meta($postid, 'twitter_suffix', true); // end of tweet
                    if (empty($twitter_suffix) || !isset($twitter_suffix)){

                        if (!empty($this->_options['twitter_text_suffix'])){
                           $tweet =   $tweet." ".urlencode(stripslashes($this->_options['twitter_text_suffix']));
                           }
                    }
                    else {$tweet = $tweet." ".urlencode(stripslashes($twitter_suffix));}
                   return $tweet;
                }

                //
                // share shortcode
                //
                function share_func($atts, $content) {
                        extract(shortcode_atts(array(
                                'heading' => '0',                                'size' => "16",
                                'list_style' => 'icon_text',                     'direction' => 'down',
                                'share'=>'no',                                   'facebook'=>'yes',
                                'stumble'=>'yes',                                'hyves'=>'no',
                                'orkut'=>'yes',                                  'digg'=>'yes',
                                'print'=>'no',                                   'reddit'=>'yes',
                                'delicious'=>'yes',                              'yahoo_buzz'=>'',
                                'linkedin'=>'',                                  'vkontakte'=>'',
                                'google_buzz'=>'',                               'twitter'=>'yes',
                                'myspace'=>'yes',                                'mixx'=>'no',
                                'email'=>'no',                                   'post_rss'=>'yes',
                                'css_images'=>'yes',                             'id'=>'self',
                                'xing'=>'no',                                    'tumblr'=>'no',
                                'title'=>'self',
                        ), $atts));
                        //shortcode defaults
                        if($id == 'self' ){
                            $id=get_the_ID();
                        }
                        $page_title=get_the_title($id);
                        if($title != 'self'){
                            $page_title = $title;
                        }

                        $page_link=get_permalink($id);
                        $args = array(
                                'list_style'=>$list_style,
                                'page_id'=>$id,                              'facebook'=>$facebook,
                                'stumble'=>$stumble,                             'hyves'=>$hyves,
                                'orkut'=>$orkut,                                 'mixx'=>$mixx,
                                'linkedin'=>$linkedin,                           'vkontakte'=>$vkontakte,
                                'digg'=>$digg,                                   'reddit'=>$reddit,
                                'delicious'=>$delicious,                         'twitter'=>$twitter,
                                'myspace'=>$myspace,                             'share'=>$share,
                                'heading' => $heading,                           'size' => $size,
                                'email' => $email,                               'echo'=>'1',
                                'direction' => $direction,                       'page_title'=>$page_title,
                                'page_link'=>$page_link,                         'post_rss'=>$post_rss,
                                'print'=>$print,                                 'tumblr'=>$tumblr,
                                'xing'=>$xing,
                                        );

                        $html = $content.$this->social_links($args);
                        return $html; // shortcodes should be a return, not a print or echo as it only puts it at the top of the post
                }
                //
                //
                //
                function interactive_func($atts, $content) {
                        extract(shortcode_atts(array(
                                'like' => 'yes',                                'tweet' => "yes",
                                'stumble' => "no",                              'style' => 'box_count',

                        ), $atts));
                        //shortcode defaults
                        $postid=get_the_ID();
                        $title=get_the_title();
                        $perma=get_permalink($postid);
                        $buildup ='';
                        $faces='false';
                        switch ($style){
                            case 'box_count':
                                $tweet_size = '65';
                                $facebook_size = '65';
                                $stumble_size = '65';
                                $tweet_look = 'vertical';
                                $like_look = 'box_count';
                                $stumble_look = '5';
                                break;
                            case 'side_count':
                                $tweet_size = '100';
                                $facebook_size = '100';
                                $stumble_size = '100';
                                $tweet_look = 'horizontal';
                                $like_look = 'button_count';
                                $stumble_look = '1';
                                break;
                        }


                        if($tweet=='yes'){
                        $buildup.= $this->doTweetiFrame($postid, urlencode($perma), '', $title, $tweet_look, $tweet_size, $faces);
                        }
                        if($like=="yes"){
                        $buildup.= $this->doLikeiFrame($postid, urlencode($perma), $like_look,$facebook_size);
                        }
                        if($stumble=='yes'){
                        $buildup.= $this->doStumbleScript($postid, $perma, $stumble_look, $size,$stumble_size );
                        }
                        $html = $content.$buildup;
                        return $html; // shortcodes should be a return, not a print or echo as it only puts it at the top of the post
                }

                //
                // replace keywords in URL so that it shares properly, check for php4 as html_entity_decode is a bug on there.
                //
                function replaceKeyWordsInURL($share_url,$page_link, $page_title, $page_excerpt, $id = '' ){
                        global $post;
                        $share_url = str_replace('EXCERPT' ,urlencode($page_excerpt), $share_url );
                        $share_url = str_replace('TITLE' ,urlencode(html_entity_decode(str_replace('&#038;','and',$page_title), ENT_QUOTES, 'UTF-8')), $share_url );
                        $share_url = str_replace('URI' ,urlencode($page_link), $share_url );
                        $share_url = str_replace('IMAGE' ,urlencode($this->getPostImage($id)), $share_url );
                    return $share_url;
                }
                //
                // replace keywords in popup text
                //
               function replaceKeyWordsInPopup ($page_id, $page_title, $popup_text){
                    $popup_text = str_replace('TITLE',strip_tags($page_title),$popup_text);
                    $popup_text = str_replace('BLOG',strip_tags($this->pagepost($page_id)),$popup_text);
                    return $popup_text;
                }
                //
                // make follow links
                //
                function makeFollowLink($args){
                     $defaults = array(
                        'list_style'=>'iconOnly',
                        'icon_set'=>'default',
                        'css_class'=>'',
                        'follow_text'=> __('Follow this','share-and-follow'),
                        'follow_popup_text'=> __('Follow this','share-and-follow'),
                        'size'=>'24',
                        'css_images'=>'no',
                        'image_name'=>'',
                        'sprite_address'=>'0,0',
                        'rel'=>'nofollow me',
                        'target'=>'_blank',
                        'add_li'=>'yes',
                        'special'=>'no',
                        'echo'=>'0',
                        'follow_url'=>'',
                    );
                 $args = wp_parse_args( $args, $defaults );
                 extract( $args, EXTR_SKIP );
                 // create result


                 $result = '';
                 $follow_text = stripslashes  ($follow_text);

                    switch ($css_images){
                          case "yes":
                              $result = "<a rel=\"".$rel."\" target=\"".$target."\"  ".$this->doImageStyle($css_class, $size,  $list_style)." href=\"".$follow_url."\" title=\"".$follow_text."\"><span class=\"head\">".$follow_text."</span></a>";
                              break;
                          case "no":
                              $result = "<a rel=\"".$rel."\" target=\"".$target."\" href=\"".$follow_url."\" title=\"".$follow_text."\" >";
                              $blank_gif = WP_PLUGIN_URL."/share-and-follow/images/blank.gif";
                                switch ($list_style){
                                    case 'text_replace':
                                        $result .= "<img src=\"". $blank_gif  ."\" class=\"".$css_class."\"  alt=\"".$follow_text."\"/> ";
                                    break;
                                    case 'iconOnly':
                                        $result .= "<img src=\"". $blank_gif  ."\" height=\"".$size."\"  width=\"".$size."\" style=\"background: transparent url(".$this->getIconSprites( $size ).") no-repeat;padding:0;margin:0;height:".$size."px;width:".$size."px;background-position:".str_replace(" ", "px ",$this->_allSites[$css_class]['sprites'][$size])."px\"  alt=\"".$follow_text."\"/> ";
                                    break;
                                    case 'icon_text':
                                        $result .= "<img src=\"". $blank_gif  ."\" height=\"".$size."\"  width=\"".$size."\" style=\"background: transparent url(".$this->getIconSprites( $size ).") no-repeat;padding:0;margin:0;height:".$size."px;width:".$size."px;background-position:".str_replace(" ", "px ",$this->_allSites[$css_class]['sprites'][$size])."px\" alt=\"".$follow_text."\"/> ";
                                    break;
                                }

                              $result .= "<span class=\"head\">".$follow_text."</span></a>";
                              break;
                    }
                 // add LI
                 if($add_li=='yes'){$result = "<li class=\"".$list_style."\">".$result."</li>";}
                 // return result as echo or variable depending on choice.
                 if($echo==1){echo $result;}
                 else {return $result;}

                }
                //
                // make a share link that goes inside a socialwrap
                //
                function makeShareLink($args){
                    // $devOptions =
                    $defaults = array(
                        'list_style'=>'iconOnly',
                        'css_class'=>'',
                        'icon_set'=>'default',
                        'page_link'=>'',
                        'page_title'=>'',
                        'page_excerpt'=>'',
                        'page_id'=>'0',
                        'excerpt'=>'',
                        'share_text'=> __('Share this','share-and-follow'),
                        'popup_text'=> __('Share this','share-and-follow'),
                        'email_body'=> __('Here is a link to a site I really like','share-and-follow'),
                        'twitter_text'=>'',
                        'size'=>'16',
                        'css_images'=>'no',
                        'image_name'=>'',
                        'sprite_address'=>'0,0',
                        'rel'=>'nofollow',
                        'target'=>'_blank',
                        'add_li'=>'yes',
                        'short_url'=>'no',
                        'special'=>'no',
                        'echo'=>'0',
                        'share_url'=>'',
                    );
                 $args = wp_parse_args( $args, $defaults );
                 extract( $args, EXTR_SKIP );
                 // create result
                 $result = '';

                 // print_r($this->_allSites);
                 $sites = $this->_allSites;


                // set base attributes
                 $attributes = " rel='". $rel . "' ";
                 $attributes .= " target='". $target . "' ";
                 $attributes .= " title='".$this->replaceKeyWordsInPopup ($page_id, $page_title, $popup_text)."' ";

                 // image settings
                 $image_attributes = " src='".WP_PLUGIN_URL."/share-and-follow/images/blank.gif' ";
                 $image_attributes .= " height='".$size."' width='". $size ."' ";
                 $image_attributes .= " class='image-".$size."' alt='".$image_name."' ";
                 $image_attributes .= " style='background: transparent url(".$this->getIconSprites( $size ).") no-repeat;padding:0;margin:0;height:".$size."px;width:".$size."px;background-position:".str_replace(" ", "px ",$sites[$css_class]['sprites'][$size])."px' ";

                 // define the type of icon to create.  deals with post RSS and email
                 switch ($special){
                      case "no":
                        if ($css_images=="yes"){
                            $attributes .= " href='".$this->replaceKeyWordsInURL($sites[$css_class]['share_url'], $page_link, $page_title, $page_excerpt, $page_id )."' ";
                            $attributes .= $this->doImageStyle($css_class, $size, $list_style);
                            $result = "<a ".$attributes ." ><span class=\"head\">".stripslashes  ($share_text)."</span></a>";
                            }
                        else{
                            $attributes .= " href='".$this->replaceKeyWordsInURL($sites[$css_class]['share_url'], $page_link, $page_title, $page_excerpt, $page_id )."' ";
                            $result="<a ". $attributes ." >";
                             if ($this->_options['list_style']!='text_only'){
                                 $result.="<img " . $image_attributes  . "  /> ";

                                 }
                             $result.="<span class=\"head\">".stripslashes ($share_text)."</span></a>";}
                     break;
                     case "short-url":
                         if ($css_images=="yes"){
                             $attributes .= $this->doImageStyle($css_class, $size, $list_style);
                             $result = "<a ". $attributes ." href='".
                                              $this->replaceKeyWordsInURL($sites[$css_class]['share_url'],
                                              $this->shortenURL ($page_link, $page_id), $page_title, $page_excerpt ) ."' ><span class='head'>".stripslashes($share_text)."</span></a>";

                             }
                         else{
                             $result="<a rel=\"".$rel."\" target=\"".$target."\" href=\"".$this->replaceKeyWordsInURL($sites[$css_class]['share_url'], $this->shortenURL ($page_link, $page_id), $page_title, $page_excerpt )."\" title=\"".$this->replaceKeyWordsInPopup ($page_id, $page_title, $popup_text)."\" >";
                             if ($this->_options['list_style']!='text_only'){
                                 $result.="<img " . $image_attributes  . "  /> ";
                                 }
                             $result.="<span class=\"head\">".stripslashes ($share_text)."</span></a>";}
                         break;
                     case "twitter":
                         if ($css_images=="yes"){$result = "<a rel=\"".$rel."\" target=\"".$target."\" ".$this->doImageStyle($css_class, $size, $list_style)." href=\"".$this->get_twitter_suffix ($page_id,$this->replaceKeyWordsInURL($sites[$css_class]['share_url'], $this->shortenURL ($page_link, $page_id), $this->get_twitter_text($page_id), $page_excerpt ))."\" title=\"".$this->replaceKeyWordsInPopup ($page_id, $page_title, $popup_text)."\"><span class=\"head\">".stripslashes  ($share_text)."</span></a>";}
                         else{$result="<a " . $attributes . " href=\"".$this->get_twitter_suffix ($page_id, $this->replaceKeyWordsInURL($sites[$css_class]['share_url'], $this->shortenURL ($page_link, $page_id), $this->get_twitter_text($page_id), $page_excerpt ))."\"  >";
                         if ($this->_options['list_style']!='text_only'){
                             $result.="<img " . $image_attributes  . "  /> ";
                             }
                         $result.="<span class=\"head\">".stripslashes ($share_text)."</span></a>";}
                         break;
                     case "clean":
                         if ($css_images=="yes"){$result = "<a " . $attributes . "   ".$this->doImageStyle($css_class, $size, $list_style)." href='".$this->replaceKeyWordsInURL($sites[$css_class]['share_url'], $page_link, $page_title, $page_excerpt, $page_id )."' ><span class='head'>".stripslashes($share_text)."</span></a>";}
                         else{$result.="<a " . $attributes . " href=\"".$this->replaceKeyWordsInURL($sites[$css_class]['share_url'], $page_link, $page_title, $page_excerpt, $page_id )."\"  ><img src=\"".WP_PLUGIN_URL."/share-and-follow/images/blank.gif\" style=\"background: transparent url(".$this->getIconSprites( $size).") no-repeat;padding:0;margin:0;height:".$size."px;width:".$size."px;background-position:".str_replace(" ", "px ",$sites[$css_class]['sprites'][$size])."px\" class=\"image-".$size."\" height=\"".$size."\"  width=\"".$size."\"  alt=\"".$image_name."\"/> <span class=\"head\">".stripslashes ($share_text)."</span></a>";}
                         break;
                     case "bookmark":
                         if ($css_images=="yes"){$result = "<a ".$this->doImageStyle($css_class, $size, $list_style)." href='".$this->replaceKeyWordsInURL($sites[$css_class]['share_url'], $page_link, $page_title, $page_excerpt )."' title='".$this->replaceKeyWordsInPopup ($page_id, $page_title, $popup_text)."'><span class='head'>".stripslashes  ($share_text)."</span></a>";}
                         else{$result="<a href='".$this->replaceKeyWordsInURL($sites[$css_class]['share_url'], $page_link, $page_title, $page_excerpt )."' title='".$this->replaceKeyWordsInPopup ($page_id, $page_title, $popup_text)."' >";
                         if ($this->_options['list_style']!='text_only'){$result.="<img src='".WP_PLUGIN_URL."/share-and-follow/images/blank.gif' style='background: transparent url(".$this->getIconSprites( $size).") no-repeat;padding:0;margin:0;height:".$size."px;width:".$size."px;background-position:".str_replace(" ", "px ",$sites[$css_class]['sprites'][$size])."px' class='image-".$size."' height='".$size."'  width='".$size."'  alt='".$image_name."'/> ";}
                             $result.="<span class='head'>".stripslashes ($share_text)."</span></a>";
                         }
                         break;
                     case "email":
                         if ($css_images=='yes'){$result ="<a rel=\"".$target."\" ".$this->doImageStyle($css_class, $size, $list_style)." href=\"mailto:?".str_replace(" ", '%20', "subject=".get_bloginfo('name')." : ".str_replace('&#038;',__('and','share-and-follow'),$page_title)."&amp;body=".stripslashes  ($email_body)."  ".$page_link)."\" title=\"".$this->replaceKeyWordsInPopup ($page_id, $page_title, $popup_text)."\"><span class=\"head\">".stripslashes  ($share_text)."</span></a>";}
                         else {$result ="<a rel=\"".$target."\" href=\"mailto:?".str_replace(" ", '%20', "subject=".get_bloginfo('name')." : ".$page_title."&amp;body=".stripslashes  ($email_body)."  ".$page_link)."\" title=\"".$this->replaceKeyWordsInPopup ($page_id, $page_title, $popup_text)."\">";
                         if ($this->_options['list_style']!='text_only'){$result.="<img src=\"".WP_PLUGIN_URL."/share-and-follow/images/blank.gif\" height=\"".$size."\"  width=\"".$size."\" style=\"background: transparent url(".$this->getIconSprites( $size).") no-repeat;padding:0;margin:0;height:".$size."px;width:".$size."px;background-position:".str_replace(" ", "px ",$sites[$css_class]['sprites'][$size])."px\" class=\"image-".$size."\"  alt=\"".$image_name."\" /> ";}
                             $result.="<span class=\"head\">".stripslashes  ($share_text)."</span></a>";}
                         break;
                     case "rss":
                        $rssSettigns = get_option("permalink_structure");
                        if (empty($rssSettigns)){$rss_link = $page_link."&feed=rss2";}
                         else {$rss_link = trailingslashit($page_link)."feed";}
                         
                        if ($css_images=='yes'){$result ="<a rel=\"".$target."\" ".$this->doImageStyle($css_class, $size, $list_style)." title=\"".$this->replaceKeyWordsInPopup ($page_id, $page_title, $popup_text)."\"  href=\"".$rss_link."\" ><span class=\"head\">".stripslashes  ($share_text)."</span></a>";}
                            else {$result ="<a rel=\"".$target."\" href=\"".$rss_link."\" title=\"".$this->replaceKeyWordsInPopup ($page_id, $page_title, $popup_text)."\">";
                                 if ($this->_options['list_style']!='text_only'){$result.="<img src=\"".WP_PLUGIN_URL."/share-and-follow/images/blank.gif\" height=\"".$size."\"  width=\"".$size."\" style=\"background: transparent url(".$this->getIconSprites( $size).") no-repeat;padding:0;margin:0;height:".$size."px;width:".$size."px;background-position:".str_replace(" ", "px ",$sites[$css_class]['sprites'][$size])."px\" class=\"image-".$size."\"  alt=\"".$image_name."\" /> ";}
                                 $result.="<span class=\"head\">".stripslashes  ($share_text)."</span></a>";}
                        break;
                 }
                 // LI wrap it
                 if($add_li=='yes'){$result = "<li class=\"".$list_style."\">".$result."</li>";}

                 // return result as echo or variable depending on choice.
                 if($echo==1){echo $result;}
                 else {return $result;}
                }
                //
                //
                //do image style
                //css single images only
                //
             protected   function doImageStyle($image, $size, $list_style){
                    switch($list_style){
                        case 'iconOnly':
                        return "style=\"display:block;background: transparent url(".$this->getIconSetDetails($image, $size).") no-repeat top left;height:".($size)."px;width:".($size)."px;\" class=\"".$image."\"";
                        break;
                        case 'text_only':
                        return "";
                        break;
                        case 'icon_text':
                        return "style=\"background: transparent url(".$this->getIconSetDetails( $image, $size ).") no-repeat top left;padding-left:".($size + 4)."px;line-height:".($size + 4)."px;\" class=\"".$image."\"";
                        break;
                    }
                }
                //
                // make urls shorter, at this time only with bit.ly
                //
              protected  function shortenURL($url, $post_ID){
                    if (function_exists('json_decode')){

                    //check for bit.ly settings
                    if (!empty($this->_options['bit_ly'])&&!empty($this->_options['bit_ly_code'])){
                        // check to see if the URL has been setup before
                        // using wordpress postmeta
                        $short_url = get_post_meta($post_ID, 'short-url', true);
                        // get current settings
                        if (empty($short_url) || (strlen($short_url) > 20) ){
                            require_once('RemoteConnector.php');
                            $buildURL = "http://api.bit.ly/v3/shorten?login=".
                                             $this->_options['bit_ly']."&apiKey=".
                                             $this->_options['bit_ly_code']."&longUrl=".
                                             urlencode($url)."&format=json";
                            $control = new Pos_RemoteConnector($buildURL);
                            $obj = json_decode($control->__toString(), true);
                            if ($obj['status_code']==200){
                                // setup new url for return
                                $endURL = $obj['data']['url'];
                                // setup optional bit.ly pro domain
                                if (!empty($this->_options['bit_ly_domain'])){
                                $endURL = str_replace('bit.ly',  $this->_options['bit_ly_domain'],$endURL);
                                }
                                // add it as metadata
                                add_post_meta($post_ID, 'short-url', $endURL, true);
                            }
                            else {// if it fails for any reason use existing URL
                                $endURL=urlencode($url);
                                }
                        }
                        else { // use short-url already from the postmeta table
                            $endURL=$short_url;
                        }
                    }
                    else {$endURL=$url;}// if not setup for Bit.ly use existing url... does not like encoded URL for twitter
                    return $endURL;
                    }
                    else {return $url;} // if no JSON support return existing URL... does not like encoded URL for twitter
                }
                // Load widgets
               function load_widgets() {
                    register_widget( 'Share_Widget' );
                    register_widget( 'Follow_Widget' );
                }
              function getCDNurlStamped($file, $time = '30' ){
                $expire = time()+(60*60*24*$time);
                $signing_url = $file . "?".CDNEXPIRE."=" . $expire . "&".PASSPHRASE."=".PASSCODE;
                $signature = MD5($signing_url);
                $output_url = CDNSERVER.$file."?".CDNEXPIRE."=" . $expire . "&amp;".PASSTOKEN."=" . $signature;
                return $output_url;
                }
                //
                // choose the right icons
                //
                 function getIconSetDetails($image, $size ){
                    // warning, without the correct passcode or passphrase there is no way into the CD

                    if($this->_options['cdn']['status_txt']!='OK'){
                       $directory =  WP_PLUGIN_URL."/share-and-follow/default/".$size."/".$image.".png";
                    }
                    else if(PASSCODE== ""||PASSPHRASE==""||PASSTOKEN==""||CDNEXPIRE==""||CDNDIRECTORY==""||CDNSERVER==""){
                        $directory =  WP_PLUGIN_URL."/share-and-follow/default/".$size."/".$image.".png";
                    }
                    else {
                        $file = CDNDIRECTORY.$this->_options['icon_set']."/".$size."/".$image.".png";
                        $directory = $this->getCDNurlStamped($file);
                    }
                return $directory;
                }
                //
                // choose the right sprites
                //
               function getIconSprites( $size ){
                    // warning, without the correct passcode or passphrase there is no way into the CDN
                    if($this->_options['cdn']['status_txt']!='OK'){
                           $directory =  WP_PLUGIN_URL."/share-and-follow/default/".$size."/sprite-feb-".$size.".png";
                    }
                    else if(PASSCODE== ""||PASSPHRASE==""||PASSTOKEN==""||CDNEXPIRE==""||CDNDIRECTORY==""||CDNSERVER==""){
                           $directory =  WP_PLUGIN_URL."/share-and-follow/default/".$size."/sprite-feb-".$size.".png";
                    }
                    else {
                        $file = CDNDIRECTORY.$this->_options['icon_set']."/".$size."/sprite-feb-".$size.".png";
                        $directory = $this->getCDNurlStamped($file);
                    }
                return $directory;
                }



             public function showUsSupport(){
                ?><div class="support-us"><?php
                if ($this->_options['cdn']==''|| $this->_options['cdn-key'] == '' || (strlen($this->_options['cdn-key'])<>40 ) ){ ?>
                    <h3 style="color:white">Important message from the maker</h3>
                            <p><?php _e('if your feeling lovely and really like this plug-in, then why not blog about it or give us a <a href="http://wordpress.org/extend/plugins/share-and-follow/">rating on the Wordpress site</a>.... help to spread the love.<br /><br />If you wish to give a <a href="http://share-and-follow.com/wordpress-plugin/donations/">donation</a>, thats cool, but we would rather you got something for your money, so why not <a href="admin.php?page=share-and-follow-menu">get the CDN</a> so you can have the extra icons and support as well.','share-and-follow'); ?></p>
                            <p><?php _e('I kindly request that all people that sell this plugin to an end user, get the <a href="admin.php?page=share-and-follow-menu">CDN with extra icon sets</a>.  <b>If just 1 in 10 users get the CDN I will be able to change the subscription to be a lifetime not just a year</b>, increasing your value for money.','share-and-follow' ); ?></p>
                            <p><?php _e('If you are looking for personal support from the maker of share and follow via the website or email, please get the CDN as a method of paying for it.   I trust you understand that my time is not free, just like yours. Or you can ask the community on the wordpress site amongst your peers.','share-and-follow' ); ?></p>
                            <p><?php _e('<b>Getting the CDN will make this message disapear</b>','share-and-follow' ); ?></p>

                    <?php } else { ?>
                        <p><?php _e('Thanks for supporting us by getting the CDN, if you want to go further then please give us a <a href="http://wordpress.org/extend/plugins/share-and-follow/">rating on the Wordpress site</a>.... help to spread the love.','share-and-follow'); ?></p>
                    <?php } ?>
               </div><?php

             }



                function loadLangauge ()
                {
                  //load languages
                  load_plugin_textdomain( 'share-and-follow', false, 'share-and-follow/language' );
                }


              function getCDNcodes(){
                  // check key
                if ((strlen($this->_options['cdn-key']) == 40)&&!empty($this->_options['cdn'])){
                    // get saved data
                 $result = get_transient('cdnrep');
                 // check values
                 if($this->_options['cdn']['status_txt']=="FAIL" || $result === false ){
                 // make call
                     require_once('RemoteConnector.php');
                     $url = "http://api.share-and-follow.com/v1/getCodes.php?url=".
                            trailingslashit(get_bloginfo('url'))."&challange=".
                            md5(trailingslashit(get_bloginfo('url')).$this->_options['cdn-key']);

                     $control = new Pos_RemoteConnector($url);
                     $result = $control->__toString();
                         if($result != false){
                             // save for 14 days
                             set_transient('cdnrep', $result, 60*60*24*14);
                         }

                 }
                 if($result === false){
                        echo $control->getErrorMessage();
                    }else
                    {
                    $replies = json_decode($result, true);

                    if ($replies['status_txt']=='FAIL'){
                        $this->_options['cdn']['status_txt']="FAIL";
                        $this->update_plugin_options();
                    define("PASSCODE", "");
                    define("PASSPHRASE", "");
                    define("PASSTOKEN", "");
                    define("CDNEXPIRE", "");
                    define("CDNDIRECTORY", "");
                    define("CDNSERVER", "");
                        // echo "<div class='errors'>The following error has happened : ".$replies['data']."</div>";
                    }
                    else {

                    $this->_options['cdn']['status_txt']="OK";
                    $this->update_plugin_options();



                    define("PASSCODE", $replies['data']['passcode']);
                    define("PASSPHRASE", $replies['data']['passphrase']);
                    define("PASSTOKEN", $replies['data']['passtoken']);
                    define("CDNEXPIRE", $replies['data']['expire']);
                    define("CDNDIRECTORY", $replies['data']['directory']);
                    define("CDNSERVER", $replies['data']['server']);
                    }
                  }
                }
                else{
                    define("PASSCODE", "");
                    define("PASSPHRASE", "");
                    define("PASSTOKEN", "");
                    define("CDNEXPIRE", "");
                    define("CDNDIRECTORY", "");
                    define("CDNSERVER", "");
                }
            }

            protected function getIpAddress() {
                return (empty($_SERVER['HTTP_CLIENT_IP'])?(empty($_SERVER['HTTP_X_FORWARDED_FOR'])?
                        $_SERVER['REMOTE_ADDR']:$_SERVER['HTTP_X_FORWARDED_FOR']):$_SERVER['HTTP_CLIENT_IP']);
            }




                    function getCDNsets(){

                        if ($this->_options['cdn-key']==''){}
                        else if (strlen($this->_options['cdn-key']) <> 40){
                            echo "<div class='errors'>It looks like you have put in an incorrect CDN API key.</div>";
                        }
                        else {

                        $result = get_transient('cdnsets');
                        if($this->_options['cdn']['status_txt'] == 'FAIL' || $result === false ){

                        require_once('RemoteConnector.php');
                        $url = "http://api.share-and-follow.com/v1/getSets2.php?url=".trailingslashit(get_bloginfo('url'))."&challange=".md5(trailingslashit(get_bloginfo('url')).$this->_options['cdn-key']);
                        $control = new Pos_RemoteConnector($url);

                        $result = $control->__toString();
                        if ($result != false){
                            set_transient('cdnsets',$result,60*60);
                        }


                        }
                        if($result === false)
                        {
                            echo $control->getErrorMessage();
                        }
                        else
                        {

                        $replies = json_decode($result, true);

                        if ($replies['status_txt']=='FAIL'){
                            $this->_options['cdn']['status_txt']='FAIL';
                            $this->update_plugin_options();
                            return "<div class='errors'>The following error has happened : ".$replies['data']."</div>";
                        }
                        else {
                        $this->_options['cdn'] = json_decode($result, true); // jason format
                        $this->update_plugin_options();

                        }
                      }
                    }
                    }
                    //
                    //
                    // what it does
                    function dashboard_widget_function() {

                    $this->show_video('http://player.vimeo.com/video/16185599', "280", "100%");
                    ?><p>We've been adding more icon sets to the CDN, <a href="https://www.share-and-follow.com/cdn-subscription/">Read more</a> about the CDN here or , or subscribe now via paypal</p><br/>Choose your yearly subscription<br /><?php
                      $this->show_paypal_subscribe();
                    }


                    public function show_video($url, $height = "280", $width = "100%"){
                        echo "<iframe src='" . $url . "' width='" . $width . "' height='" . $height . "' frameborder='0'></iframe>";
                    }

                    public function show_paypal_subscribe(){
                        global $current_user; get_currentuserinfo(); ?>
                        <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
                        <input type="hidden" name="cmd" value="_s-xclick">
                        <input type="hidden" name="hosted_button_id" value="28KJ4DA6ZMLGY">
                        <input type="hidden" name="on0" value="Choose your yearly subscription">
                            <select name="os0">
                                <option value="up to 5000 visitors per month.">up to 5000 visitors per month. : €9,99EUR - yearly</option>
                                <option value="up to 10,000 visitors per month.">up to 10,000 visitors per month. : €18,50EUR - yearly</option>
                                <option value="up to 25,000 visitors per month.">up to 25,000 visitors per month. : €45,00EUR - yearly</option>
                                <option value="up to 50,000 visitors per month.">up to 50,000 visitors per month. : €85,00EUR - yearly</option>
                                <option value="up to 100,000 visitors per month.">up to 100,000 visitors per month. : €160,00EUR - yearly</option>
                        </select><br />
                        <input type="hidden" name="currency_code" value="EUR">
                        <input type="hidden" name="on1" value="website address">
                        <input type="hidden" name="os1" maxlength="60" value="<?php echo trailingslashit(get_bloginfo('url')); ?>" >

                        <input type="hidden" name="on2" value="signup email">
                        <input type="hidden" name="os2" maxlength="60" value="<?php echo $current_user->user_email; ?>" >

                        <input type="hidden" name="first_name" value="<?php echo $current_user->user_firstname; ?>">
                        <input type="hidden" name="last_name" value="<?php echo $current_user->user_lastname; ?>">
                        <input type="hidden" name="email" value="<?php echo $current_user->user_email; ?>">
                        <input type="image" src="https://www.paypal.com/en_US/i/btn/btn_subscribeCC_LG_global.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online.">
                        <img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
                        </form>
                        <?php
                    }
                    //
                    //
                    // hook function from action
                    function add_dashboard_widgets() {
                    if ( current_user_can( 'create_users' ) ) {
                         if ($this->_options['cdn']==''|| $this->_options['cdn-key'] == '' || (strlen($this->_options['cdn-key'])<>40 ) ){
                            wp_add_dashboard_widget('dashboard_widget', 'Share and Follow', array($this,'dashboard_widget_function'));
                          }
                        }
                    }



                    function admin_init_shareFollow()
                    {
                       if (isset($_GET['page']) && in_array( $_GET['page'], array('share-and-follow-menu','share-and-follow-sharing','share-and-follow-following','share-and-follow-share-image', 'share-and-follow-css', 'share-and-follow-plugin-support','share-and-follow-reset','share-and-follow-interactive' )) ){
                        /* Register the script. */
                       wp_register_script('colourpicker', WP_PLUGIN_URL . '/share-and-follow/js/colorpicker.js');
                       wp_register_script('adminpages', WP_PLUGIN_URL . '/share-and-follow/js/admin.js');
                       wp_enqueue_script('jquery');
                       wp_enqueue_script('jquery-ui-core');
                       wp_enqueue_script('jquery-ui-tabs');
                       wp_enqueue_script('colourpicker');
                       wp_enqueue_script('adminpages');

                       $this->stylesheet_loader('colorpicker', 'screen');
                       $this->stylesheet_loader('admin', 'screen');
                        }
                    }



        public function my_share_links($args){

            $postid = ($args['id'] != 'self') ? $args['id'] : get_the_ID() ;

            $title=get_the_title($postid);
            if($args['title']!= 'self'){
                $title = $args['title'];
            }
            $perma=get_permalink($postid);

            $echo = $args['echo'];

            $args = array (
                        'page_id' => $postid,
                        'heading' => "0",
                        'direction' => 'row',
                        'page_title'=>$title,
                        'page_link'=>$perma,
                        'echo'=>$echo,

            );
            $additionalSettings = array('size','spacing','share','list_style','email_body_text','share_text','css_images');

            foreach ($this->_allSites as $item => $siteValue){
                if($item=='rss'){}
                else{
                    if(strstr($siteValue['service'],"share")){
                        $adminSettings[]=$item;
                    }
                }
            }
            $adminSettings[]='post_rss';
            foreach ($adminSettings as $item){
                $args[$item] = $this->_options[$item];
                $args[$item.'_share_text'] = $this->_options[$item.'_share_text'];
                $args[$item.'_popup_text'] = $this->_options[$item.'_popup_text'];
            }
            foreach ($additionalSettings as $item){
                $args[$item] = $this->_options[$item];
            }
            $this->social_links($args);
        }

        public function the_share_links(){
                $perma=get_permalink();
                $title=get_the_title();
                $postid = get_the_ID();
                $args = array (
                            'page_id' => $postid,
                            'heading' => "0",
                            'list_style' => "icon_text",
                            'direction' => 'row',
                            'page_title'=>$title,
                            'page_link'=>$perma,
                            'echo'=>'0',
                            'facebook_share_text' => __('Recommend','share-and-follow'),
                            'stumble_share_text'=> __('Stumble uppon','share-and-follow'),
                            'twitter_share_text'=>__('Tweet','share-and-follow'),
                            'delicious_share_text'=>__('Bookmark','share-and-follow'),
                            'digg_share_text'=>__('Digg','share-and-follow'),
                            'reddit_share_text'=>__('Share','share-and-follow'),
                            'hyves_share_text'=>__('Tip','share-and-follow'),
                            'orkut_share_text'=>__('Share','share-and-follow'),
                            'myspace_share_text'=>__('Share','share-and-follow'),
                );
                $this->social_links($args);
        }

        public function my_wp_ecommerce_share_links(){
            $perma=wpsc_the_product_permalink();
            $title=wpsc_the_product_title();
            // $postid = get_the_ID();
            $args = array (
                        'heading' => "0",
                        'direction' => 'row',
                        'page_title'=>$title,
                        'page_link'=>$perma,
                        'echo'=>'0',

            );
            $adminSettings = array('size','spacing','share','list_style','email_body_text','share_text','css_images');
            foreach ($adminSettings as $item){
                $args[$item] = $this->_options[$item];
            }

            foreach ($this->_allSites as $item => $siteValue){
                if($item=='rss'){}
                else{
                    if(strstr($siteValue['service'],"share")){
                        $shareIcons[]=$item;
                    }
                }
            }
            $shareIcons[]='post_rss';
            foreach ($shareIcons as $item){
                $args[$item] = $this->_options[$item];
                $args[$item.'_share_text'] = $this->_options[$item.'_share_text'];
                $args[$item.'_popup_text'] = $this->_options[$item.'_popup_text'];
            }
            foreach ($adminSettings as $item){
                $args[$item] = $this->_options[$item];
            }

            $this->social_links($args);
        }


        public function social_links($args =  array() ){
                $defaults = $this->_defaults;
                $defaults['page_id']=0;
                $defaults['heading'] = "1";
                $defaults['share']='yes';
                $defaults['page_excerpt']=get_bloginfo('description');
                $defaults['page_title']='';
                $defaults['page_link']='';
                $defaults['echo']='0';
                $defaults['css_images']='no';
                $defaults['size'] = "16";
                $defaults['list_style'] = "icon_text";
                
                $args = wp_parse_args( $args, $defaults  );
                extract( $args, EXTR_SKIP );
                

                if ($page_id != 0){ $page_excerpt = substr(strip_tags(get_the_content($page_id)),0,320); }
                if (empty($page_title) && empty($page_link)){
                   $page_title = get_bloginfo('name');
                   if(is_category() || is_archive() || is_tag() || is_month()) {
                           if ( is_category() || is_archive()) {
                               $category = get_the_category();
                               $page_title = $page_title."&nbsp;|&nbsp;".$category[0]->cat_name;
                            }
                           if ( is_tag() ) {
                                $page_title = get_bloginfo('name')."&nbsp;|&nbsp;".single_tag_title("", false);
                            }
                       $page_link = $this->currentPageURI();
                       $page_id = 0;
                   }
                   else if(is_front_page()) {
                         $page_title = get_bloginfo('name');
                         $page_link = get_option('home');
                         $page_id = 0;
                   }
                   else{
                        $page_title = get_the_title($page_id);
                        $page_link = get_permalink($page_id);
                   }
                }
            $html='';
            if($heading==1){
                     $html = "<h2 class=\"clean\" >". _e('Share this ');
                     if ($page_id==0){$html .='blog';}
                     else {$html .='page';}
                     $html.= "</h2>";
            }
            if ($css_images=='yes'){$html .= "<ul class=\"socialwrap size".$size." ".$direction."\">";}
            if ($css_images=='no'){$html .= "<ul class=\"socialwrap ".$direction."\">";}
            if($share=='yes'){$html.="<li class=\"".$list_style." share\">".$share_text."</li>";}

                foreach ($this->_allSites as $item => $siteValue){
                        if(strstr($siteValue['service'], "share")){
                        if ($args[$item]=="yes"){
                            if ($item!='email'){
                            switch ($item){
                                case 'print':
                                case 'bookmark':
                                    $shareLinks=array('css_class'=>$item,'page_id'=>$page_id,'page_link'=>$page_link,  'list_style'=>$list_style, 'target'=>"_self",
                                    'page_title'=>$page_title, 'css_images'=>$css_images, 'size'=>$size, 'image_name'=>$item, 'share_text'=>$args[$item.'_share_text'], 'popup_text'=>$args[$item.'_popup_text'],);
                                    break;
                                case 'post_rss':
                                    $shareLinks=array('css_class'=>'rss','page_id'=>$page_id,'page_link'=>$page_link,  'list_style'=>$list_style, 'target'=>"_self",'special'=>'rss',
                                    'page_title'=>$page_title, 'css_images'=>$css_images, 'size'=>$size, 'image_name'=>'rss', 'share_text'=>$args[$item.'_share_text'], 'popup_text'=>$args[$item.'_popup_text'],);
                                    break;
                                case 'twitter':
                                    $shareLinks=array('css_class'=>$item,'page_id'=>$page_id,'page_link'=>$page_link,  'list_style'=>$list_style, 'target'=>"_blank",'special'=>'twitter',
                                    'page_title'=>$page_title, 'css_images'=>$css_images, 'size'=>$size, 'image_name'=>$item, 'share_text'=>$args[$item.'_share_text'], 'popup_text'=>$args[$item.'_popup_text'],);
                                    break;
                                default:
                                $shareLinks=array('css_class'=>$item, 'page_id'=>$page_id, 'page_link'=>$page_link, 'list_style'=>$list_style, 'page_excerpt'=>$page_excerpt,
                                'page_title'=>$page_title, 'css_images'=>$css_images, 'size'=>$size, 'image_name'=>$item, 'share_text'=>$args[$item.'_share_text'], 'popup_text'=>$args[$item.'_popup_text'],);
                            }
                             $html.= $this->makeShareLink($shareLinks);
                          }
                        }
                      }
                    }
                    if($email=='yes'){
                        $args=array('css_class'=>'email','page_id'=>$page_id,'page_link'=>$page_link, 'target'=>"_self", 'special'=>"email", 'email_body'=>$email_body_text, 'list_style'=>$list_style,
                                    'page_title'=>$page_title, 'css_images'=>$css_images, 'size'=>$size, 'image_name'=>'email', 'share_text'=>$email_share_text, 'popup_text'=>$email_popup_text);
                        $html.= $this->makeShareLink($args);
                        }
            $html .= "</ul>";

            if ($direction=='row'){$html .= "<div class=\"clean\"></div> ";}
            if ($echo=='0'){
                echo $html;
            }
            else {
                return $html;
            }



        }

        public function follow_links($args = array()){
                $defaults = array(
                                'size' => "16",
                                'list_style' => 'text_replacement',
                                'icon_set'=>'default',
                                'direction' => 'down',
                                'iconset'=>'default',
                                'word_value'=>'follow',
                                'word_text'=>__('follow:','share-and-follow'),
                                
                                'page_title'=>'',
                                'page_link'=>'',
                                'echo'=>'0',
                                'words'=>'long',
                                'sidebar_tab'=>'tab',
                                'add_follow_text'=>'true',
                                'css_images'=>'no',
                                'follow_location'=>'right',
                            );
                $args = wp_parse_args( $args, $defaults );
                extract( $args, EXTR_SKIP );
            if ($list_style=='text_replace' && $sidebar_tab=='tab' ){$css_images='no';}
            $html ='';
            $rss_link = get_bloginfo($this->_options['rss_style']);
            if ($sidebar_tab=='tab'){$html .="<div id=\"follow\" class=\"".$follow_location."\">";}
                if ($css_images=='yes'){$html .= "<ul class=\"".$sidebar_tab." size".$size." ".$direction."\">";}
                if ($css_images=='no'){$html .= "<ul class=\"".$sidebar_tab." ".$direction."\">";}
                if($add_follow_text=='true') {$html .= "<li class=\"".$word_value."\"><img src=\"".WP_PLUGIN_URL."/share-and-follow/images/blank.gif\"  alt=\"".$word_text."\"/><span>".$word_text."</span></li>";}


                foreach ($this->_allSites as $item => $siteValue){
                        if(strstr($siteValue['service'], "follow")){
                            if ($item == 'rss' && $args['follow_'.$item]=="yes"){
                                      $followLinks = array('icon_set'=>$icon_set,'css_class'=>$item,'follow_text'=>$args[$item.'_text'], 'follow_popup_text'=>$args[$item.'_text'], 'size'=>$size,
                                       'css_images'=>$css_images,'image_name'=>$item ,'rel'=>'nofollow me','target'=>'_blank','follow_url'=>$rss_link,'list_style'=>$list_style);
                                      $html.=$this->makeFollowLink($followLinks);
                                }
                            if (isset ($args['follow_'.$item])){
                                if ($args['follow_'.$item]=="yes"&&!empty($args[$item.'_link'])){
                                       $followLinks = array('icon_set'=>$icon_set,'css_class'=>$item,'follow_text'=>$args[$item.'_text'], 'follow_popup_text'=>$args[$item.'_text'], 'size'=>$size,
                                       'css_images'=>$css_images,'image_name'=>$item ,'rel'=>'nofollow me','target'=>'_blank','follow_url'=>$args[$item.'_link'],'list_style'=>$list_style);
                                      $html.=$this->makeFollowLink($followLinks);
                                }
                            }
                        }
                    }
            $html .= "</ul>";
            if ($direction=='row'){$html .= "<div class=\"clean\"></div> ";}
            if ($sidebar_tab=='tab'){$html .="</div>";}
            if ($echo=='0'){echo $html;}
            else {return $html;}
        }


        public function my_follow_links(){
                $adminSettings = array ('list_style'=>$this->_options['follow_list_style'],    'size'=>$this->_options['tab_size'],
                        'add_follow_text'=>$this->_options['add_follow_text'],                 'css_images'=>'yes',
                        'spacing'=>$this->_options['spacing'],                                  'add_content'=>'true',
                        'word_value'=>$this->_options['word_value'],                            'word_text'=>$this->_options['word_text'],
                        'add_follow'=>$this->_options['add_follow'],                            'add_css'=>$this->_options['add_css'],
                        'follow_rss'=>$this->_options['follow_rss'],                            'rss_text'=>$this->_options['rss_link_text'],
                        'border_color'=>$this->_options['border_color'],                        'sidebar_tab'=>'',
                        'follow_location'=>'none',                                          'list_style' => 'iconOnly',
                );
            foreach ($adminSettings as $item => $settings){
                $args[$item] = $settings;
            }

            foreach ($this->_allSites as $item => $siteValue){
                    if(strstr($siteValue['service'], "follow")){
                    $args['follow_'.$item] = $this->_options['follow_'.$item];
                    $args[$item.'_link'] = $this->_options[$item.'_link'];
                    $args[$item.'_link_text'] = $this->_options[$item.'_link_text'];
                    }
                }
              $this->follow_links($args);

            }

        public function get_the_share_links(){
                $perma=get_permalink();
                $title=get_the_title();
                $postid = get_the_ID();
                $args = array (
                            'page_id' => $postid,
                            'heading' => "0",
                            'list_style' => "icon_text",
                            'direction' => 'row',
                            'page_title'=>$title,
                            'page_link'=>$perma,
                            'echo'=>'1',
                            'facebook_share_text' => __('Recommend','share-and-follow'),
                            'stumble_share_text'=> __('Stumble uppon','share-and-follow'),
                            'twitter_share_text'=>__('Tweet','share-and-follow'),
                            'delicious_share_text'=>__('Bookmark','share-and-follow'),
                            'digg_share_text'=>__('Digg','share-and-follow'),
                            'reddit_share_text'=>__('Share','share-and-follow'),
                            'hyves_share_text'=>__('Tip','share-and-follow'),
                            'orkut_share_text'=>__('Share','share-and-follow'),
                            'myspace_share_text'=>__('Share','share-and-follow'),
                );
                $this->social_links($args);
        }


              function show_interactive_links($args= array()){

    $defaults= array(
        'facebook'=>'yes',
        'twitter'=>'yes',
        'stumble'=>'no',
        'pinterest'=>'no',
        'googleplus'=>'yes',
        'style'=>'box_count',
        'facebook_size'=>'50',
        'twitter_size'=>'65',
        'stumble_size'=>'65',
    );
    $args = wp_parse_args( $args, $defaults );
    extract( $args, EXTR_SKIP );
    $perma=get_permalink();
    $postid = get_the_ID();
    $title=get_the_title();
    switch ($style){
        case 'box_count':
            $tweet_look = 'vertical';
            $like_look = 'box_count';
            $stumble_look = '5';
            $google_size = 'tall';
            $google_style = 'bubble';
            $pinterest_style = 'vertical';
            break;
        case 'side_count':            
            $tweet_look = 'horizontal';
            $like_look = 'button_count';
            $stumble_look = '1';
            $google_size = 'medium';
            $google_style = 'inline';
            $pinterest_style = 'horizontal';
            break;
    }
    $html = '';
   
    if($twitter=='yes'){
    $html.= $this->interactive_holder($this->doTweetiFrame($postid, $perma, '', $title, $tweet_look, $tweet_size, $faces=''), 'show_interactive');
    }
    if($facebook=="yes"){
    $html.=  $this->interactive_holder($this->doLikeiFrame($postid, $perma, $like_look,$facebook_size), 'show_interactive');
    }
    if($stumble=='yes'){
    $html.= $this->interactive_holder($this->doStumbleScript($postid, $perma, $stumble_look, $size), 'show_interactive');
    }
    if($googleplus=='yes'){
    $html.= $this->interactive_holder($this->doGooglePlusButton($postid, $perma, $google_style, $google_size), 'show_interactive ' .  $google_size);
    }
    if($pinterest=='yes'){
    $html.= $this->interactive_holder($this->doPinterestButton($postid, $perma, $pinterest_style), 'show_interactive ');
    }
echo $html;
}



        // menu admin pages

function cache_warning_check(){
if(WP_CACHE === FALSE ){
$output = "<div class='updated'><b>Important message:</b><br /><br/>";
$output .= "If you want to make this plugin perform much faster, then add <code>WP_CACHE</code> to your <code>wp-config.php</code>.  Put it at the end just before <code>/* That's all, stop editing! Happy blogging. */</code><br/>This will add caching for parts of the plugin.  Vital if you want it to load faster, especially for anybody running the CDN.<br/>";
$output .= "like this:<br />";
$output .= "<pre><code>define('WP_CACHE', true);
/* That's all, stop editing! Happy blogging. */</code></pre></div>";
echo $output;
}
}

              function printAdminPage() {
                  if(is_admin() && $_GET['page']== 'share-and-follow-menu'){
                        require_once('admin-page.php');
                  }
             }

             function resetAdminPage() {
                 if(is_admin() && $_GET['page']== 'share-and-follow-reset' ){
                        require_once('admin-reset.php');
                 }
             }

             function cssAdminPage() {
                 if(is_admin()  && $_GET['page']== 'share-and-follow-css'  ){
                        require_once('admin-css.php');
                 }
             }

             function shareAdminPage() {
                 if(is_admin() && $_GET['page']== 'share-and-follow-sharing'){
                        require_once('admin-share.php');
                 }
             }

             function followAdminPage() {
                 if(is_admin() && $_GET['page']== 'share-and-follow-following'){
                        require_once('admin-follow.php');
                 }
             }

             function interactiveAdminPage() {
                 if(is_admin() && $_GET['page']== 'share-and-follow-interactive'){
                        require_once('admin-interactive.php');
                 }
             }

             function imageAdminPage() {
                 if(is_admin() && $_GET['page']== 'share-and-follow-share-image'){
                        require_once('admin-image.php');
                 }
             }

             function pluginAdminPage() {
                 if(is_admin() && $_GET['page']== 'share-and-follow-plugin-support' ){
                        require_once('admin-plugin.php');
                 }
             }
        // end admin pages


             function google_plus_footer_code(){

                 echo "<script type='text/javascript'>
                    window.___gcfg = {lang: '"  .  $this->_options['googleplus_lang'] .  "'};
                  (function() {
                    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
                    po.src = 'https://apis.google.com/js/plusone.js';
                    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
                  })();
                </script>";

             }

              function stumble_footer_code(){
                 echo " <script type='text/javascript'>
                        (function() {
                         var li = document.createElement('script'); li.type = 'text/javascript'; li.async = true;
                          li.src = 'https://platform.stumbleupon.com/1/widgets.js';
                          var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(li, s);
                     })();
                     </script>";
             }

             function pinterest_footer_code(){
                echo " <script type='text/javascript'>
                        (function() {
                            window.PinIt = window.PinIt || { loaded:false };
                            if (window.PinIt.loaded) return;
                            window.PinIt.loaded = true;
                            function async_load(){
                                var s = document.createElement('script');
                                s.type = 'text/javascript';
                                s.async = true;
                                if (window.location.protocol == 'https:')
                                    s.src = 'https://assets.pinterest.com/js/pinit.js';
                                else
                                    s.src = 'http://assets.pinterest.com/js/pinit.js';
                                var x = document.getElementsByTagName('script')[0];
                                x.parentNode.insertBefore(s, x);
                            }
                            if (window.attachEvent)
                                window.attachEvent('onload', async_load);
                            else
                                window.addEventListener('load', async_load, false);
                        })();
                        </script> ";
             }

             function pin_it(){

if($this->_options['pinterest_images']=='yes'){
                 echo "<script type='text/javascript' src='" .  WP_PLUGIN_URL ."/share-and-follow/js/add-pins-to-images.js'></script>";
                 ?>
                <script type='text/javascript'>

                        var headID = document.getElementsByTagName("head")[0];
                        var cssNode = document.createElement('link');
                        cssNode.type = 'text/css';
                        cssNode.rel = 'stylesheet';
                        cssNode.href = '<?php echo WP_PLUGIN_URL; ?>/share-and-follow/css/pinterest.css';
                        cssNode.media = 'screen';
                        headID.appendChild(cssNode);

                  jQuery(document).ready(function() {

<?php if($this->_options['pins_to_add']=='all'){ ?>
              add_pins();
<?php }else{ ?>
              add_pins('class');
<?php } ?>

                });
                </script>
  


                 <?php
                   }
             }


        }
}

require_once('share-widget.php');   //  includes the code for the share widget
require_once('follow-widget.php');  //  includes the code for the follow widget
require_once('saf-functions.php');      //  includes the functions social_links(), follow_links() and share_links() and any needed items


//
//  setup new instance of plugin
if (class_exists("ShareAndFollow")) {$cons_shareFollow = new ShareAndFollow();}
//Actions and Filters
if (isset($cons_shareFollow)) {
    //Initialize the admin panel
        if (!function_exists("shareFollow_ap")) {
	function shareFollow_ap() {
		global $cons_shareFollow;
		if (!isset($cons_shareFollow)) {
			return;
		}
		if (function_exists('add_options_page')) {
                   // add_options_page('Share and Follow', 'Share and Follow', 'manage_options', basename(__FILE__), array(&$cons_shareFollow, 'printAdminPage'));
                    add_menu_page('Share &amp; Follow', 'Share &amp; Follow', 'manage_options', 'share-and-follow-menu',  array(&$cons_shareFollow, 'printAdminPage'), WP_PLUGIN_URL.'/share-and-follow/images/icon.png');
                    add_submenu_page('share-and-follow-menu', 'Auto added share icons', 'Sharing', 'manage_options', 'share-and-follow-sharing', array(&$cons_shareFollow, 'shareAdminPage'));
                    add_submenu_page('share-and-follow-menu', 'Auto added share icons', 'Following', 'manage_options', 'share-and-follow-following', array(&$cons_shareFollow, 'followAdminPage'));
                    add_submenu_page('share-and-follow-menu', 'Auto added share icons', 'Interactive share buttons', 'manage_options', 'share-and-follow-interactive', array(&$cons_shareFollow, 'interactiveAdminPage'));
                    add_submenu_page('share-and-follow-menu', 'Auto added share icons', 'Share image', 'manage_options', 'share-and-follow-share-image', array(&$cons_shareFollow, 'imageAdminPage'));
                    add_submenu_page('share-and-follow-menu', 'Auto added share icons', 'CSS style &amp; configuration', 'manage_options', 'share-and-follow-css', array(&$cons_shareFollow, 'cssAdminPage'));
                    add_submenu_page('share-and-follow-menu', 'Auto added share icons', 'Plugin support', 'manage_options', 'share-and-follow-plugin-support', array(&$cons_shareFollow, 'pluginAdminPage'));
                    add_submenu_page('share-and-follow-menu', 'Auto added share icons', 'Reset defaults', 'manage_options', 'share-and-follow-reset', array(&$cons_shareFollow, 'resetAdminPage'));

		}
	}
}
//Actions
        add_action('admin_menu', 'shareFollow_ap',1); //admin page
	add_action('init', array(&$cons_shareFollow, 'getCDNcodes'),1); // adds items into head section
         add_action('wp_head', array(&$cons_shareFollow, 'addHeaderCode'),1); // adds items into head section
        add_action('wp_head', array(&$cons_shareFollow, 'addHeaderCodeEndBlock'),10); // adds items into head section
        add_action('wp_footer',array(&$cons_shareFollow, 'show_follow_links'),1); // adds follow links
        add_action('wp_footer',array(&$cons_shareFollow, 'pin_it'),1); // adds pin it script
  
        add_action('widgets_init',array(&$cons_shareFollow, 'load_widgets'),1); // loads widgets
        // add_action('activate_share-and-follow/share-and-follow.php',  array(&$cons_shareFollow, 'init'),1); // plugin activation (meeds to be tested)
        add_action ('init',array(&$cons_shareFollow, 'loadLangauge'),1);  // add languages
        add_action ('admin_init',array(&$cons_shareFollow, 'admin_init_shareFollow'));  // add admin page scripts
        add_action ('init',array(&$cons_shareFollow, 'plugin_support'),10);  // add plugin support
//Filters
       add_filter('the_content', array(&$cons_shareFollow, 'addContent'),10); // adds the icons automatically to the content
// short codes
        add_shortcode('share_links', array(&$cons_shareFollow,'share_func'),1); // setup shortcode [share_links]
        add_shortcode('interactive_links', array(&$cons_shareFollow,'interactive_func'),1); // setup shortcode [interactive_links]

// add video to dashboard
        add_action('wp_dashboard_setup',array(&$cons_shareFollow,'add_dashboard_widgets'),1  );
//
}
?>
