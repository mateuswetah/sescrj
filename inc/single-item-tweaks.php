<?php
/**
 * Modificações no template single dos items Tainacan
 */

/**
 * Adiciona metadados especiais abaixo do banner de título
 */
function sescrj_single_page_hero_title_after() {

	if ( is_single() ) {
        if ( get_post_type() === SESCRJ_OBRAS_COLLECTION_POST_TYPE ) {

            $special_metadata_ids = [ 
                SESCRJ_OBRAS_AUTORIA_METADATUM_ID, // Autoria
                SESCRJ_OBRAS_ANO_METADATUM_ID, // Ano
                SESCRJ_OBRAS_DIMENCOES_METADATUM_ID // Dimensões resumo
            ];
            
            ?>
            <div class="sescrj-single-page-hero-meta">
                <?php 
                    tainacan_the_metadata( array(
                        'metadata__in' => $special_metadata_ids,
                        'before_title' => '<h3 class="screen-reader-text">',
                        'after_title' => '</h3>',
                        'before_value' => '<p class="tainacan-metadata-value">',
                        'after_value' => '</p>'
                    ) );
                ?>
            </div>
            <?php
        
        } else if ( get_post_type() === SESCRJ_ARTISTAS_COLLECTION_POST_TYPE ) {

            // Gets the current Item
            $current_item = \Tainacan\Theme_Helper::get_instance()->tainacan_get_item();
            if ( !$current_item )
                return;
            
            // Then fetches related ones
            $related_items = $current_item->get_related_items();
            if ( !$related_items || !is_array($related_items) || !count($related_items) )
                return;

            $total_obras = 0;

            foreach($related_items as $related_group) {
                if ( isset($related_group['total_items']) && (int)$related_group['total_items'] > 0 && isset($related_group['metadata_id']) && $related_group['metadata_id'] == SESCRJ_OBRAS_AUTORIA_METADATUM_ID ) {
                    $total_obras = (int)$related_group['total_items'];
                }
            }
            
            echo '<span class="sescrj-single-page-hero-meta">';

            if ( $total_obras > 0)
                echo '<strong>' . $total_obras . '</strong> ' . _n( 'obra no acervo', 'obras no acervo', $total_obras, 'sescrj');

            $tainacan_item_metadata_repository = \Tainacan\Repositories\Item_Metadata::get_instance();

            $current_item_exposicoes = $tainacan_item_metadata_repository->fetch( $current_item, 'OBJECT', [ 'post__in' => [ SESCRJ_ARTISTAS_EXPOSICOES_METADATUM_ID ] ] );
            $current_item_exposicoes = is_array($current_item_exposicoes) ? $current_item_exposicoes[0] : $current_item_exposicoes;

            if ( $current_item_exposicoes instanceof \Tainacan\Entities\Item_Metadata_Entity ) {
                $current_item_exposicoes_value = $current_item_exposicoes->get_value();

                if ( $current_item_exposicoes_value ) {
                    $total_exposicoes = is_array($current_item_exposicoes_value) ? count($current_item_exposicoes_value) : 1;
                    echo ' • <strong>' . $total_exposicoes . '</strong> ' . _n( 'exposição no Sesc', 'exposições no Sesc', $total_exposicoes, 'sescrj');
                }
            }

            echo '</span>';

        } else if ( get_post_type() === SESCRJ_EXPOSICOES_COLLECTION_POST_TYPE ) {
            ?>
            <div class="sescrj-single-page-hero-meta">
                <?php 
                    tainacan_the_metadata( array(
                        'metadata__in' => [ SESCRJ_EXPOSICOES_ARTISTAS_DO_SESC_METADATUM_ID ],
                        'before_title' => '<h3 class="screen-reader-text">',
                        'after_title' => '</h3>',
                        'before_value' => '<p class="tainacan-metadata-value">',
                        'after_value' => '</p>'
                    ) );
                    tainacan_the_metadata( array(
                        'metadata__in' =>[ SESCRJ_EXPOSICOES_ARTISTAS_CONVIDADOS_METADATUM_ID ],
                        'before_title' => '<h3 class="tainacan-metadata-label tainacan-metadata-label--convidados">',
                        'after_title' => '</h3>',
                        'before_value' => '<p class="tainacan-metadata-value tainacan-metadata-value--convidados">',
                        'after_value' => '</p>'
                    ) );
                ?>
            </div>
            <?php
        }
    }
}
add_action('blocksy:hero:title:after', 'sescrj_single_page_hero_title_after' );

/** 
 * Removes default hero from collection single pages
 */
