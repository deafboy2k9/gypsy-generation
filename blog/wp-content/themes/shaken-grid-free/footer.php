<!--=================================
    Footer
================================= -->
<br class="clearfix" />
<div class="footer_container">
	<div class="footer_menu">
		<ul>
			<li>
				<div class="footer_item">
					<a class="footer" href="/legal.php">&#169; 2012 Gypsy Generation</a>
				</div>
			</li>
			<li class="float">
				<div class="footer_item">
					<a class="footer" href="/about.php" title="about">say what?</a>
				</div>
			</li>
			<li> 
				<div class="footer_item">
					<a class="footer" href="/contact.php" title="contact">get in touch</a>
				</div>
			</li>
			<li>
				<div class="footer_item">
						<a class="footer" href="/advertise.php" title="advertising">advertise</a>
				</div>
			</li>
		</ul>
	</div>
	<div class="social_menu">
		<ul>
			<li>
				<div class="footer_item">
					 <a class="footer" href="http://www.facebook.com/pages/GypsyGeneration/154579501238072" target="_blank">facebook</a>
				</div>
			</li>
			<li>
				<div class="footer_item">
					 <a class="footer" href="http://twitter.com/gypsygeneration" target="_blank">twitter</a>
				</div>
			</li>
			<li>
				<div class="footer_item">
					 <a class="footer" href="http://gypsygeneration.tumblr.com/" target="_blank">tumblr</a>
				</div> 
			</li>
		</ul>
	</div>
</div>

<aside id="sidebar_tab">
	<ul>
		<?php if(is_user_logged_in()): ?>
		
		<li><a href="<?php echo wp_logout_url(); ?>">Dashboard</a></li>
		<li><a href="<?php echo wp_logout_url(); ?>">Profile</a></li>
		
		<?php else: ?>
		
		<li><a href="<?php echo esc_url( site_url( 'wp-login.php?action=register', 'login' ) ); ?>">Register</a></li>
		<li><a href="<?php echo wp_login_url(); ?>">Login</a></li>
		
		<?php endif;?>
		
	</ul>
</aside>
<?php wp_footer(); ?>
<?php if (get_option('soy_stats')) : ?><?php echo get_option('soy_stats'); ?><?php endif; ?>
<!--colorbox.js-->
<script src="<?php echo get_template_directory_uri(); ?>/js/jquery.colorbox-min.js"></script>
<!--isotope.js-->
<script src="<?php echo get_template_directory_uri(); ?>/js/plugins.js"></script>
<!--isotope init-->
<script src="<?php echo get_template_directory_uri(); ?>/js/script.js"></script>
<script type="text/javascript" src="http://platform.tumblr.com/v1/share.js"></script>
</body>
</html>