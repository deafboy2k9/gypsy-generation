<?php get_header() ?>
	<div id="content">
		<div class="padder">
			<div id="item-header">
				<?php locate_template( array( 'members/single/member-header.php' ), true ) ?>
			</div>
            
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
                
                 <?php if ( is_user_logged_in() && bp_is_my_profile()) { ?>
                    <div style="width:100%; margin-bottom:10px;">                
                         <span style="width:45%; padding-left:2%; padding-right:3%;"><a href="<?php echo $link.'album/pictures';?>">My files</a></span>
                         <span style="width:50%;"><a href="<?php echo $link.'album/upload';?>">Upload file</a></span>               	
                    </div>
                 <?php } ?>
                 
            </div>
            
            <!--by jaspreet 5 June 2012-->
            
			
			<div id="item-body">
				<div class="item-list-tabs no-ajax" id="subnav">
					<ul>
						<?php
							if(strpos($_SERVER['REQUEST_URI'],"album") == false)
							{ 
								bp_get_options_nav();
							}	
								?>
					</ul>
				</div>
					<?php if ( bp_album_has_pictures() ) : ?>					
				<div class="picture-pagination">
					<?php bp_album_picture_pagination(); ?>	
				</div>			
					
				<div class="picture-gallery">												
						<?php 
							$listvideo = 1;
						while ( bp_album_has_pictures() ) : bp_album_the_picture(); ?>							
                            	<?php
                            	/*
									***************By Jaspreet 30 May 2012******************
								*/
								?>                            
								<div class="picture-thumb-box">
                                <?php
								$is_externalurl = explode("blog",bp_album_picture_thumb_url());
								$isExternalURL = strpos($is_externalurl[1],'http');
																
								if(is_numeric($isExternalURL))
								{																		
									?>              
                                <a class="picture-thumb"  href="<?php bp_album_picture_url(); ?>" rel="prettyPhoto[mixed]"><img width="100" height="100" alt="External Link" src="<?php echo $is_externalurl[1];?>"></a>                                                                                                                                																
								<?php	
									
								}
								else if(stripos(bp_album_picture_thumb_url(),"jpg") || stripos(bp_album_picture_thumb_url(),"jpeg") || stripos(bp_album_picture_thumb_url(),"png") || stripos(bp_album_picture_thumb_url(),"gif")) 
								{
								
								?>
								<a rel="prettyPhoto[mixed]" href="<?php echo bp_album_picture_thumb_url() ?>" class="picture-thumb"><img src='<?php echo bp_album_picture_thumb_url() ?>' /></a>
                                <?php 
								}
								else
								{									
								?>
                               
                                <a rel="prettyPhoto[mixed]" href="<?php echo bp_album_picture_url(); ?>" class="picture-thumb"><img width="100" height="100" src='<?php echo get_template_directory_uri(); ?>/images/video_dummy_image.jpg' alt="See Video"/></a>
                                <?php	
								}
								?>
                                
                                <?php
                            	/*
									***************By Jaspreet 30 May 2012******************
								*/
								?>
                                
								<a href="<?php bp_album_picture_url(); ?>"  class="picture-title"><?php bp_album_picture_title_truncate() ?></a>	
								</div>
												   
					

					
						<?php 
							$listvideo++;
						 	endwhile; 
						?>
				</div>					
					<?php else : ?>
					
				<div id="message" class="info">
					<p><?php echo bp_word_or_name( __('No pics here, show something to the community!', 'bp-album' ), __( "Either %s hasn't uploaded any picture yet or they have restricted access", 'bp-album' )  ,false,false) ?></p>
				</div>
				
				<?php endif; ?>

			</div><!-- #item-body -->
		
			<div id="item-nav">
				<div class="item-list-tabs no-ajax" id="object-nav">
					<ul>
						<?php bp_get_displayed_user_nav() ?>
					</ul>
				</div>
			</div>
			
		
		</div><!-- .padder -->
	</div><!-- #content -->

	<?php locate_template( array( 'sidebar.php' ), true ) ?>

<?php get_footer() ?>
