<?php /* If there are no posts to display, such as an empty archive page  */ ?>
<?php if (have_posts()) : ?>

<?php /* Display navigation to next/previous pages when applicable  */ ?>
<?php if (  $wp_query->max_num_pages > 1 ) : ?>
	<div id="nav-above" class="navigation">
        <div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older') ); ?></div>
        <div class="nav-next"><?php previous_posts_link( __( 'Newer <span class="meta-nav">&rarr;</span>') ); ?></div>
    </div><!-- #nav-below -->
<?php endif; ?>

	<div id="sort">
	<?php while (have_posts()) : the_post(); ?>
	<?php $url = get_permalink();?>
	<div class="box">
		<div class="click" onclick="window.open('<?php echo $url; ?>', '_self');">
			<span class="title"><?php the_title(); ?></span>
			<br>
			<?php $meta_values = get_post_meta($post->ID, "custom_excerpt", true); echo $meta_values; ?><a href="<?php echo $url; ?>"> &rarr;</a>
			<br>
    	</div><!-- .click -->
    	<?php edit_post_link('Edit this post'); ?>
    	<?php $featVid = get_post_meta($post->ID, "featured_video", true);?>
    	<?php if($featVid != '' && !has_post_thumbnail())
       			{
					echo '<div class="featured_video">'.$featVid.'</div>';
				}
				else
				{
					echo '<div onclick="window.open(\''. $url .'\', \'_self\');">';
					the_post_thumbnail(array(480,480)); 
					echo '</div><!--featured image-->';
				}
			?>
    </div><!-- .box -->
    <?php endwhile; ?>
    </div><!-- #sort -->

<?php /* Display navigation to next/previous pages when applicable  */ ?>
<?php if (  $wp_query->max_num_pages > 1 ) : ?>
	<div id="nav-below" class="navigation">
        <div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older') ); ?></div>
        <div class="nav-next"><?php previous_posts_link( __( 'Newer <span class="meta-nav">&rarr;</span>') ); ?></div>
    </div><!-- #nav-below -->
<?php endif; ?>

<?php else : ?>
<div id="sort">
<div class="box">
	<h2>Sorry, no posts were found</h2>
    <?php get_search_form(); ?>
</div>
</div><!-- #sort -->
<?php endif; ?>
