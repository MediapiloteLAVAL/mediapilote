<?php

/**
 * Charger la feuille de style customizer sur le site public
 */
function mediapilote_enqueue_customizer_front_styles() {
	wp_enqueue_style( 'mediapilote-customizer-styles', get_template_directory_uri() . '/css/customizer.css' );
}
add_action( 'wp_enqueue_scripts', 'mediapilote_enqueue_customizer_front_styles' );

/**
 * Charger la feuille de style customizer dans le panneau du customizer (admin)
 */
function mediapilote_enqueue_customizer_admin_styles() {
	wp_enqueue_style( 'mediapilote-customizer-admin-styles', get_template_directory_uri() . '/css/customizer.css' );
}
add_action( 'customize_controls_enqueue_scripts', 'mediapilote_enqueue_customizer_admin_styles' );

/**
 * Charger les scripts personnalisés pour le customizer
 */
function mediapilote_enqueue_customizer_ui_script() {
	wp_enqueue_script(
		'mediapilote-customizer-ui-admin',
		get_template_directory_uri() . '/js/customizer-ui-admin.js',
		array('jquery'),
		null,
		true
	);
}
add_action('customize_controls_enqueue_scripts', 'mediapilote_enqueue_customizer_ui_script');

/**
 * Masquer la section "Couleurs" par défaut de WordPress
 */
function mediapilote_remove_default_color_section($wp_customize) {
	$wp_customize->remove_section('colors');
}
add_action('customize_register', 'mediapilote_remove_default_color_section');

	
/**
 * Panel et sections pour la police de caractère
 */
function mediapilote_customize_register_fonts_panel($wp_customize) {


	// Panel parent
	$wp_customize->add_panel('mediapilote_fonts_panel', array(
		'title'    => __('Police de caractère', 'mediapilote'),
		'priority' => 40,
		'description' => __('Personnalisez la police des titres et du corps de texte.', 'mediapilote'),
	));

	// Section Titres
	$wp_customize->add_section('mediapilote_fonts_section_titles', array(
		'title'    => __('Titres', 'mediapilote'),
		'priority' => 10,
		'panel'    => 'mediapilote_fonts_panel',
	));

	// Section Corps de texte
	$wp_customize->add_section('mediapilote_fonts_section_body', array(
		'title'    => __('Corps de texte', 'mediapilote'),
		'priority' => 20,
		'panel'    => 'mediapilote_fonts_panel',
	));

	// Récupérer la liste des Google Fonts
	$google_fonts = include get_template_directory() . '/inc/google-fonts-list.php';
	$fonts_choices = array();
	foreach ($google_fonts as $font_name => $variants) {
		$fonts_choices[$font_name] = $font_name;
	}

	// Contrôle pour la police des titres
	$wp_customize->add_setting('mediapilote_fonts_titles', array(
		'default'   => 'Roboto',
		'transport' => 'refresh',
		'sanitize_callback' => 'sanitize_text_field',
	));
	$wp_customize->add_control('mediapilote_fonts_titles', array(
		'type'     => 'select',
		'label'    => __('Police des titres', 'mediapilote'),
		'section'  => 'mediapilote_fonts_section_titles',
		'choices'  => $fonts_choices,
	));

	// Contrôle pour la police du corps de texte
	$wp_customize->add_setting('mediapilote_fonts_body', array(
		'default'   => 'Roboto',
		'transport' => 'refresh',
		'sanitize_callback' => 'sanitize_text_field',
	));
	$wp_customize->add_control('mediapilote_fonts_body', array(
		'type'     => 'select',
		'label'    => __('Police du corps de texte', 'mediapilote'),
		'section'  => 'mediapilote_fonts_section_body',
		'choices'  => $fonts_choices,
	));
}
add_action('customize_register', 'mediapilote_customize_register_fonts_panel');




/**
 * Ajoute une section "Menu" personnalisée au customizer WordPress
 */
