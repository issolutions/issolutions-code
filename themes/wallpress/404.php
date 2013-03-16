<?php
/**
 *
 * @package WallPress
 * @since WallPress 1.0.3
 */

get_header(); ?>

		<div id="container" class="clearfix">
			
			<div id="content">
			<div id="errorboxoutline">
				<div id="error-code"><?php _e('404', 'WallPress') ?></div>
				<div id="error-message"><?php _e('Nothing Found', 'WallPress') ?></div>
			</div>
			</div>
			<!-- #content -->
		</div>
		<!-- #container -->

<?php get_footer(); ?>