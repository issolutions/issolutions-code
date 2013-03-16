<?php
/**
 * Loop shop template
 *
 * DISCLAIMER
 *
 * Do not edit or add directly to this file if you wish to upgrade Jigoshop to newer
 * versions in the future. If you wish to customise Jigoshop core for your needs,
 * please use our GitHub repository to publish essential changes for consideration.
 *
 * @package             Jigoshop
 * @category            Catalog
 * @author              Jigowatt
 * @copyright           Copyright © 2011-2012 Jigowatt Ltd.
 * @license             http://jigoshop.com/license/commercial-edition
 */
?>

<?php
global $columns, $per_page;

do_action('jigoshop_before_shop_loop');

$loop = 0;

if (!isset($columns) || !$columns) $columns = apply_filters('loop_shop_columns', 4);
//if (!isset($per_page) || !$per_page) $per_page = apply_filters('loop_shop_per_page', get_option('posts_per_page'));

//if ($per_page > get_option('posts_per_page')) query_posts( array_merge( $wp_query->query, array( 'posts_per_page' => $per_page ) ) );

ob_start();

if ( ! have_posts() ) { wp_reset_query(); }
if (have_posts()) : while (have_posts()) : the_post(); $_product = new jigoshop_product( $post->ID ); $loop++;

	?>
	<div class="item product 
			<?php if(  get_post_meta(get_the_ID(), 'featured', true) ) { echo 'featured'; } ?> <?php if ($loop%$columns==0) echo 'last'; if (($loop-1)%$columns==0) echo 'first'; ?>">
		<div class="item-inner">

		<?php do_action('jigoshop_before_shop_loop_item'); ?>

		<?php do_action('jigoshop_before_shop_loop_item_title', $post, $_product); ?>

		<h2 class="item-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>

		<?php do_action('jigoshop_after_shop_loop_item_title', $post, $_product); ?>

		<?php do_action('jigoshop_after_shop_loop_item', $post, $_product); ?>

		</div>

	</div><?php

	if ($loop==$per_page) break;

endwhile; endif;

if ($loop==0) :

	echo '<p class="info">'.__('No products found which match your selection.', 'jigoshop').'</p>';

else :

	$found_posts = ob_get_clean();

	echo $found_posts;

endif;

do_action('jigoshop_after_shop_loop');