add_filter('blocksy:single:has-default-hero', function() {
	$collections_post_types = \Tainacan\Repositories\Repository::get_collections_db_identifiers();
	$current_post_type = get_post_type();
		
	if ( in_array($current_post_type, $collections_post_types) )
		return false;

	return true;
});

/**
 * Adiciona hero section após o documento do item
 */
function sescrj_single_item_after_document() {
	echo blocksy_output_hero_section([
		'type' => 'type-2'
	]);
}
add_action('tainacan-blocksy-single-item-after-document', 'sescrj_single_item_after_document');

/** 
 * Adiciona seção de itens relacionados ao final do single item
 */
function sescrj_single_item_bottom() {
	?>
	<div class="tainaxan-item-single">
		<section class="is-style-sescrj-bleeding-content is-style-sescrj-bleeding-border-top tainacan-item-section tainacan-item-section--items-related-to-this">
			
			<h2 class="tainacan-single-item-section" id="tainacan-item-items-related-to-this-label">
				<?php _e('Itens relacionados', 'sescrj'); ?>
			</h2>

			<div class="tainacan-item-section__items-related-to-this">
				<?php 
					tainacan_the_related_items_carousel([
						'items_list_layout' => 'carousel',
						'collection_heading_tag' => 'h3',
						'collection_heading_class_name' => 'screen-reader-text',
						'carousel_args' => [
							'max_items_per_screen' => 6,
							'image_size' => 'tainacan-medium-full',
							'space_between_items' => 56,
							'arrow_style' => 'type-2',
							'hide_title' => false,
							'space_around_carousel' => 20
						]
					]);
					do_action('sescrj-extra-related-items-carousels');
				?>
			<div>
		</section>
	</div>
	<?php
}
add_action('tainacan-blocksy-single-item-bottom', 'sescrj_single_item_bottom');

/**
 * Cria carrosseis de relacionamento especiais para a coleção de Obras
 */
