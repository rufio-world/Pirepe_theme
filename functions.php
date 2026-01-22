<?php
/**
 * Twenty Twenty-Five functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_Five
 * @since Twenty Twenty-Five 1.0
 */

// Adds theme support for post formats.
if ( ! function_exists( 'twentytwentyfive_post_format_setup' ) ) :
	/**
	 * Adds theme support for post formats.
	 *
	 * @since Twenty Twenty-Five 1.0
	 *
	 * @return void
	 */
	function twentytwentyfive_post_format_setup() {
		add_theme_support( 'post-formats', array( 'aside', 'audio', 'chat', 'gallery', 'image', 'link', 'quote', 'status', 'video' ) );
	}
endif;
add_action( 'after_setup_theme', 'twentytwentyfive_post_format_setup' );

// Enqueues editor-style.css in the editors.
if ( ! function_exists( 'twentytwentyfive_editor_style' ) ) :
	/**
	 * Enqueues editor-style.css in the editors.
	 *
	 * @since Twenty Twenty-Five 1.0
	 *
	 * @return void
	 */
	function twentytwentyfive_editor_style() {
		add_editor_style( 'assets/css/editor-style.css' );
	}
endif;
add_action( 'after_setup_theme', 'twentytwentyfive_editor_style' );

// Enqueues the theme stylesheet on the front.
if ( ! function_exists( 'twentytwentyfive_enqueue_styles' ) ) :
	/**
	 * Enqueues the theme stylesheet on the front.
	 *
	 * @since Twenty Twenty-Five 1.0
	 *
	 * @return void
	 */
	function twentytwentyfive_enqueue_styles() {
		$suffix = SCRIPT_DEBUG ? '' : '.min';
		$src    = 'style' . $suffix . '.css';

		wp_enqueue_style(
			'twentytwentyfive-style',
			get_parent_theme_file_uri( $src ),
			array(),
			wp_get_theme()->get( 'Version' )
		);
		wp_style_add_data(
			'twentytwentyfive-style',
			'path',
			get_parent_theme_file_path( $src )
		);
	}
endif;
add_action( 'wp_enqueue_scripts', 'twentytwentyfive_enqueue_styles' );

// Registers custom block styles.
if ( ! function_exists( 'twentytwentyfive_block_styles' ) ) :
	/**
	 * Registers custom block styles.
	 *
	 * @since Twenty Twenty-Five 1.0
	 *
	 * @return void
	 */
	function twentytwentyfive_block_styles() {
		register_block_style(
			'core/list',
			array(
				'name'         => 'checkmark-list',
				'label'        => __( 'Checkmark', 'twentytwentyfive' ),
				'inline_style' => '
				ul.is-style-checkmark-list {
					list-style-type: "\2713";
				}

				ul.is-style-checkmark-list li {
					padding-inline-start: 1ch;
				}',
			)
		);
	}
endif;
add_action( 'init', 'twentytwentyfive_block_styles' );

// Registers layout utility block styles for boxed and full-bleed sections.
if ( ! function_exists( 'pirepe_layout_block_styles' ) ) :
	/**
	 * Adds layout helper block styles to surface boxed/full-bleed toggles in the editor.
	 *
	 * @since Pirepe 1.0
	 *
	 * @return void
	 */
	function pirepe_layout_block_styles() {
		register_block_style(
			'core/group',
			array(
				'name'         => 'pirepe-boxed',
				'label'        => __( 'Boxed canvas', 'twentytwentyfive' ),
				'inline_style' => '.is-style-pirepe-boxed{}',
			)
		);

		register_block_style(
			'core/group',
			array(
				'name'         => 'pirepe-full-bleed',
				'label'        => __( 'Full-bleed stripe', 'twentytwentyfive' ),
				'inline_style' => '.is-style-pirepe-full-bleed{}',
			)
		);
	}
endif;
add_action( 'init', 'pirepe_layout_block_styles' );

