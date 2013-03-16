<?php
/**
 * The Sidebar containing the main widget area.
 *
 * @package WallPress
 * @since WallPress 1.0.7
 */
?>

<div id="sidebar" class="widget-area" >
	<div class="sidebar-inner">	
	<?php if ( ! dynamic_sidebar( 'sidebar-shop' ) ) : ?>
		
		<div id="meta" class="widget">
			<h2 class="widget-title"><?php _e( 'Meta', 'wallpress' ); ?></h2>
			<ul>
				<?php wp_register(); ?>
				<li><?php wp_loginout(); ?></li>
				<?php wp_meta(); ?>
			</ul>
		</div>
		
	<?php endif; // end sidebar widget area ?>
		<div id="copyright">
			<?php do_action( 'wallpress_credits' ); ?>
			<a href="<?php echo esc_url( __( 'http://wordpress.org/', 'wallpress' ) ); ?>" title="<?php esc_attr_e( 'Semantic Personal Publishing Platform', 'wallpress' ); ?>" rel="generator"><?php printf( __( 'Proudly powered by %s', 'wallpress' ), 'WordPress' ); ?></a>
		</div>
	</div>
</div><!-- #sidebar .widget-area -->