<?php
/*
 * Plugin Name: WP Parallax Content Slider
 * Plugin URI: http://jltweb.info/realisations/wp-parallax-content-plugin/
 * Description: A customizable JQuery content slider with CSS3 animations and parallax effects
 * Author URI: http://jltweb.info/
 * Author: Julien Le Thuaut
 * Version: 0.9.1
 * Licence: GPLv2
*/
class WpParallaxContentSlider
{
	/*
	 * Parameters default values - All this parameters can be changed easyly in the plugin admin section
	 */
	var $display_mode = 'static';		// Slider display mode (static / dynamic)
	var $bgincrement = 50; 				// increment the background position (i.e. parallax effect) when sliding
	var $autoplay = 0;     				// slideshow ON (1) / OFF (0)
	var $interval = 4000;  				// time between transitions (ms)

	// - Only useful for static mode
	var $first_slide = 1;      			// index of first slide to display

	// - Only useful for dynamic mode
	var $nb_max_articles = 5;  			// Max number of articles to query in the blog database
	var $title_max_chars = 20;  		// Max number of characters to display for a slide title
	var $default_image = "default.png";	// Default image to display in dynamic mode for posts without thumbnails

	var $default_sort = "date"; 		// Default field for post sorting
	var $default_order = "desc";     	// Default type of ordering
	var $category_filter = 0;     		// Category filtering ON (1) / OFF (0)
	var $categories_to_display = '';    // Default is all (empty)

	var $themeCss = 'silver'; // default / silver / retro / dark

	/*
	 * Constructor
	 */
	function WpParallaxContentSlider()
	{
		load_plugin_textdomain( 'wp-parallax-content-slider', false, basename( dirname( __FILE__ ) ) . '/locale' );
		add_action( 'admin_menu',  array( $this, 'admin_menu' ) );
		add_shortcode( 'wp-parallax-content-slider' , array( $this, 'get_parallax_content_slider' ) );

		// Javascript and CSS init.
		wp_enqueue_script('jquery');
		wp_enqueue_script( 'wp-parallax-content-slider-modernizr', plugins_url( 'js/modernizr.custom-2.6.2.js', __FILE__ ) );
		wp_enqueue_script( 'wp-parallax-content-slider-jgestures', plugins_url( 'js/jgestures.min.js', __FILE__ ) );
		wp_enqueue_script( 'wp-parallax-content-slider-jswipe', plugins_url( 'js/jquery.jswipe.js', __FILE__ ) );
		wp_enqueue_script( 'wp-parallax-content-slider-cslider', plugins_url( 'js/jquery.cslider.js', __FILE__ ) );
		wp_enqueue_style( 'wp-parallax-content-slider-css', plugins_url( 'css/style.css', __FILE__ ) );
		// add_action('wp_head', 'parallax_slider_styles'); // When styles will have to be generated in PHP
	}

