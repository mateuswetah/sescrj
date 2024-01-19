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
                'inline_style' => $inline_style_bleeding_content
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
    }
    add_action( 'init', 'sescrj_block_styles' );
}
