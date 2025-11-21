<?php
/**
 * Bloc Chiffres Clés
 * Bloc Gutenberg natif FSE pour afficher des chiffres clés ou des points clés avec effet d'incrémentation
 *
 * @package MediaPilote
 * @since 1.0.0
 */

// Empêcher l'accès direct
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Enregistrer le bloc Chiffres Clés
 */
function mediapilote_register_block_chiffres_cles() {
    // Vérifier si la fonction existe
    if (!function_exists('register_block_type')) {
        return;
    }

    // Enregistrer le script du bloc
    wp_register_script(
        'mediapilote-block-chiffres-cles-editor',
        get_template_directory_uri() . '/blocks/blockChiffresCles/block.js',
        array('wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-i18n'),
        filemtime(get_template_directory() . '/blocks/blockChiffresCles/block.js')
    );

    // Enregistrer le style du bloc (frontend et editor)
    wp_register_style(
        'mediapilote-block-chiffres-cles-style',
        get_template_directory_uri() . '/blocks/blockChiffresCles/style.css',
        array('utilitary'),
        filemtime(get_template_directory() . '/blocks/blockChiffresCles/style.css')
    );

    // Enregistrer le style de l'éditeur
    wp_register_style(
        'mediapilote-block-chiffres-cles-editor-style',
        get_template_directory_uri() . '/blocks/blockChiffresCles/editor.css',
        array('wp-edit-blocks'),
        filemtime(get_template_directory() . '/blocks/blockChiffresCles/editor.css')
    );

    // Enregistrer le script frontend
    wp_register_script(
        'mediapilote-block-chiffres-cles-frontend',
        get_template_directory_uri() . '/blocks/blockChiffresCles/frontend.js',
        array('jquery'),
        filemtime(get_template_directory() . '/blocks/blockChiffresCles/frontend.js'),
        true
    );

    // Enregistrer le bloc
    register_block_type('mediapilote/chiffres-cles', array(
        'editor_script' => 'mediapilote-block-chiffres-cles-editor',
        'style' => 'mediapilote-block-chiffres-cles-style',
        'editor_style' => 'mediapilote-block-chiffres-cles-editor-style',
        'script' => 'mediapilote-block-chiffres-cles-frontend',
        'render_callback' => 'mediapilote_render_block_chiffres_cles',
        'attributes' => array(
            'backgroundColor' => array(
                'type' => 'string',
                'default' => '#ffffff'
            ),
            'textColor' => array(
                'type' => 'string',
                'default' => '#2d3037'
            ),
            'title' => array(
                'type' => 'string',
                'default' => ''
            ),
            'description' => array(
                'type' => 'string',
                'default' => ''
            ),
            'mode' => array(
                'type' => 'string',
                'default' => 'chiffres' // 'chiffres' ou 'points'
            ),
            'items' => array(
                'type' => 'array',
                'default' => array(
                    array(
                        'value' => '150',
                        'label' => 'Duo dolores et ea rebum.',
                        'icon' => null
                    ),
                    array(
                        'value' => '20',
                        'label' => 'Duo dolores et ea rebum.',
                        'icon' => null
                    ),
                    array(
                        'value' => '59',
                        'label' => 'Duo dolores et ea rebum.',
                        'icon' => null
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
add_action('init', 'mediapilote_register_block_chiffres_cles');

/**
 * Rendu du bloc côté frontend
 */
function mediapilote_render_block_chiffres_cles($attributes, $content, $block) {
    $background_color = isset($attributes['backgroundColor']) ? $attributes['backgroundColor'] : '#ffffff';
    $text_color = isset($attributes['textColor']) ? $attributes['textColor'] : '#2d3037';
    $title = isset($attributes['title']) ? $attributes['title'] : '';
    $description = isset($attributes['description']) ? $attributes['description'] : '';
    $mode = isset($attributes['mode']) ? $attributes['mode'] : 'chiffres';
    $items = isset($attributes['items']) ? $attributes['items'] : array();
    $align = isset($attributes['align']) ? $attributes['align'] : 'full';

    // Calculer le nombre de colonnes selon le nombre d'éléments
    $nb_items = count($items);
    if ($nb_items <= 0) return '';

    $col_class = '';
    switch($nb_items) {
        case 1:
            $col_class = 'col-12';
            break;
        case 2:
            $col_class = 'col-md-6';
            break;
        case 3:
            $col_class = 'col-lg-4';
            break;
        case 4:
            $col_class = 'col-lg-3 col-md-6';
            break;
        case 5:
            $col_class = 'col-xl-2 col-lg-4 col-md-6';
            break;
        default:
            $col_class = 'col-lg-4';
    }

    // Styles CSS inline pour la personnalisation
    $block_styles = array(
        'background-color: ' . esc_attr($background_color),
        'color: ' . esc_attr($text_color),
        'padding: 60px 0'
    );
    $block_style_attr = 'style="' . implode('; ', $block_styles) . '"';

    ob_start();
    ?>
    <section class="bloc-chiffres-cles alignfull" <?php echo $block_style_attr; ?> data-items="<?php echo esc_attr($nb_items); ?>">
        <div class="container">
            <?php if (!empty($title)): ?>
                <div class="row">
                    <div class="col-12 text-center mb-4">
                        <h2 class="bloc-chiffres-cles__title" style="color: <?php echo esc_attr($text_color); ?>">
                            <?php echo wp_kses_post($title); ?>
                        </h2>
                    </div>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($description)): ?>
                <div class="row">
                    <div class="col-12 text-center mb-5">
                        <p class="bloc-chiffres-cles__description" style="color: <?php echo esc_attr($text_color); ?>">
                            <?php echo wp_kses_post($description); ?>
                        </p>
                    </div>
                </div>
            <?php endif; ?>
            
            <div class="row justify-content-center">
                <?php foreach ($items as $index => $item): ?>
                    <?php if (!empty($item['value']) || !empty($item['icon'])): ?>
                        <div class="<?php echo esc_attr($col_class); ?> mb-4">
                            <div class="bloc-chiffres-cles__item text-center">
                                <?php if ($mode === 'chiffres'): ?>
                                    <div class="bloc-chiffres-cles__number" 
                                         style="color: <?php echo esc_attr($text_color); ?>"
                                         data-target="<?php echo esc_attr($item['value']); ?>">
                                        0
                                    </div>
                                <?php else: ?>
                                    <?php if (!empty($item['icon'])): ?>
                                        <div class="bloc-chiffres-cles__icon">
                                            <img src="<?php echo esc_url($item['icon']['url']); ?>" 
                                                 alt="<?php echo esc_attr($item['icon']['alt']); ?>"
                                                 class="img-fluid">
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>
                                
                                <?php if (!empty($item['label'])): ?>
                                    <p class="bloc-chiffres-cles__label" style="color: <?php echo esc_attr($text_color); ?>">
                                        <?php echo wp_kses_post($item['label']); ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php
    return ob_get_clean();
}