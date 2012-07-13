<?php

/**
 * BuddyPress - Users Home
 *
 * @package BuddyPress
 * @subpackage bp-default
 */
?>

<?php get_header( 'buddypress' ); ?>

	<div id="content">
		<div class="padder">

			<?php do_action( 'bp_before_member_home_content' ); ?>

			<div id="item-header" role="complementary">

				<?php locate_template( array( 'members/single/member-header.php' ), true ); ?>

			</div><!-- #item-header -->
			
            <!--by jaspreet 5 June 2012-->                                    
                        
            <?php 
				global $bp;
				$link = $bp->displayed_user->domain . $user_nav_item['link'];
			 ?>
            <div>
            	<?php global $current_user;	?>
                
                <div style="width:100%; background:#EAEAEA; padding: 5px 10px; margin-bottom:5px;">
                	<a href="<?php echo $link.'album';?>">Album</a>
                </div>
               <?php if (is_user_logged_in() && bp_is_my_profile()) { ?>                 
                <div style="width:100%; margin-bottom:10px;">                
                	 <span style="width:45%; padding-left:2%; padding-right:3%;"><a href="<?php echo $link.'album/pictures';?>">My files</a></span>
                     <span style="width:50%;"><a href="<?php echo $link.'album/upload';?>">Upload file</a></span>               	
                </div> 
                <?php } ?>                               
            </div>            
            <!--by jaspreet 5 June 2012-->
            
            
            
             <!--by Jaspreet 8 June 2012-->            
            <div class="picture-gallery">
            
            <?php 				           
				global $wpdb;								
								
				
				$member_explode = explode("members/",$_SERVER['REQUEST_URI']);
				$member_explode1 = explode("/",$member_explode[1]);				
										
				$user_id_query = "select * from wp_users where user_login = '".$member_explode1[0]."'";
				$fetch_user_id_query = $wpdb->get_results($user_id_query);
				
				
				$myQuery = "select * from wp_bp_album where owner_id = '".$fetch_user_id_query[0]->ID."'";
				$myQueryResult = $wpdb->get_results($myQuery);
				
				
					foreach($myQueryResult as $mQR)
					{
					?>                
					<div class="picture-thumb-box">
						
							<a class="picture-thumb" href="<?php echo $bp->displayed_user->domain.'album/picture/'.$mQR->id;?>">                  
						
						<?php
						if($mQR->pic_org_url == '' && $mQR->embed_url!='')
						{
						?>
							<img width="100" height="100" src='<?php echo get_template_directory_uri(); ?>/images/video_dummy_image.jpg' alt="Click Here to See Video"/></a>
						 <?php 
						 }
						 else if(is_numeric(strpos($mQR->pic_org_url,"http")))
						 {					 						 
						 ?>                       	
							<img width="100" height="100" src='<?php echo $mQR->pic_thumb_url;?>' alt="Click Here"/>
						<?php						
						 }
						 else
						 {
							
							
							
							if($_SERVER['HTTP_HOST'] == "gypsy-generation.com") 
							{
							
									if(is_numeric(strpos($mQR->pic_org_url,"mp3")) || is_numeric(strpos($mQR->pic_org_url,"m4a")) || is_numeric(strpos($mQR->pic_org_url,"oga")) || is_numeric(strpos($mQR->pic_org_url,"flv")) || is_numeric(strpos($mQR->pic_org_url,"mp4")) || is_numeric(strpos($mQR->pic_org_url,"mov")) || is_numeric(strpos($mQR->pic_org_url,"avi")) || is_numeric(strpos($mQR->pic_org_url,"m4v")) || is_numeric(strpos($mQR->pic_org_url,"f4v")))
									{
									?>
									<img width="100" height="100" src='<?php echo get_template_directory_uri(); ?>/images/video_dummy_image.jpg' alt="Click Here"/></a>
									
									<?php
									}
									else
									{			 
									 ?>                       	
										<img width="100" height="100" src='http://gypsy-generation.com/blog<?php echo $mQR->pic_thumb_url;?>' alt="Click Here"/>
									
									<?php
									}
								}
								else
								{
									if(is_numeric(strpos($mQR->pic_org_url,"mp3")) || is_numeric(strpos($mQR->pic_org_url,"m4a")) || is_numeric(strpos($mQR->pic_org_url,"oga")) || is_numeric(strpos($mQR->pic_org_url,"flv")) || is_numeric(strpos($mQR->pic_org_url,"mp4")) || is_numeric(strpos($mQR->pic_org_url,"mov")) || is_numeric(strpos($mQR->pic_org_url,"avi")) || is_numeric(strpos($mQR->pic_org_url,"m4v")) || is_numeric(strpos($mQR->pic_org_url,"f4v")))
									{
									?>
									<img width="100" height="100" src='<?php echo get_template_directory_uri(); ?>/images/video_dummy_image.jpg' alt="Click Here"/></a>
									
									<?php
									}
									else
									{	
								?>
									<img width="100" height="100" src='http://192.168.1.200/gypsy/blog<?php echo $mQR->pic_thumb_url;?>' alt="Click Here"/>
								<?php
									}
								} 
							}
							?>
							</a>
						
						<!--title below thumb-->
							<a class="picture-title" href="<?php echo $bp->displayed_user->domain.'album/picture/'.$mQR->id;?>"><?php echo substr($mQR->title,0,13);?></a>                                        
						<!--title below thumb-->
						
					</div>
					<?php	
					}				
			?>
			
            </div>
            <!--by Jaspreet 8 June 2012-->
            
            
			<div id="item-nav">            	
                
				<div class="item-list-tabs no-ajax" id="object-nav" role="navigation">
					<ul>

						<?php bp_get_displayed_user_nav(); ?>

						<?php do_action('bp_member_options_nav' ); ?>


					</ul>
				</div>
			</div><!-- #item-nav -->

			<div id="item-body">

				<?php do_action( 'bp_before_member_body' );

				if ( bp_is_user_activity() || !bp_current_component() ) :
					locate_template( array( 'members/single/activity.php'  ), true );

				 elseif ( bp_is_user_blogs() ) :
					locate_template( array( 'members/single/blogs.php'     ), true );

				elseif ( bp_is_user_friends() ) :
					locate_template( array( 'members/single/friends.php'   ), true );

				elseif ( bp_is_user_groups() ) :
					locate_template( array( 'members/single/groups.php'    ), true );

				elseif ( bp_is_user_messages() ) :
					locate_template( array( 'members/single/messages.php'  ), true );

				elseif ( bp_is_user_profile() ) :
					locate_template( array( 'members/single/profile.php'   ), true );

				elseif ( bp_is_user_forums() ) :
					locate_template( array( 'members/single/forums.php'    ), true );

				elseif ( bp_is_user_settings() ) :
					locate_template( array( 'members/single/settings.php'  ), true );
					

				// If nothing sticks, load a generic template
				else :
					locate_template( array( 'members/single/plugins.php'   ), true );

				endif;
				
				
				do_action( 'bp_after_member_body' ); ?>

			</div><!-- #item-body -->

			<?php do_action( 'bp_after_member_home_content' ); ?>

		</div><!-- .padder -->
	</div><!-- #content -->

<?php get_sidebar( 'buddypress' ); ?>
<?php get_footer( 'buddypress' ); ?>
