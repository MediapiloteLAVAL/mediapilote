<?php
/**
 * Bloc Réseaux Sociaux
 * Bloc Gutenberg natif FSE pour afficher une section de réseaux sociaux
 *
 * @package MediaPilote
 * @since 1.0.0
 */

// Empêcher l'accès direct
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Enregistrer le bloc Réseaux Sociaux
 */
function mediapilote_register_block_reseaux_sociaux() {
    // Vérifier si la fonction existe
    if (!function_exists('register_block_type')) {
        return;
    }

    // Enregistrer le script du bloc
    wp_register_script(
        'mediapilote-block-reseaux-sociaux-editor',
        get_template_directory_uri() . '/blocks/blockReseauxSociaux/block.js',
        array('wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-i18n'),
        filemtime(get_template_directory() . '/blocks/blockReseauxSociaux/block.js')
    );

    // Enregistrer le style du bloc (frontend et editor)
    wp_register_style(
        'mediapilote-block-reseaux-sociaux-style',
        get_template_directory_uri() . '/blocks/blockReseauxSociaux/style.css',
        array('utilitary'),
        filemtime(get_template_directory() . '/blocks/blockReseauxSociaux/style.css')
    );

    // Enregistrer le style de l'éditeur
    wp_register_style(
        'mediapilote-block-reseaux-sociaux-editor-style',
        get_template_directory_uri() . '/blocks/blockReseauxSociaux/editor.css',
        array('wp-edit-blocks'),
        filemtime(get_template_directory() . '/blocks/blockReseauxSociaux/editor.css')
    );

    // Enregistrer le bloc
    register_block_type('mediapilote/reseaux-sociaux', array(
        'editor_script' => 'mediapilote-block-reseaux-sociaux-editor',
        'style' => 'mediapilote-block-reseaux-sociaux-style',
        'editor_style' => 'mediapilote-block-reseaux-sociaux-editor-style',
        'render_callback' => 'mediapilote_render_block_reseaux_sociaux',
        'attributes' => array(
            'backgroundColor' => array(
                'type' => 'string',
                'default' => '#2d3037'
            ),
            'height' => array(
                'type' => 'number',
                'default' => 200
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
add_action('init', 'mediapilote_register_block_reseaux_sociaux');

/**
 * Rendu du bloc côté frontend
 */
function mediapilote_render_block_reseaux_sociaux($attributes, $content, $block) {
    $background_color = isset($attributes['backgroundColor']) ? $attributes['backgroundColor'] : '#2d3037';
    $height = isset($attributes['height']) ? $attributes['height'] : 200;

    $wrapper_attributes = get_block_wrapper_attributes();

    // Récupérer les réseaux sociaux depuis ACF Options
    $social_networks = get_field('social', 'option');

    ob_start();
    ?>
    <div class="wp-block-mediapilote-reseaux-sociaux reseaux-sociaux-section alignfull" 
         style="background-color: <?php echo esc_attr($background_color); ?>; height: <?php echo esc_attr($height); ?>px;" 
         <?php echo $wrapper_attributes; ?>>
        <div class="container-fluid h-100">
            <div class="reseaux-sociaux-section__content d-flex justify-content-center align-items-center h-100">
                <?php if ($social_networks && is_array($social_networks)) : ?>
                    <div class="social-network-grid">
                        <div class="social-network-icons">
                            <?php foreach ($social_networks as $index => $social) : ?>
                                <?php if ($index < 4) : // Limiter à 4 réseaux sociaux ?>
                                    <?php 
                                    $social_name = $social['social_name'] ?? '';
                                    $social_img = $social['social_img'] ?? null;
                                    $social_url = $social['social_url'] ?? null;
                                    ?>
                                    <div class="social-network-item social-network-item--<?php echo $index + 1; ?>">
                                        <?php if ($social_url && is_array($social_url) && !empty($social_url['url'])) : ?>
                                            <a href="<?php echo esc_url($social_url['url']); ?>" 
                                               title="<?php echo esc_attr($social_name); ?>"
                                               target="<?php echo esc_attr($social_url['target'] ?? '_blank'); ?>"
                                               class="social-network-link">
                                        <?php endif; ?>
                                        
                                        <?php if ($social_img && is_array($social_img) && !empty($social_img['url'])) : ?>
                                            <img src="<?php echo esc_url($social_img['url']); ?>" 
                                                 alt="<?php echo esc_attr($social_name); ?>" 
                                                 class="social-network-icon">
                                        <?php endif; ?>
                                        
                                        <?php if ($social_url && is_array($social_url) && !empty($social_url['url'])) : ?>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php else : ?>
                    <div class="social-networks-empty">
                        <p style="color: #ffffff;">Aucun réseau social configuré. Veuillez ajouter des réseaux sociaux dans les options du thème.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}