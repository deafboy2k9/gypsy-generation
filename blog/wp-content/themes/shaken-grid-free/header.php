<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />

<title><?php wp_title('&laquo;', true, 'right'); ?> <?php bloginfo('name'); ?></title>

<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />

<?php if (get_option('soy_style') == "black") : ?>
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/black.css" />
<?php endif; ?>

<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/js/colorbox/colorbox.css" />

<!--Gypsy Generation-->
<!--<link href='http://fonts.googleapis.com/css?family=Quicksand|Josefin+Sans:300,400' rel='stylesheet' type='text/css' />-->
<link rel="stylesheet" href="/css/main.css?<?php echo time(); ?>" />

<?php if (get_option('soy_favicon')) : ?>
	<link rel="shortcut icon" href="<?php echo get_option('soy_favicon'); ?>">
<?php endif; ?>

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/swfobject/2.2/swfobject.js"></script>

<!--[if lte IE 8]>
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/ie.css" />
<![endif]-->
<!--[if lte IE 7]>
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/ie7.css" />
<![endif]-->

<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>
<?php wp_head(); ?>

<script type="text/javascript" src="/js/utils.js"></script>

<link href="<?php echo get_bloginfo('template_directory'); ?>/skin/blue.monday/jplayer.blue.monday.css" rel="stylesheet" type="text/css" />                    
<script type="text/javascript" src="<?php echo get_bloginfo('template_directory'); ?>/js/jquery.jplayer.min.js"></script>
<script type="text/javascript" src="<?php bloginfo('wpurl');?>/wp-content/plugins/bp-album/includes/mediaplayer/jwplayer.js"></script>

</head>

<body <?php body_class(); ?>>

<!-- =================================
	Header and Nav
================================= -->
<div id="header">
<div class="header_container">
	<div class="headerlogo">
		<div class="logo"><img width="80px" src="/img/gg.png"/></div>
		<div class="header">GYPSY GENERATION</div>
	</div>
	<?php get_search_form( true ); ?>
	<!--START TOP MENU-->
	<?php 
		$blog_title = get_bloginfo(); 
		$is_component = (bp_is_profile_component() || bp_is_activity_component() || bp_is_blogs_component() || bp_is_messages_component() || bp_is_friends_component() || bp_is_groups_component() || bp_is_settings_component());
	?>	
	<?php 
		if($blog_title === 'Gypsy Generation Community' || bp_is_directory() || $is_component)
		{
			 include('community_menu.php');
		}
		else
		{
			include('blog_menu.php');
		}
	?>
</div>
</div>