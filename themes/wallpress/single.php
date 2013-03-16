<?php
/**
 * The Template for displaying all single posts.
 *
 * @package WallPress
 * @since WallPress 1.0.3
 */

get_header(); ?>

<?php get_sidebar(); ?>

		<div id="container" class="clearfix">

			<div id="content" class="single">

			<?php while ( have_posts() ) : the_post(); ?>
			
				<?php get_template_part( 'content-single', get_post_format() ); ?>

			<?php endwhile; ?>

			</div>
			<!-- #content -->

			<?php get_sidebar('secondary'); ?>
			
		</div>
		<!-- #container -->

<?php get_footer(); ?>