	/*
	 * Return the plugin HTML code for output
	 */
	function get_parallax_content_slider()
	{
		// --------------------------------------------------------
		// Display parameters
		$prlx_slider_mode = get_option( 'prlx_slider_mode');         		if(empty($prlx_slider_mode)){$prlx_slider_mode = $this->display_mode;} ;
		$prlx_slider_theme = get_option( 'prlx_slider_theme');         		if(empty($prlx_slider_theme)){$prlx_slider_theme = $this->themeCss;} ;
		$prlx_slider_bgincrement = get_option( 'prlx_slider_bgincrement'); 	if(empty($prlx_slider_bgincrement)){$prlx_slider_bgincrement = $this->bgincrement;} ;
		$prlx_slider_autoplay = get_option( 'prlx_slider_autoplay');       	if(empty($prlx_slider_autoplay)){$prlx_slider_autoplay = $this->autoplay;} ;
		$prlx_slider_interval = get_option( 'prlx_slider_interval');       	if(empty($prlx_slider_interval)){$prlx_slider_interval = $this->interval;} ;
		// - Static mode parameters
		$prlx_slider_first_slide = get_option( 'prlx_slider_first_slide');  if(empty($prlx_slider_first_slide)){$prlx_slider_first_slide = $this->first_slide;} ;
		// - Dynamic mode parameters
		$prlx_slider_nb_articles  = get_option( 'prlx_slider_nb_articles'); if(empty($prlx_slider_nb_articles)){$prlx_slider_nb_articles = $this->nb_max_articles;} ;
		$prlx_title_max_chars = get_option( 'prlx_title_max_chars');      	if(empty($prlx_title_max_chars)){$prlx_title_max_chars = $this->title_max_chars;} ;
		$prlx_sort = get_option( 'prlx_slider_sort_by');      				if(empty($prlx_sort)){$prlx_sort = $this->default_sort;} ;
		$prlx_order = get_option( 'prlx_slider_order_by');      			if(empty($prlx_order)){$prlx_order = $this->default_order;} ;
		$prlx_default_image = get_option( 'prlx_default_image');      		if(empty($prlx_default_image)){$prlx_default_image = $this->default_image;} ;
		$prlx_slider_category_filter
			= get_option( 'prlx_slider_category_filter');      				if(empty($prlx_slider_category_filter)){$prlx_slider_category_filter
																														= $this->category_filter;} ;
		$prlx_slider_categories
			= get_option( 'prlx_slider_categories');      					if(empty($prlx_slider_categories)){$prlx_slider_categories
																														= $this->categories_to_display;} ;

		// TODO add extra parameters (Width, height, colors, images, images size, default images...)

		switch( $prlx_slider_theme )
		{
			case 'dark' :
				wp_enqueue_style( 'wp-parallax-content-slider-css-theme', plugins_url( 'css/theme-dark.css', __FILE__ ) );
			case 'retro' :
				wp_enqueue_style( 'wp-parallax-content-slider-css-theme', plugins_url( 'css/theme-retro.css', __FILE__ ) );
			case 'silver' :
				wp_enqueue_style( 'wp-parallax-content-slider-css-theme', plugins_url( 'css/theme-silver.css', __FILE__ ) );
		}

		// --------------------------------------------------------
		// Posts selection
		global $post;

		$cat = '';
		if ($prlx_slider_category_filter)
		{
			$cat = $prlx_slider_categories;
		}

		$args = array( 'post_type' => array('post'), // not 'page'
					   'orderby' => $prlx_sort,
					   'order' => $prlx_order,
					   'numberposts' =>  $prlx_slider_nb_articles,
					   'cat' => $cat );

		$myposts = get_posts( $args );

		// --------------------------------------------------------
		// HTML Output beginning

		// TODO remove this code in production mode
		//echo $debug  = "";//prlx_slider_category_filter : " . $prlx_slider_category_filter . "<br/>";

		$outputDynamic = "<div id='da-slider' class='da-slider'>\n";

		foreach( $myposts as $post ) :	setup_postdata($post);

			$custom = get_post_custom($post->ID);
			$plugin_abs_path = plugins_url( '', __FILE__ );
			$default_slide_image = $plugin_abs_path."/images/".$prlx_default_image;

			// Display the post thumbnail if there is one (Thank you John)
			if ( has_post_thumbnail() ) {
				$thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'medium' );
				$url = $thumb['0'];
				$default_slide_image = $url;
			}

			$outputDynamic .= $this->get_article_slide( get_the_title(),
														get_the_excerpt(),
														get_permalink(),
														$default_slide_image,
														$prlx_title_max_chars)."\n";

		endforeach; wp_reset_postdata();

$outputDynamic .= <<<DYNAMICOUTPUT
<nav class="da-arrows">
	<span class="da-arrows-prev"></span>
	<span class="da-arrows-next"></span>
</nav>
</div>
DYNAMICOUTPUT;

$outputScript = <<<SCRIPTOUTPUT
<script type="text/javascript">
	jQuery(function() {
		jQuery('#da-slider').cslider({
			bgincrement	: $prlx_slider_bgincrement,
			autoplay    : $prlx_slider_autoplay,
			interval    : $prlx_slider_interval,
			current     : $prlx_slider_first_slide-1
		});

		jQuery('#da-slider').swipe({
		     swipeLeft:  function() { jQuery('#da-slider').find('span.da-arrows-next').click() },
		     swipeRight: function() { jQuery('#da-slider').find('span.da-arrows-prev').click() },
		})

		/* FIXME
		jQuery('#da-slider').bind('swipeleft',function(){
			console.log('Swipe event detected > left');
			jQuery('#da-slider').find('span.da-arrows-next').click();
		});
		jQuery('#da-slider').bind('swiperight',function(){
			console.log('Swipe event detected > right');
			jQuery('#da-slider').find('span.da-arrows-prev').click();
		});
		*/
	});
</script>
SCRIPTOUTPUT;

