<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WallPress
 * @since WallPress 1.0.3
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<meta name="HandheldFriendly" content="true" />
<meta name="apple-touch-fullscreen" content="yes" />
<title><?php
	/*
	 * Print the <title> tag based on what is being viewed.
	 */
	global $page, $paged;

	wp_title( '|', true, 'right' );

	// Add the blog name.
	bloginfo( 'name' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		echo " | $site_description";

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 )
		echo ' | ' . sprintf( __( 'Page %s', 'wallpress' ), max( $paged, $page ) );

	?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

<!--[if lt IE 9]>
	<script src="<?php echo get_template_directory_uri(); ?>/assets/js/css3-mediaqueries.js"></script>
<![endif]-->

<?php
	if ( is_singular() && get_option( 'thread_comments' ) )
	wp_enqueue_script( 'comment-reply' );
	wp_head();
?>
<link rel="stylesheet" type="text/css" media="all" href="<?php echo get_stylesheet_uri(); ?>" />
</head>

<body <?php body_class(); ?>>
<?php do_action( 'before-header' ); ?>
<div id="header" class="main">
	<div id="header-inner" class="clearfix">
		<div id="branding">
			<h1 id="site-title">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
			</h1>
		</div>
		<div id="navigation">
			<div class="menu-inner">
				<?php wp_nav_menu( array( 'theme_location' => 'primary', 'container' => false ) ); ?>
			 </div>
		</div>
		<?php do_action( 'after-navigation' ); ?>
	</div>
</div>
<!-- #header -->
<?php do_action( 'after-header' ); ?>
<div id="main">
