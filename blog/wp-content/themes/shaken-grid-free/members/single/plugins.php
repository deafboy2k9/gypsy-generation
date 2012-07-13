<?php

/**
 * BuddyPress - Users Plugins
 *
 * This is a fallback file that external plugins can use if the template they
 * need is not installed in the current theme. Use the actions in this template
 * to output everything your plugin needs.
 *
 * @package BuddyPress
 * @subpackage bp-default
 */

?>

<?php get_header( 'buddypress' ); ?>

	<div id="content">
		<div class="padder">

			<?php do_action( 'bp_before_member_plugin_template' ); ?>

			<div id="item-header">

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
                
                 <?php if ( is_user_logged_in() && bp_is_my_profile()) { ?>
                    <div style="width:100%; margin-bottom:10px;">                
                         <span style="width:45%; padding-left:2%; padding-right:3%;"><a href="<?php echo $link.'album/pictures';?>">My files</a></span>
                         <span style="width:50%;"><a href="<?php echo $link.'album/upload';?>">Upload file</a></span>               	
                    </div>
                 <?php } ?>
                 
            </div>
            
            <!--by jaspreet 5 June 2012-->


			

			<div id="item-body" role="main">

				<?php do_action( 'bp_before_member_body' ); ?>

				<div class="item-list-tabs no-ajax" id="subnav">
					<ul>						
						
                        <?php
							if(strpos($_SERVER['REQUEST_URI'],"album") == false)
							{ 
								bp_get_options_nav();
							}	
							?>
                        
						<?php do_action( 'bp_member_plugin_options_nav' ); ?>

					</ul>
				</div><!-- .item-list-tabs -->

				<h3><?php do_action( 'bp_template_title' ); ?></h3>

				<?php do_action( 'bp_template_content' ); ?>

				<?php do_action( 'bp_after_member_body' ); ?>

			</div><!-- #item-body -->

			<?php do_action( 'bp_after_member_plugin_template' ); ?>
		<br/><br/>
		<div id="item-nav">
				<div class="item-list-tabs no-ajax" id="object-nav" role="navigation">
					<ul>

						<?php bp_get_displayed_user_nav(); ?>

						<?php do_action( 'bp_member_options_nav' ); ?>

					</ul>
				</div>
			</div><!-- #item-nav -->

		</div><!-- .padder -->
	</div><!-- #content -->

<?php get_sidebar( 'buddypress' ); ?>
<?php get_footer( 'buddypress' ); ?>
