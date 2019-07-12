<?php
/**
 * Functions to register client-side assets (scripts and stylesheets) for the
 * Gutenberg block.
 */

/**
 * Registers all block assets so that they can be enqueued through Gutenberg in
 * the corresponding context.
 *
 * @see https://wordpress.org/gutenberg/handbook/designers-developers/developers/tutorials/block-tutorial/writing-your-first-block-type/
 */
function multi_step_form_block_init($handler) {
    
    if (!function_exists("register_block_type")) {
        return;
    }

    add_action('enqueue_block_editor_assets', function() {

        $dir = dirname( __FILE__ );

        $block_js = 'msf-block.js';
        wp_register_script(
            'msf-block-editor',
            plugins_url( $block_js, __FILE__ ),
            array(
                'wp-blocks',
                'wp-i18n',
                'wp-element',
                'wp-editor',
            ),
            filemtime( "$dir/$block_js" )
        );

        $editor_css = 'msf-editor.css';
        wp_register_style(
            'msf-block-editor',
            plugins_url( $editor_css, __FILE__ ),
            array(),
            filemtime( "$dir/$editor_css" )
        );

    });

    register_block_type( 'multi-step-form/msf', array(
        'editor_script' => 'msf-block-editor',
        'editor_style'  => 'msf-block-editor',
        'render_callback' => $handler,
    ) );
}