function mediapilote_customize_register_menu_section($wp_customize) {
	// Ajout du panel Menu
	$wp_customize->add_panel('mediapilote_menu_panel', array(
		'title'    => __('Haut de page', 'mediapilote'),
		'priority' => 30,
		'description' => __('Personnalisez les options du menu principal.', 'mediapilote'),
	));

	// Section Bannière
	$wp_customize->add_section('mediapilote_menu_section_banner', array(
		'title'    => __('Bannière', 'mediapilote'),
		'priority' => 3,
		'panel'    => 'mediapilote_menu_panel',
		'description' => __('Affichez un message en haut de toutes les pages.', 'mediapilote'),
	));
	// Activation de la bannière
	$wp_customize->add_setting('mediapilote_banner_enable', array(
		'default'   => false,
		'transport' => 'refresh',
		'sanitize_callback' => 'wp_validate_boolean',
	));
	$wp_customize->add_control('mediapilote_banner_enable', array(
		'type'     => 'checkbox',
		'label'    => __('Activer la bannière', 'mediapilote'),
		'section'  => 'mediapilote_menu_section_banner',
		'settings' => 'mediapilote_banner_enable',
	));
	// Texte de la bannière
	$wp_customize->add_setting('mediapilote_banner_text', array(
		'default'   => __('Message important', 'mediapilote'),
		'transport' => 'refresh',
		'sanitize_callback' => 'sanitize_text_field',
	));
	$wp_customize->add_control('mediapilote_banner_text', array(
		'type'     => 'text',
		'label'    => __('Texte de la bannière', 'mediapilote'),
		'section'  => 'mediapilote_menu_section_banner',
		'settings' => 'mediapilote_banner_text',
	));
	// Couleur de fond de la bannière
	$wp_customize->add_setting('mediapilote_banner_bg_color', array(
		'default'   => '#f0f0f0',
		'transport' => 'refresh',
		'sanitize_callback' => 'sanitize_hex_color',
	));
	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'mediapilote_banner_bg_color', array(
		'label'    => __('Couleur de fond', 'mediapilote'),
		'section'  => 'mediapilote_menu_section_banner',
		'settings' => 'mediapilote_banner_bg_color',
	)));
	// Couleur de texte de la bannière
	$wp_customize->add_setting('mediapilote_banner_text_color', array(
		'default'   => '#333333',
		'transport' => 'refresh',
		'sanitize_callback' => 'sanitize_hex_color',
	));
	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'mediapilote_banner_text_color', array(
		'label'    => __('Couleur du texte', 'mediapilote'),
		'section'  => 'mediapilote_menu_section_banner',
		'settings' => 'mediapilote_banner_text_color',
	)));

	// Section Type de menu
	$wp_customize->add_section('mediapilote_menu_section_type', array(
		'title'    => __('Type de menu', 'mediapilote'),
		'priority' => 5,
		'panel'    => 'mediapilote_menu_panel',
	));
	// Switch Menu classique/burger
	$wp_customize->add_setting('mediapilote_menu_burger', array(
		'default'   => false,
		'transport' => 'refresh',
		'sanitize_callback' => 'wp_validate_boolean',
	));
	$wp_customize->add_control('mediapilote_menu_burger', array(
		'type'     => 'checkbox',
		'label'    => __('Activer le menu burger', 'mediapilote'),
		'description' => __('Si activé, le menu s\'affichera sous forme de menu burger (hamburger icon). Sinon, le menu classique horizontal sera affiché.', 'mediapilote'),
		'section'  => 'mediapilote_menu_section_type',
		'settings' => 'mediapilote_menu_burger',
	));

	// Section Couleur
	$wp_customize->add_section('mediapilote_menu_section_color', array(
		'title'    => __('Couleur', 'mediapilote'),
		'priority' => 10,
		'panel'    => 'mediapilote_menu_panel',
	));
	$wp_customize->add_setting('mediapilote_menu_bg_color', array(
		'default'   => '#ffffff',
		'transport' => 'refresh',
		'sanitize_callback' => 'sanitize_hex_color',
	));
	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'mediapilote_menu_bg_color', array(
		'label'    => __('Couleur de fond du menu', 'mediapilote'),
		'description' => __('Si l\'option "Menu transparent" est activée dans le menu "Avancé", cette couleur ne sera pas appliquée.', 'mediapilote'),
		'section'  => 'mediapilote_menu_section_color',
		'settings' => 'mediapilote_menu_bg_color',
	)));
	// Couleur des textes de menu
	$wp_customize->add_setting('mediapilote_menu_text_color', array(
		'default'   => '#000000',
		'transport' => 'refresh',
		'sanitize_callback' => 'sanitize_hex_color',
	));
	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'mediapilote_menu_text_color', array(
		'label'    => __('Couleur des textes de menus', 'mediapilote'),
		'section'  => 'mediapilote_menu_section_color',
		'settings' => 'mediapilote_menu_text_color',
	)));

	// Section Avancé
	$wp_customize->add_section('mediapilote_menu_section_advanced', array(
		'title'    => __('Avancé', 'mediapilote'),
		'priority' => 20,
		'panel'    => 'mediapilote_menu_panel',
	));
	// Switchs avancés
	$wp_customize->add_setting('mediapilote_menu_sticky', array(
		'default'   => false,
		'transport' => 'refresh',
		'sanitize_callback' => 'wp_validate_boolean',
	));
	$wp_customize->add_control('mediapilote_menu_sticky', array(
		'type'     => 'checkbox',
		'label'    => __('Accrocher le menu en haut de page', 'mediapilote'),
		'section'  => 'mediapilote_menu_section_advanced',
		'settings' => 'mediapilote_menu_sticky',
	));
	$wp_customize->add_setting('mediapilote_menu_transparent', array(
		'default'   => false,
		'transport' => 'refresh',
		'sanitize_callback' => 'wp_validate_boolean',
	));
	$wp_customize->add_control('mediapilote_menu_transparent', array(
		'type'     => 'checkbox',
		'label'    => __('Rendre le menu transparent', 'mediapilote'),
		'section'  => 'mediapilote_menu_section_advanced',
		'settings' => 'mediapilote_menu_transparent',
	));
	$wp_customize->add_setting('mediapilote_menu_overlay', array(
		'default'   => false,
		'transport' => 'refresh',
		'sanitize_callback' => 'wp_validate_boolean',
	));
	$wp_customize->add_control('mediapilote_menu_overlay', array(
		'type'     => 'checkbox',
		'label'    => __('Superposer le menu au contenu', 'mediapilote'),
		'description' => __('Dans vos pages, veillez à utiliser un bloc approprié, tel que le Header afin que le menu ne soit pas superposé à du contenu textuel.', 'mediapilote'),
		'section'  => 'mediapilote_menu_section_advanced',
		'settings' => 'mediapilote_menu_overlay',
	));
}
add_action('customize_register', 'mediapilote_customize_register_menu_section');

