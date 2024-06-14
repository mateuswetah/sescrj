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

/* Adiciona placeholder para documentos vazios */
function sescrj_show_placeholder_for_empty_documents() {
	if ( tainacan_has_document() )
		return;
?>
	<div class="sescrj-empty-document-placeholder">
		<img src="<?php echo get_stylesheet_directory_uri() . '/images/undefined-document.png'; ?>" alt="<?php _e('Imagem indisponível', 'sescrj'); ?>" /> 
	</div>
<?php
}	
add_action( 'tainacan-blocksy-single-item-after-document', 'sescrj_show_placeholder_for_empty_documents' );

require get_stylesheet_directory() . '/inc/single-item-tweaks.php';