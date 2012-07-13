<?php
/**
 * The template for displaying search forms in Twenty Eleven
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */
?>
	<form method="get" id="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
		<label>search content</label>
		<input type="text" class="field" name="s" id="s" placeholder="<?php esc_attr_e( 'who / what / where', 'twentyeleven' ); ?>" />
	</form>
