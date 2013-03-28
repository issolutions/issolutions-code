<?php
/**
 * The template for display Homepage if you choosed "Static Category" in Customize.
 *
 * @package WallPress
 * @since WallPress 1.0.3
 */

get_header(); 

if( wallpress_get_customize( 'static_cat', array() ) ) {
	$cat_arr = wallpress_get_customize( 'static_cat', array());
	if($cat_arr[0] != '-1') {
		$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
		$wp_query = new WP_Query( 'cat='.implode(',', $cat_arr).'&paged=' . $paged );
	}
}
?>

<?php get_sidebar(); ?>

		<div id="container" class="clearfix">
			
			<div id="content" class="masonry">

			<?php if ( have_posts() ) : ?>

				<?php while ( have_posts() ) : the_post(); ?>

					<?php get_template_part( 'content', get_post_format() ); ?>

				<?php endwhile; ?>

				<?php if ( $wp_query->max_num_pages > 1 ) : ?>
				<div class="navigation">
					<?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'wallpress' ) ); ?>
					<?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'wallpress' ) ); ?>
				</div>
				<?php endif; ?>

			<?php endif; ?>

			<?php wp_reset_query(); ?>
			</div>
			<!-- #content -->

		<a href="#header" class="scroll-top">Top</a>
		</div>
		<!-- #container -->

<?php get_footer(); ?>