<?php

/**
 * @snippet  Déclarer les champs globaux
 * @author   Emmanuel Claude
 */

function mediapilote_init() {
    global $logo;
    
    // Récupérer le logo depuis les options ACF
    $logo = get_field( 'option_logo', 'option' );
    
    // Si le logo n'existe pas, utiliser le logo par défaut de WordPress
    if (!$logo) {
        $custom_logo_id = get_theme_mod('custom_logo');
        if ($custom_logo_id) {
            $logo = array(
                'url' => wp_get_attachment_image_url($custom_logo_id, 'full'),
                'alt' => get_post_meta($custom_logo_id, '_wp_attachment_image_alt', true),
                'title' => get_the_title($custom_logo_id)
            );
        }
    }
    
    // S'assurer que $logo est toujours un tableau valide
    if (!$logo || !is_array($logo)) {
        $logo = array(
            'url' => '',
            'alt' => get_bloginfo('name'),
            'title' => get_bloginfo('name')
        );
    }
}
add_action( 'after_setup_theme', 'mediapilote_init' );

/**
 * @snippet  Désactiver l'éditeur de fichiers des thèmes et extensions
 * @author   Emmanuel Claude
 * @note     Empêche la modification des fichiers PHP depuis le back-office WordPress
 */
function mediapilote_disable_file_editor() {
    // Définir la constante si elle n'existe pas déjà
    if ( ! defined( 'DISALLOW_FILE_EDIT' ) ) {
        define( 'DISALLOW_FILE_EDIT', true );
    }
}
add_action( 'init', 'mediapilote_disable_file_editor', 1 );

/**
 * mediapilote functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package mediapilote
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function mediapilote_setup() {
	/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on mediapilote, use a find and replace
		* to change 'mediapilote' to the name of your theme in all the template files.
		*/
	load_theme_textdomain( 'mediapilote', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
		* Let WordPress manage the document title.
		* By adding theme support, we declare that this theme does not use a
		* hard-coded <title> tag in the document head, and expect WordPress to
		* provide it for us.
		*/
	add_theme_support( 'title-tag' );

	/*
		* Enable support for Post Thumbnails on posts and pages.
		*
		* @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		*/
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
			'menu-1' => esc_html__( 'Primary', 'mediapilote' ),
			'footer-menu' => esc_html__( 'Footer Menu', 'mediapilote' ),
			'legal-menu' => esc_html__( 'Legal Menu', 'mediapilote' ),
		)
	);

	/*
		* Switch default core markup for search form, comment form, and comments
		* to output valid HTML5.
		*/
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

// Support custom-background désactivé pour éviter les conflits avec la couleur du menu

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);
}
add_action( 'after_setup_theme', 'mediapilote_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function mediapilote_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'mediapilote_content_width', 640 );
}
add_action( 'after_setup_theme', 'mediapilote_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function mediapilote_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'mediapilote' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'mediapilote' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'mediapilote_widgets_init' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

/**
 * Charger les blocs personnalisés
 */
require get_template_directory() . '/blocks/autoload_blocks.php';


/**
 * Fonctions personnnalisées
 */
include 'inc/mediapilote.php';

/**
 * Ajoute une classe dynamique au body selon la police des titres choisie
 */
function mediapilote_add_font_class_to_body($classes) {
    $font_titles = get_theme_mod('mediapilote_fonts_titles', 'Roboto');
    $font_titles_class = 'h-' . strtolower(str_replace(' ', '', $font_titles));
    $classes[] = $font_titles_class;
    
    // Ajout de la classe pour la police du corps de texte
    $font_body = get_theme_mod('mediapilote_fonts_body', 'Roboto');
    $font_body_class = 'body-' . strtolower(str_replace(' ', '', $font_body));
    $classes[] = $font_body_class;
    
    // Ajouter classe pour menu burger si activé
    $menu_burger = get_theme_mod('mediapilote_menu_burger', false);
    if ($menu_burger) {
        $classes[] = 'menu-burger-active';
    }
    
    // Ajouter classe pour bannière si activée
    $banner_enable = get_theme_mod('mediapilote_banner_enable', false);
    if ($banner_enable) {
        $classes[] = 'banner-active';
    }
    
    return $classes;
}
add_filter('body_class', 'mediapilote_add_font_class_to_body');

/**
 * Fonction globale de détection de langue
 * 
 * @return bool True si la langue est anglaise, false sinon
 */
function mediapilote_is_english() {
    $current_language = get_locale();
    return strpos($current_language, 'en') === 0;
}

/**
 * Fonction utilitaire pour afficher du texte selon la langue
 * 
 * @param string $french_text Texte en français
 * @param string $english_text Texte en anglais
 * @return string Le texte approprié selon la langue
 */
function mediapilote_get_text($french_text, $english_text) {
    return mediapilote_is_english() ? $english_text : $french_text;
}

add_filter('wp_get_attachment_url', function($url, $post_id) {
    if (strpos($url, '//localhost') === 0) {
        $url = 'http:' . $url; // ou 'https:' selon ton besoin
    }
    return $url;
}, 10, 2);

/**
 * Générer le CSS dynamique pour appliquer les couleurs du customizer
 */
function mediapilote_customizer_css() {
    $menu_bg_color = get_theme_mod('mediapilote_menu_bg_color', '#ffffff');
    ?>
    <style type="text/css">
        .site-header.scrolled {
            background-color: <?php echo esc_attr($menu_bg_color); ?> !important;
        }
    </style>
    <?php
}
add_action('wp_head', 'mediapilote_customizer_css');

/**
 * Enqueue Bootstrap grid CSS
 */
function mediapilote_enqueue_bootstrap_grid() {
    wp_enqueue_style(
        'bootstrap-grid',
        'https://cdn.jsdelivr.net/npm/bootstrap-v4-grid-only@1.0.0/dist/bootstrap-grid.min.css',
        array(),
        '1.0.0',
        'all'
    );
}
add_action('wp_enqueue_scripts', 'mediapilote_enqueue_bootstrap_grid');

/**
 * Enqueue Bootstrap grid CSS in admin for editor
 */
function mediapilote_enqueue_bootstrap_grid_admin() {
    wp_enqueue_style(
        'bootstrap-grid-admin',
        'https://cdn.jsdelivr.net/npm/bootstrap-v4-grid-only@1.0.0/dist/bootstrap-grid.min.css',
        array(),
        '1.0.0',
        'all'
    );
}
add_action('admin_enqueue_scripts', 'mediapilote_enqueue_bootstrap_grid_admin');

/**
 * Ajoute une classe dynamique au body de l'éditeur Gutenberg selon la police des titres choisie
 */
function mediapilote_add_font_class_to_editor_body() {
    $font_titles = get_theme_mod('mediapilote_fonts_titles', 'Roboto');
    $font_titles_class = 'h-' . strtolower(str_replace(' ', '', $font_titles));
    $font_body = get_theme_mod('mediapilote_fonts_body', 'Roboto');
    $font_body_class = 'body-' . strtolower(str_replace(' ', '', $font_body));
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var editorBody = document.querySelector('.block-editor-page, .edit-post-visual-editor');
        if (editorBody) {
            editorBody.classList.add('<?php echo esc_attr($font_titles_class); ?>');
            editorBody.classList.add('<?php echo esc_attr($font_body_class); ?>');
        }
    });
    </script>
    <?php
}
add_action('admin_footer', 'mediapilote_add_font_class_to_editor_body');

