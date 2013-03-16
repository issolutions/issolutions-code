<?php
/**
 * WallPress functions and definitions
 *
 * @package WallPress
 * @since WallPress 1.0.7
 */

/*-----------------------------------------------------------------------------------*/
/*	Include Jigoshop Functions
/*-----------------------------------------------------------------------------------*/
if ( function_exists('jigoshop_init') ) :
	function wallpress_jigoshop_style_method() {
		wp_register_style( 'wallpress_jigoshop', get_template_directory_uri().'/jigoshop/style.css' );
		wp_enqueue_style( 'wallpress_jigoshop' );
	}
	add_action( 'wp_enqueue_scripts', 'wallpress_jigoshop_style_method' );
	require_once 'jigoshop/functions.php';
endif;

/*-----------------------------------------------------------------------------------*/
/*	Include Custom Post Type Functions
/*-----------------------------------------------------------------------------------*/
require_once 'inc/post-format.php';

/*-----------------------------------------------------------------------------------*/
/*	Include Widgets Functions
/*-----------------------------------------------------------------------------------*/
require_once 'inc/widgets.php';

/*-----------------------------------------------------------------------------------*/
/* Apply Theme Customize & Apply
/*-----------------------------------------------------------------------------------*/
require_once get_template_directory().'/inc/customize/customize.php';

/*-----------------------------------------------------------------------------------*/
/*	Set the content width based on the theme's design and stylesheet.
/*-----------------------------------------------------------------------------------*/
if ( ! isset( $content_width ) ) $content_width = 620;

/*-----------------------------------------------------------------------------------*/
/*	Add Theme Default Options
/*-----------------------------------------------------------------------------------*/
add_action( 'after_setup_theme', 'wallpress_default_options' );
if ( ! function_exists( 'wallpress_default_options' ) ) :
function wallpress_default_options() {

	load_theme_textdomain( 'wallpress', get_template_directory() . '/languages' );

	add_theme_support( 'automatic-feed-links' );

	register_nav_menu( 'primary', __( 'Primary Menu', 'wallpress' ) );

	add_theme_support( 'post-formats', array( 'gallery', 'link', 'image', 'quote', 'status', 'video', 'chat', 'audio', 'aside' ) );

	if ( is_admin() && isset( $_GET['activated'] ) ) {
		update_option( 'thumbnail_size_w', '300' );
		update_option( 'thumbnail_size_h', '0' );
		update_option( 'medium_size_w', '620' );
		update_option( 'medium_size_h', '0' );
		update_option( 'large_size_w', '940' );
		update_option( 'large_size_h', '0' );
	}

	add_theme_support( 'post-thumbnails' );
}
endif;

/*-----------------------------------------------------------------------------------*/
/*	Theme default excerpt more
/*-----------------------------------------------------------------------------------*/
add_filter( 'excerpt_more', 'wallpress_excerpt_more' );
if ( !function_exists( 'wallpress_excerpt_more' ) ) :
function wallpress_excerpt_more( $more ) {
	global $post;
	return '<a href="'.get_permalink( $post->ID ).'" > ...</a>';
}
endif;

/*-----------------------------------------------------------------------------------*/
/*	Javascript
/*-----------------------------------------------------------------------------------*/
add_action( 'wp_enqueue_scripts', 'wallpress_script_method' );
if ( !function_exists( 'wallpress_script_method' ) ) :
function wallpress_script_method() {
	wp_enqueue_script( 'wallpress_jquery_masonry', get_template_directory_uri().'/assets/js/jquery.masonry.min.js', array( 'jquery' ) );
	wp_enqueue_script( 'wallpress_jquery_infinite', get_template_directory_uri().'/assets/js/jquery.infinitescroll.min.js', array( 'jquery' ) );
	wp_enqueue_script( 'wallpress_jquery_custom', get_template_directory_uri().'/assets/js/jquery.custom.js', array( 'jquery' ) );
	wp_enqueue_script( 'wallpress_jquery_jcarousel', get_template_directory_uri().'/assets/js/jquery.jcarousel.min.js', array( 'jquery' ) );
	wp_enqueue_script( 'wallpress_jquery_jplayer', get_template_directory_uri().'/assets/js/jquery.jplayer.min.js', array( 'jquery' ) );
	wp_enqueue_script( 'wallpress_jquery_iscroll', get_template_directory_uri().'/assets/js/iscroll.js', array( 'jquery' ) );
	wp_enqueue_script( 'wallpress_jquery_wheel', get_template_directory_uri().'/assets/js/jquery.mousewheel.js', array( 'jquery' ) );
}
endif;

