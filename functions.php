<?php
if (! defined('WP_DEBUG')) {
	die( 'Direct access forbidden.' );
}
add_action( 'wp_enqueue_scripts', function () {
	wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
	wp_enqueue_style( 'sescrj-style', get_stylesheet_uri() );
	wp_enqueue_script( 'sescrj-masonry', get_stylesheet_directory_uri() . '/vendor/masonry.pkgd.min.js', array(), wp_get_theme()->get( 'Version' ), true );
	wp_enqueue_script( 'sescrj-images-loaded', get_stylesheet_directory_uri() . '/vendor/imagesloaded.pkgd.min.js', array(), wp_get_theme()->get( 'Version' ), true );
	wp_enqueue_script( 'sescrj-view-mode-grid', get_stylesheet_directory_uri() . '/js/view-mode-sescrjgrid.js', array( 'sescrj-masonry', 'sescrj-images-loaded' ), wp_get_theme()->get( 'Version' ), true );
});

require get_stylesheet_directory() . '/inc/block-styles.php';

/** Adiciona classe dedicada para o cabeçalho das listas de itens */
function sescrj_body_class($classes) {
	
	$collections_post_types = \Tainacan\Repositories\Repository::get_collections_db_identifiers();
	$current_post_type = get_post_type();
		
	if (is_archive() && in_array($current_post_type, $collections_post_types)) {
		$classes[] = 'sescrj-tainacan-archive-page';
	}

	return $classes;
}
add_filter('body_class', 'sescrj_body_class');

/* Builds navigation link for custom view modes */
function get_item_link_for_navigation($item_url, $index) {
		
	if ( $_GET && isset($_GET['paged']) && isset($_GET['perpage']) ) {
		$query = '';
		$perpage = (int)$_GET['perpage'];
		$paged = (int)$_GET['paged'];
		$index = (int)$index;
		$query .= '&pos=' . ( ($paged - 1) * $perpage + $index );
		$query .= '&source_list=' . (is_tax() ? 'term' : 'collection');
		return $item_url . '?' .  $_SERVER['QUERY_STRING'] . $query;
	}
	return $item_url;
}


/* Registra modos de visualização do SESC RJ */
function sescrj_register_tainacan_view_modes() {
	if ( function_exists( 'tainacan_register_view_mode' ) ) {

		// Grid
		tainacan_register_view_mode('sescrjgrid', array(
			'label' => __( 'Cartões', 'sescrj' ),
			'description' => __( 'Uma grade de itens feita para o Sesc RJ', 'sescrj' ),
			'icon' => '<span class="icon"><i class="tainacan-icon tainacan-icon-viewmasonry tainacan-icon-1-25em"></i></span>',
			'dynamic_metadata' => false,
			'template' => get_stylesheet_directory() . '/tainacan/view-mode-sescrjgrid.php'
		));

		// Table
		tainacan_register_view_mode('sescrjtable', array(
			'label' => __( 'Tabela', 'sescrj' ),
			'description' => __( 'Uma tabela de itens feita para o Sesc RJ', 'sescrj' ),
			'icon' => '<span class="icon"><i class="tainacan-icon tainacan-icon-viewtable tainacan-icon-1-25em"></i></span>',
			'dynamic_metadata' => true,
			'template' => get_stylesheet_directory() . '/tainacan/view-mode-sescrjtable.php'
		));
	}
}
add_action( 'after_setup_theme', 'sescrj_register_tainacan_view_modes' );
