<?php
/**
 * Template Name: Fixed Width
 *
 * @package WallPress
 * @since WallPress 1.0.3
 */

get_header(); ?>
<div class="page-header"><?php the_title(); ?></div>
		<div id="container" class="clearfix">
						
			<div id="content">

			<?php while ( have_posts() ) : the_post(); ?>
				<div class="main-post">
					<?php get_template_part( 'content-page', get_post_format() ); ?>
				</div>
			<?php endwhile; ?>

			</div>
			<!-- #content -->
			<a href="#header" class="scroll-top">Top</a>
		</div>
		<!-- #container -->

<?php get_footer(); ?>