function sescrj_render_extra_related_items_carousels_for_obras() {
    
    if ( get_post_type() !== SESCRJ_OBRAS_COLLECTION_POST_TYPE )
		return;

    $tainacan_items_repository = \Tainacan\Repositories\Items::get_instance();
    $tainacan_item_metadata_repository = \Tainacan\Repositories\Item_Metadata::get_instance();

    $current_item = tainacan_get_item(); 
    $current_item_metadata = $tainacan_item_metadata_repository->fetch( $current_item );

    // Guarda metadados que serão usados em variáveis dedicadas
    foreach( $current_item_metadata as $item_metadatum ) {
        if ( $item_metadatum->get_metadatum()->get_id() == SESCRJ_OBRAS_AUTORIA_METADATUM_ID ) {
            $current_item_author_item_metadata = $item_metadatum;
        } else if ( $item_metadatum->get_metadatum()->get_id() == SESCRJ_OBRAS_SERIE_METADATUM_ID ) {
            $current_item_serie_item_metadata = $item_metadatum;
        }else if ( $item_metadatum->get_metadatum()->get_id() == SESCRJ_OBRAS_TECNICA_METADATUM_ID ) {
            $current_item_tecnica_item_metadata = $item_metadatum;
        }
    }

    // Monta consulta para encontrar itens de Por autoria
    if ( isset($current_item_author_item_metadata) ) {

        $current_item_author = $current_item_author_item_metadata->get_value();
        $current_item_author = is_array($current_item_author) ? $current_item_author[0] : $current_item_author;

        if ( $current_item_author ) {

            $related_items_by_author = [];
            $related_items_by_author_query = $tainacan_items_repository->fetch([
                    'posts_per_page' => 12,
                    'meta_query' => [
                        [
                            'key'   => SESCRJ_OBRAS_AUTORIA_METADATUM_ID,
                            'value' => $current_item_author,
                        ]
                    ],
                    'post__not_in' => [ $current_item->get_id() ]
                ],
                [],
                'WP_Query'
            );
            $total_related_items_by_author = $related_items_by_author_query->found_posts;

            if ( $related_items_by_author_query->have_posts() ) {
                while ( $related_items_by_author_query->have_posts() ) {
                    $related_items_by_author_query->the_post();
                    
                    $item_related_as_entity = new \Tainacan\Entities\Item($related_items_by_author_query->post);
                    $item_related_as_object = $item_related_as_entity->_toArray();
                    $item_related_as_object['thumbnail'] = $item_related_as_entity->get_thumbnail();

                    $related_items_by_author[] = $item_related_as_object;
                }
                wp_reset_postdata();
            }

            $related_items_by_author_link = esc_url( get_permalink( SESCRJ_OBRAS_COLLECTION_ID ) ) . '?metaquery[0][key]=' . SESCRJ_OBRAS_AUTORIA_METADATUM_ID . '&metaquery[0][value][0]=' . $current_item_author . '&metaquery[0][compare]=IN';

            sescrj_render_items_carousel( 'Por autoria', SESCRJ_OBRAS_COLLECTION_ID, $related_items_by_author, $total_related_items_by_author, $related_items_by_author_link );
        }
    }

    // Por "Seŕie"
    if ( isset($current_item_serie_item_metadata) ) {

        $current_item_serie = $current_item_serie_item_metadata->get_value();
        $current_item_serie = is_array($current_item_serie) ? $current_item_serie : [ $current_item_serie ];
        
        if ( $current_item_serie && count($current_item_serie) > 0 ) {

            $related_items_by_serie = [];
            $related_items_by_serie_query = $tainacan_items_repository->fetch([
                    'posts_per_page' => 12,
                    'tax_query' => [
                        [
                            'taxonomy'   => $current_item_serie[0]->get_taxonomy(),
                            'field' => 'term_id',
                            'terms' => array_map(function($term) { return $term->get_term_id(); }, $current_item_serie),
                        ]
                    ],
                    'post__not_in' => [ $current_item->get_id() ]
                ],
                [],
                'WP_Query'
            );
            $total_related_items_by_serie = $related_items_by_serie_query->found_posts;

            if ( $related_items_by_serie_query->have_posts() ) {
                while ( $related_items_by_serie_query->have_posts() ) {
                    $related_items_by_serie_query->the_post();
                    
                    $item_related_as_entity = new \Tainacan\Entities\Item($related_items_by_serie_query->post);
                    $item_related_as_object = $item_related_as_entity->_toArray();
                    $item_related_as_object['thumbnail'] = $item_related_as_entity->get_thumbnail();

                    $related_items_by_serie[] = $item_related_as_object;
                }
                wp_reset_postdata();
            }

            $related_items_by_serie_link = esc_url( get_permalink( SESCRJ_OBRAS_COLLECTION_ID ) ) . '?taxquery[0][taxonomy]=' . $current_item_serie[0]->get_taxonomy() . '&taxquery[0][field]=term_id';
            foreach( $current_item_serie as $index => $term ) {
                $related_items_by_serie_link .= '&taxquery[0][terms][' . $index . ']=' . $term->get_term_id();
            }

            // Get label based on the root term.
            $series_label = 'Por série';

            $child_term_object = $current_item_serie[0];
            $ancestors = get_ancestors($child_term_object->get_term_id(),  $child_term_object->get_taxonomy(), 'taxonomy');

            if ( isset($ancestors) && count($ancestors) > 0 ) {
                $root_term_id = $ancestors[count($ancestors) - 1];
                $root_term = get_term( $root_term_id, $child_term_object->get_taxonomy() );
                if ( $root_term instanceof WP_Term)
                    $series_label = 'Por ' . $root_term->name;
            }

            sescrj_render_items_carousel( $series_label, SESCRJ_OBRAS_COLLECTION_ID, $related_items_by_serie, $total_related_items_by_serie, $related_items_by_serie_link );
        }
    }

    // Por Técnica
    if ( isset($current_item_tecnica_item_metadata) ) {

        $current_item_tecnica = $current_item_tecnica_item_metadata->get_value();
        $current_item_tecnica = is_array($current_item_tecnica) ? $current_item_tecnica : [ $current_item_tecnica ];
        
        if ( $current_item_tecnica && count($current_item_tecnica) > 0 ) {

            $related_items_by_tecnica = [];
            $related_items_by_tecnica_query = $tainacan_items_repository->fetch([
                    'posts_per_page' => 12,
                    'tax_query' => [
                        [
                            'taxonomy'   => $current_item_tecnica[0]->get_taxonomy(),
                            'field' => 'term_id',
                            'terms' => array_map(function($term) { return $term->get_term_id(); }, $current_item_tecnica),
                        ]
                    ],
                    'post__not_in' => [ $current_item->get_id() ]
                ],
                [],
                'WP_Query'
            );
            $total_related_items_by_tecnica = $related_items_by_tecnica_query->found_posts;

            if ( $related_items_by_tecnica_query->have_posts() ) {
                while ( $related_items_by_tecnica_query->have_posts() ) {
                    $related_items_by_tecnica_query->the_post();
                    
                    $item_related_as_entity = new \Tainacan\Entities\Item($related_items_by_tecnica_query->post);
                    $item_related_as_object = $item_related_as_entity->_toArray();
                    $item_related_as_object['thumbnail'] = $item_related_as_entity->get_thumbnail();

                    $related_items_by_tecnica[] = $item_related_as_object;
                }
                wp_reset_postdata();
            }

            $related_items_by_tecnica_link = esc_url( get_permalink( SESCRJ_OBRAS_COLLECTION_ID ) ) . '?taxquery[0][taxonomy]=' . $current_item_tecnica[0]->get_taxonomy() . '&taxquery[0][field]=term_id';
            foreach( $current_item_tecnica as $index => $term ) {
                $related_items_by_tecnica_link .= '&taxquery[0][terms][' . $index . ']=' . $term->get_term_id();
            }

            sescrj_render_items_carousel( 'Por técnica', SESCRJ_OBRAS_COLLECTION_ID, $related_items_by_tecnica, $total_related_items_by_tecnica, $related_items_by_tecnica_link );
        }
    }
}
add_action('sescrj-extra-related-items-carousels', 'sescrj_render_extra_related_items_carousels_for_obras');

