<?php
/**
 * The template used for displaying single content in single.php
 *
 * @package WallPress
 * @since WallPress 1.0.3
 */
?>
<div id="item-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="item-inner">

		<?php if( has_post_format( 'gallery') ) echo wallpress_gallery(); ?>

		<?php if(has_post_format('audio')) wallpress_audio_player( get_the_ID() ); ?>
		
		<div class="item-main">
			<h1 class="item-title"><?php the_title(); ?></h1>

			<div class="item-meta meta-top clearfix">
				<span class="item-author">
					<?php _e( 'By', 'wallpress' );?>
					<?php the_author_posts_link(); ?>
				</span>
				<?php
					$categories_list = get_the_category_list( __( ', ', 'wallpress' ) );
					if ( $categories_list ):
				?>
				<span class="item-category">
					<?php printf( __( '<span class="%1$s">in</span> %2$s', 'wallpress' ), 'entry-utility-prep entry-utility-prep-cat-links', $categories_list );
				$show_sep = true; ?>
				</span>
				<?php endif; ?>

				<span class="item-date">
					<?php _e( 'on', 'wallpress' ); ?>
					<?php the_time(get_option('date_format')); ?>
				</span>
			</div>

			<?php if( has_post_format( 'image') ) : ?>
			<div class="item-thumbnail">
				<?php the_post_thumbnail('full'); ?>
			</div>
			<?php endif; ?>

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

			<div class="item-comments">
				<?php comments_template( '', true ); ?>
			</div>
		</div>
    </div>
</div>
<!-- #item-<?php the_ID(); ?> -->