// Enqueue editor enhancements (layout presets, grid span controls, visibility toggles).
if ( ! function_exists( 'pirepe_enqueue_editor_assets' ) ) :
	/**
	 * Loads custom editor script for layout controls.
	 *
	 * @since Pirepe 1.0
	 *
	 * @return void
	 */
	function pirepe_enqueue_editor_assets() {
		if ( ! current_user_can( 'edit_theme_options' ) ) {
			return;
		}
		$handle = 'pirepe-editor';
		wp_enqueue_script(
			$handle,
			get_theme_file_uri( 'assets/js/editor.js' ),
			array( 'wp-hooks', 'wp-compose', 'wp-element', 'wp-components', 'wp-blocks', 'wp-block-editor', 'wp-i18n', 'wp-data', 'wp-edit-post' ),
			filemtime( get_theme_file_path( 'assets/js/editor.js' ) ),
			true
		);
	}
endif;
add_action( 'enqueue_block_editor_assets', 'pirepe_enqueue_editor_assets' );

/**
 * PERFORMANCE TWEAKS
 */
if ( ! function_exists( 'pirepe_performance_tweaks' ) ) :
	/**
	 * Disable emojis and oEmbed discovery to reduce requests.
	 *
	 * @return void
	 */
	function pirepe_performance_tweaks() {
		// Emojis.
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_action( 'admin_print_styles', 'print_emoji_styles' );

		// oEmbed discovery links.
		remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
		remove_action( 'wp_head', 'rest_output_link_wp_head' );
	}
endif;
add_action( 'init', 'pirepe_performance_tweaks' );

// Ensure lazy loading remains enabled but allow opting out via filter.
add_filter( 'wp_lazy_loading_enabled', '__return_true' );

/**
 * SEO / SCHEMA: Basic Organization schema in head.
 */
if ( ! function_exists( 'pirepe_output_schema' ) ) :
	/**
	 * Outputs lightweight Organization schema using site data.
	 *
	 * @return void
	 */
	function pirepe_output_schema() {
		if ( is_admin() ) {
			return;
		}

		$site_name = get_bloginfo( 'name' );
		$site_url  = home_url( '/' );
		$logo_id   = get_theme_mod( 'custom_logo' );
		$logo_url  = $logo_id ? wp_get_attachment_image_url( $logo_id, 'full' ) : '';

		$schema = array(
			'@context'   => 'https://schema.org',
			'@type'      => 'Organization',
			'name'       => $site_name,
			'url'        => $site_url,
			'logo'       => $logo_url ?: $site_url,
			'sameAs'     => array(),
			'description'=> get_bloginfo( 'description' ),
		);

		echo '<script type="application/ld+json">' . wp_json_encode( $schema ) . '</script>';
	}
endif;
add_action( 'wp_head', 'pirepe_output_schema' );

/**
 * PATTERN EXPORT/IMPORT (theme-scoped).
 */
if ( ! function_exists( 'pirepe_register_custom_patterns' ) ) :
	/**
	 * Register user-imported patterns stored in the options table.
	 *
	 * @return void
	 */
	function pirepe_register_custom_patterns() {
		$patterns = get_option( 'pirepe_custom_patterns', array() );
		if ( empty( $patterns ) || ! is_array( $patterns ) ) {
			return;
		}

		foreach ( $patterns as $pattern ) {
			if ( empty( $pattern['slug'] ) || empty( $pattern['title'] ) || empty( $pattern['content'] ) ) {
				continue;
			}

			register_block_pattern(
				sanitize_key( $pattern['slug'] ),
				array(
					'title'         => wp_strip_all_tags( $pattern['title'] ),
					'description'   => isset( $pattern['description'] ) ? sanitize_text_field( $pattern['description'] ) : '',
					'categories'    => isset( $pattern['categories'] ) && is_array( $pattern['categories'] ) ? array_map( 'sanitize_key', $pattern['categories'] ) : array( 'layout' ),
					'content'       => $pattern['content'],
					'inserter'      => true,
				)
			);
		}
	}
