<?php get_header(); ?>

<div class="wrap"> 
	<?php if (have_posts()) : ?>
    <div id="page">
    	<div id="nav-above" class="navigation">
        	<div class="nav-previous"><?php next_post_link('%link','<span class="meta-nav">&larr;</span> %title | next',TRUE); ?></div>
        	<div class="nav-next"><?php previous_post_link('%link','previous | %title <span class="meta-nav">&rarr;</span>',TRUE); ?></div>
    	</div><!-- #nav-above -->
    	<div class="wide-col">
        	<?php while(have_posts()) : the_post(); ?>
            	<h2><?php the_title(); ?></h2>
                <span class='date'><?php echo get_the_date(); ?></span>
        		<span class='author'>words by <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>" target="_self"><?php echo get_the_author_meta( 'first_name' ) . " " . get_the_author_meta( 'last_name' ); ?></a></span>
                <?php the_content(); ?>
                <div class="post_column_3" style="width:30%; float: left; padding-right: 0; display: inline;">
	        		<?php get_sidebar(); ?>
	        	</div>
                <br class="clearfix" />
            <?php endwhile; ?>
        </div>
       <div id="nav-below" class="navigation">
        <div class="nav-previous"><?php next_post_link('%link','<span class="meta-nav">&larr;</span> %title | next',TRUE); ?></div>
		<div class="nav-next"><?php previous_post_link('%link','previous | %title <span class="meta-nav">&rarr;</span>',TRUE); ?></div>
    </div><!-- #nav-below -->
	</div><!-- #page -->
	<?php endif; ?>
</div><!-- #wrap -->
<?php comments_template( '', true ); ?>
<?php get_footer(); ?>