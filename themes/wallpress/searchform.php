<?php
/**
 * The template for displaying search forms in WallPress
 *
 * @package WallPress
 * @since WallPress 1.0.3
 */
?>
<form method="get" id="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label for="s" class="assistive-text"><?php _e( 'Search', 'wallpress' ); ?></label>
	<input type="text" class="field" name="s" id="s" placeholder="<?php esc_attr_e( '', 'wallpress' ); ?>" />
	<input type="submit" class="submit" name="submit" id="searchsubmit" value="<?php esc_attr_e( 'Search', 'wallpress' ); ?>" />
</form>