endif;
add_action( 'init', 'pirepe_register_custom_patterns', 20 );

if ( ! function_exists( 'pirepe_patterns_admin_menu' ) ) :
	/**
	 * Adds a Patterns Toolkit admin page for export/import.
	 *
	 * @return void
	 */
	function pirepe_patterns_admin_menu() {
		if ( ! current_user_can( 'edit_theme_options' ) ) {
			return;
		}

		add_submenu_page(
			'themes.php',
			__( 'Pirepe Patterns', 'twentytwentyfive' ),
			__( 'Pirepe Patterns', 'twentytwentyfive' ),
			'edit_theme_options',
			'pirepe-patterns',
			'pirepe_render_patterns_page'
		);
	}
endif;
add_action( 'admin_menu', 'pirepe_patterns_admin_menu' );

if ( ! function_exists( 'pirepe_render_patterns_page' ) ) :
	/**
	 * Render admin page.
	 *
	 * @return void
	 */
	function pirepe_render_patterns_page() {
		if ( ! current_user_can( 'edit_theme_options' ) ) {
			return;
		}
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Pirepe Pattern Library', 'twentytwentyfive' ); ?></h1>
			<p><?php esc_html_e( 'Export theme-registered patterns or import custom patterns as JSON.', 'twentytwentyfive' ); ?></p>
			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
				<?php wp_nonce_field( 'pirepe_export_patterns' ); ?>
				<input type="hidden" name="action" value="pirepe_export_patterns" />
				<?php submit_button( __( 'Export Patterns JSON', 'twentytwentyfive' ), 'primary' ); ?>
			</form>
			<hr />
			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" enctype="multipart/form-data">
				<?php wp_nonce_field( 'pirepe_import_patterns' ); ?>
				<input type="hidden" name="action" value="pirepe_import_patterns" />
				<p>
					<label for="pirepe_patterns_file"><?php esc_html_e( 'Import JSON file', 'twentytwentyfive' ); ?></label><br />
					<input type="file" id="pirepe_patterns_file" name="pirepe_patterns_file" accept=".json" required />
				</p>
				<p>
					<label for="pirepe_import_mode"><?php esc_html_e( 'When items already exist', 'twentytwentyfive' ); ?></label><br />
					<select id="pirepe_import_mode" name="pirepe_import_mode">
						<option value="skip"><?php esc_html_e( 'Skip existing', 'twentytwentyfive' ); ?></option>
						<option value="overwrite"><?php esc_html_e( 'Overwrite existing', 'twentytwentyfive' ); ?></option>
					</select>
				</p>
				<?php submit_button( __( 'Import Patterns', 'twentytwentyfive' ), 'secondary' ); ?>
			</form>
		</div>
		<?php
	}
endif;

