<?php
/**
 * Title: Full width section (12/12)
 * Slug: twentytwentyfive/layout-full
 * Categories: layout
 * Keywords: layout, grid, full width
 * Block Types: core/group
 * Viewport width: 1400
 * Description: Single full-width area using the 12-column grid helper.
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
		<!-- wp:group {"className":"pirepe-col-12","layout":{"type":"constrained"}} -->
		<div class="wp-block-group pirepe-col-12">
			<!-- wp:heading {"level":3} -->
			<h3 class="wp-block-heading"><?php esc_html_e( 'Full width content', 'twentytwentyfive' ); ?></h3>
			<!-- /wp:heading -->
			<!-- wp:paragraph -->
			<p><?php esc_html_e( 'Drop anything here to span the full grid.', 'twentytwentyfive' ); ?></p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
