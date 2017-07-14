<!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width" />
<title><?php wp_title( '|', true, 'right' ); ?></title>
<script type='text/javascript' src='http://libs.baidu.com/jquery/1.8.2/jquery.min.js?ver=1.8.2'></script>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/form.css">
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/label.css">    
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/zocial.css">
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/global.css">
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/header.css">
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/content.css">
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/comments.css">
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/zidingyi.css">
<link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
<![endif]-->
<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="back-to-top" class="teal" data-scroll="body" style="display: block;">
    <svg id="rocket" version="1.1" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 64 64">
        <path fill="#CCCCCC" d="M42.057,37.732c0,0,4.139-25.58-9.78-36.207c-0.307-0.233-0.573-0.322-0.802-0.329
        c-0.227,0.002-0.493,0.083-0.796,0.311c-13.676,10.31-8.95,35.992-8.95,35.992c-10.18,8-7.703,9.151-1.894,23.262
        c1.108,2.693,3.048,2.06,3.926,0.115c0.877-1.943,2.815-6.232,2.815-6.232l11.029,0.128c0,0,2.035,4.334,2.959,6.298
        c0.922,1.965,2.877,2.644,3.924-0.024C49.974,47.064,52.423,45.969,42.057,37.732z M31.726,23.155
        c-2.546-0.03-4.633-2.118-4.664-4.665c-0.029-2.547,2.012-4.587,4.559-4.558c2.546,0.029,4.634,2.117,4.663,4.664
        C36.314,21.143,34.272,23.184,31.726,23.155z"></path>
    </svg>
</div>
<div class="navbar">
<?php if ( is_user_logged_in() ) : ?> 
	<a href="<?php bloginfo('url');?>/wp-admin/" title="博客后台" class="houtai fa fa-user">后台</a>
<?php endif; ?>	
<div class="container">
<nav id="primary-navigation" class="site-navigation primary-navigation" role="navigation">
	<?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_class' => 'nav-menu' ) ); ?>
</nav>
<?php if ( is_single() ) : ?>
<div class="edit-wz">
<?php edit_post_link("<p class='fa fa-pencil'>编辑文章</p>"); ?>
</div>
<?php endif; ?>
<?php if ( is_page() ) : ?>
<div class="edit-wz">
<?php edit_post_link("<p class='fa fa-pencil'>编辑文章</p>"); ?>
</div>
<?php endif; ?>
<div class="nav-right">
	<form class="search-form" method="post" action="<?php bloginfo('url');?>">
        <input type="text" name="s" class="search-input" placeholder="站内搜索">
        <button type="submit" class="search-submit sousuo"></button>
    </form>
</div>
</div>
</div>

<header id="description">
    <div id="site-header">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
    </div>
</header>
<div id="information" class="info block">
	
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><img src="<?php header_image(); ?>" class="avatar" data-pinit="registered" width="<?php echo get_custom_header()->width; ?>" height="<?php echo get_custom_header()->height; ?>" alt="" /></a>
          	
                
                <p class="description"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">一个正在奔跑的小青年</a></p>
</div>
<div class="container" id="content">

<div class="article-list">