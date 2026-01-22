<?php
/**
 * Title: Two columns (1/3 + 2/3)
 * Slug: twentytwentyfive/layout-third-two-third
 * Categories: layout
 * Keywords: layout, thirds, grid
 * Block Types: core/group
 * Viewport width: 1400
 * Description: One-third plus two-thirds layout using the grid helper.
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
		<!-- wp:group {"className":"pirepe-col-4","layout":{"type":"constrained"}} -->
		<div class="wp-block-group pirepe-col-4">
			<!-- wp:heading {"level":3} -->
			<h3 class="wp-block-heading"><?php esc_html_e( 'One third', 'twentytwentyfive' ); ?></h3>
			<!-- /wp:heading -->
			<!-- wp:paragraph -->
			<p><?php esc_html_e( 'Drop content here.', 'twentytwentyfive' ); ?></p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:group -->

		<!-- wp:group {"className":"pirepe-col-8","layout":{"type":"constrained"}} -->
		<div class="wp-block-group pirepe-col-8">
			<!-- wp:heading {"level":3} -->
			<h3 class="wp-block-heading"><?php esc_html_e( 'Two thirds', 'twentytwentyfive' ); ?></h3>
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
