<?php
/**
 * Template Name: Sidebar left + Content
 *
 * @package WallPress
 * @since WallPress 1.0.3
 */

get_header(); ?>

	<?php get_sidebar(); ?>

		<div id="container" class="clearfix">

			<div id="content" class="masonry">

			<?php if( have_posts() ) : while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'content' ); ?>

			<?php endwhile; else :?>
				
				<h1 class="entry-title">Nothing Found</h1>

				<p>Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.</p>	
			
			<?php endif; ?>			

			</div>
			<!-- #content -->
		</div>
		<!-- #container -->

<?php get_footer(); ?>