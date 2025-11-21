<?php
/**
 * Bloc Entête Hero
 * Bloc Gutenberg natif FSE pour afficher un hero banner en pleine largeur
 * 
 * @package MediaPilote
 * @since 1.0.0
 */

// Empêcher l'accès direct
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Enregistrer le bloc Entête
 */
function mediapilote_register_block_entete() {
    // Vérifier si la fonction existe
    if (!function_exists('register_block_type')) {
        return;
    }

    // Enregistrer le script du bloc
    wp_register_script(
        'mediapilote-block-entete-editor',
        get_template_directory_uri() . '/blocks/blockEntete/block.js',
        array('wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-i18n'),
        filemtime(get_template_directory() . '/blocks/blockEntete/block.js')
    );

    // Enregistrer le script frontend du bloc
    wp_register_script(
        'mediapilote-block-entete-frontend',
        get_template_directory_uri() . '/blocks/blockEntete/frontend.js',
        array(),
        filemtime(get_template_directory() . '/blocks/blockEntete/frontend.js')
    );

    // Enregistrer le style du bloc (frontend et editor)
    wp_register_style(
        'mediapilote-block-entete-style',
        get_template_directory_uri() . '/blocks/blockEntete/style.css',
        array(),
        filemtime(get_template_directory() . '/blocks/blockEntete/style.css')
    );

    // Enregistrer le style de l'éditeur
    wp_register_style(
        'mediapilote-block-entete-editor-style',
        get_template_directory_uri() . '/blocks/blockEntete/editor.css',
        array('wp-edit-blocks'),
        filemtime(get_template_directory() . '/blocks/blockEntete/editor.css')
    );

    // Enregistrer le bloc
    register_block_type('mediapilote/entete', array(
        'editor_script' => 'mediapilote-block-entete-editor',
        'script' => 'mediapilote-block-entete-frontend',
        'style' => 'mediapilote-block-entete-style',
        'editor_style' => 'mediapilote-block-entete-editor-style',
        'render_callback' => 'mediapilote_render_block_entete',
        'attributes' => array(
            'content' => array(
                'type' => 'object',
                'default' => array(
                    'title' => 'H1 - The quick brown fox jumps over the lazy dog',
                    'description' => 'Glad to see you! You\'re one password away from creating something amazing',
                    'buttonText' => 'En savoir +',
                    'buttonUrl' => '#'
                )
            ),
            'images' => array(
                'type' => 'array',
                'default' => array(
                    array(
                        'backgroundImageId' => 0,
                        'backgroundImageUrl' => ''
                    )
                )
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
add_action('init', 'mediapilote_register_block_entete');

/**
 * Rendu du bloc côté frontend
 */
function mediapilote_render_block_entete($attributes) {
    $content = isset($attributes['content']) ? $attributes['content'] : array();
    $images = isset($attributes['images']) ? $attributes['images'] : array();
    
    if (empty($images)) {
        return '';
    }
    
    ob_start();
    ?>
    <div class="wp-block-mediapilote-entete hero-banner alignfull">
        <div class="hero-banner__slider" data-images='<?php echo esc_attr(json_encode(array_column($images, 'backgroundImageUrl'))); ?>'>
            <div class="hero-banner__container hero-banner__container--current" style="background-image: url('<?php echo esc_url($images[0]['backgroundImageUrl']); ?>');">
                <div class="hero-banner__overlay"></div>
                <div class="hero-banner__content">
                    <div class="hero-banner__text-content">
                        <?php if (!empty($content['title'])) : ?>
                            <h1 class="hero-banner__title"><?php echo wp_kses_post($content['title']); ?></h1>
                        <?php endif; ?>
                        <?php if (!empty($content['description'])) : ?>
                            <p class="hero-banner__description"><?php echo esc_html($content['description']); ?></p>
                        <?php endif; ?>
                        <?php if (!empty($content['buttonText'])) : ?>
                            <a href="<?php echo esc_url($content['buttonUrl']); ?>" class="btn">
                                <span class="btn-text"><?php echo esc_html($content['buttonText']); ?></span>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="hero-banner__container hero-banner__container--next">
                <div class="hero-banner__overlay"></div>
                <div class="hero-banner__content">
                    <div class="hero-banner__text-content">
                        <?php if (!empty($content['title'])) : ?>
                            <h1 class="hero-banner__title"><?php echo wp_kses_post($content['title']); ?></h1>
                        <?php endif; ?>
                        <?php if (!empty($content['description'])) : ?>
                            <p class="hero-banner__description"><?php echo esc_html($content['description']); ?></p>
                        <?php endif; ?>
                        <?php if (!empty($content['buttonText'])) : ?>
                            <a href="<?php echo esc_url($content['buttonUrl']); ?>" class="btn">
                                <span class="btn-text"><?php echo esc_html($content['buttonText']); ?></span>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="hero-banner__decorative-lines">
                <?php foreach ($images as $index => $image) : ?>
                    <span class="hero-banner__line" data-slide-nav="<?php echo $index; ?>"></span>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
