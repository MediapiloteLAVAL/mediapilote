<?php
/**
 * Bloc Bannière
 * Bloc Gutenberg natif FSE pour afficher une bannière avec titre de page, description optionnelle et bouton CTA
 *
 * @package MediaPilote
 * @since 1.0.0
 */

// Empêcher l'accès direct
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Enregistrer le bloc Bannière
 */
function mediapilote_register_block_banniere() {
    // Vérifier si la fonction existe
    if (!function_exists('register_block_type')) {
        return;
    }

    // Enregistrer le script du bloc
    wp_register_script(
        'mediapilote-block-banniere-editor',
        get_template_directory_uri() . '/blocks/blockBanniere/block.js',
        array('wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-i18n'),
        filemtime(get_template_directory() . '/blocks/blockBanniere/block.js')
    );

    // Enregistrer le style du bloc (frontend et editor)
    wp_register_style(
        'mediapilote-block-banniere-style',
        get_template_directory_uri() . '/blocks/blockBanniere/style.css',
        array('utilitary'),
        filemtime(get_template_directory() . '/blocks/blockBanniere/style.css')
    );

    // Enregistrer le style de l'éditeur
    wp_register_style(
        'mediapilote-block-banniere-editor-style',
        get_template_directory_uri() . '/blocks/blockBanniere/editor.css',
        array('wp-edit-blocks'),
        filemtime(get_template_directory() . '/blocks/blockBanniere/editor.css')
    );

    // Enregistrer le bloc
    register_block_type('mediapilote/banniere', array(
        'editor_script' => 'mediapilote-block-banniere-editor',
        'style' => 'mediapilote-block-banniere-style',
        'editor_style' => 'mediapilote-block-banniere-editor-style',
        'render_callback' => 'mediapilote_render_block_banniere',
        'attributes' => array(
            'backgroundType' => array(
                'type' => 'string',
                'default' => 'color'
            ),
            'backgroundColor' => array(
                'type' => 'string',
                'default' => '#0a3c33'
            ),
            'backgroundImage' => array(
                'type' => 'object',
                'default' => null
            ),
            'description' => array(
                'type' => 'string',
                'default' => ''
            ),
            'buttonText' => array(
                'type' => 'string',
                'default' => 'Nous contacter'
            ),
            'buttonUrl' => array(
                'type' => 'string',
                'default' => '#'
            ),
            'textColor' => array(
                'type' => 'string',
                'default' => '#ffffff'
            ),
            'align' => array(
                'type' => 'string',
                'default' => 'full'
            ),
            'lock' => array(
                'type' => 'object',
                'default' => array()
            )
        ),
        'supports' => array(
            'align' => array('full'),
            'html' => false,
            'lock' => true
        )
    ));
}
add_action('init', 'mediapilote_register_block_banniere');

/**
 * Rendu du bloc côté frontend
 */
function mediapilote_render_block_banniere($attributes, $content, $block) {
    $background_type = isset($attributes['backgroundType']) ? $attributes['backgroundType'] : 'color';
    $background_color = isset($attributes['backgroundColor']) ? $attributes['backgroundColor'] : '#0a3c33';
    $background_image = isset($attributes['backgroundImage']) ? $attributes['backgroundImage'] : null;
    $description = isset($attributes['description']) ? $attributes['description'] : '';
    $button_text = isset($attributes['buttonText']) ? $attributes['buttonText'] : 'Nous contacter';
    $button_url = isset($attributes['buttonUrl']) ? $attributes['buttonUrl'] : '#';
    $text_color = isset($attributes['textColor']) ? $attributes['textColor'] : '#ffffff';

    // Récupérer le titre de la page
    $page_title = get_the_title();
    
    // Si on est sur la page d'accueil, utiliser le nom du site
    if (is_front_page()) {
        $page_title = get_bloginfo('name');
    }

    // Vérifier si on est dans un post "news"
    $is_news_post = (get_post_type() === 'news');
    $news_meta = '';
    
    if ($is_news_post) {
        // Récupérer la date de publication au format "21 Novembre 2025"
        $post_date = get_the_date('j F Y');
        
        // Récupérer les catégories
        $categories = get_the_category();
        $category_names = array();
        
        if (!empty($categories)) {
            foreach ($categories as $category) {
                $category_names[] = $category->name;
            }
        }
        
        // Construire les métadonnées
        $news_meta .= '<div class="banniere-news-meta">';
        $news_meta .= '<span class="news-date">' . esc_html($post_date) . '</span>';
        
        if (!empty($category_names)) {
            $news_meta .= ' <span class="news-separator">•</span> ';
            $news_meta .= '<span class="news-category">' . esc_html(implode(', ', $category_names)) . '</span>';
        }
        
        $news_meta .= '</div>';
    }

    $wrapper_attributes = get_block_wrapper_attributes();

    // Préparer le style de fond
    $background_style = '';
    if ($background_type === 'image' && $background_image && isset($background_image['url'])) {
        $background_style = "background-image: url('" . esc_url($background_image['url']) . "'); background-size: cover; background-position: center; background-repeat: no-repeat;";
    } else {
        $background_style = "background-color: " . esc_attr($background_color) . ";";
    }

    ob_start();
    ?>
    <div class="wp-block-mediapilote-banniere banniere-section alignfull" style="<?php echo $background_style; ?>" <?php echo $wrapper_attributes; ?>>
        <?php if ($background_type === 'image' && $background_image) : ?>
            <div class="banniere-section__overlay"></div>
        <?php endif; ?>
        <div class="container-fluid">
            <div class="banniere-section__content" style="color: <?php echo esc_attr($text_color); ?>;">
                <h1 class="banniere-section__title"><?php echo wp_kses_post($page_title); ?></h1>
                <?php if ($is_news_post && !empty($news_meta)) : ?>
                    <?php echo $news_meta; ?>
                <?php endif; ?>
                <?php if (!empty($description)) : ?>
                    <div class="banniere-section__description">
                        <p><?php echo wp_kses_post($description); ?></p>
                    </div>
                <?php endif; ?>
                <?php if (!empty($button_text)) : ?>
                    <a href="<?php echo esc_url($button_url); ?>" class="btn" style="border-color: <?php echo esc_attr($text_color); ?>; color: <?php echo esc_attr($text_color); ?>;">
                        <span class="btn-text"><?php echo esc_html($button_text); ?></span>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}