<?php /* If there are no posts to display, such as an empty archive page  */ ?>
<?php if (have_posts()) : ?>
	<?php while (have_posts()) : the_post(); ?>
	<div class="box">
    	<?php
		if ( has_post_thumbnail() ){ ?>
			<?php $thumbID = get_post_thumbnail_id($post->ID); ?>
            <a href="<?php echo wp_get_attachment_url($thumbID); ?>" rel="gallery" title="<?php the_title(); ?>">        
                <?php the_post_thumbnail(); ?>
            </a>
        <?php } ?>
        
        <h2><?php the_title(); ?></h2>
        <?php the_content(); ?>
    </div>
    <?php endwhile; ?>

<?php /* Display navigation to next/previous pages when applicable  */ ?>
<?php if (  $wp_query->max_num_pages > 1 ) : ?>
	<div id="nav-below" class="navigation wrap">
        <div class="alignleft nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts') ); ?></div>
        <div class="alignright nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>') ); ?></div>
    </div><!-- #nav-below -->
<?php endif; ?>


<?php else : ?>
<div class="box">
	<h2>Sorry, no posts were found</h2>
</div>
<?php endif; ?>
