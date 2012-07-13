<?php get_header() ?>
	<div id="content">
		<div class="padder">
			<div id="item-header">
				<?php locate_template( array( 'members/single/member-header.php' ), true ) ?>
			</div>
			<div id="item-nav">
				<div class="item-list-tabs no-ajax" id="object-nav">
					<ul>
						<?php bp_get_displayed_user_nav() ?>
					</ul>
				</div>
			</div>
			<div id="item-body">
				<div class="item-list-tabs no-ajax" id="subnav">
					<ul>
						<?php bp_get_options_nav() ?>
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
                                if(stripos(bp_album_picture_thumb_url(),"jpg") || stripos(bp_album_picture_thumb_url(),"jpeg") || stripos(bp_album_picture_thumb_url(),"png") || stripos(bp_album_picture_thumb_url(),"gif")) 
								{
								
								?>
								<a rel="prettyPhoto[mixed]" href="<?php echo bp_album_picture_thumb_url() ?>" class="picture-thumb"><img src='<?php echo bp_album_picture_thumb_url() ?>' /></a>
                                <?php 
								}
								else
								{									
								?>
                               
                                <a rel="prettyPhoto[mixed]" href="<?php echo bp_album_picture_url(); ?>" class="picture-thumb"><img width="100" height="100" src='<?php echo get_template_directory_uri(); ?>/images/video_dummy_image.jpeg' alt="See Video"/></a>
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

		</div><!-- .padder -->
	</div><!-- #content -->

	<?php locate_template( array( 'sidebar.php' ), true ) ?>

<?php get_footer() ?>