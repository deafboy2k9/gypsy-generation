		</div> <!-- #container -->

		<?php do_action( 'bp_after_container' ) ?>
		<?php do_action( 'bp_before_footer' ) ?>

		<footer id="footer" class="clearfix">
			<nav class="column">
				<ul>
    				<li>
    			        <?php echo '&copy; ' .  date('Y') . ' Gypsy Generation' ?>
    			    </li>
			    </ul>
			</nav>
			<?php if ( is_active_sidebar( 'first-footer-widget-area' ) ) : ?>
        	<nav id="first" class="widget-area column" role="complementary">
        		<ul>
        		    <?php dynamic_sidebar( 'first-footer-widget-area' ); ?>
        		</ul>
        	</nav><!-- #first .widget-area -->
        	<?php endif; ?>
        	<?php if ( is_active_sidebar( 'second-footer-widget-area' ) ) : ?>
        	<nav id="first" class="widget-area column align-right" role="complementary">
        		<ul>
        		    <?php dynamic_sidebar( 'second-footer-widget-area' ); ?>
        		</ul>
        	</nav><!-- #first .widget-area -->
        	<?php endif; ?>
        	<?php do_action( 'bp_footer' ) ?>
		</footer><!-- #footer -->

		<?php do_action( 'bp_after_footer' ) ?>

		<?php wp_footer(); ?>

	</body>

</html>