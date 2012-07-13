<?php 	get_header() ;?>

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
						<?php
							if(strpos($_SERVER['REQUEST_URI'],"album") == false)
							{ 
								bp_get_options_nav();
							}	
							?>
					</ul>
				</div>

					<?php if (bp_album_has_pictures() ) : bp_album_the_picture();?>
					
				<div class="picture-single activity">
					<h3><?php bp_album_picture_title() ?></h3>
					
                	<div class="picture-outer-container">
                		<div class="picture-inner-container">
			                <div class="picture-middle">
                            <?php
                            	/*
									***************By Jaspreet 30 May 2012******************
								*/
								
								if(bp_album_picture_thumb_url() == get_bloginfo('wpurl'))
								{									
									$uri_=explode('/',$_SERVER['REQUEST_URI']);
									$id_=$uri_[count($uri_)-1];
									
									global $wpdb;
									
									$embed_video_query = "select embed_url from wp_bp_album where id='".(int)$id_."'";									
									$embed_video = $wpdb->get_results($embed_video_query);
									echo $embed_video[0]->embed_url;
																		
								}
								else if(stripos(bp_album_picture_thumb_url(),"jpg") || stripos(bp_album_picture_thumb_url(),"jpeg") || stripos(bp_album_picture_thumb_url(),"png") || stripos(bp_album_picture_thumb_url(),"gif")) 
								{
								?>
									 <img src="<?php bp_album_picture_middle_url() ?>" />
				                	<?php //bp_album_adjacent_links() ?>
                                   <?php 
								}
								else
								{
							?>
                            	<script type="text/javascript" src="<?php bloginfo('wpurl');?>/wp-content/plugins/bp-album/includes/mediaplayer/jwplayer.js"></script>                              
                                <div id="my_video_test">Loading the player ...</div>                                
								<script type="text/javascript">
									jwplayer("my_video_test").setup({
									flashplayer: "<?php bloginfo('wpurl');?>/wp-content/plugins/bp-album/includes/mediaplayer/player.swf",
									file: "<?php bp_album_picture_middle_url() ?>",								
									height: 270,
									width: 480
									});
                                </script>
                            
                             <?php
							 	}
                            	/*
									***************By Jaspreet 30 May 2012******************
								*/
									
							?>
                            
				               
			                </div>
		                </div>
	                </div>
	                
					<div class="dec_file"><p class="picture-description"><?php bp_album_picture_desc() ?></p></div>
	                <p class="picture-meta">
	                <?php bp_album_picture_edit_link()  ?>	
	                <?php bp_album_picture_delete_link()  ?></p>
	                
				<?php bp_album_load_subtemplate( apply_filters( 'bp_album_template_screen_comments', 'album/comments' ) ); ?>
				</div>
					
					<?php else : ?>
					
				<div id="message" class="info">
					<p><?php echo bp_word_or_name( __( "This url is not valid.", 'bp-album' ), __( "Either this url is not valid or file has restricted access.", 'bp-album' ),false,false ) ?></p>
				</div>
					
					<?php endif; ?>

			</div><!-- #item-body -->

		</div><!-- .padder -->
	</div><!-- #content -->

	<?php locate_template( array( 'sidebar.php' ), true ) ?>

<?php get_footer() ?>