<?php
/**
 * Blocks Initializer
 *
 * Enqueue CSS/JS of all the blocks.
 *
 * @since   1.0.0
 * @package BS
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}

$block = 'block-bs-customer-opinions';

// Hook server side rendering into render callback
register_block_type('bonseo/' . $block,
	array(
		'attributes' => array(
			'title' => array(
				'type' => 'string',
			),
			'max_opinions' => array(
				'type' => 'string',
			)
		),
		'render_callback' => 'render_bs_customer_opinions',
	)
);


/**
 * Enqueue Gutenberg block assets for both frontend + backend.
 *
 * @uses {wp-editor} for WP editor styles.
 * @since 1.0.0
 */
function bs_customer_opinions_assets()
{
	wp_enqueue_style(
		'bs_customer_opinions-style-css',
		plugins_url('dist/blocks.style.build.css', dirname(__FILE__)),
		array('wp-editor')
	);
}

add_action('enqueue_block_assets', 'bs_customer_opinions_assets');

/**
 * Enqueue Gutenberg block assets for backend editor.
 *
 * @uses {wp-blocks} for block type registration & related functions.
 * @uses {wp-element} for WP Element abstraction — structure of blocks.
 * @uses {wp-i18n} to internationalize the block's text.
 * @uses {wp-editor} for WP editor styles.
 * @since 1.0.0
 */
function bs_customer_opinions_editor_assets()
{ // phpcs:ignore
	// Scripts.
	wp_enqueue_script(
		'bs_customer_opinions-block-js', // Handle.
		plugins_url('/dist/blocks.build.js', dirname(__FILE__)), // Block.build.js: We register the block here. Built with Webpack.
		array('wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor'), // Dependencies, defined above.
		// filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.build.js' ), // Version: File modification time.
		true // Enqueue the script in the footer.
	);

	// Styles.
	wp_enqueue_style(
		'bs_customer_opinions-block-editor-css', // Handle.
		plugins_url('dist/blocks.editor.build.css', dirname(__FILE__)), // Block editor CSS.
		array('wp-edit-blocks') // Dependency to include the CSS after it.
	// filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.editor.build.css' ) // Version: File modification time.
	);
}

function render_bs_customer_opinions_render($opinions)
{
	$html = '';
	while ($opinions->have_posts()) : $opinions->the_post();
		$title = get_the_title();
		$content = get_the_content();
		$image = get_the_post_thumbnail_url(get_the_ID());
		$html .= '
			<div class="ml-card-testimony l-flex l-flex--justify-center l-column--1-2 l-column--mobile--1-1 a-mar u-shadow--bottom l-flex--align-center">
				<picture class="a-pad">
					<img class="a-image a-image--avatar " src="' . esc_url($image) . '">
				</picture>
				<div class="ml-card-testimony__content l-flex l-flex--direction-column l-flex--align-center a-pad">
					<h3 class="a-text a-text--s  a-text--bold l-flex-item--align-start">
					' . esc_html($title) . '
					</h3>
					<p class="a-text l-flex-item--align-start">
						' . $content . '
					</p>
				</div>
			</div>
		';
		unset($post);
	endwhile;
	return $html;
}

function render_bs_customer_opinions($attributes)
{
	echo 'hola';
	$max_opinions = isset($attributes['max_opinions']) ? $attributes['max_opinions'] : 6;
	$title = $attributes['title'];
	$args = array(
		'post_type' => 'opinion',
		'post_status' => 'publish',
		'posts_per_page' => $max_opinions
	);
	$opinions = new WP_Query($args);
	if (empty($opinions)) {
		return "";
	}

	return '
		<section class="og-block-testimony a-pad-40">
			<h2 class="a-text a-text--xl  ">
        		' . $title . '
  		    </h2>
			<div class="og-block-testimony__group l-flex a-pad l-flex--wrap l-flex--justify-center ">
				' . render_bs_customer_opinions_render($opinions) . '
			</div>
		</section>';
}

add_action('enqueue_block_editor_assets', 'bs_customer_opinions_editor_assets');
