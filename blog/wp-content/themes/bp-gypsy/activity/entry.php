<?php
/**
 * BuddyPress - Activity Stream (Single Item)
 *
 * This template is used by activity-loop.php and AJAX functions to show
 * each activity.
 *
 * @package BuddyPress
 * @subpackage bp-default
 */
?>

<?php do_action( 'bp_before_activity_entry' ); ?>

<li class="<?php bp_activity_css_class(); ?> clearfix row" id="activity-<?php bp_activity_id(); ?>">
	<div class="clearfix">
	<div class="activity-avatar column alpha">
		<a href="<?php bp_activity_user_link(); ?>">
			<?php bp_activity_avatar(); ?>
		</a>
	</div>

	<div class="activity-content column alpha">

		<div class="activity-header">
			<?php bp_activity_action(); ?>
		</div>

		<?php if ('activity_comment' == bp_get_activity_type() ) : ?>

			<div class="activity-inreplyto">
				<strong><?php _e( 'In reply to: ', 'buddypress' ); ?></strong><?php bp_activity_parent_content(); ?> <a href="<?php bp_activity_thread_permalink(); ?>" class="view" title="<?php _e( 'View Thread / Permalink', 'buddypress' ); ?>"><?php _e( 'View', 'buddypress' ); ?></a>
			</div>

		<?php endif; ?>

		<?php
			$is_embed_video_query = "select content,item_id, primary_link from wp_bp_activity where id='".(int)bp_get_activity_id()."'";									
			$is_embed_video = $wpdb->get_results($is_embed_video_query);
			
			$is_embed_video_query1 = "select pic_org_url from wp_bp_album where id='".(int)$is_embed_video[0]->item_id."'";									
			$is_embed_video1 = $wpdb->get_results($is_embed_video_query1);							
		 ?>

		<?php if(bp_activity_has_content()) : ?>
			<div class="activity-inner">
				<?php
					 if(strpos($is_embed_video[0]->primary_link,'gypsy') == false)
					 {
					 	echo $is_embed_video[0]->content;
					 }
					 else if(is_numeric(strpos($is_embed_video1[0]->pic_org_url,'mp3')) || is_numeric(strpos($is_embed_video1[0]->pic_org_url,'m4a')) || is_numeric(strpos($is_embed_video1[0]->pic_org_url,'oga')))
					{					
					?>						                 
                        <script type="text/javascript">						
						jQuery(document).ready(function(){							
							jQuery("#jquery_jplayer_<?php echo bp_get_activity_id();?>").jPlayer({								
								ready: function () {
									jQuery(this).jPlayer("setMedia", {
										<?php if(is_numeric(strpos($is_embed_video1[0]->pic_org_url,'mp3'))) { ?>									
										 mp3:"<?php bloginfo('wpurl'); echo $is_embed_video1[0]->pic_org_url; ?>"
										<?php } else if(is_numeric(strpos($is_embed_video1[0]->pic_org_url,'m4a'))) { ?>
										 m4a:"<?php bloginfo('wpurl'); echo $is_embed_video1[0]->pic_org_url; ?>"
										<?php } else if(is_numeric(strpos($is_embed_video1[0]->pic_org_url,'oga'))) { ?>
										oga:"<?php bloginfo('wpurl'); echo $is_embed_video1[0]->pic_org_url; ?>"
										<?php  } ?>
									});
								},
							
								swfPath: "<?php echo get_bloginfo('template_directory'); ?>/js",
								<?php if(is_numeric(strpos($is_embed_video1[0]->pic_org_url,'mp3'))) { ?>
								supplied: "mp3",
								<?php } else if(is_numeric(strpos($is_embed_video1[0]->pic_org_url,'m4a'))) { ?>
								supplied: "m4a",
								<?php } else if(is_numeric(strpos($is_embed_video1[0]->pic_org_url,'oga'))) { ?>
								supplied: "oga",
								<?php  } ?>
								wmode: "window",
								cssSelectorAncestor: "#jp_container_<?php echo bp_get_activity_id();?>"
							});							
						});					
						</script>
                        
                        <div id="jquery_jplayer_<?php echo bp_get_activity_id();?>" class="jp-jplayer"></div>
                        
                        <div id="jp_container_<?php echo bp_get_activity_id();?>" class="jp-audio">
                            <div class="jp-type-single">
                                <div class="jp-gui jp-interface">
                                    <ul class="jp-controls">
                                        <li><a href="javascript:;" class="jp-play" tabindex="1">play</a></li>
                                        <li><a href="javascript:;" class="jp-pause" tabindex="1">pause</a></li>
                                        <li><a href="javascript:;" class="jp-stop" tabindex="1">stop</a></li>
                                        <li><a href="javascript:;" class="jp-mute" tabindex="1" title="mute">mute</a></li>
                                        <li><a href="javascript:;" class="jp-unmute" tabindex="1" title="unmute">unmute</a></li>
                                        <li><a href="javascript:;" class="jp-volume-max" tabindex="1" title="max volume">max volume</a></li>
                                    </ul>
                                    <div class="jp-progress">
                                        <div class="jp-seek-bar">
                                            <div class="jp-play-bar"></div>
                                        </div>
                                    </div>
                                    <div class="jp-volume-bar">
                                        <div class="jp-volume-bar-value"></div>
                                    </div>
                                    <div class="jp-time-holder">
                                        <div class="jp-current-time"></div>
                                        <div class="jp-duration"></div>
                
                                        <ul class="jp-toggles">
                                            <li><a href="javascript:;" class="jp-repeat" tabindex="1" title="repeat">repeat</a></li>
                                            <li><a href="javascript:;" class="jp-repeat-off" tabindex="1" title="repeat off">repeat off</a></li>
                                        </ul>
                                    </div>
                                </div>
                               
                            </div>
		</div>
                        
                        
                     <?php   
					}
					else if(is_numeric(strpos($is_embed_video1[0]->pic_org_url,'mp4')) || is_numeric(strpos($is_embed_video1[0]->pic_org_url,'flv')) || is_numeric(strpos($is_embed_video1[0]->pic_org_url,'mov')))
					{
						
						?>						                              
                                <div id="my_video_test<?php echo bp_get_activity_id();?>">Loading the player ...</div>                                
								<script type="text/javascript">
									jwplayer("my_video_test<?php echo bp_get_activity_id();?>").setup({
									flashplayer: "<?php bloginfo('wpurl');?>/wp-content/plugins/bp-album/includes/mediaplayer/player.swf",
									file: "<?php bloginfo('wpurl'); echo $is_embed_video1[0]->pic_org_url; ?>",								
									height: 270,
									width: 480
									});
                                </script>
					<?php	
					}
					else if(strpos($is_embed_video[0]->content,'iframe') == false)
					{ 
						bp_activity_content_body(); 
					}
					else
					{
						echo stripslashes($is_embed_video[0]->content);	
				
					}	
				?>
			</div>
		<?php endif; ?>
        	        			
		<?php do_action( 'bp_activity_entry_content' ); ?>

		<?php if ( is_user_logged_in() ) : ?>

			<div class="activity-meta">

				<?php if ( bp_activity_can_comment() ) : ?>

					<a href="<?php bp_get_activity_comment_link(); ?>" class="acomment-reply bp-primary-action" id="acomment-comment-<?php bp_activity_id(); ?>"><?php printf( __( 'Comment <span>%s</span>', 'buddypress' ), bp_activity_get_comment_count() ); ?></a>

				<?php endif; ?>

				<?php if ( bp_activity_can_favorite() ) : ?>
					<?php if ( !bp_get_activity_is_favorite() ) : ?>

						<a href="<?php bp_activity_favorite_link(); ?>" class="fav bp-secondary-action" title="<?php esc_attr_e( 'Mark as Favorite', 'buddypress' ); ?>"><?php _e( 'Favorite', 'buddypress' ) ?></a>

					<?php else : ?>

						<a href="<?php bp_activity_unfavorite_link(); ?>" class="unfav bp-secondary-action" title="<?php esc_attr_e( 'Remove Favorite', 'buddypress' ); ?>"><?php _e( 'Remove Favorite', 'buddypress' ) ?></a>

					<?php endif; ?>
				<?php endif; ?>

				<?php //if ( bp_activity_user_can_delete() ) bp_activity_delete_link(); ?>

				<?php do_action( 'bp_activity_entry_meta' ); ?>

			</div>

		<?php endif; ?>
	</div>
	</div>
	<?php do_action( 'bp_before_activity_entry_comments' ); ?>

	<?php if ( ( is_user_logged_in() && bp_activity_can_comment() ) || bp_activity_get_comment_count() ) : ?>

		<div class="activity-comments clearfix">

			<?php bp_activity_comments(); ?>

			<?php if ( is_user_logged_in() ) : ?>
				<form action="<?php bp_activity_comment_form_action(); ?>" method="post" id="ac-form-<?php bp_activity_id(); ?>" class="ac-form"<?php bp_activity_comment_form_nojs_display(); ?>>
					<div class="ac-reply-avatar column alpha"><?php bp_loggedin_user_avatar( 'width=' . BP_AVATAR_THUMB_WIDTH . '&height=' . BP_AVATAR_THUMB_HEIGHT ); ?></div>
					<div class="ac-reply-content columns nine alpha">
						<div class="ac-textarea">
							<textarea id="ac-input-<?php bp_activity_id(); ?>" class="ac-input" name="ac_input_<?php bp_activity_id(); ?>"></textarea>
						</div>
						<input type="submit" name="ac_form_submit" value="<?php _e( 'Post', 'buddypress' ); ?>" /> &nbsp; <?php _e( 'or press esc to cancel.', 'buddypress' ); ?>
						<input type="hidden" name="comment_form_id" value="<?php bp_activity_id(); ?>" />
					</div>

					<?php do_action( 'bp_activity_entry_comments' ); ?>

					<?php wp_nonce_field( 'new_activity_comment', '_wpnonce_new_activity_comment' ); ?>

				</form>

			<?php endif; ?>

		</div>

	<?php endif; ?>

	<?php do_action( 'bp_after_activity_entry_comments' ); ?>

</li>

<?php do_action( 'bp_after_activity_entry' ); ?>