/**
 * Cria carrosseis de relacionamento especiais para a coleção de Artistas
 */
function sescrj_render_extra_related_items_carousels_for_artists() {
    
    if ( get_post_type() !== SESCRJ_ARTISTAS_COLLECTION_POST_TYPE )
		return;

    $tainacan_items_repository = \Tainacan\Repositories\Items::get_instance();

    $current_item = tainacan_get_item(); 

    // Monta consulta para encontrar outros artistas
    $related_items_by_author = [];
    $related_items_by_author_query = $tainacan_items_repository->fetch([
            'posts_per_page' => 12,
            'orderby' => 'rand',
            'post__not_in' => [ $current_item->get_id() ]
        ],
        [ SESCRJ_ARTISTAS_COLLECTION_ID ],
        'WP_Query'
    );
    $total_related_items_by_author = $related_items_by_author_query->found_posts;
    
    if ( $related_items_by_author_query->have_posts() ) {
        while ( $related_items_by_author_query->have_posts() ) {
            $related_items_by_author_query->the_post();
            
            $item_related_as_entity = new \Tainacan\Entities\Item($related_items_by_author_query->post);
            $item_related_as_object = $item_related_as_entity->_toArray();
            $item_related_as_object['thumbnail'] = $item_related_as_entity->get_thumbnail();

            $related_items_by_author[] = $item_related_as_object;
        }
        wp_reset_postdata();
    }

    $related_items_by_author_link = esc_url( get_permalink( SESCRJ_ARTISTAS_COLLECTION_ID ) );

    sescrj_render_items_carousel( 'Outros artistas', SESCRJ_ARTISTAS_COLLECTION_ID, $related_items_by_author, $total_related_items_by_author, $related_items_by_author_link );

}
add_action('sescrj-extra-related-items-carousels', 'sescrj_render_extra_related_items_carousels_for_artists');

/** 
 * Função dedicada para gerar carrosseis para o SESCRJ
 */
function sescrj_render_items_carousel( $label, $collection_id, $related_items, $total_items, $view_all_link) {

    if ( $total_items <= 0 )
        return;

    $block_args = [
        'max_items_per_screen' => 6,
        'image_size' => 'tainacan-medium-full',
        'space_between_items' => 56,
        'arrow_style' => 'type-2',
        'hide_title' => false,
        'space_around_carousel' => 20
    ];

    $items_list_args = wp_parse_args([
        'collection_id' => $collection_id,
        'load_strategy' => 'parent',
        'selected_items' => json_encode($related_items),
        'image_size' => 'tainacan-medium-full'
    ], $block_args);

    $items_list_div = \Tainacan\Theme_Helper::get_instance()->get_tainacan_items_carousel($items_list_args);

    $output = '<div class="wp-block-group">
        <div class="wp-block-group__inner-container">' .
            '<h3 class="sescrj-related-items-carousel-title">' . $label . '</h3>' .
            $items_list_div .
                ( 
                $total_items > 1 ?
                    '<div class="wp-block-buttons">
                        <div class="wp-block-button">
                            <a class="wp-block-button__link" href="' . esc_url( $view_all_link ) . '">
                                ' . __('Ver tudo ▸', 'tainacan') . '
                            </a>
                        </div>
                    </div>'
                : ''
                )
            . '<div style="height:30px" aria-hidden="true" class="wp-block-spacer">
            </div>
        </div>
    </div>';

    echo $output;
} 

