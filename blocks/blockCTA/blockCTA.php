<?php
/**
 * Bloc CTA
 * Bloc Gutenberg natif FSE pour afficher une section CTA avec titre, description et bouton
 *
 * @package MediaPilote
 * @since 1.0.0
 */

// Empêcher l'accès direct
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Enregistrer le bloc CTA
 */
function mediapilote_register_block_cta() {
    // Vérifier si la fonction existe
    if (!function_exists('register_block_type')) {
        return;
    }

    // Enregistrer le script du bloc
    wp_register_script(
        'mediapilote-block-cta-editor',
        get_template_directory_uri() . '/blocks/blockCTA/block.js',
        array('wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-i18n'),
        filemtime(get_template_directory() . '/blocks/blockCTA/block.js')
    );

    // Enregistrer le style du bloc (frontend et editor)
    wp_register_style(
        'mediapilote-block-cta-style',
        get_template_directory_uri() . '/blocks/blockCTA/style.css',
        array('utilitary'),
        filemtime(get_template_directory() . '/blocks/blockCTA/style.css')
    );

    // Enregistrer le style de l'éditeur
    wp_register_style(
        'mediapilote-block-cta-editor-style',
        get_template_directory_uri() . '/blocks/blockCTA/editor.css',
        array('wp-edit-blocks'),
        filemtime(get_template_directory() . '/blocks/blockCTA/editor.css')
    );

    // Enregistrer le bloc
    register_block_type('mediapilote/cta', array(
        'editor_script' => 'mediapilote-block-cta-editor',
        'style' => 'mediapilote-block-cta-style',
        'editor_style' => 'mediapilote-block-cta-editor-style',
        'render_callback' => 'mediapilote_render_block_cta',
        'attributes' => array(
            'title' => array(
                'type' => 'string',
                'default' => 'Titre CTA'
            ),
            'description' => array(
                'type' => 'string',
                'default' => 'Description du CTA'
            ),
            'buttonText' => array(
                'type' => 'string',
                'default' => 'Cliquez ici'
            ),
            'buttonUrl' => array(
                'type' => 'string',
                'default' => '#'
            ),
            'backgroundColor' => array(
                'type' => 'string',
                'default' => '#c67652'
            ),
            'textColor' => array(
                'type' => 'string',
                'default' => '#ffffff'
            ),
            'padding' => array(
                'type' => 'number',
                'default' => 100
            ),
            'align' => array(
                'type' => 'string',
                'default' => 'full'
            )
        ),
        'supports' => array(
            'align' => array('full'),
            'html' => false
        )
    ));
}
add_action('init', 'mediapilote_register_block_cta');

/**
 * Rendu du bloc côté frontend
 */
function mediapilote_render_block_cta($attributes, $content, $block) {
    $title = isset($attributes['title']) ? $attributes['title'] : '';
    $description = isset($attributes['description']) ? $attributes['description'] : '';
    $button_text = isset($attributes['buttonText']) ? $attributes['buttonText'] : '';
    $button_url = isset($attributes['buttonUrl']) ? $attributes['buttonUrl'] : '#';
    $background_color = isset($attributes['backgroundColor']) ? $attributes['backgroundColor'] : '#c67652';
    $text_color = isset($attributes['textColor']) ? $attributes['textColor'] : '#ffffff';
    $padding = isset($attributes['padding']) ? $attributes['padding'] : 100;

    $wrapper_attributes = get_block_wrapper_attributes();

    ob_start();
    ?>
    <div class="wp-block-mediapilote-cta cta-section alignfull" style="background-color: <?php echo esc_attr($background_color); ?>; padding: <?php echo esc_attr($padding); ?>px 0;" <?php echo $wrapper_attributes; ?>>
        <div class="container-fluid">
            <div class="cta-section__content text-center" style="color: <?php echo esc_attr($text_color); ?>;">
                <?php if (!empty($title)) : ?>
                    <h2 class="cta-section__title"><?php echo wp_kses_post($title); ?></h2>
                <?php endif; ?>
                <?php if (!empty($description)) : ?>
                    <div class="cta-section__description"><p><?php echo wp_kses_post($description); ?></p></div>
                <?php endif; ?>
                <?php if (!empty($button_text)) : ?>
                    <a href="<?php echo esc_url($button_url); ?>" class="btn">
                        <span class="btn-text"><?php echo esc_html($button_text); ?></span>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}