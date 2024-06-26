<?php
/**
 * Define block bindings para puxar dados dinâmicos do Tainacan, como o total de itens
 */
function sescrj_register_block_bindings() {

    if ( ! function_exists( 'register_block_bindings_source' ) ) {
        return;
    }

    function sescrj_bindings_callback( $source_args ) {

        // Return null if there's no collection ID.
        if ( ! isset( $source_args['collectionId'] ) )
            return null;
        
        $total_items = wp_count_posts( 'tnc_col_' . $source_args['collectionId'] . '_item', 'readable' );

        return ( $total_items->publish ?? '0' );
    }

	register_block_bindings_source( 'sescrj/collection-total-items', array(
		'label'              => __( 'Total de Itens do Acervo', 'sescrj' ),
		'get_value_callback' => 'sescrj_bindings_callback'
	) );
}
add_action( 'init', 'sescrj_register_block_bindings' );