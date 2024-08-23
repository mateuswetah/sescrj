<?php
/** 
 * Theme Block Styles
 */

 if ( function_exists('register_block_style') ) {
    /**
     * Adds block styles
     */
    function sescrj_block_styles() {

        $inline_style_bleeding_content = '
            .is-style-sescrj-bleeding-content {
                width: var(--bleeding-width) !important;
                max-width: var(--bleeding-width) !important;
            }
            .is-style-sescrj-bleeding-content > * {
                max-width: none;
                width: 100%;
            }
            @media (max-width: 689.98px) {
                .alignfull.is-style-sescrj-bleeding-content {
                    width: calc(var(--bleeding-width) + 6vw) !important;
                    max-width: calc(var(--bleeding-width) + 6vw) !important;
                    margin-left: -6vw !important;
                }
            }';

        register_block_style(
            'core/group',
            array(
                'name'         => 'sescrj-bleeding-content',
                'label'        => __( 'Conteúdo vazado', 'sescrj' ),
                'inline_style' => $inline_style_bleeding_content
            )
        );
        register_block_style(
            'core/group',
            array(
                'name'         => 'sescrj-bleeding-border-top',
                'label'        => __( 'Borda vazada (acima)', 'sescrj' )
            )
        );
        register_block_style(
            'core/group',
            array(
                'name'         => 'sescrj-bleeding-border-bottom',
                'label'        => __( 'Borda vazada (abaixo)', 'sescrj' )
            )
        );

        register_block_style(
            'core/column',
            array(
                'name'         => 'sescrj-bleeding-content',
                'label'        => __( 'Conteúdo vazado', 'sescrj' ),
                'inline_style' => $inline_style_bleeding_content
            )
        );
        register_block_style(
            'core/column',
            array(
                'name'         => 'sescrj-bleeding-border-top',
                'label'        => __( 'Borda vazada (acima)', 'sescrj' )
            )
        );
        register_block_style(
            'core/column',
            array(
                'name'         => 'sescrj-bleeding-border-bottom',
                'label'        => __( 'Borda vazada (abaixo)', 'sescrj' )
            )
        );

        register_block_style(
            'core/image',
            array(
                'name'         => 'sescrj-bleeding-content',
                'label'        => __( 'Conteúdo vazado', 'sescrj' ),
                'inline_style' => $inline_style_bleeding_content
            )
        );
        register_block_style(
            'core/image',
            array(
                'name'         => 'sescrj-bleeding-border-top',
                'label'        => __( 'Borda vazada (acima)', 'sescrj' )
            )
        );
        register_block_style(
            'core/image',
            array(
                'name'         => 'sescrj-bleeding-border-bottom',
                'label'        => __( 'Borda vazada (abaixo)', 'sescrj' )
            )
        );

        register_block_style(
            'core/gallery',
            array(
                'name'         => 'sescrj-bleeding-content',
                'label'        => __( 'Conteúdo vazado', 'sescrj' ),
                'inline_style' => $inline_style_bleeding_content
            )
        );
        register_block_style(
            'core/gallery',
            array(
                'name'         => 'sescrj-bleeding-border-top',
                'label'        => __( 'Borda vazada (acima)', 'sescrj' )
            )
        );
        register_block_style(
            'core/gallery',
            array(
                'name'         => 'sescrj-bleeding-border-bottom',
                'label'        => __( 'Borda vazada (abaixo)', 'sescrj' )
            )
        );

        register_block_style(
            'core/cover',
            array(
                'name'         => 'sescrj-bleeding-content',
                'label'        => __( 'Conteúdo vazado', 'sescrj' ),
                'inline_style' => $inline_style_bleeding_content . ' .wp-block-separator:not(.is-style-dots).is-style-sescrj-bleeding-content { height: 8px; }'
            )
        );
        register_block_style(
            'core/cover',
            array(
                'name'         => 'sescrj-bleeding-border-top',
                'label'        => __( 'Borda vazada (acima)', 'sescrj' )
            )
        );
        register_block_style(
            'core/cover',
            array(
                'name'         => 'sescrj-bleeding-border-bottom',
                'label'        => __( 'Borda vazada (abaixo)', 'sescrj' )
            )
        );

        register_block_style(
            'core/separator',
            array(
                'name'         => 'sescrj-bleeding-content',
                'label'        => __( 'Conteúdo vazado', 'sescrj' ),
                'inline_style' => $inline_style_bleeding_content
            )
        );

        register_block_style(
            'tainacan/dynamic-items-list',
            array(
                'name'         => 'sescrj-stacked',
                'label'        => __( 'Empilhado', 'sescrj' ),
                'inline_style' => $inline_style_bleeding_content
            )
        );

        register_block_style(
            'core/separator',
            array(
                'name'         => 'sescrj-thick-line',
                'label'        => __( 'Grosso', 'sescrj' ),
                'inline_style' => 'hr.wp-block-separator.is-style-sescrj-thick-line { height: 8px; --theme-block-max-width: 100% !important; }'
            )
        );

        register_block_style(
            'core/columns',
            array(
                'name'         => 'sescrj-reverse-columns-on-mobile',
                'label'        => __( 'Inverter no mobile', 'sescrj' ),
                'inline_style' => '
                    @media (max-width: 781px) {
                        .wp-block-columns.is-style-sescrj-reverse-columns-on-mobile {
                            flex-direction: column-reverse;
                        }
                    }'
            )
        );
    }
    add_action( 'init', 'sescrj_block_styles' );
}
