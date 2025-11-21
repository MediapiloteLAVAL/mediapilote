<?php
/**
 * Bloc Images
 * Bloc Gutenberg natif FSE pour afficher une galerie d'images horizontales
 *
 * @package MediaPilote
 * @since 1.0.0
 */

// Empêcher l'accès direct
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Enregistrer le bloc Images
 */
function mediapilote_register_block_images() {
    // Vérifier si la fonction existe
    if (!function_exists('register_block_type')) {
        return;
    }

    // Enregistrer le script du bloc
    wp_register_script(
        'mediapilote-block-images-editor',
        get_template_directory_uri() . '/blocks/blockImages/block.js',
        array('wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-i18n'),
        filemtime(get_template_directory() . '/blocks/blockImages/block.js')
    );

    // Enregistrer le style du bloc (frontend et editor)
    wp_register_style(
        'mediapilote-block-images-style',
        get_template_directory_uri() . '/blocks/blockImages/style.css',
        array('utilitary'),
        filemtime(get_template_directory() . '/blocks/blockImages/style.css')
    );

    // Enregistrer le style de l'éditeur
    wp_register_style(
        'mediapilote-block-images-editor-style',
        get_template_directory_uri() . '/blocks/blockImages/editor.css',
        array('wp-edit-blocks'),
        filemtime(get_template_directory() . '/blocks/blockImages/editor.css')
    );

    // Enregistrer le bloc
    register_block_type('mediapilote/images', array(
        'editor_script' => 'mediapilote-block-images-editor',
        'style' => 'mediapilote-block-images-style',
        'editor_style' => 'mediapilote-block-images-editor-style',
        'render_callback' => 'mediapilote_render_block_images',
        'attributes' => array(
            'images' => array(
                'type' => 'array',
                'default' => array()
            ),
            'bannerHeight' => array(
                'type' => 'number',
                'default' => 465
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
add_action('init', 'mediapilote_register_block_images');

/**
 * Rendu du bloc côté frontend
 */
function mediapilote_render_block_images($attributes) {
    $images = isset($attributes['images']) ? $attributes['images'] : array();
    $banner_height = isset($attributes['bannerHeight']) ? intval($attributes['bannerHeight']) : 465;

    // Limiter la hauteur entre 200 et 800px
    $banner_height = max(200, min(800, $banner_height));

    // Si pas d'images, ne rien afficher
    if (empty($images)) {
        return '';
    }

    ob_start();
    ?>
    <div class="wp-block-mediapilote-images block-images alignfull">
        <div class="container-fluid no-padding">
            <div class="block-images__wrapper">
                <div class="block-images__gallery" style="height: <?php echo esc_attr($banner_height); ?>px;">
                    <?php foreach ($images as $image) : ?>
                        <?php if (!empty($image['url'])) : ?>
                            <div class="block-images__item">
                                <img 
                                    src="<?php echo esc_url($image['url']); ?>" 
                                    alt="<?php echo esc_attr($image['alt'] ?? ''); ?>"
                                    class="block-images__image"
                                    style="height: <?php echo esc_attr($banner_height); ?>px;"
                                />
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}