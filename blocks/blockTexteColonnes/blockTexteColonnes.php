<?php
/**
 * Bloc Texte en Colonnes
 * Bloc Gutenberg natif FSE pour afficher du texte en colonnes (1, 2 ou 3)
 *
 * @package MediaPilote
 * @since 1.0.0
 */

// Empêcher l'accès direct
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Enregistrer le bloc Texte Colonnes
 */
function mediapilote_register_block_texte_colonnes() {
    // Vérifier si la fonction existe
    if (!function_exists('register_block_type')) {
        return;
    }

    // Enregistrer le script du bloc
    wp_register_script(
        'mediapilote-block-texte-colonnes-editor',
        get_template_directory_uri() . '/blocks/blockTexteColonnes/block.js',
        array('wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-i18n'),
        filemtime(get_template_directory() . '/blocks/blockTexteColonnes/block.js')
    );

    // Enregistrer le style du bloc (frontend et editor)
    wp_register_style(
        'mediapilote-block-texte-colonnes-style',
        get_template_directory_uri() . '/blocks/blockTexteColonnes/style.css',
        array('utilitary'),
        filemtime(get_template_directory() . '/blocks/blockTexteColonnes/style.css')
    );

    // Enregistrer le style de l'éditeur
    wp_register_style(
        'mediapilote-block-texte-colonnes-editor-style',
        get_template_directory_uri() . '/blocks/blockTexteColonnes/editor.css',
        array('wp-edit-blocks'),
        filemtime(get_template_directory() . '/blocks/blockTexteColonnes/editor.css')
    );

    // Enregistrer le bloc
    register_block_type('mediapilote/texte-colonnes', array(
        'editor_script' => 'mediapilote-block-texte-colonnes-editor',
        'style' => 'mediapilote-block-texte-colonnes-style',
        'editor_style' => 'mediapilote-block-texte-colonnes-editor-style',
        'render_callback' => 'mediapilote_render_block_texte_colonnes',
        'attributes' => array(
            'title' => array(
                'type' => 'string',
                'default' => 'Lorem - H4 - The quick brown'
            ),
            'titleColor' => array(
                'type' => 'string',
                'default' => '#e0e648'
            ),
            'textColor' => array(
                'type' => 'string',
                'default' => '#0a3c33'
            ),
            'backgroundColor' => array(
                'type' => 'string',
                'default' => '#f8f8f8'
            ),
            'columns' => array(
                'type' => 'number',
                'default' => 2
            ),
            'columnContent' => array(
                'type' => 'array',
                'default' => array(
                    'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.',
                    'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.',
                    ''
                ),
                'items' => array(
                    'type' => 'string'
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
add_action('init', 'mediapilote_register_block_texte_colonnes');

/**
 * Rendu du bloc Texte Colonnes
 */
function mediapilote_render_block_texte_colonnes($attributes) {
    // Récupérer les attributs
    $title = isset($attributes['title']) ? $attributes['title'] : 'Lorem - H4 - The quick brown';
    $title_color = isset($attributes['titleColor']) ? $attributes['titleColor'] : '#e0e648';
    $text_color = isset($attributes['textColor']) ? $attributes['textColor'] : '#0a3c33';
    $background_color = isset($attributes['backgroundColor']) ? $attributes['backgroundColor'] : '#f8f8f8';
    $columns = isset($attributes['columns']) ? intval($attributes['columns']) : 2;
    $column_content = isset($attributes['columnContent']) ? $attributes['columnContent'] : array(
        'Lorem ipsum dolor sit amet, consectetur adipiscing elit...',
        'Lorem ipsum dolor sit amet, consectetur adipiscing elit...'
    );

    // Assurer que nous avons le bon nombre de colonnes de contenu
    while (count($column_content) < $columns) {
        $column_content[] = '';
    }

    // Calculer la classe CSS pour le nombre de colonnes
    $columns_class = 'mp-texte-columns-' . $columns;

    // Construire le HTML
    $html = '<div class="mp-texte-colonnes ' . $columns_class . ' alignfull" style="background-color: ' . esc_attr($background_color) . ';">';
    
    $html .= '<div class="mp-texte-colonnes-container">';
    
    // Titre
    if (!empty($title)) {
        $html .= '<h3 class="mp-texte-colonnes-title" style="color: ' . esc_attr($title_color) . ';">' . esc_html($title) . '</h3>';
    }
    
    // Conteneur des colonnes
    $html .= '<div class="mp-texte-colonnes-content">';
    
    for ($i = 0; $i < $columns; $i++) {
        $content = isset($column_content[$i]) ? $column_content[$i] : '';
        $html .= '<div class="mp-texte-colonne" style="color: ' . esc_attr($text_color) . ';">';
        $html .= '<p>' . wp_kses_post(nl2br($content)) . '</p>';
        $html .= '</div>';
    }
    
    $html .= '</div>'; // .mp-texte-colonnes-content
    $html .= '</div>'; // .mp-texte-colonnes-container
    $html .= '</div>'; // .mp-texte-colonnes

    return $html;
}