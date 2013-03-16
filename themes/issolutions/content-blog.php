<?php
/**
 * The template used for displaying page content in layout-blog.php
 *
 * @package WallPress
 * @since WallPress 1.0.3
 */
?>
<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="post-inner">

				<a href="<?php echo wp_get_attachment_url( get_post_thumbnail_id() ); ?>" class="image-btn zoom" title="<?php printf( esc_attr__( '%s', 'wallpress' ), the_title_attribute( 'echo=0' ) ); ?>" ><?php the_title(); ?></a>
		<?php endif; ?>
		</div>
		<?php endif; ?>

		<div class="post-main">
			<h2 class="post-title">
			<a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'wallpress' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a>
			</h2>

			<div class="post-meta meta-top clearfix">
			
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

			<div class="post-content">
				<?php if ( is_search() ) : ?>
					<?php the_excerpt(); ?>
				<?php else : ?>
					<?php
						global $more;
						$more = 0;
						the_content( '' );
					?>
					<?php wp_link_pages( array( 'before' => '<div class="item-link-pages"><span> ' . __( 'Pages:', 'wallpress' ) . '</span>', 'after' => '</div>' ) ); ?>
				<?php endif; ?>
			</div>

			<div class="post-meta meta-bottom clear">
				<?php $tags_list = get_the_tag_list( '', __( ', ', 'nex' ) ); ?>
				<?php if ( $tags_list ): ?>

				<span class="tags"><?php printf( __( '<span class="%1$s">Tagged:</span> %2$s', 'nex' ), 'entry-utility-prep entry-utility-prep-tag-links', $tags_list ); ?></span>
				<?php endif; ?>

				<?php $show_sep = true; ?>
				<span class="item-permalink">
					<a title="<?php the_title(); ?>" href="<?php echo get_permalink(); ?>"><?php _e( 'Read more', 'wallpress' ); ?></a>
				</span>
				<?php if ( $show_sep ) : ?>
				<span class="sep"> &bull; </span>
				<?php endif; // End if $show_sep ?>
				
			</div>
		</div>
		
    </div>
</div>
<!-- #post-<?php the_ID(); ?> -->
