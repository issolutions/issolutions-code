<?php
/**
 * Template Name: About Fixed Width
 *
 * @package WallPress
 * @since WallPress 1.0.3
 */

get_header(); ?>
<div class="page-header"><?php the_title(); ?></div>

		<div id="container" class="clearfix">
						
			<div id="content">
			<?php query_posts( array( 'post__in' => array(82),  'orderby' => 'post__in' ) ); ?>
			
			  <?php while (have_posts()) : the_post(); ?>
			  <div class="main-post">
			  <h2><?php the_title(); ?></h2>
			  <p><?php the_content();?></p>
			  </div>
			  <?php endwhile;?>

			</div>
			
			<div style="margin:10px auto; width:100%; max-width:50px; height:2px;border-bottom:2px dotted #0095dc;"></div>
			
			<div id="content">
			<?php query_posts( array( 'post__in' => array(91),  'orderby' => 'post__in' ) ); ?>
			
			  <?php while (have_posts()) : the_post(); ?>
			  <div class="main-post">
			  <h2><?php the_title(); ?></h2>
			  <p><?php the_content();?></p>
			  </div>
			  <?php endwhile;?>

			</div>
			
			<div style="margin:10px auto; width:100%; max-width:50px; height:2px;border-bottom:2px dotted #0095dc;"></div>
			
			<div id="content">
			<?php query_posts( array( 'post__in' => array(93),  'orderby' => 'post__in' ) ); ?>
			
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
		<!-- #container -->

<?php get_footer(); ?>