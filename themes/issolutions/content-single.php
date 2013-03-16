<?php
/**
 * The template used for displaying single content in single.php
 *
 * @package WallPress
 * @since WallPress 1.0.3
 */
?>
<div id="item-<?php the_ID(); ?>" class="post-single">
	<div class="item-inner">
		
		<div class="item-main">
			<h1 class="item-title"><?php the_title(); ?></h1>

			<div class="item-meta meta-top clearfix">
				
				<?php
					$categories_list = get_the_category_list( __( ', ', 'wallpress' ) );
					if ( $categories_list ):
				?>
				<span class="item-category">
					<?php printf( __( '<span class="%1$s">in</span> %2$s', 'wallpress' ), 'entry-utility-prep entry-utility-prep-cat-links', $categories_list );
				$show_sep = true; ?>
				</span>
				<?php endif; ?>

			</div>

			<div class="item-content">
				<?php the_content( '' ); ?>
				<?php wp_link_pages( array( 'before' => '<div class="item-link-pages"><span> ' . __( 'Pages:', 'wallpress' ) . '</span>', 'after' => '</div>' ) ); ?>
			</div>

			<div class="post-meta meta-bottom clear">
				<?php $tags_list = get_the_tag_list( '', __( ', ', 'nex' ) ); ?>
				<?php if ( $tags_list ): ?>

				<span class="tags"><?php printf( __( '<span class="%1$s">Tagged:</span> %2$s', 'nex' ), 'entry-utility-prep entry-utility-prep-tag-links', $tags_list ); ?></span>
				<?php endif; ?>		
			</div>

		</div>
    </div>
</div>
<!-- #item-<?php the_ID(); ?> -->
