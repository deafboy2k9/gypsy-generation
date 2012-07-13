<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
	<head profile="http://gmpg.org/xfn/11">
		<meta http-equiv="Content-Type" content="<?php bloginfo( 'html_type' ) ?>; charset=<?php bloginfo( 'charset' ) ?>" />
		<title><?php wp_title( '|', true, 'right' ); bloginfo( 'name' ); ?></title>

		<?php do_action( 'bp_head' ) ?>

		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ) ?>" />

		<?php
			if ( is_singular() && bp_is_blog_page() && get_option( 'thread_comments' ) )
				wp_enqueue_script( 'comment-reply' );

			wp_head();
		?>
	</head>

	<body <?php body_class() ?> id="bp-default">

		<?php do_action( 'bp_before_header' ) ?>
		<nav id="sidebar-left">
			<ul>
				<li>
					<a href="<?php gg_get_my_profile_url(); ?>" id="profile">Profile</a>
				</li>
				<li>
					<a href="<?php echo get_permalink(get_page_by_title('Dashboard')); ?>" id="dashboard">Dashboard</a>
				</li>
			</ul>
		</nav>
		<?php if(is_user_logged_in()){ ?>
		<nav id="account-nav">
			<ul id="account-links">
				<?php gg_notifications_count(); ?>
			</ul>
		</nav>
		<?php } ?>
		<header>
			
			<div id="header-content-wrapper">
    			<div id="search" role="search" class="column">
    			<?php get_search_form(); ?>
    			</div>
    			
    			<div id="logo" role="logo" class="column">
    				<a href="<?php echo home_url(); ?>" title="<?php _ex( 'Home', 'Home page banner link title', 'buddypress' ); ?>">
    					<img src="<?php bloginfo('stylesheet_directory');?>/images/gypsy-generation-logo.png" alt="<?php bp_site_name(); ?>" height="100" />
    					<strong><?php bp_site_name(); ?></strong>
    				</a>
    			</div>
    			
    			<div id="dropdown" role="dropdown" class="column align-right">
    				<?php if(is_user_logged_in()){ ?>
        			<nav id="personal-nav">
        				<ul id="personal-nav-menu">
        					<li>
        						<a href="javascript:void;">Hello, <?php bp_loggedin_user_username(); ?></a>
        						<ul>
        							<li><a href="<?php echo get_permalink(get_page_by_title('Dashboard')); ?>" id="account-links-dashboard">Dashboard</a></li>
        							<li><a href="<?php gg_get_my_profile_url(); ?>" id="account-links-profile">Profile</a></li>
        							<li><a href="<?php echo bp_loggedin_user_domain(); ?>settings" id="account-links-account">My settings</a></li>
        							<li><a href="#">My articles</a></li>
									<li><a href="<?php echo wp_logout_url(); ?>" id="account-links-account">Logout</a></li>
        						</ul>
        					</li>
        				</ul>
        			</nav>
        			<?php } ?>
    			</div>
			</div>
			<nav id="primary-navigation" role="navigation">
				<?php wp_nav_menu( array( 'container' => false, 'menu_id' => 'nav', 'theme_location' => 'primary', 'fallback_cb' => 'bp_dtheme_main_nav' ) ); ?>
			</nav>

			<?php do_action( 'bp_header' ) ?>
			
		</header><!-- #header -->

		<?php do_action( 'bp_after_header' ) ?>
		<?php do_action( 'bp_before_container' ) ?>

		<div id="container" class="container clearfix">