/*-----------------------------------------------------------------------------------*/
/*	Register our sidebars and widgetized areas.
/*-----------------------------------------------------------------------------------*/
add_action( 'widgets_init', 'wallpress_widgets_init' );
if ( ! function_exists( 'wallpress_widgets_init' ) ) :
function wallpress_widgets_init() {

	register_sidebar( array(
		'name' => __( 'Main Sidebar', 'wallpress' ),
		'id' => 'sidebar-main',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => "</div>",
		'before_title' => '<h2 class="widget-title">',
		'after_title' => '</h2>',
	) );

	register_sidebar( array(
		'name' => __( 'Secondary Sidebar', 'wallpress' ),
		'id' => 'sidebar-secondary',
		'before_widget' => '<div id="%1$s" class="item widget %2$s"><div class="widget-inner">',
		'after_widget' => "</div></div>",
		'before_title' => '<h2 class="widget-title">',
		'after_title' => '</h2>',
	) );

	register_sidebar( array(
		'name' => __( 'Sidebar Shop', 'wallpress' ),
		'id' => 'sidebar-shop',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => "</div>",
		'before_title' => '<h2 class="widget-title">',
		'after_title' => '</h2>',
	) );

}
endif;

/*-----------------------------------------------------------------------------------*/
/* Body Classes
/*-----------------------------------------------------------------------------------*/
/* Check browser classes ---*/
add_filter( 'body_class', 'wallpress_browser_classes' );
if ( ! function_exists( 'wallpress_browser_classes' ) ) :
function wallpress_browser_classes( $classes ) {
	global $is_lynx, $is_gecko, $is_IE, $is_opera, $is_NS4, $is_safari, $is_chrome;

	$useragent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : "";
	
	$is_iphone = preg_match('/iphone/i',$useragent);
	$is_ipad = preg_match('/ipad/i',$useragent);

	if ( $is_lynx ) $classes[] = 'lynx';
	elseif ( $is_gecko ) $classes[] = 'gecko';
	elseif ( $is_opera ) $classes[] = 'opera';
	elseif ( $is_NS4 ) $classes[] = 'ns4';
	elseif ( $is_safari ) $classes[] = 'safari';
	elseif ( $is_chrome ) $classes[] = 'chrome';
	elseif ( $is_IE ) $classes[] = 'ie';
	else $classes[] = 'unknown';

	if ( $is_iphone ) $classes[] = 'iphone';
	if ( $is_ipad ) $classes[] = 'ipad';

	$ie_check = array();
	$ie_classes = array( 'ie7', 'ie8', 'ie9' );
	$version = 7;
	while ( $version < 10 ) {
		$ie_check[] = strpos( $_SERVER['HTTP_USER_AGENT'], 'MSIE ' . $version . '.' ) !== FALSE;
		++$version;
	}
	$ie = '';
	foreach ( $ie_check as $key => $value  ) {
		if ( $value == 1 ) {
			$ie = $ie_classes[$key] . ' oldie';
		}
	}
	$classes[] = $ie;

	return $classes;
}
endif;

/* Page Slug Class ---*/
add_filter( 'body_class', 'wallpress_page_slug_class' );
if ( ! function_exists( 'wallpress_page_slug_class' ) ) :
function wallpress_page_slug_class( $classes ) {
	global $post;
	if ( isset( $post ) ) {
		$classes[] = $post->post_type . '-' . $post->post_name;
	}
	return $classes;
}
endif;

/*-----------------------------------------------------------------------------------*/
/* Post Classes
/*-----------------------------------------------------------------------------------*/
add_filter( 'post_class', 'wallpress_post_class' );
if ( ! function_exists( 'wallpress_post_class' ) ) :
function wallpress_post_class( $classes ) {
	global $post;
	if ( isset( $post ) ) {
		if ( is_page_template( 'layout-blog.php' ) ) {
			$classes[] = 'clearfix';
		}
		else {
			$classes[] = 'item clearfix';
		}

		//Item Grid
		if ( get_post_meta( get_the_ID(), 'grid', true ) ) :
			$classes[] = 'grid-'.get_post_meta( get_the_ID(), 'grid', true );
		endif;

		//Item Grid
		if ( get_post_meta( get_the_ID(), 'style', true ) ) :
			$classes[] = 'style-'.get_post_meta( get_the_ID(), 'style', true );
		endif;

		//Item Ribbon
		if ( get_post_meta( get_the_ID(), 'ribbon', true ) ) :
			$classes[] = 'has-ribbon ribbon-'.get_post_meta( get_the_ID(), 'ribbon', true );
		endif;

		//Has thumbnail
		if ( has_post_thumbnail() ) {
			$classes[]="has-thumbnail";
		}

		//Has new posts
		if ( wallpress_is_new_post( $post->ID ) ) {
			$classes[]="has-ribbon ribbon-new";
		}
	}
	return $classes;
}
endif;
/*-----------------------------------------------------------------------------------*/
/*	Toolbar in header
/*-----------------------------------------------------------------------------------*/
add_action( 'after-navigation' , 'wallpress_toobar' );
if ( ! function_exists( 'wallpress_toobar' ) ) :
function wallpress_toobar() { ?>
	<a href="javascript:void(0);" class="sidebar-control"><?php _e( 'Collabse sidebar', 'wallpress' ); ?></a>
	<a href="javascript:void(0);" class="navigation-control"><?php _e( 'Collabse navigation', 'wallpress' ); ?></a>
	<?php get_search_form(); ?>
<?php }
endif;

