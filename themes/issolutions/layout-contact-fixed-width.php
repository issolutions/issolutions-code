<?php
/**
 * Template Name: Contact Fixed Width
 *
 * @package WallPress
 * @since WallPress 1.0.3
 */

get_header(); ?>
<div class="page-header"><?php the_title(); ?></div>

		<div id="container" class="clearfix">
						
			<div id="content">

			<?php query_posts( array( 'post__in' => array(141),  'orderby' => 'post__in' ) ); ?>
			
			  <?php while (have_posts()) : the_post(); ?>
			  <div class="main-post">
			  <p><?php the_content();?></p>
			  </div>
			  <?php endwhile;?>

			</div>
			<!-- #content -->
			<a href="#header" class="scroll-top">Top</a>
		</div>
		<!-- #container -->

<?php get_footer(); ?>