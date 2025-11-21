<?php
/**
 * Bloc Image Texte
 * Bloc Gutenberg natif FSE pour afficher une section avec image et texte
 *
 * @package MediaPilote
 * @since 1.0.0
 */

// Empêcher l'accès direct
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Enregistrer le bloc Image Texte
 */
function mediapilote_register_block_image_texte() {
    // Vérifier si la fonction existe
    if (!function_exists('register_block_type')) {
        return;
    }

    // Enregistrer le script du bloc
    wp_register_script(
        'mediapilote-block-image-texte-editor',
        get_template_directory_uri() . '/blocks/blockImageTexte/block.js',
        array('wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-i18n'),
        filemtime(get_template_directory() . '/blocks/blockImageTexte/block.js')
    );

    // Enregistrer le style du bloc (frontend et editor)
    wp_register_style(
        'mediapilote-block-image-texte-style',
        get_template_directory_uri() . '/blocks/blockImageTexte/style.css',
        array('utilitary'),
        filemtime(get_template_directory() . '/blocks/blockImageTexte/style.css')
    );

    // Enregistrer le style de l'éditeur
    wp_register_style(
        'mediapilote-block-image-texte-editor-style',
        get_template_directory_uri() . '/blocks/blockImageTexte/editor.css',
        array('wp-edit-blocks'),
        filemtime(get_template_directory() . '/blocks/blockImageTexte/editor.css')
    );

    // Enregistrer le bloc
    register_block_type('mediapilote/image-texte', array(
        'editor_script' => 'mediapilote-block-image-texte-editor',
        'style' => 'mediapilote-block-image-texte-style',
        'editor_style' => 'mediapilote-block-image-texte-editor-style',
        'render_callback' => 'mediapilote_render_block_image_texte',
        'attributes' => array(
            'title' => array(
                'type' => 'string',
                'default' => 'Lorem ipsum sit amet'
            ),
            'description' => array(
                'type' => 'string',
                'default' => 'At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.'
            ),
            'buttonText' => array(
                'type' => 'string',
                'default' => 'En savoir +'
            ),
            'buttonUrl' => array(
                'type' => 'string',
                'default' => '#'
            ),
            'backgroundColor' => array(
                'type' => 'string',
                'default' => '#E0E648'
            ),
            'imageId' => array(
                'type' => 'number',
                'default' => 0
            ),
            'imageUrl' => array(
                'type' => 'string',
                'default' => ''
            ),
                'align' => array(
                    'type' => 'string',
                    'default' => 'full'
                ),
                'reverse' => array(
                    'type' => 'boolean',
                    'default' => false
                )
                // Missing comma added here
        ),
        'supports' => array(
            'align' => array('full'),
            'html' => false
        )
    ));
}
add_action('init', 'mediapilote_register_block_image_texte');

/**
 * Rendu du bloc côté frontend
 */
function mediapilote_render_block_image_texte($attributes) {
    $title = isset($attributes['title']) ? $attributes['title'] : '';
    $description = isset($attributes['description']) ? $attributes['description'] : '';
    $button_text = isset($attributes['buttonText']) ? $attributes['buttonText'] : '';
    $button_url = isset($attributes['buttonUrl']) ? $attributes['buttonUrl'] : '#';
    $background_color = isset($attributes['backgroundColor']) ? $attributes['backgroundColor'] : '#E0E648';
    $image_url = isset($attributes['imageUrl']) ? $attributes['imageUrl'] : '';
    $reverse = isset($attributes['reverse']) ? $attributes['reverse'] : false;

    ob_start();
    ?>
    <div class="wp-block-mediapilote-image-texte image-texte-section alignfull" style="background-color: <?php echo esc_attr($background_color); ?>;">
        <div class="container-fluid">
            <div class="image-texte-section__content row">
                <?php if ($reverse) : ?>
                    <div class="image-texte-section__image col-xl-5 col-lg-6 col-sm-12"<?php if (!empty($image_url)) : ?> style="background-image: url('<?php echo esc_url($image_url); ?>');"<?php endif; ?>></div>
                    <div class="image-texte-section__text image-texte-section__text--right col-xl-7 col-lg-6 col-sm-12">
                        <?php if (!empty($title)) : ?>
                            <h2 class="image-texte-section__title"><?php echo wp_kses_post($title); ?></h2>
                        <?php endif; ?>
                        <?php if (!empty($description)) : ?>
                            <div class="image-texte-section__description"><p><?php echo wp_kses_post($description); ?></p></div>
                        <?php endif; ?>
                        <?php if (!empty($button_text)) : ?>
                            <a href="<?php echo esc_url($button_url); ?>" class="btn">
                                <span class="btn-text"><?php echo esc_html($button_text); ?></span>
                            </a>
                        <?php endif; ?>
                    </div>
                <?php else : ?>
                    <div class="image-texte-section__text image-texte-section__text--left col-xl-7 col-lg-6 col-sm-12">
                        <?php if (!empty($title)) : ?>
                            <h2 class="image-texte-section__title"><?php echo wp_kses_post($title); ?></h2>
                        <?php endif; ?>
                        <?php if (!empty($description)) : ?>
                            <div class="image-texte-section__description"><p><?php echo wp_kses_post($description); ?></p></div>
                        <?php endif; ?>
                        <?php if (!empty($button_text)) : ?>
                            <a href="<?php echo esc_url($button_url); ?>" class="btn">
                                <span class="btn-text"><?php echo esc_html($button_text); ?></span>
                            </a>
                        <?php endif; ?>
                    </div>
                    <div class="image-texte-section__image col-xl-5 col-lg-6 col-sm-12"<?php if (!empty($image_url)) : ?> style="background-image: url('<?php echo esc_url($image_url); ?>');"<?php endif; ?>></div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}