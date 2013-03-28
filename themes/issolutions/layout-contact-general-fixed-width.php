<?php
/**
 * Template Name: Contact General Fixed Width
 *
 * @package WallPress
 * @since WallPress 1.0.3
 */

get_header(); ?>

		<div class="page-header"><?php the_title(); ?></div>

		<div id="container" class="clearfix">
						
			<div id="content">

			<?php query_posts( array( 'post__in' => array(429),  'orderby' => 'post__in' ) ); ?>
			
			  <?php while (have_posts()) : the_post(); ?>
			  <div class="main-post">
			  <h2><?php the_title(); ?></h2>
			  <p><?php the_content();?></p>
			  </div>
			  <?php endwhile;?>

			</div>
			<!-- #content -->
			<a href="#header" class="scroll-top">Top</a>
		</div>

<?php get_footer(); ?>