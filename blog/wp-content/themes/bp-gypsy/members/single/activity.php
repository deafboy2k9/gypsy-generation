<?php

/**
 * BuddyPress - Users Activity
 *
 * @package BuddyPress
 * @subpackage bp-default
 */

?>

<?php do_action( 'bp_before_member_home_content' ); ?>

			<div id="item-header" role="complementary" class="clearfix row">

				<?php locate_template( array( 'members/single/member-header.php' ), true ); ?>

			</div><!-- #item-header -->
			
			<?php if(bp_member_profile_data( 'field=You in 700 characters' )): ?>
			
			<div id="item-about" class="row">
				<?php bp_member_profile_data( 'field=You in 700 characters' ); ?>
			</div>

			<?php endif; ?>
			
			<ul class="picture-gallery row clearfix">
            
            <?php 				           
				global $wpdb;								
								
				
				$member_explode = explode("members/",$_SERVER['REQUEST_URI']);
				$member_explode1 = explode("/",$member_explode[1]);				
										
				$user_id_query = "select * from wp_users where user_login = '".$member_explode1[0]."'";
				$fetch_user_id_query = $wpdb->get_results($user_id_query);
				
				
				$myQuery = "select * from wp_bp_album where owner_id = '".$fetch_user_id_query[0]->ID."'";
				$myQueryResult = $wpdb->get_results($myQuery);
				
				    $i = 0;
				
					foreach($myQueryResult as $mQR)
					{
					    
					    $class = (($i % 3) != 0) ? 'offset-by-one' : '';
					?>                
					
							
							
							<li class="columns three alpha <?php echo $class; ?>">
							
							<a href="<?php echo $bp->displayed_user->domain.'album/picture/'.$mQR->id;?>">
							
							<h4><?php echo $mQR->title;?></h4>                  
    						<div class="gallery-container">
        						<div class="gallery-likes">
        							asdlfkjadslj
        						</div>
    						<?php
    						if($mQR->pic_org_url == '' && $mQR->embed_url!='')
    						{
    						?>
    							<img src='<?php echo get_template_directory_uri(); ?>/images/video_dummy_image.jpg' alt="Click Here to See Video"/></a>
    						 <?php 
    						 }
    						 else if(is_numeric(strpos($mQR->pic_org_url,"http")))
    						 {					 						 
    						 ?>                       	
    							<img  src='<?php echo $mQR->pic_thumb_url;?>' alt="Click Here"/>
    						<?php						
    						 }
    						 else
    						 {
    							
    							
    							
    							if($_SERVER['HTTP_HOST'] == "gypsy-generation.com") 
    							{
    							
    									if(is_numeric(strpos($mQR->pic_org_url,"mp3")) || is_numeric(strpos($mQR->pic_org_url,"m4a")) || is_numeric(strpos($mQR->pic_org_url,"oga")) || is_numeric(strpos($mQR->pic_org_url,"flv")) || is_numeric(strpos($mQR->pic_org_url,"mp4")) || is_numeric(strpos($mQR->pic_org_url,"mov")) || is_numeric(strpos($mQR->pic_org_url,"avi")) || is_numeric(strpos($mQR->pic_org_url,"m4v")) || is_numeric(strpos($mQR->pic_org_url,"f4v")))
    									{
    									?>
    									<img src='<?php echo get_template_directory_uri(); ?>/images/video_dummy_image.jpg' alt="Click Here"/></a>
    									
    									<?php
    									}
    									else
    									{			 
    									 ?>                       	
    										<img src='http://gypsy-generation.com/blog<?php echo $mQR->pic_thumb_url;?>' alt="Click Here"/>
    									
    									<?php
    									}
    								}
    								else
    								{
    									if(is_numeric(strpos($mQR->pic_org_url,"mp3")) || is_numeric(strpos($mQR->pic_org_url,"m4a")) || is_numeric(strpos($mQR->pic_org_url,"oga")) || is_numeric(strpos($mQR->pic_org_url,"flv")) || is_numeric(strpos($mQR->pic_org_url,"mp4")) || is_numeric(strpos($mQR->pic_org_url,"mov")) || is_numeric(strpos($mQR->pic_org_url,"avi")) || is_numeric(strpos($mQR->pic_org_url,"m4v")) || is_numeric(strpos($mQR->pic_org_url,"f4v")))
    									{
    									?>
    									<img src='<?php echo get_template_directory_uri(); ?>/images/video_dummy_image.jpg' alt="Click Here"/></a>
    									
    									<?php
    									}
    									else
    									{	
    								?>
    									<img src='http://192.168.1.200/gypsy/blog<?php echo $mQR->pic_thumb_url;?>' alt="Click Here"/>
    								<?php
    									}
    								} 
    							}
    							?>
    							</div>
    							</a>
							</li>
						
					<?php	
					$i++;
					}				
			?>
			
            </ul>

			<div id="item-nav" class="column alpha omega clearfix">
				<div class="item-list-tabs no-ajax" id="object-nav" role="navigation">
					<ul>
						<?php bp_get_displayed_user_nav(); ?>

						<?php do_action( 'bp_member_options_nav' ); ?>

					</ul>
				</div>
			</div><!-- #item-nav -->

			<div id="item-body" class="row column alpha omega clearfix">

				<?php do_action( 'bp_before_member_body' ); ?>

                <div class="item-list-tabs no-ajax" id="subnav" role="navigation">
                	<ul>
                		<?php bp_get_options_nav() ?>
                	</ul>
                </div><!-- .item-list-tabs -->

                <div class="activity" role="main">

	                <?php locate_template( array( 'activity/activity-loop.php' ), true ); ?>

				</div><!-- .activity -->

            <?php do_action( 'bp_after_member_activity_content' ); ?>
            <?php do_action( 'bp_after_member_body' ); ?>

			</div><!-- #item-body -->

<?php do_action( 'bp_after_member_home_content' ); ?>

