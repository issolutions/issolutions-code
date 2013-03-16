<?php
/**
 * The Sidebar containing the secondary widget area.
 *
 * @package WallPress
 * @since WallPress 1.0.7
 */
?>
<div id="sidebar-secondary" class="widget-area">
	<div class="sidebar-secondary-inner  masonry">
		<?php if ( ! dynamic_sidebar( 'sidebar-secondary' ) ) : ?>

			<div id="search" class="widget widget_search item">
				<div class="widget-inner">
					<?php get_search_form(); ?>
				</div>
			</div>

			<div id="archives" class="widget widget_archive item">
				<div class="widget-inner">
					<h2 class="widget-title"><?php _e( 'Archives', 'wallpress' ); ?></h2>
					<ul>
						<?php wp_get_archives( array( 'type' => 'monthly' ) ); ?>
					</ul>
				</div>
			</div>
			
			<div id="meta" class="widget widget_meta item">
				<div class="widget-inner">
					<h2 class="widget-title"><?php _e( 'Meta', 'wallpress' ); ?></h2>
					<ul>
						<?php wp_register(); ?>
						<li><?php wp_loginout(); ?></li>
						<?php wp_meta(); ?>
					</ul>
				</div>
			</div>
			
		<?php endif; // end sidebar widget area ?>
	</div>
</div><!-- #sidebar .widget-area -->