/**
 * Panel général pour les raccourcis
 */
function mediapilote_customize_register_shortcuts_panel($wp_customize) {
	$wp_customize->add_panel('mediapilote_shortcuts_panel', array(
		'title'    => __('Raccourcis', 'mediapilote'),
		'priority' => 50,
		'description' => __('Configurez vos raccourcis globaux pour le site.', 'mediapilote'),
	));

	// Créer 3 sections pour 3 raccourcis
	for ($i = 1; $i <= 3; $i++) {
		// Section pour chaque raccourci
		$wp_customize->add_section("mediapilote_shortcut_section_{$i}", array(
			'title'    => sprintf(__('Raccourci %d', 'mediapilote'), $i),
			'priority' => $i * 10,
			'panel'    => 'mediapilote_shortcuts_panel',
		));

		// Image du raccourci
		$wp_customize->add_setting("mediapilote_shortcut_{$i}_image", array(
			'default'   => '',
			'transport' => 'refresh',
			'sanitize_callback' => 'absint',
		));
		$wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, "mediapilote_shortcut_{$i}_image", array(
			'label'    => __('Image du raccourci', 'mediapilote'),
			'description' => __('Format recommandé : 100x100 pixels', 'mediapilote'),
			'section'  => "mediapilote_shortcut_section_{$i}",
			'mime_type' => 'image',
		)));

		// Lien du raccourci
		$wp_customize->add_setting("mediapilote_shortcut_{$i}_link", array(
			'default'   => '',
			'transport' => 'refresh',
			'sanitize_callback' => 'esc_url_raw',
		));
		$wp_customize->add_control("mediapilote_shortcut_{$i}_link", array(
			'type'     => 'url',
			'label'    => __('Lien du raccourci', 'mediapilote'),
			'section'  => "mediapilote_shortcut_section_{$i}",
		));

		// Titre du raccourci (optionnel)
		$wp_customize->add_setting("mediapilote_shortcut_{$i}_title", array(
			'default'   => '',
			'transport' => 'refresh',
			'sanitize_callback' => 'sanitize_text_field',
		));
		$wp_customize->add_control("mediapilote_shortcut_{$i}_title", array(
			'type'     => 'text',
			'label'    => __('Titre du raccourci (optionnel)', 'mediapilote'),
			'section'  => "mediapilote_shortcut_section_{$i}",
		));
	}
}
add_action('customize_register', 'mediapilote_customize_register_shortcuts_panel');

/**
 * Ajoute une section "Pied de page" personnalisée au customizer WordPress
 */
