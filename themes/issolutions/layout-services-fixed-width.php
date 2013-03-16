<?php
/**
 * Template Name: Services Fixed Width
 *
 * @package WallPress
 * @since WallPress 1.0.3
 */

get_header(); ?>
<div class="page-header"><?php the_title(); ?></div>

		<div id="container" class="clearfix">
		
			<div id="content" style="background:#1d6a98; padding:0px;">
			
			  <div class="main-post" style="margin-right: 0px;">
			  <div style="width:100%; max-width:400px; float: left;">
			  <div style="color:#ffffff; font-family: 'Cuprum', Arial, sans-serif; font-size: 32px; font-weight:bold; margin: 10px;">Contact A Representative</div>
			  </div>
			  <a href="../contact-iss/employers/">
			  <div style="width:100%; max-width:270px; float: right; background:#278fcc; padding:10px; color:#ffffff; font-family: 'Cuprum', Arial, sans-serif; font-size: 32px; font-weight:bold;">
				Sign Up Now >
			  </div></a>
			  <div class="spacer" style="clear: both;"></div>
			  </div>
			  
			</div>
			
			<div style="margin:10px auto; width:100%; max-width:50px; height:2px;border-bottom:2px dotted #0095dc;"></div>
						
			<div id="content">
			
			<?php query_posts( array( 'post__in' => array(227),  'orderby' => 'post__in' ) ); ?>
			
			  <?php while (have_posts()) : the_post(); ?>
			  <div class="main-post">
			  <h2><?php the_title(); ?></h2>
			  <p><?php the_content();?></p>
			  </div>
			  <?php endwhile;?>
			  
			</div>
			
			<div style="margin:10px auto; width:100%; max-width:50px; height:2px;border-bottom:2px dotted #0095dc;"></div>
			
			<div id="content">
			
			<?php query_posts( array( 'post__in' => array(234),  'orderby' => 'post__in' ) ); ?>
			
			  <?php while (have_posts()) : the_post(); ?>
			  <div class="main-post">
			  <h2><?php the_title(); ?></h2>
			  <p><?php the_content();?></p>
			  </div>
			  <?php endwhile;?>

			</div>
			
			<div style="margin:10px auto; width:100%; max-width:50px; height:2px;border-bottom:2px dotted #0095dc;"></div>
			
			<div id="content">
			
			<?php query_posts( array( 'post__in' => array(222),  'orderby' => 'post__in' ) ); ?>
			
			  <?php while (have_posts()) : the_post(); ?>
			  <div class="main-post">
			  <p><?php the_content();?></p>
			  </div>
			  <?php endwhile;?>

			</div>
			
			<div style="margin:10px auto; width:100%; max-width:50px; height:2px;border-bottom:2px dotted #0095dc;"></div>
			
			<div id="content" style="background:#1d6a98; padding:0px;">
			
			  <div class="main-post" style="margin-right: 0px;">
			  <div style="width:100%; max-width:400px; float: left;">
			  <div style="color:#ffffff; font-family: 'Cuprum', Arial, sans-serif; font-size: 32px; font-weight:bold; margin: 10px;">Contact A Representative</div>
			  </div>
			  <a href="../contact-iss/employers/">
			  <div style="width:100%; max-width:270px; float: right; background:#278fcc; padding:10px; color:#ffffff; font-family: 'Cuprum', Arial, sans-serif; font-size: 32px; font-weight:bold;">
				Sign Up Now >
			  </div></a>
			  <div class="spacer" style="clear: both;"></div>
			  </div>
			  
			</div>
			<!-- #content -->
			<a href="#header" class="scroll-top">Top</a>
		</div>
		<!-- #container -->

<?php get_footer(); ?>