if ( ! function_exists( 'pirepe_patterns_admin_notices' ) ) :
	/**
	 * Show status messages on the patterns admin page.
	 *
	 * @return void
	 */
	function pirepe_patterns_admin_notices() {
		if ( ! isset( $_GET['page'] ) || 'pirepe-patterns' !== $_GET['page'] ) { // phpcs:ignore WordPress.Security.NonceVerification
			return;
		}
		$status = isset( $_GET['pirepe_import'] ) ? sanitize_key( wp_unslash( $_GET['pirepe_import'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification
		$export = isset( $_GET['pirepe_export'] ) ? sanitize_key( wp_unslash( $_GET['pirepe_export'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification

		$messages = array(
			'success'      => array( __( 'Patterns/templates imported successfully.', 'twentytwentyfive' ), 'notice-success' ),
			'invalid'      => array( __( 'Import failed: invalid or empty JSON.', 'twentytwentyfive' ), 'notice-error' ),
			'missing'      => array( __( 'Import failed: file missing.', 'twentytwentyfive' ), 'notice-error' ),
			'export_empty' => array( __( 'Export aborted: no patterns/templates found.', 'twentytwentyfive' ), 'notice-warning' ),
		);

		$slug = $status ? $status : $export;
		if ( $slug && isset( $messages[ $slug ] ) ) {
			printf( '<div class="notice %1$s"><p>%2$s</p></div>', esc_attr( $messages[ $slug ][1] ), esc_html( $messages[ $slug ][0] ) );
		}

		// Export success notice (stored as option because download keeps the same page URL).
		$export_status = get_option( 'pirepe_last_export_status', array() );
		if ( ! empty( $export_status['timestamp'] ) ) {
			$pattern_count   = isset( $export_status['patterns'] ) ? (int) $export_status['patterns'] : 0;
			$template_count  = isset( $export_status['templates'] ) ? (int) $export_status['templates'] : 0;
			$part_count      = isset( $export_status['templateParts'] ) ? (int) $export_status['templateParts'] : 0;
			$synced_count    = isset( $export_status['syncedPatterns'] ) ? (int) $export_status['syncedPatterns'] : 0;
			$timestamp_human = date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), $export_status['timestamp'] );
			$message         = sprintf(
				/* translators: 1: pattern count, 2: template count, 3: template part count, 4: synced pattern count, 5: timestamp */
				__( 'Exported %1$d patterns, %2$d templates, %3$d template parts, %4$d synced patterns at %5$s.', 'twentytwentyfive' ),
				$pattern_count,
				$template_count,
				$part_count,
				$synced_count,
				$timestamp_human
			);
			printf( '<div class="notice notice-success"><p>%s</p></div>', esc_html( $message ) );
			delete_option( 'pirepe_last_export_status' );
		}

		// Import duplicate report.
		$import_report = get_option( 'pirepe_last_import_report', array() );
		if ( ! empty( $import_report['mode'] ) ) {
			$mode     = $import_report['mode'];
			$skipped  = isset( $import_report['skipped'] ) ? (array) $import_report['skipped'] : array();
			$overwrote = isset( $import_report['overwrote'] ) ? (array) $import_report['overwrote'] : array();
			$lines    = array();
			if ( $skipped ) {
				$lines[] = sprintf(
					/* translators: %s: comma separated slugs */
					__( 'Skipped (existing): %s', 'twentytwentyfive' ),
					implode( ', ', array_map( 'esc_html', $skipped ) )
				);
			}
			if ( $overwrote ) {
				$lines[] = sprintf(
					/* translators: %s: comma separated slugs */
					__( 'Overwrote: %s', 'twentytwentyfive' ),
					implode( ', ', array_map( 'esc_html', $overwrote ) )
				);
			}
			if ( $lines ) {
				printf(
					'<div class="notice notice-info"><p>%s</p><p>%s</p></div>',
					esc_html( sprintf( __( 'Import mode: %s', 'twentytwentyfive' ), $mode ) ),
					implode( '<br>', $lines ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				);
			}
			delete_option( 'pirepe_last_import_report' );
		}
	}
endif;
add_action( 'admin_notices', 'pirepe_patterns_admin_notices' );

if ( ! function_exists( 'pirepe_handle_export_patterns' ) ) :
	/**
	 * Handle export request.
	 *
	 * @return void
	 */
	function pirepe_handle_export_patterns() {
		if ( ! current_user_can( 'edit_theme_options' ) ) {
			wp_die( __( 'Access denied', 'twentytwentyfive' ) );
		}
		check_admin_referer( 'pirepe_export_patterns' );

		$registry    = WP_Block_Patterns_Registry::get_instance();
		$all         = $registry->get_all_registered();
		$pattern_set = array();
		foreach ( $all as $slug => $pattern ) {
			if ( str_starts_with( $slug, 'twentytwentyfive/' ) || str_starts_with( $slug, 'pirepe-' ) ) {
				$pattern_set[] = array(
					'slug'        => $slug,
					'title'       => $pattern['title'] ?? '',
					'description' => $pattern['description'] ?? '',
					'categories'  => $pattern['categories'] ?? array(),
					'content'     => $pattern['content'] ?? '',
				);
			}
		}

		$templates      = function_exists( 'get_block_templates' ) ? get_block_templates( array( 'theme' => wp_get_theme()->get_stylesheet(), 'post_type' => 'wp_template' ) ) : array();
		$template_parts = function_exists( 'get_block_templates' ) ? get_block_templates( array( 'theme' => wp_get_theme()->get_stylesheet(), 'post_type' => 'wp_template_part' ) ) : array();

		$template_set = array();
		foreach ( $templates as $template ) {
			$template_set[] = array(
				'slug'        => $template->slug,
				'title'       => $template->title,
				'description' => $template->description,
				'content'     => $template->content,
				'type'        => 'wp_template',
			);
		}

		$template_part_set = array();
		foreach ( $template_parts as $part ) {
			$areas = array();
			if ( isset( $part->area ) && $part->area ) {
				$areas[] = $part->area;
			}
			if ( isset( $part->wp_id ) && $part->wp_id ) {
				$terms = get_the_terms( $part->wp_id, 'wp_template_part_area' );
				if ( $terms && ! is_wp_error( $terms ) ) {
					foreach ( $terms as $term ) {
						$areas[] = $term->slug;
					}
				}
			}
			$areas = array_unique( array_filter( $areas ) );

			$template_part_set[] = array(
				'slug'        => $part->slug,
				'title'       => $part->title,
				'description' => $part->description,
				'content'     => $part->content,
				'type'        => 'wp_template_part',
				'area'        => isset( $part->area ) ? $part->area : '',
				'areas'       => $areas,
			);
		}

		$synced_patterns = array();
		$blocks          = get_posts(
			array(
				'post_type'      => 'wp_block',
				'post_status'    => 'publish',
				'posts_per_page' => -1,
				'tax_query'      => array(
					array(
						'taxonomy' => 'wp_theme',
						'field'    => 'name',
						'terms'    => wp_get_theme()->get_stylesheet(),
					),
				),
			)
		);
		foreach ( $blocks as $block ) {
			$synced_patterns[] = array(
				'slug'        => $block->post_name,
				'title'       => $block->post_title,
				'content'     => $block->post_content,
				'description' => '',
			);
		}

		$payload = array(
			'patterns'       => $pattern_set,
			'templates'      => $template_set,
			'templateParts'  => $template_part_set,
			'syncedPatterns' => $synced_patterns,
		);

		$has_payload = ( ! empty( $pattern_set ) || ! empty( $template_set ) || ! empty( $template_part_set ) || ! empty( $synced_patterns ) );
		if ( ! $has_payload ) {
			wp_redirect( add_query_arg( 'pirepe_export', 'export_empty', wp_get_referer() ) );
			exit;
		}

		update_option(
			'pirepe_last_export_status',
			array(
				'patterns'       => count( $pattern_set ),
				'templates'      => count( $template_set ),
				'templateParts'  => count( $template_part_set ),
				'syncedPatterns' => count( $synced_patterns ),
				'timestamp'      => time(),
			)
		);

		$json = wp_json_encode( $payload );
		nocache_headers();
		header( 'Content-Type: application/json; charset=utf-8' );
		header( 'Content-Disposition: attachment; filename="pirepe-patterns.json"' );
		echo $json;
		exit;
	}
endif;
add_action( 'admin_post_pirepe_export_patterns', 'pirepe_handle_export_patterns' );

if ( ! function_exists( 'pirepe_handle_import_patterns' ) ) :
	/**
	 * Handle import request.
	 *
	 * @return void
	 */
	function pirepe_handle_import_patterns() {
		if ( ! current_user_can( 'edit_theme_options' ) ) {
			wp_die( __( 'Access denied', 'twentytwentyfive' ) );
		}
		check_admin_referer( 'pirepe_import_patterns' );

		if ( empty( $_FILES['pirepe_patterns_file']['tmp_name'] ) ) {
			wp_redirect( add_query_arg( 'pirepe_import', 'missing', wp_get_referer() ) );
			exit;
		}

		$contents = file_get_contents( $_FILES['pirepe_patterns_file']['tmp_name'] );
		$data     = json_decode( $contents, true );

		if ( empty( $data ) || ! is_array( $data ) ) {
			wp_redirect( add_query_arg( 'pirepe_import', 'invalid', wp_get_referer() ) );
			exit;
		}

		$pattern_payload = isset( $data['patterns'] ) ? $data['patterns'] : ( is_assoc( $data ) ? array() : $data ); // Backward compat: flat array.
		$mode            = isset( $_POST['pirepe_import_mode'] ) ? sanitize_key( wp_unslash( $_POST['pirepe_import_mode'] ) ) : 'skip';
		$mode            = in_array( $mode, array( 'skip', 'overwrite' ), true ) ? $mode : 'skip';

		$sanitized_patterns = array();
		$skipped_slugs   = array();
		$overwrote_slugs = array();

		if ( is_array( $pattern_payload ) ) {
			foreach ( $pattern_payload as $pattern ) {
				if ( empty( $pattern['slug'] ) || empty( $pattern['title'] ) || empty( $pattern['content'] ) ) {
					continue;
				}
				$sanitized_patterns[] = array(
					'slug'        => sanitize_key( $pattern['slug'] ),
					'title'       => wp_strip_all_tags( $pattern['title'] ),
					'description' => isset( $pattern['description'] ) ? sanitize_text_field( $pattern['description'] ) : '',
					'categories'  => isset( $pattern['categories'] ) && is_array( $pattern['categories'] ) ? array_map( 'sanitize_key', $pattern['categories'] ) : array( 'layout' ),
					'content'     => wp_slash( (string) $pattern['content'] ),
				);
			}
		}
		$current_patterns = get_option( 'pirepe_custom_patterns', array() );
		$merged_patterns  = array();
		if ( 'overwrite' === $mode ) {
			$merged_patterns = $sanitized_patterns;
			// Preserve any existing that aren't in import.
			foreach ( $current_patterns as $pattern ) {
				$exists = array_filter(
					$sanitized_patterns,
					function ( $p ) use ( $pattern ) {
						return $p['slug'] === $pattern['slug'];
					}
				);
				if ( empty( $exists ) ) {
					$merged_patterns[] = $pattern;
				}
			}
		} else {
			$merged_patterns = $current_patterns;
			foreach ( $sanitized_patterns as $pattern ) {
				$exists = array_filter(
					$current_patterns,
					function ( $p ) use ( $pattern ) {
						return $p['slug'] === $pattern['slug'];
					}
				);
				if ( empty( $exists ) ) {
					$merged_patterns[] = $pattern;
				}
				if ( ! empty( $exists ) ) {
					$skipped_slugs[] = $pattern['slug'];
				}
			}
		}
		update_option( 'pirepe_custom_patterns', $merged_patterns );

		// Import block templates.
		if ( function_exists( 'wp_insert_post' ) ) {
			$templates = isset( $data['templates'] ) && is_array( $data['templates'] ) ? $data['templates'] : array();
			foreach ( $templates as $template ) {
				if ( empty( $template['slug'] ) || empty( $template['content'] ) ) {
					continue;
				}
				$existing = get_posts(
					array(
						'name'           => sanitize_key( $template['slug'] ),
						'post_type'      => 'wp_template',
						'post_status'    => 'any',
						'posts_per_page' => 1,
						'tax_query'      => array(
							array(
								'taxonomy' => 'wp_theme',
								'field'    => 'name',
								'terms'    => wp_get_theme()->get_stylesheet(),
							),
						),
					)
				);
				if ( $existing && 'skip' === $mode ) {
					$skipped_slugs[] = $template['slug'];
					continue;
				}
				if ( $existing && 'overwrite' === $mode ) {
					$post_id = $existing[0]->ID;
					wp_update_post(
						array(
							'ID'           => $post_id,
							'post_status'  => 'publish',
							'post_title'   => wp_strip_all_tags( $template['title'] ?? $template['slug'] ),
							'post_content' => wp_slash( (string) $template['content'] ),
						)
					);
					$overwrote_slugs[] = $template['slug'];
				} else {
					$post_id = wp_insert_post(
						array(
							'post_type'    => 'wp_template',
							'post_status'  => 'publish',
							'post_title'   => wp_strip_all_tags( $template['title'] ?? $template['slug'] ),
							'post_name'    => sanitize_key( $template['slug'] ),
							'post_content' => wp_slash( (string) $template['content'] ),
						)
					);
				}
				if ( $post_id && ! is_wp_error( $post_id ) ) {
					wp_set_post_terms( $post_id, wp_get_theme()->get_stylesheet(), 'wp_theme' );
				}
			}

			$template_parts = isset( $data['templateParts'] ) && is_array( $data['templateParts'] ) ? $data['templateParts'] : array();
			foreach ( $template_parts as $part ) {
				if ( empty( $part['slug'] ) || empty( $part['content'] ) ) {
					continue;
				}
				$existing = get_posts(
					array(
						'name'           => sanitize_key( $part['slug'] ),
						'post_type'      => 'wp_template_part',
						'post_status'    => 'any',
						'posts_per_page' => 1,
						'tax_query'      => array(
							array(
								'taxonomy' => 'wp_theme',
								'field'    => 'name',
								'terms'    => wp_get_theme()->get_stylesheet(),
							),
						),
					)
				);
				if ( $existing && 'skip' === $mode ) {
					$skipped_slugs[] = $part['slug'];
					continue;
				}
				if ( $existing && 'overwrite' === $mode ) {
					$post_id = $existing[0]->ID;
					wp_update_post(
						array(
							'ID'           => $post_id,
							'post_status'  => 'publish',
							'post_title'   => wp_strip_all_tags( $part['title'] ?? $part['slug'] ),
							'post_content' => wp_slash( (string) $part['content'] ),
						)
					);
					$overwrote_slugs[] = $part['slug'];
				} else {
					$post_id = wp_insert_post(
						array(
							'post_type'    => 'wp_template_part',
							'post_status'  => 'publish',
							'post_title'   => wp_strip_all_tags( $part['title'] ?? $part['slug'] ),
							'post_name'    => sanitize_key( $part['slug'] ),
							'post_content' => wp_slash( (string) $part['content'] ),
						)
					);
				}
				if ( $post_id && ! is_wp_error( $post_id ) ) {
					wp_set_post_terms( $post_id, wp_get_theme()->get_stylesheet(), 'wp_theme' );
					$areas = array();
					if ( ! empty( $part['areas'] ) && is_array( $part['areas'] ) ) {
						$areas = $part['areas'];
					} elseif ( ! empty( $part['area'] ) ) {
						$areas = array( $part['area'] );
					}
					foreach ( $areas as $area_slug ) {
						wp_set_post_terms( $post_id, sanitize_key( $area_slug ), 'wp_template_part_area', true );
					}
				}
			}

			$synced = isset( $data['syncedPatterns'] ) && is_array( $data['syncedPatterns'] ) ? $data['syncedPatterns'] : array();
			foreach ( $synced as $block ) {
				if ( empty( $block['slug'] ) || empty( $block['content'] ) ) {
					continue;
				}
				$existing = get_posts(
					array(
						'name'           => sanitize_key( $block['slug'] ),
						'post_type'      => 'wp_block',
						'post_status'    => 'any',
						'posts_per_page' => 1,
						'tax_query'      => array(
							array(
								'taxonomy' => 'wp_theme',
								'field'    => 'name',
								'terms'    => wp_get_theme()->get_stylesheet(),
							),
						),
					)
				);
				if ( $existing && 'skip' === $mode ) {
					$skipped_slugs[] = $block['slug'];
					continue;
				}
				if ( $existing && 'overwrite' === $mode ) {
					$post_id = $existing[0]->ID;
					wp_update_post(
						array(
							'ID'           => $post_id,
							'post_status'  => 'publish',
							'post_title'   => wp_strip_all_tags( $block['title'] ?? $block['slug'] ),
							'post_content' => wp_slash( (string) $block['content'] ),
						)
					);
					$overwrote_slugs[] = $block['slug'];
				} else {
					$post_id = wp_insert_post(
						array(
							'post_type'    => 'wp_block',
							'post_status'  => 'publish',
							'post_title'   => wp_strip_all_tags( $block['title'] ?? $block['slug'] ),
							'post_name'    => sanitize_key( $block['slug'] ),
							'post_content' => wp_slash( (string) $block['content'] ),
							'post_author'  => get_current_user_id(),
						)
					);
				}
				if ( $post_id && ! is_wp_error( $post_id ) ) {
					wp_set_post_terms( $post_id, wp_get_theme()->get_stylesheet(), 'wp_theme' );
				}
			}
		}

		update_option(
			'pirepe_last_import_report',
			array(
				'mode'       => $mode,
				'skipped'    => array_slice( array_values( array_unique( $skipped_slugs ) ), 0, 12 ),
				'overwrote'  => array_slice( array_values( array_unique( $overwrote_slugs ) ), 0, 12 ),
				'timestamp'  => time(),
			)
		);

		wp_redirect( add_query_arg( 'pirepe_import', 'success', wp_get_referer() ) );
		exit;
	}
endif;
add_action( 'admin_post_pirepe_import_patterns', 'pirepe_handle_import_patterns' );

if ( ! function_exists( 'is_assoc' ) ) :
	/**
	 * Determine if array is associative.
	 *
	 * @param array $array Input array.
	 * @return bool
	 */
	function is_assoc( $array ) {
		if ( ! is_array( $array ) ) {
			return false;
		}
		$keys = array_keys( $array );
		return array_keys( $keys ) !== $keys;
	}
endif;

// Registers pattern categories.
if ( ! function_exists( 'twentytwentyfive_pattern_categories' ) ) :
	/**
	 * Registers pattern categories.
	 *
	 * @since Twenty Twenty-Five 1.0
	 *
	 * @return void
	 */
	function twentytwentyfive_pattern_categories() {

		register_block_pattern_category(
			'twentytwentyfive_page',
			array(
				'label'       => __( 'Pages', 'twentytwentyfive' ),
				'description' => __( 'A collection of full page layouts.', 'twentytwentyfive' ),
			)
		);

		register_block_pattern_category(
			'twentytwentyfive_post-format',
			array(
				'label'       => __( 'Post formats', 'twentytwentyfive' ),
				'description' => __( 'A collection of post format patterns.', 'twentytwentyfive' ),
			)
		);
	}
endif;
add_action( 'init', 'twentytwentyfive_pattern_categories' );

// Registers block binding sources.
if ( ! function_exists( 'twentytwentyfive_register_block_bindings' ) ) :
	/**
	 * Registers the post format block binding source.
	 *
	 * @since Twenty Twenty-Five 1.0
	 *
	 * @return void
	 */
	function twentytwentyfive_register_block_bindings() {
		register_block_bindings_source(
			'twentytwentyfive/format',
			array(
				'label'              => _x( 'Post format name', 'Label for the block binding placeholder in the editor', 'twentytwentyfive' ),
				'get_value_callback' => 'twentytwentyfive_format_binding',
			)
		);
	}
endif;
add_action( 'init', 'twentytwentyfive_register_block_bindings' );

// Registers block binding callback function for the post format name.
if ( ! function_exists( 'twentytwentyfive_format_binding' ) ) :
	/**
	 * Callback function for the post format name block binding source.
	 *
	 * @since Twenty Twenty-Five 1.0
	 *
	 * @return string|void Post format name, or nothing if the format is 'standard'.
	 */
	function twentytwentyfive_format_binding() {
		$post_format_slug = get_post_format();

		if ( $post_format_slug && 'standard' !== $post_format_slug ) {
			return get_post_format_string( $post_format_slug );
		}
	}
endif;
