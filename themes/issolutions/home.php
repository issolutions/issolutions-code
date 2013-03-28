<?php
/**
 * The template for display Homepage if you choosed "Static Category" in Customize.
 *
 * @package WallPress
 * @since WallPress 1.0.3
 */

get_header(); 
?>

		<div id="gallery">
			<?php if ( function_exists( 'get_wp_parallax_content_slider' ) ) { get_wp_parallax_content_slider(); } ?>
		</div>
		<div id="container" class="clearfix">
				
			<div id="content">
			
			<?php query_posts( array( 'post__in' => array(72),  'orderby' => 'post__in' ) ); ?>


			  <?php while (have_posts()) : the_post(); ?>
			  <div class="main-post">
			  <h3><?php the_title(); ?></h3>
			  <p><?php the_content();?></p>
			  </div>
			  <?php endwhile;?>

			</div>
			<!-- #content -->
		
		<a href="#header" class="scroll-top">Top</a>
		</div>
		<!-- #container -->

<?php get_footer(); ?>
