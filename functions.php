<?php

if (! defined('WP_DEBUG')) {
	die( 'Direct access forbidden.' );
}

define( 'SESCRJ_OBRAS_COLLECTION_ID', 5 );
define( 'SESCRJ_OBRAS_COLLECTION_POST_TYPE', 'tnc_col_' . SESCRJ_OBRAS_COLLECTION_ID . '_item' );

define( 'SESCRJ_ARTISTAS_COLLECTION_ID', 2509 );
define( 'SESCRJ_ARTISTAS_COLLECTION_POST_TYPE', 'tnc_col_' . SESCRJ_ARTISTAS_COLLECTION_ID . '_item' );

define( 'SESCRJ_EXPOSICOES_COLLECTION_ID', 8134 );
define( 'SESCRJ_EXPOSICOES_COLLECTION_POST_TYPE', 'tnc_col_' . SESCRJ_EXPOSICOES_COLLECTION_ID . '_item' );

define( 'SESCRJ_OBRAS_AUTORIA_METADATUM_ID', 14134 );
define( 'SESCRJ_OBRAS_SERIE_METADATUM_ID', 63591 );
define( 'SESCRJ_OBRAS_TECNICA_METADATUM_ID', 23180 );
define( 'SESCRJ_OBRAS_ANO_METADATUM_ID', 27107 );
define( 'SESCRJ_OBRAS_DIMENCOES_METADATUM_ID', 106 );

define( 'SESCRJ_ARTISTAS_EXPOSICOES_METADATUM_ID', 8974 );

define( 'SESCRJ_EXPOSICOES_ARTISTAS_DO_SESC_METADATUM_ID', 24237 );
define( 'SESCRJ_EXPOSICOES_ARTISTAS_CONVIDADOS_METADATUM_ID', 63871 );


// define( 'SESCRJ_OBRAS_COLLECTION_ID', 267 );
// define( 'SESCRJ_OBRAS_COLLECTION_POST_TYPE', 'tnc_col_' . SESCRJ_OBRAS_COLLECTION_ID . '_item' );
// define( 'SESCRJ_ARTISTAS_COLLECTION_ID', 466 );
// define( 'SESCRJ_ARTISTAS_COLLECTION_POST_TYPE', 'tnc_col_' . SESCRJ_ARTISTAS_COLLECTION_ID . '_item' );
// define( 'SESCRJ_EXPOSICOES_COLLECTION_ID', 9212 );
// define( 'SESCRJ_EXPOSICOES_COLLECTION_POST_TYPE', 'tnc_col_' . SESCRJ_ARTISTAS_COLLECTION_ID . '_item' );
// define( 'SESCRJ_OBRAS_AUTORIA_METADATUM_ID', 1298 );
// define( 'SESCRJ_OBRAS_SERIE_METADATUM_ID', 278 );
// define( 'SESCRJ_OBRAS_TECNICA_METADATUM_ID', 278 );
// define( 'SESCRJ_OBRAS_ANO_METADATUM_ID', 27107 );
// define( 'SESCRJ_OBRAS_DIMENCOES_METADATUM_ID', 106 );
// define( 'SESCRJ_ARTISTAS_EXPOSICOES_METADATUM_ID', 6771 );
// define( 'SESCRJ_EXPOSICOES_ARTISTAS_DO_SESC_METADATUM_ID', 24237 );
// define( 'SESCRJ_EXPOSICOES_ARTISTAS_CONVIDADOS_METADATUM_ID', 63871 );

/**
 * Enqueue scripts and styles.
 */
add_action( 'wp_enqueue_scripts', function () {
	wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
	wp_enqueue_style( 'sescrj-style', get_stylesheet_uri() );
	wp_enqueue_script( 'sescrj-copylink', get_stylesheet_directory_uri() . '/js/copy-link.js', array(), wp_get_theme()->get( 'Version' ), true );
	wp_enqueue_script( 'sescrj-masonry', get_stylesheet_directory_uri() . '/vendor/masonry.pkgd.min.js', array(), wp_get_theme()->get( 'Version' ), true );
	wp_enqueue_script( 'sescrj-images-loaded', get_stylesheet_directory_uri() . '/vendor/imagesloaded.pkgd.min.js', array(), wp_get_theme()->get( 'Version' ), true );
	wp_enqueue_script( 'sescrj-view-mode-grid', get_stylesheet_directory_uri() . '/js/view-mode-sescrjgrid.js', array( 'sescrj-masonry', 'sescrj-images-loaded' ), wp_get_theme()->get( 'Version' ), true );
});

/** View modes do SESCRJ */
require get_stylesheet_directory() . '/inc/block-styles.php';
require get_stylesheet_directory() . '/inc/block-bindings.php';

/** Adiciona classe dedicada para o cabeçalho das listas de itens e na single */
function sescrj_body_class($classes) {
	
	$collections_post_types = \Tainacan\Repositories\Repository::get_collections_db_identifiers();
	$current_post_type = get_post_type();
		
	if ( in_array($current_post_type, $collections_post_types) ) {

		if ( is_archive() )
			$classes[] = 'sescrj-tainacan-archive-page';

		if ( is_single() )
			$classes[] = 'sescrj-tainacan-single-page';
	}

	return $classes;
}
add_filter('body_class', 'sescrj_body_class');

/* Builds navigation link for custom view modes */
function sescrj_get_item_link_for_navigation($item_url, $index) {
		
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
			'dynamic_metadata' => true,
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

/* Define o novo Cartões como modo de visualização padrão */
function sescrj_set_default_view_mode($default) {
	if ( !is_admin() )
		return 'sescrjgrid';

	return $default;
}
add_filter( 'tainacan-default-view-mode-for-themes', 'sescrj_set_default_view_mode', 10, 1 );

function sescrj_set_enabled_view_modes($registered_view_modes_slugs) {

	if ( !is_admin() )
		return [ 'sescrjgrid', 'sescrjtable' ];

	return $registered_view_modes_slugs;
}
add_filter( 'tainacan-enabled-view-modes-for-themes', 'sescrj_set_enabled_view_modes', 10, 1 );

/* Usa o mesmo ícone de busca do Tainacan */
add_filter('blocksy:header:search:icon', function ($icon) {
    return '<svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
		<path d="M8.13545 0.5C4.02498 0.5 0.669922 3.85506 0.669922 7.96552C0.669922 12.076 4.02498 15.431 8.13545 15.431C9.99969 15.431 11.7029 14.7357 13.0139 13.598L13.468 14.0521V15.431L19.4254 21.3885C20.0141 21.9772 20.9697 21.9772 21.5584 21.3885C22.1471 20.7998 22.1471 19.8442 21.5584 19.2555L15.601 13.298H14.222L13.7679 12.8439C14.9056 11.533 15.601 9.82977 15.601 7.96552C15.601 3.85506 12.2459 0.5 8.13545 0.5ZM8.13545 2.63301C11.0931 2.63301 13.468 5.00782 13.468 7.96552C13.468 10.9232 11.0931 13.298 8.13545 13.298C5.17775 13.298 2.80293 10.9232 2.80293 7.96552C2.80293 5.00782 5.17775 2.63301 8.13545 2.63301Z" fill="#1E1E1E"/>
	</svg>';
});

require get_stylesheet_directory() . '/inc/single-item-tweaks.php';