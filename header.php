<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package mediapilote
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">

    <?php
    // Google Fonts dynamiques
    $google_fonts = include get_template_directory() . '/inc/google-fonts-list.php';
    $font_titles = get_theme_mod('mediapilote_fonts_titles', 'Roboto');
    $font_body = get_theme_mod('mediapilote_fonts_body', 'Roboto');
    $fonts_to_load = [$font_titles, $font_body];
    $fonts_loaded = [];
    foreach ($fonts_to_load as $font) {
        if (!in_array($font, $fonts_loaded) && isset($google_fonts[$font])) {
            $variants = implode(',', $google_fonts[$font]);
            echo '<link href="https://fonts.googleapis.com/css?family=' . urlencode($font) . ':' . $variants . '&display=swap" rel="stylesheet">';
            $fonts_loaded[] = $font;
        }
    }
    ?>
    
    <style>
        /* Appliquer la couleur de fond du menu au menu burger */
        .menu-burger-active .header-right-menu.burger-menu-open {
            background-color: <?php echo esc_attr(get_theme_mod('mediapilote_menu_bg_color', '#ffffff')); ?> !important;
        }
        
        /* Appliquer la couleur des textes de menu */
        .header-right-menu a,
        .header-right .header-right-menu a,
        .header-right .header-right-menu .sub-menu a,
        .menu-burger-active .header-right-menu.burger-menu-open a {
            color: <?php echo esc_attr(get_theme_mod('mediapilote_menu_text_color', '#000000')); ?> !important;
        }
    </style>
    
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>
    <div id="page" class="site">
        <a class="skip-link screen-reader-text"
            href="#primary"><?php esc_html_e( 'Skip to content', 'mediapilote' ); ?></a>
        <div id="masthead">

            <?php
            // Affichage de la bannière si activée
            $banner_enable = get_theme_mod('mediapilote_banner_enable', false);
            if ($banner_enable) {
                $banner_text = get_theme_mod('mediapilote_banner_text', __('Message important', 'mediapilote'));
                $banner_bg_color = get_theme_mod('mediapilote_banner_bg_color', '#f0f0f0');
                $banner_text_color = get_theme_mod('mediapilote_banner_text_color', '#333333');
                ?>
                <div class="site-banner" style="background-color: <?php echo esc_attr($banner_bg_color); ?>; color: <?php echo esc_attr($banner_text_color); ?>;">
                    <div class="container">
                        <div class="banner-content">
                            <span class="banner-text"><?php echo esc_html($banner_text); ?></span>
                            <button class="banner-close" aria-label="<?php esc_attr_e('Fermer la bannière', 'mediapilote'); ?>">
                                <span aria-hidden="true">×</span> <span><?php esc_html_e('Fermer', 'mediapilote'); ?></span>
                            </button>
                        </div>
                    </div>
                </div>
                <?php
            }
            ?>

            <?php
            // Récupérer la couleur de fond du menu depuis le customizer
            $menu_bg_color = get_theme_mod('mediapilote_menu_bg_color', '#ffffff');
            $menu_sticky = get_theme_mod('mediapilote_menu_sticky', false);
            $menu_overlay = get_theme_mod('mediapilote_menu_overlay', false);
            $menu_transparent = get_theme_mod('mediapilote_menu_transparent', false);
            $header_classes = 'site-header';
            if ($menu_sticky) {
                $header_classes .= ' menu-sticky';
            }
            if ($menu_overlay) {
                $header_classes .= ' menu-overlay';
            }
            if ($menu_transparent) {
                $header_classes .= ' menu-transparent';
            }
            ?>
            <header class="<?php echo esc_attr($header_classes); ?>" style="background-color: <?php echo esc_attr($menu_bg_color); ?>;">
                <div class="container align80">
                    <div class="header-content">
                        <div class="header-left">
                            <a class="header-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
                                <?php
                                $custom_logo_id = get_theme_mod('custom_logo');
                                if ($custom_logo_id) {
                                    $logo_url = wp_get_attachment_image_url($custom_logo_id, 'full');
                                    echo '<img src="' . esc_url($logo_url) . '" alt="' . get_bloginfo('name') . '">';
                                } else {
                                    echo '<h1>' . get_bloginfo('name') . '</h1>';
                                }
                                ?>
                            </a>
                        </div>
                        
                        <div class="header-right">
                            <!-- Icône burger -->
                            <div class="menu-burger-icon">
                                <span></span>
                                <span></span>
                                <span></span>
                            </div>
                            
                            <?php 
                            if (has_nav_menu('top-right-menu')) {
                                wp_nav_menu(array(
                                    'theme_location' => 'top-right-menu',
                                    'menu_class'     => 'header-right-menu',
                                    'container'      => 'nav',
                                    'container_class'=> 'header-right-menu-container',
                                ));
                            }
                            ?>
                        </div>
                        
                        <!-- Overlay pour fermer le menu -->
                        <div class="burger-menu-overlay"></div>
                        
                    </div>
                </div>
            </header><!-- #masthead -->
        </div>


<?php 

$image_nos_produits = get_field('menu_decoration_img', 'option');

?>

<style>
    .menu-nos-produits .submenu-image{
        aspect-ratio: 1/1;
        background-image: url(<?php echo $image_nos_produits['url'] ?>);
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        border-radius: 25px;
    }
</style>