/**
 * Importer automatiquement les champs ACF pour les réseaux sociaux
 */
function mediapilote_import_acf_social_fields() {
    // Vérifier si ACF est activé et si les champs n'existent pas déjà
    if (function_exists('acf_add_local_field_group') && !function_exists('get_field_group')) {
        return; // ACF n'est pas disponible
    }

    // Vérifier si le groupe de champs existe déjà
    if (function_exists('acf_get_field_group') && acf_get_field_group('group_654e38251e76b')) {
        return; // Les champs existent déjà
    }

    // Ajouter le groupe de champs localement
    if (function_exists('acf_add_local_field_group')) {
        acf_add_local_field_group(array(
            'key' => 'group_654e38251e76b',
            'title' => 'Réseaux sociaux',
            'fields' => array(
                array(
                    'key' => 'field_654e382596b9a',
                    'label' => 'Réseaux sociaux',
                    'name' => 'social',
                    'aria-label' => '',
                    'type' => 'repeater',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'layout' => 'table',
                    'pagination' => 0,
                    'min' => 0,
                    'max' => 0,
                    'collapsed' => '',
                    'button_label' => 'Ajouter un élément',
                    'rows_per_page' => 20,
                    'sub_fields' => array(
                        array(
                            'key' => 'field_654e384c96b9b',
                            'label' => 'Nom',
                            'name' => 'social_name',
                            'aria-label' => '',
                            'type' => 'text',
                            'instructions' => '',
                            'required' => 1,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => '',
                            'maxlength' => '',
                            'placeholder' => 'Exemple : Facebook',
                            'prepend' => '',
                            'append' => '',
                        ),
                        array(
                            'key' => 'field_654e386a96b9c',
                            'label' => 'Icône',
                            'name' => 'social_img',
                            'aria-label' => '',
                            'type' => 'image',
                            'instructions' => '',
                            'required' => 1,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'return_format' => 'array',
                            'library' => 'all',
                            'min_width' => '',
                            'min_height' => '',
                            'min_size' => '',
                            'max_width' => 100,
                            'max_height' => 100,
                            'max_size' => '',
                            'mime_types' => '.svg, .png, .jpg, .jpeg',
                            'preview_size' => 'medium',
                        ),
                        array(
                            'key' => 'field_654e38cc96b9d',
                            'label' => 'Lien',
                            'name' => 'social_url',
                            'aria-label' => '',
                            'type' => 'link',
                            'instructions' => '',
                            'required' => 1,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'return_format' => 'array',
                        ),
                    ),
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'options_page',
                        'operator' => '==',
                        'value' => 'acf-options-reseaux-sociaux',
                    ),
                ),
            ),
            'menu_order' => 0,
            'position' => 'normal',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen' => '',
            'active' => true,
            'description' => '',
        ));
    }
}
add_action('acf/init', 'mediapilote_import_acf_social_fields');

/**
 * Personnalisation de l'affichage des mises à jour
 */
require get_template_directory() . '/theme-display-customizer.php';



/**
 * Affichage propre du nom du thème (solution sûre)
 */
if (is_admin()) {
    require_once get_template_directory() . '/safe-theme-display.php';
    require_once get_template_directory() . '/fix-version-display.php';
    
    // Contournement du backup pour environnement local
    if (defined('WP_DEBUG') && WP_DEBUG) {
        require_once get_template_directory() . '/bypass-backup.php';
    }
}