/*-----------------------------------------------------------------------------------*/
/*	Pagenavi without any plugin
/*-----------------------------------------------------------------------------------*/
if ( ! function_exists( 'wallpress_pagenavi' ) ) :
function wallpress_pagenavi( $the_query ) {
	global $wp_query, $wp_rewrite;

	//User wordpress function paginate_links to create pagination, see codex.wordpress.org/Function_reference/paginate_links
	if ( $the_query ) $wp_query = $the_query;
	$pages = '';
	$max = $wp_query->max_num_pages;
	if ( !$current = get_query_var( 'paged' ) ) $current = 1;
	$a['base'] = ( $wp_rewrite->using_permalinks() ) ? user_trailingslashit( trailingslashit( remove_query_arg( 's', get_pagenum_link( 1 ) ) ) . 'page/%#%/', 'paged' ) : @add_query_arg( 'paged', '%#%' );
	if ( !empty( $wp_query->query_vars['s'] ) ) $a['add_args'] = array( 's' => get_query_var( 's' ) );
	$a['total'] = $max;
	$a['current'] = $current;

	$total = 0; //1 - display the text "Page N of N", 0 - not display
	
	$a['mid_size'] = 5; //how many links to show on the left and right of the current
	$a['end_size'] = 1; //how many links to show in the beginning and end
	$a['prev_text'] = '&laquo; Previous'; //text of the "Previous page" link
	$a['next_text'] = 'Next &raquo;'; //text of the "Next page" link

	if ( $max > 1 ){
		echo '<div class="pagenav">';
		$pages = '<span class="pages">Page ' . $current . ' of ' . $max . '</span>'."\r\n";
		echo $pages . paginate_links( $a );
		echo '</div>';
	}
}
endif;

/*-----------------------------------------------------------------------------------*/
/* WallPress Comment
/*-----------------------------------------------------------------------------------*/
if ( ! function_exists( 'wall_comment' ) ) :
function wallpress_comment( $comment, $args, $depth ) {
	global $commentnumber;
	$GLOBALS['comment'] = $comment;
	
	switch ( $comment->comment_type ) :

		case 'pingback' :
		case 'trackback' : ?>
		<li class="post pingback">
			<p><?php _e( 'Pingback:', 'wallpress' ); ?> <?php comment_author_link(); ?></p>
			<?php edit_comment_link( __( 'Edit', 'wallpress' ), '<span class="edit-link">', '</span>' ); ?>
		</li>
		<?php break;

		default :
		$commentnumber++; ?>

		<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
			<div id="comment-<?php comment_ID(); ?>" class="comment">
				<div class="comment-meta">
					<div class="comment-author vcard">
						<?php
						$avatar_size = 68;
						if ( '0' != $comment->comment_parent )
							$avatar_size = 39;

						echo get_avatar( $comment, $avatar_size );

						/* translators: 1: comment author, 2: date and time */
						printf( __( '%1$s %2$s', 'wallpress' ),
							sprintf( '<span class="fn">%s</span>', get_comment_author_link() ),
							sprintf( '<a href="%1$s"><time pubdate datetime="%2$s">%3$s</time></a>',
								esc_url( get_comment_link( $comment->comment_ID ) ),
								get_comment_time( 'c' ),
								/* translators: 1: date, 2: time */
								sprintf( __( '%1$s %2$s', 'wallpress' ), get_comment_date(), get_comment_time() )
							)
						); ?>
					</div><!-- .comment-author .vcard -->

					<?php if ( $comment->comment_approved == '0' ) : ?>
					<em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'wallpress' ); ?></em>
					<?php endif; ?>

				</div>

				<div class="comment-content">
					<span class="commentnumber"><?php echo $commentnumber; ?></span>
					<?php comment_text(); ?>
				</div>

				<div class="reply">
					<?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Reply', 'wallpress' ), 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
				</div><!-- .reply -->

				<?php edit_comment_link( __( 'Edit', 'wallpress' ), '<span class="edit-link">', '</span>' ); ?>
				
			</div><!-- #comment-## -->

		<?php
		break;
	endswitch;
}
endif;