/**
 * Função para gerar os botões de compartilhar
 */
function sescrj_render_sharing_buttons() {

    $collections_post_types = \Tainacan\Repositories\Repository::get_collections_db_identifiers();
	$current_post_type = get_post_type();
		
	if ( is_single() && in_array($current_post_type, $collections_post_types) ) : ?>

        <div class="sescrj-header-elements">
            <button title="<?php _e(' Copiar link', 'sescrj'); ?>" class="sescrj-header-button sescrj-header-button--copy" onclick="copyTextToClipboard('<?php the_permalink(); ?>');">
                <span class="icon">
                    <svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g clip-path="url(#clip0_1078_3691)">
                            <path d="M15.9697 1.70703H4.96973C3.86973 1.70703 2.96973 2.60703 2.96973 3.70703V16.707C2.96973 17.257 3.41973 17.707 3.96973 17.707C4.51973 17.707 4.96973 17.257 4.96973 16.707V4.70703C4.96973 4.15703 5.41973 3.70703 5.96973 3.70703H15.9697C16.5197 3.70703 16.9697 3.25703 16.9697 2.70703C16.9697 2.15703 16.5197 1.70703 15.9697 1.70703ZM19.9697 5.70703H8.96973C7.86973 5.70703 6.96973 6.60703 6.96973 7.70703V21.707C6.96973 22.807 7.86973 23.707 8.96973 23.707H19.9697C21.0697 23.707 21.9697 22.807 21.9697 21.707V7.70703C21.9697 6.60703 21.0697 5.70703 19.9697 5.70703ZM18.9697 21.707H9.96973C9.41973 21.707 8.96973 21.257 8.96973 20.707V8.70703C8.96973 8.15703 9.41973 7.70703 9.96973 7.70703H18.9697C19.5197 7.70703 19.9697 8.15703 19.9697 8.70703V20.707C19.9697 21.257 19.5197 21.707 18.9697 21.707Z" fill="#1E1E1E"/>
                        </g>
                        <defs>
                            <clipPath id="clip0_1078_3691">
                                <rect width="24" height="24" fill="white" transform="translate(0.969727 0.707031)"/>
                            </clipPath>
                        </defs>
                    </svg>
                </span>
            </button>
            <button title="<?php _e(' Compartilhar', 'sescrj'); ?>" class="sescrj-header-button sescrj-header-button--share" onclick="event.preventDefault(); document.getElementsByClassName('sescrj-social-icons')[0].classList.toggle('is-list-open');">
                <span class="icon">
                    <svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g clip-path="url(#clip0_1078_3683)">
                            <path d="M18.9697 8.70703C20.6266 8.70703 21.9697 7.36389 21.9697 5.70703C21.9697 4.05018 20.6266 2.70703 18.9697 2.70703C17.3129 2.70703 15.9697 4.05018 15.9697 5.70703C15.9697 7.36389 17.3129 8.70703 18.9697 8.70703Z" stroke="#1E1E1E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M6.96973 15.707C8.62658 15.707 9.96973 14.3639 9.96973 12.707C9.96973 11.0502 8.62658 9.70703 6.96973 9.70703C5.31287 9.70703 3.96973 11.0502 3.96973 12.707C3.96973 14.3639 5.31287 15.707 6.96973 15.707Z" stroke="#1E1E1E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M18.9697 22.707C20.6266 22.707 21.9697 21.3639 21.9697 19.707C21.9697 18.0502 20.6266 16.707 18.9697 16.707C17.3129 16.707 15.9697 18.0502 15.9697 19.707C15.9697 21.3639 17.3129 22.707 18.9697 22.707Z" stroke="#1E1E1E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M9.55957 14.217L16.3896 18.197" stroke="#1E1E1E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M16.3796 7.21704L9.55957 11.197" stroke="#1E1E1E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </g>
                        <defs>
                            <clipPath id="clip0_1078_3683">
                                <rect width="24" height="24" fill="white" transform="translate(0.969727 0.707031)"/>
                            </clipPath>
                        </defs>
                    </svg>
                </span>
                <div class="sescrj-social-icons">
                    <?php echo blocksy_get_social_share_box(); ?>
                </div>
            </button>
        </div>

    <?php endif;
}
add_action('blocksy:hero:title:before', 'sescrj_render_sharing_buttons');

/* Permite que a galeria de mídia mude a altura a depender da imagem */
add_filter( 'tainacan-swiper-main-options', function($options) {
    return array_merge(
        $options,
        array(
            'autoHeight' => true
        )
    );
}, 9 , 1);