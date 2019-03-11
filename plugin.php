<?php
/**
 * Plugin Name: Customer Opinions [BonSeo Block]
 * Plugin URI: https://www.bonseo.es/wordpress-gutenberg-blocks/customer-opinions
 * Description: Un bloque Gutenberg para mostrar las opiniones de los clientes
 * Author: jjlmoya
 * Author URI: https://www.bonseo.es/
 * Version: 1.0.0
 * License: GPL2+
 * License URI: https://www.gnu.org/licenses/gpl-2.0.txt
 * @package BS
 */


if (!defined('ABSPATH')) {
	exit;
}


if (!function_exists('bs_create_block_category')) {
	function bs_create_block_category($categories, $post)
	{
		return array_merge(
			$categories,
			array(
				array(
					'slug' => 'bonseo-blocks',
					'title' => __('BonSeo', 'bonseo-blocks'),
				),
			)
		);
	}

	add_filter('block_categories', 'bs_create_block_category', 10, 2);
}

function bs_register_opinion_post_type()
{

	/**
	 * Post Type: Opiniones.
	 */

	$labels = array(
		"name" => __("Opiniones", "custom-post-type-ui"),
		"singular_name" => __("Opinión", "custom-post-type-ui"),
		"menu_name" => __("Opiniones", "custom-post-type-ui"),
		"add_new" => __("Nueva opinión", "custom-post-type-ui"),
		"edit_item" => __("Editar Opinión", "custom-post-type-ui"),
	);

	$args = array(
		"label" => __("Opiniones", "custom-post-type-ui"),
		"labels" => $labels,
		"description" => "Opiniones de los clientes",
		"public" => true,
		"publicly_queryable" => true,
		"show_ui" => true,
		"delete_with_user" => false,
		"show_in_rest" => true,
		"rest_base" => "opinion",
		"rest_controller_class" => "WP_REST_Posts_Controller",
		"has_archive" => false,
		"show_in_menu" => true,
		"show_in_nav_menus" => false,
		"exclude_from_search" => true,
		"capability_type" => "post",
		"map_meta_cap" => true,
		"hierarchical" => false,
		"rewrite" => array("slug" => "opinion", "with_front" => true),
		"query_var" => true,
		"supports" => array("title", "editor", "thumbnail"),
	);

	register_post_type("opinion", $args);
}

add_action('init', 'bs_register_opinion_post_type');
add_action('add_meta_boxes', 'bs_opinion_metabox_add');
add_action('save_post', 'bs_opinion_on_save');


function bs_opinion_metabox_add()
{
	add_meta_box(
		'bs_theme_brand',
		'Theme',
		'bs_opinion_brand_action',
		'opinion',
		'side',
		'high'
	);
}

function bs_opinion_brand_action()
{
	global $post;
	wp_nonce_field(basename(__FILE__), 'bs_theme_brand');
	$location = get_post_meta($post->ID, 'bs_theme_brand', true);
	echo '<input type="text" name="bs_theme_brand" value="' . esc_textarea($location) . '" class="widefat">';
}


function bs_opinion_on_save($post_id)
{
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return;
	}
	if (isset($_POST['post_type']) && $_POST['post_type'] == 'opinion') {
		if (!current_user_can('edit_page', $post_id)) {
			return;
		}
	} else {
		if (!current_user_can('edit_post', $post_id)) {
			return;
		}
	}
	if (!isset($_POST['bs_theme_brand'])) {
		return;
	}
	$myBrand = sanitize_text_field($_POST['bs_theme_brand']);
	update_post_meta($post_id, 'bs_theme_brand', $myBrand);
}


require_once plugin_dir_path(__FILE__) . 'src/init.php';