		// New in v0.3
		// You can modify the slides in the php file : static-slides-sample.php
		// Note : you should copy the sample file and include the new file here
		// Doing this will prevent you to lose your changes when you will update the plugin automatically
		include('static-slides-sample.php');
		if ($prlx_slider_mode === 'dynamic')
        {
        	print $outputDynamic.$outputScript;
        }
        else
        {
        	print $outputStatic.$outputScript;
        }

		// HTML Output end
		// --------------------------------------------------------
	}

	/*
	 * Generate HTML output for an article slide
	 */
	function get_article_slide($title, $excerpt, $link_article, $url_image, $title_length, $alt_image = 'Alternative text')
	{
		// Parameters
		if (strlen($title) > $title_length) $title = substr($title, 0, $title_length)."...";

		// Slide output
		$outputSlide  = "<div class='da-slide'>"."\n";
		$outputSlide .= "<h2>".$title."</h2>"."\n";
		$outputSlide .= "<p>".$excerpt."</p>"."\n";
		$outputSlide .= "<a href='".$link_article."' class='da-link'>Read more</a>"."\n";
		$outputSlide .= "<div class='da-img'><img src='".$url_image."' alt='".$alt_image."' /></div>"."\n";
		$outputSlide .= "</div>"."\n";
		return $outputSlide;
	}

	/*
	 * Add a menu to navigate to the admin interface of the plugin
	 */
	function admin_menu()
	{
		$page = add_options_page( __( 'WP Parallax Content Slider', 'wp-parallax-content-slider' ),
								  __( 'WP Parallax Content Slider', 'wp-parallax-content-slider' ),
								  'administrator', 'wp-parallax-content-slider',
								  array( $this, 'admin_interface' ) );
	}

	/*
	 * Display the admin interface to configure the plugin
	 */
	function admin_interface()
	{
		if ( isset($_POST['action']) && ($_POST['action'] == 'update') )
		{
			if ( !current_user_can( 'manage_options' ) )
			{
				wp_die( __( 'No access', 'wp-parallax-content-slider' ) );
			}

			check_admin_referer( 'wp-parallax-content-slider' );
			$validation = true;

			// Check values
			$debug = "";//POST : " . var_dump($_POST) . "<br/>";
			$error = "<ul>";
			// General parameters
			if ( empty($_POST['prlx_slider_mode']) )
			{
				$validation = false;
				$error .= "<li>".__( 'Incorrect slider mode', 'wp-parallax-content-slider' )."</li>";
			}
			if ( empty($_POST['prlx_slider_theme']) )
			{
				$validation = false;
				$error .= "<li>".__( 'Incorrect slider theme', 'wp-parallax-content-slider' )."</li>";
			}
			if ( empty($_POST['prlx_slider_bgincrement']) )
			{
				$validation = false;
				$error .= "<li>".__( 'Incorrect background increment pixel size', 'wp-parallax-content-slider' )."</li>";
			}
			if ( empty($_POST['prlx_slider_interval']) )
			{
				$validation = false;
				$error .= "<li>".__( 'Incorrect time interval', 'wp-parallax-content-slider' )."</li>";
			}
			// Static parameters
			if ( empty($_POST['prlx_slider_first_slide']) )
			{
				$validation = false;
				$error .= "<li>".__( 'Incorrect first slide number', 'wp-parallax-content-slider' )."</li>";
			}
			$error .= "</ul>";
			// Dynamic parameters
			if ( empty($_POST['prlx_slider_nb_articles']) )
			{
				$validation = false;
				$error .= "<li>".__( 'Incorrect maximum slide number', 'wp-parallax-content-slider' )."</li>";
			}
			if ( empty($_POST['prlx_title_max_chars']) )
			{
				$validation = false;
				$error .= "<li>".__( 'Incorrect maximum title length', 'wp-parallax-content-slider' )."</li>";
			}
			if ( empty($_POST['prlx_default_image']) )
			{
				$validation = false;
				$error .= "<li>".__( 'Incorrect default image', 'wp-parallax-content-slider' )."</li>";
			}

			if ( $validation )
			{
				update_option( 'prlx_slider_mode', $_POST['prlx_slider_mode'] );
				update_option( 'prlx_slider_theme', $_POST['prlx_slider_theme'] );
				update_option( 'prlx_slider_bgincrement', $_POST['prlx_slider_bgincrement'] );
				update_option( 'prlx_slider_autoplay', $_POST['prlx_slider_autoplay'] );
				update_option( 'prlx_slider_interval', $_POST['prlx_slider_interval'] );
				// Static parameters
				update_option( 'prlx_slider_first_slide', $_POST['prlx_slider_first_slide'] );
				// Dynamic parameters
				update_option( 'prlx_slider_nb_articles', $_POST['prlx_slider_nb_articles'] );
				update_option( 'prlx_title_max_chars', $_POST['prlx_title_max_chars'] );
				update_option( 'prlx_slider_sort_by', $_POST['prlx_slider_sort_by'] );
				update_option( 'prlx_slider_order_by', $_POST['prlx_slider_order_by'] );
				update_option( 'prlx_default_image', $_POST['prlx_default_image'] );
				update_option( 'prlx_slider_category_filter', $_POST['prlx_slider_category_filter'] );

				// categories (multiple selection)
				if ($_POST['prlx_slider_category_filter'] && !empty($_POST['prlx_slider_categories'])) {
					$categories_selected_values = '';
					foreach($_POST['prlx_slider_categories'] as $selected_categorie){
						$categories_selected_values.=$selected_categorie.',';
					}
				}
				update_option( 'prlx_slider_categories', $categories_selected_values );

				echo "<div class='updated fade'><p>" . __( 'Settings updated', 'wp-parallax-content-slider' ) ."</p></div>".$debug;
			}
			else
			{
				echo "<div class='error fade'><p>" . __( 'Settings update failed:', 'wp-parallax-content-slider' ) . $error . "</p></div>".$debug;
			}
		}

		// Get customized values or else default values
		$prlx_slider_mode = get_option( 'prlx_slider_mode', $this->mode );
		$prlx_slider_theme = get_option( 'prlx_slider_theme', $this->themeCss );
		$prlx_slider_bgincrement = get_option( 'prlx_slider_bgincrement', $this->bgincrement );
		$prlx_slider_autoplay = get_option( 'prlx_slider_autoplay', $this->autoplay );
		$prlx_slider_interval = get_option( 'prlx_slider_interval', $this->interval );

		$prlx_slider_first_slide = get_option( 'prlx_slider_first_slide', $this->first_slide );

		$prlx_slider_nb_articles = get_option( 'prlx_slider_nb_articles', $this->nb_max_articles );
		$prlx_title_max_chars = get_option( 'prlx_title_max_chars', $this->title_max_chars );
		$prlx_slider_sort_by = get_option( 'prlx_slider_sort_by', $this->default_sort );
		$prlx_slider_order_by = get_option( 'prlx_slider_order_by', $this->default_order );
		$prlx_default_image = get_option( 'prlx_default_image', $this->default_image );
		$prlx_slider_category_filter = get_option( 'prlx_slider_category_filter', $this->category_filter );
		$prlx_slider_categories = get_option( 'prlx_slider_categories', $this->categories_to_display );

		?>
<script type="text/javascript">
	jQuery(document).ready(function($) {
		// jQuery in the admin page
		jQuery('#prlx_slider_mode').bind("change", function() {
			if( jQuery(this).attr('value') == 'static' )
			{
				jQuery('#dynamic-options').slideUp(250, function() {
					jQuery('#static-options').slideDown(500);
				});
			}
			else
			{
				jQuery('#static-options').slideUp(250, function() {
					jQuery('#dynamic-options').slideDown(500);
				});
			}
		});
		jQuery('#prlx_slider_category_filter').bind("click", function() {
			jQuery('#filteroptions').toggle(500);
		});
	});
</script>
<div class="wrap">
	<div class="icon32" id="icon-options-general"><br /></div>
	<h2><?php _e( 'WP Parallax Content Slider Settings', 'wp-parallax-content-slider' ); ?></h2>

	<div style="margin-top:1em; border: 1px solid #FFCC99; width: 96,5%; padding: 5px 15px; -webkit-border-radius: 6px; -moz-border-radius: 6px; border-radius: 6px;">
		<p><?php
			_e( 'This plugin is continuing to evolve because of contributions from Wordpress users like you. Thank you. If you found this plugin useful, especially if you use it for commercial purposes, feel free to make a', 'wp-parallax-content-slider' );
			echo "&nbsp;<a href=\"http://jltweb.info/realisations/wp-parallax-content-plugin/#contribute\" target=\"_blank\">";
			_e( 'donation', 'wp-parallax-content-slider' );
			echo "</a>.&nbsp;";
			_e( 'Your support helps me to spend more time on development and provide better customer service.', 'wp-parallax-content-slider' );
			?>
		</p>
		<p><?php
			_e( "Alternatively, if you like this plugin, don't hesitate to ", 'wp-parallax-content-slider' );
			echo "<a href=\"https://twitter.com/JulienLeThuaut\" target=\"_blank\">";
			_e( 'spread the word', 'wp-parallax-content-slider'  );
			echo "</a>&nbsp;";
			_e( 'about it on Twitter, on Facebook,on your own blog,', 'wp-parallax-content-slider' );
			echo "&nbsp;<a href=\"http://wordpress.org/support/view/plugin-reviews/wp-parallax-content-slider\" target=\"_blank\">";
			_e( 'rate it', 'wp-parallax-content-slider'  );
			echo "</a>&nbsp;";
			_e( 'on Wordpress.org, thanks!', 'wp-parallax-content-slider' );
			?>
		</p>
	</div>

	<h3><?php _e( 'Code to insert', 'wp-parallax-content-slider' ); ?></h3>
	<p><?php _e( 'The code below must be inserted in a Wordpress file, where you want to display the parallax content slider:', 'wp-parallax-content-slider' ); ?></p>
	<code>
	if ( function_exists( 'get_wp_parallax_content_slider' ) ) {
		get_wp_parallax_content_slider();
	}
	</code>
	<p><?php _e( 'Since v0.9, an alternative is to call the plugin with a shortcode. Doing this, you will be able to include the slider inside a page or inside a blog post:', 'wp-parallax-content-slider' ); ?></p>
	<code>
	[parallaxcontentslider]
	</code>

	<form action="?page=wp-parallax-content-slider" method="POST">

		<input type="hidden" name="action" value="update" />
		<?php wp_nonce_field( 'wp-parallax-content-slider' ); ?>

		<div class="metabox-holder">
			<div class="postbox">

				<h3><?php _e( 'General Display Options', 'wp-parallax-content-slider' ); ?>:</h3>
				<table class="form-table">

					<tr>
						<th scope="row"><?php _e( 'Slider display mode', 'wp-parallax-content-slider' ); ?>:</th>
						<td>
							<select name="prlx_slider_mode" id="prlx_slider_mode">
                            	<option value="dynamic"><?php _e( 'Dynamic : display last posts', 'wp-parallax-content-slider' ); ?></option>
                            	<option value="static" <?php if ( $prlx_slider_mode === "static") echo 'selected="selected"'; ?>><?php _e( 'Static : display static HTML content', 'wp-parallax-content-slider' ); ?></option>
                            </select>
						</td>
					</tr>

					<tr>
						<th scope="row"><?php _e( 'Number of pixels for background increment', 'wp-parallax-content-slider' ); ?>:</th>
						<td>
							<input type="text" name="prlx_slider_bgincrement" id="prlx_slider_bgincrement" maxlength="5" size="5" value="<?php echo $prlx_slider_bgincrement; ?>" />
							<label for="prlx_slider_bgincrement"><?php _e( 'A negative value will invert the parallax effect', 'wp-parallax-content-slider' ); ?></label><br />
						</td>
					</tr>

					<tr valign="top">
						<th scope="row"><?php _e( 'Auto-play mode', 'wp-parallax-content-slider' ); ?>:</th>
						<td>
							<input type="checkbox" name="prlx_slider_autoplay" id="prlx_slider_autoplay" value="1" <?php if ( $prlx_slider_autoplay ) echo 'checked="checked"'; ?>/>
							<label for="prlx_slider_autoplay"><?php _e( 'Activate auto-play', 'wp-parallax-content-slider' ); ?></label><br />
						</td>
					</tr>

					<tr>
						<th scope="row"><?php _e( 'Time between each slide (in ms)', 'wp-parallax-content-slider' ); ?>:</th>
						<td>
							<input type="text" name="prlx_slider_interval" id="prlx_slider_interval" value="<?php echo $prlx_slider_interval; ?>" maxlength="5" size="5" />
						</td>
					</tr>

					<tr>
						<th scope="row"><?php _e( 'Slider theme', 'wp-parallax-content-slider' ); ?>:</th>
						<td>
							<select name="prlx_slider_theme" id="prlx_slider_theme">
                            	<option value="default"><?php _e( 'Default : Yellow waves', 'wp-parallax-content-slider' ); ?></option>
                            	<option value="dark" <?php if ( $prlx_slider_theme === "dark") echo 'selected="selected"'; ?>><?php _e( 'Dark', 'wp-parallax-content-slider' ); ?></option>
                            	<option value="retro" <?php if ( $prlx_slider_theme === "retro") echo 'selected="selected"'; ?>><?php _e( 'Retro Red', 'wp-parallax-content-slider' ); ?></option>
                            	<option value="silver" <?php if ( $prlx_slider_theme === "silver") echo 'selected="selected"'; ?>><?php _e( 'Silver', 'wp-parallax-content-slider' ); ?></option>
                            </select>
						</td>
					</tr>

				</table>
			</div>

			<div id="static-options" class="postbox" <?php if ( $prlx_slider_mode !== "static") echo 'style="display:none"'; ?>>

				<h3><?php _e( 'Static Mode Display Options', 'wp-parallax-content-slider' ); ?>:</h3>
				<table class="form-table">

					<tr>
						<th scope="row"><?php _e( 'Index of the first slide to display', 'wp-parallax-content-slider' ); ?>:</th>
						<td>
							<input type="text" name="prlx_slider_first_slide" id="prlx_slider_first_slide" value="<?php echo $prlx_slider_first_slide; ?>" maxlength="1" size="1" />
						</td>
					</tr>

				</table>
			</div>

			<div id="dynamic-options" class="postbox" <?php if ( $prlx_slider_mode === "static") echo 'style="display:none"'; ?>>

				<h3><?php _e( 'Dynamic Mode Display Options', 'wp-parallax-content-slider' ); ?>:</h3>
				<table class="form-table">

					<tr>
						<th scope="row"><?php _e( 'Number of articles to display', 'wp-parallax-content-slider' ); ?>:</th>
						<td>
							<input type="text" name="prlx_slider_nb_articles" id="prlx_slider_nb_articles" value="<?php echo $prlx_slider_nb_articles; ?>" maxlength="1" size="1" />
							<label for="prlx_slider_nb_articles"><?php _e( 'Maximum number of articles to display in the dynamic slider', 'wp-parallax-content-slider' ); ?></label><br />
						</td>
					</tr>

					<tr>
						<th scope="row"><?php _e( 'Sort posts by', 'wp-parallax-content-slider' ); ?>:</th>
						<td>
							<select name="prlx_slider_sort_by" id="prlx_slider_sort_by">
                            	<option value="date" <?php if ( $prlx_slider_sort_by === "date") echo 'selected="selected"'; ?>><?php _e( 'Date', 'wp-parallax-content-slider' ); ?></option>
                            	<option value="rand" <?php if ( $prlx_slider_sort_by === "rand") echo 'selected="selected"'; ?>><?php _e( 'Random', 'wp-parallax-content-slider' ); ?></option>
                            	<option value="title" <?php if ( $prlx_slider_sort_by === "title") echo 'selected="selected"'; ?>><?php _e( 'Title', 'wp-parallax-content-slider' ); ?></option>
                            	<option value="author" <?php if ( $prlx_slider_sort_by === "author") echo 'selected="selected"'; ?>><?php _e( 'Author', 'wp-parallax-content-slider' ); ?></option>
                            	<option value="comment_count" <?php if ( $prlx_slider_sort_by === "comment_count") echo 'selected="selected"'; ?>><?php _e( 'Number of comments', 'wp-parallax-content-slider' ); ?></option>
                            	<option value="modified" <?php if ( $prlx_slider_sort_by === "modified") echo 'selected="selected"'; ?>><?php _e( 'Last modified date', 'wp-parallax-content-slider' ); ?></option>
                            </select>
							<label for="prlx_slider_sort_by"><?php _e( 'Choose how do you want to sort the posts in the slider', 'wp-parallax-content-slider' ); ?></label><br />
						</td>
					</tr>

					<tr>
						<th scope="row"><?php _e( 'Sort order', 'wp-parallax-content-slider' ); ?>:</th>
						<td>
							<select name="prlx_slider_order_by" id="prlx_slider_order_by">
                            	<option value="asc" <?php if ( $prlx_slider_order_by === "asc") echo 'selected="selected"'; ?>><?php _e( 'Ascending', 'wp-parallax-content-slider' ); ?></option>
                            	<option value="desc" <?php if ( $prlx_slider_order_by === "desc") echo 'selected="selected"'; ?>><?php _e( 'Descending', 'wp-parallax-content-slider' ); ?></option>
                            </select>
							<label for="prlx_slider_order_by"><?php _e( 'Choose how do you want to order the posts in the slider', 'wp-parallax-content-slider' ); ?></label><br />
						</td>
					</tr>

					<tr>
						<th scope="row"><?php _e( 'Category filter', 'wp-parallax-content-slider' ); ?>:</th>
						<td>
							<input type="checkbox" name="prlx_slider_category_filter" id="prlx_slider_category_filter" value="1" <?php if ( $prlx_slider_category_filter ) echo 'checked="checked"'; ?>/>
							<label for="prlx_slider_category_filter"><?php _e( 'Only display posts of chosen categories', 'wp-parallax-content-slider' ); ?></label><br />
						</td>
					</tr>

					<tr id="filteroptions" <?php if ( !$prlx_slider_category_filter) echo 'style="display:none"'; ?>>
						<th scope="row"><?php _e( 'Categories to display', 'wp-parallax-content-slider' ); ?>:</th>
						<td>
							<select name="prlx_slider_categories[]" id="prlx_slider_categories" multiple="multiple" size="3" style="vertical-align: top;">
                            	<?php
                            		$args = array( 'orderby' => 'name',
												   'order' => 'ASC',
                            					   'hide_empty' => 1 ); // Set to 0 if you want to show empty categories
                            		$wp_categories = get_categories( $args );

                            		// Get selected values
                            		$prlx_slider_categories_array = preg_split("/[\s,]+/",$prlx_slider_categories);

                            		//var_dump($prlx_slider_categories);
                            		//var_dump($wp_categories);

                            		foreach ($wp_categories as $i => $categ)
                            		{
                            			echo '<option value="'.$categ->term_id.'" ';
                            			if (in_array($categ->term_id, $prlx_slider_categories_array))
                            					echo 'selected="selected"';
                            			echo '>'.$categ->name.'</option>\n';
                            		}
                            	?>
                            </select>

							<label for="prlx_slider_categories"><?php _e( 'Categories to display (multiple selection). Empty selection will display all categories.', 'wp-parallax-content-slider' ); ?></label><br />
						</td>
					</tr>

					<tr>
						<th scope="row"><?php _e( 'Slide title max length', 'wp-parallax-content-slider' ); ?>:</th>
						<td>
							<input type="text" name="prlx_title_max_chars" id="prlx_title_max_chars" value="<?php echo $prlx_title_max_chars; ?>" maxlength="5" size="5" />
							<label for="prlx_title_max_chars"><?php _e( 'Maximum number of characters to display in a dynamic slide title', 'wp-parallax-content-slider' ); ?></label><br />
						</td>
					</tr>

					<tr>
						<th scope="row"><?php _e( 'Default image', 'wp-parallax-content-slider' ); ?>:</th>
						<td>
							plugins/wp-parallax-content-slider/images/<input type="text" name="prlx_default_image" id="prlx_default_image" value="<?php echo $prlx_default_image; ?>" />
							<label for="prlx_default_image"><?php _e( 'Name of the default image to display for posts without thumbnail', 'wp-parallax-content-slider' ); ?></label><br />
						</td>
					</tr>

				</table>
			</div>

			<input name="Submit" value="<?php _e( 'Save Changes', 'wp-parallax-content-slider' ); ?>" type="submit">
		</div>
	</form>
</div>

<?php
	}
}

add_action( 'init', 'wp_parallax_content_slider' );

function wp_parallax_content_slider()
{
	global $wp_parallax_content_slider;
	$wp_parallax_content_slider = new WpParallaxContentSlider();
}

function get_wp_parallax_content_slider()
{
	global $wp_parallax_content_slider;
	echo $wp_parallax_content_slider->get_parallax_content_slider();
}

//Enable shortcode : [parallaxcontentslider]
function parallaxcontentslider_func( $atts ){
	get_wp_parallax_content_slider();
}

add_shortcode( 'parallaxcontentslider', 'parallaxcontentslider_func' );

?>
