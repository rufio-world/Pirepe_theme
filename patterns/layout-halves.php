<?php
/**
 * Title: Two columns (1/2 + 1/2)
 * Slug: twentytwentyfive/layout-halves
 * Categories: layout
 * Keywords: layout, halves, grid
 * Block Types: core/group
 * Viewport width: 1400
 * Description: Two even columns using the 12-column grid helper.
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
		<!-- wp:group {"className":"pirepe-col-6","layout":{"type":"constrained"}} -->
		<div class="wp-block-group pirepe-col-6">
			<!-- wp:heading {"level":3} -->
			<h3 class="wp-block-heading"><?php esc_html_e( 'Column one', 'twentytwentyfive' ); ?></h3>
			<!-- /wp:heading -->
			<!-- wp:paragraph -->
			<p><?php esc_html_e( 'Drop content here.', 'twentytwentyfive' ); ?></p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:group -->

		<!-- wp:group {"className":"pirepe-col-6","layout":{"type":"constrained"}} -->
		<div class="wp-block-group pirepe-col-6">
			<!-- wp:heading {"level":3} -->
			<h3 class="wp-block-heading"><?php esc_html_e( 'Column two', 'twentytwentyfive' ); ?></h3>
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
