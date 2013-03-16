<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package WallPress
 * @since WallPress 1.0.3
 */
?>
<div id="item-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="item-inner">
		<div class="item-main">
			
			<div class="item-content">
				<?php the_content( '' ); ?>
				<?php wp_link_pages( array( 'before' => '<div class="item-link-pages"><span> ' . __( 'Pages:', 'wallpress' ) . '</span>', 'after' => '</div>' ) ); ?>
			</div>
		</div>
    </div>
</div>
<!-- #item-<?php the_ID(); ?> -->
