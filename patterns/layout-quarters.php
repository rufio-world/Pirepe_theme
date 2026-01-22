<?php
/**
 * Title: Four columns (1/4 x 4)
 * Slug: twentytwentyfive/layout-quarters
 * Categories: layout
 * Keywords: layout, quarters, grid
 * Block Types: core/group
 * Viewport width: 1400
 * Description: Four even quarter columns using the grid helper.
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_Five
 * @since Pirepe 1.0
 */

?>
<!-- wp:group {"align":"wide","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignwide">
	<!-- wp:group {"className":"pirepe-grid stack-sm","layout":{"type":"default"}} -->
	<div class="wp-block-group pirepe-grid stack-sm">
		<!-- wp:group {"className":"pirepe-col-3","layout":{"type":"constrained"}} -->
		<div class="wp-block-group pirepe-col-3">
			<!-- wp:heading {"level":3} -->
			<h3 class="wp-block-heading"><?php esc_html_e( 'Quarter one', 'twentytwentyfive' ); ?></h3>
			<!-- /wp:heading -->
			<!-- wp:paragraph -->
			<p><?php esc_html_e( 'Drop content here.', 'twentytwentyfive' ); ?></p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:group -->

		<!-- wp:group {"className":"pirepe-col-3","layout":{"type":"constrained"}} -->
		<div class="wp-block-group pirepe-col-3">
			<!-- wp:heading {"level":3} -->
			<h3 class="wp-block-heading"><?php esc_html_e( 'Quarter two', 'twentytwentyfive' ); ?></h3>
			<!-- /wp:heading -->
			<!-- wp:paragraph -->
			<p><?php esc_html_e( 'Drop content here.', 'twentytwentyfive' ); ?></p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:group -->

		<!-- wp:group {"className":"pirepe-col-3","layout":{"type":"constrained"}} -->
		<div class="wp-block-group pirepe-col-3">
			<!-- wp:heading {"level":3} -->
			<h3 class="wp-block-heading"><?php esc_html_e( 'Quarter three', 'twentytwentyfive' ); ?></h3>
			<!-- /wp:heading -->
			<!-- wp:paragraph -->
			<p><?php esc_html_e( 'Drop content here.', 'twentytwentyfive' ); ?></p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:group -->

		<!-- wp:group {"className":"pirepe-col-3","layout":{"type":"constrained"}} -->
		<div class="wp-block-group pirepe-col-3">
			<!-- wp:heading {"level":3} -->
			<h3 class="wp-block-heading"><?php esc_html_e( 'Quarter four', 'twentytwentyfive' ); ?></h3>
			<!-- /wp:heading -->
			<!-- wp:paragraph -->
			<p><?php esc_html_e( 'Drop content here.', 'twentytwentyfive' ); ?></p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