/*-----------------------------------------------------------------------------------*/
/* Valid search form - Replace valid search form
/* role="search" is an attribute introduced in XHTML draft for accessibility however does not validate.
/*-----------------------------------------------------------------------------------*/
add_filter( 'get_search_form', 'wallpress_valid_search_form' );
if ( ! function_exists( 'wallpress_valid_search_form' ) ) :
function wallpress_valid_search_form( $form ) {
	return str_replace( 'role="search" ', '', $form );
}
endif;

/*-----------------------------------------------------------------------------------*/
/* WallPress Fix Valid W3C ref="category"
/*-----------------------------------------------------------------------------------*/
add_filter( 'the_category', 'wallpress_add_nofollow_cat' );
add_filter( 'wp_list_categories', 'wallpress_add_nofollow_cat' );
if ( ! function_exists( 'wallpress_add_nofollow_cat' ) ) :
function wallpress_add_nofollow_cat( $text ) {
	$text = str_replace( 'rel="category tag"', "", $text );
	$text = str_replace( 'rel="category"', "", $text );
	return $text;
}
endif;

/*-----------------------------------------------------------------------------------*/
/* Get newest post day of each category
/*-----------------------------------------------------------------------------------*/
if ( !function_exists( 'wallpress_is_new_post' ) ) :
function wallpress_is_new_post( $post_id ) {
	//Get newest point of time of each category, must be newest and lessthan 3 days
	$categories = get_the_category( $post_id );
	foreach ( $categories as $category ) {
		$date = wallpress_newest_post_category( $category->cat_ID );
		$post_date = get_the_time( 'Y-m-d', $post_id );
		//if date of post equal to date of recent post and date difference with current date less than 3 day
		if ( ( strtotime( $post_date ) - strtotime( $date ) >= 0 ) && ( ( strtotime( $post_date ) - time() )/( 60*60*24 ) > -3 ) ) {
			return true;
		}
	}
	return false;
}
endif;

/*-----------------------------------------------------------------------------------*/
/* Get newest post in a categry
/*-----------------------------------------------------------------------------------*/
if ( !function_exists( 'wallpress_newest_post_category' ) ) :
function wallpress_newest_post_category( $category_id ) {
	$args = array(
		'numberposts' => 1,
		'offset' => 0,
		'category' => $category_id,
		'post_status' => 'publish'
	);
	$posts = get_posts( $args );
	$date = get_the_time( 'Y-m-d', $posts[0]->ID );
	return $date;
}
endif;

/*-----------------------------------------------------------------------------------*/
/* Style for Login page
/*-----------------------------------------------------------------------------------*/
// Change the Login logo
add_action( 'login_enqueue_scripts', 'login_logo' );
if ( ! function_exists('login_logo') ) :

function login_logo() { ?>
<style type="text/css">
	body.login div#login h1 a {
		background-image: url(<?php echo wallpress_get_customize( 'logo_admin_image' ); ?>);
	}
</style>
<?php }
endif;

// Change the Login logo url
add_filter( 'login_headerurl', 'login_logo_url' );
if ( ! function_exists('login_logo_url') ) :
function login_logo_url() {
	return home_url();
}
endif;

// Change the Login logo url title
add_filter( 'login_headertitle', 'login_logo_url_title' );
if ( ! function_exists('login_logo_url_title') ) :
function login_logo_url_title() {
	return get_bloginfo('title');
}
endif;

/*-----------------------------------------------------------------------------------*/
/* Remove tags
/*-----------------------------------------------------------------------------------*/
add_filter('the_excerpt', 'wallpress_strip_tags');
add_filter('the_content', 'wallpress_strip_tags');
if( !function_exists('wallpress_strip_tags') ) :
function wallpress_strip_tags($text) {
	if( !is_single() & !is_page() ) {
		$text = preg_replace(
		array(
			// Remove invisible content
			'@<iframe[^>]*?>.*?</iframe>@siu',
			'@<embed[^>]*?>.*?</embed>@siu',
			'@<br.*?>@siu'
			),
		array(
		' ', ' '), $text );
		}
	return $text;
}
endif;