function mediapilote_customize_register_footer_section($wp_customize) {
	// Ajout du panel Pied de page
	$wp_customize->add_panel('mediapilote_footer_panel', array(
		'title'    => __('Pied de page', 'mediapilote'),
		'priority' => 35,
		'description' => __('Personnalisez les options du pied de page.', 'mediapilote'),
	));

	// Section Couleur
	$wp_customize->add_section('mediapilote_footer_section_color', array(
		'title'    => __('Couleur', 'mediapilote'),
		'priority' => 10,
		'panel'    => 'mediapilote_footer_panel',
	));
	
	// Couleur de fond du footer
	$wp_customize->add_setting('mediapilote_footer_bg_color', array(
		'default'   => '#2d3037',
		'transport' => 'refresh',
		'sanitize_callback' => 'sanitize_hex_color',
	));
	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'mediapilote_footer_bg_color', array(
		'label'    => __('Couleur de fond du pied de page', 'mediapilote'),
		'section'  => 'mediapilote_footer_section_color',
		'settings' => 'mediapilote_footer_bg_color',
	)));
	
	// Couleur des textes du footer
	$wp_customize->add_setting('mediapilote_footer_text_color', array(
		'default'   => '#ffffff',
		'transport' => 'refresh',
		'sanitize_callback' => 'sanitize_hex_color',
	));
	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'mediapilote_footer_text_color', array(
		'label'    => __('Couleur des textes', 'mediapilote'),
		'section'  => 'mediapilote_footer_section_color',
		'settings' => 'mediapilote_footer_text_color',
	)));

	// Couleur du bouton "Back to top"
	$wp_customize->add_setting('mediapilote_footer_back_to_top_color', array(
		'default'   => '#e86a56',
		'transport' => 'refresh',
		'sanitize_callback' => 'sanitize_hex_color',
	));
	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'mediapilote_footer_back_to_top_color', array(
		'label'    => __('Couleur du bouton "Back to top"', 'mediapilote'),
		'section'  => 'mediapilote_footer_section_color',
		'settings' => 'mediapilote_footer_back_to_top_color',
	)));

	// Section Contenu
	$wp_customize->add_section('mediapilote_footer_section_content', array(
		'title'    => __('Contenu', 'mediapilote'),
		'priority' => 20,
		'panel'    => 'mediapilote_footer_panel',
	));

	// Texte du bouton "Back to top"
	$wp_customize->add_setting('mediapilote_footer_back_to_top_text', array(
		'default'   => 'Back to the top',
		'transport' => 'refresh',
		'sanitize_callback' => 'sanitize_text_field',
	));
	$wp_customize->add_control('mediapilote_footer_back_to_top_text', array(
		'type'     => 'text',
		'label'    => __('Texte du bouton "Back to top"', 'mediapilote'),
		'section'  => 'mediapilote_footer_section_content',
		'settings' => 'mediapilote_footer_back_to_top_text',
	));
}
add_action('customize_register', 'mediapilote_customize_register_footer_section');

/**
 * Appliquer les polices choisies dans le customizer
 */
function mediapilote_apply_custom_fonts() {
    $title_font = get_theme_mod('mediapilote_fonts_titles', 'Roboto');
    $body_font = get_theme_mod('mediapilote_fonts_body', 'Roboto');
    
    if ($title_font !== 'Roboto' || $body_font !== 'Roboto') {
        echo '<style type="text/css">';
        if ($title_font !== 'Roboto') {
            echo "h1, h2, h3, h4, h5, h6 { font-family: '{$title_font}', sans-serif; }";
        }
        if ($body_font !== 'Roboto') {
            echo "body { font-family: '{$body_font}', sans-serif; }";
        }
        echo '</style>';
    }
}
add_action('wp_head', 'mediapilote_apply_custom_fonts');

/**
 * Appliquer les couleurs du footer choisies dans le customizer
 */
function mediapilote_apply_footer_colors() {
    $footer_bg_color = get_theme_mod('mediapilote_footer_bg_color', '#2d3037');
    $footer_text_color = get_theme_mod('mediapilote_footer_text_color', '#ffffff');
    $back_to_top_color = get_theme_mod('mediapilote_footer_back_to_top_color', '#e86a56');
    
    echo '<style type="text/css">';
    echo '.site-footer { background-color: ' . esc_attr($footer_bg_color) . ' !important; }';
    echo '.site-footer, .site-footer p, .site-footer a, .site-footer span, .site-footer li { color: ' . esc_attr($footer_text_color) . ' !important; }';
    echo '.site-footer a:hover { opacity: 0.8; }';
    echo '.back-to-top-link, .back-to-top-link .back-to-top-arrow, .back-to-top-link .back-to-top-text { color: ' . esc_attr($back_to_top_color) . ' !important; }';
    echo '.footer-divider { background-color: rgba(255, 255, 255, 0.2) !important; }';
    echo '</style>';
}
add_action('wp_head', 'mediapilote_apply_footer_colors');

/**
 * Charger la feuille de style customizer dans l'éditeur Gutenberg
 */
function mediapilote_enqueue_customizer_editor_styles() {
    wp_enqueue_style('mediapilote-customizer-editor-styles', get_template_directory_uri() . '/css/customizer.css', array(), null);
}
add_action('enqueue_block_editor_assets', 'mediapilote_enqueue_customizer_editor_styles');
