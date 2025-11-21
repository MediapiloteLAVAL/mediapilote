<?php 

/**
 * @snippet  Créer un rôle modérateur
 * @author   Emmanuel Claude
 */
add_action('init', 'cloneRole');

function cloneRole()
{
    global $wp_roles;
    if ( ! isset( $wp_roles ) )
        $wp_roles = new WP_Roles();

    $adm = $wp_roles->get_role('administrator');
    //Adding a 'new_role' with all admin caps
    $wp_roles->add_role('moderator', 'Modérateur', $adm->capabilities);
}

/**
 * @snippet  Cacher les pages d'administration pour le rôle modérateur
 * @author   Emmanuel Claude
 */
add_action('admin_menu', 'hide_admin_pages_for_moderator', 999);

function hide_admin_pages_for_moderator() {
    // Vérifier si l'utilisateur actuel a le rôle "moderator"
    $user = wp_get_current_user();
    if (!in_array('moderator', (array) $user->roles)) {
        return;
    }

    // Supprimer les pages du menu pour les modérateurs
    remove_menu_page('plugins.php');                    // Plugins
    remove_menu_page('tools.php');                      // Outils
    remove_menu_page('options-general.php');            // Réglages
    
    // Supprimer les menus de plugins spécifiques
    remove_menu_page('duplicator');                     // Duplicator
    remove_menu_page('smush');                          // Smush
    remove_menu_page('wpseo_workouts');                 // Yoast SEO (slug correct)
    remove_menu_page('wp-mail-smtp');                   // WP Mail SMTP
    remove_menu_page('filebird-settings');              // FileBird
    remove_menu_page('hcaptcha');                       // hCaptcha
}

/**
 * @snippet  Bloquer l'accès direct aux pages d'administration pour le rôle modérateur
 * @author   Emmanuel Claude
 */
add_action('admin_init', 'block_admin_pages_for_moderator');

function block_admin_pages_for_moderator() {
    // Vérifier si l'utilisateur actuel a le rôle "moderator"
    $user = wp_get_current_user();
    if (!in_array('moderator', (array) $user->roles)) {
        return;
    }

    // Liste des pages à bloquer (ID de page)
    $blocked_pages = array(
        'duplicator',
        'smush',
        'wpseo_dashboard',
        'wpseo_titles',
        'wpseo_social',
        'wpseo_tools',
        'wp-mail-smtp',
        'wp-mail-smtp-logs',
        'wp-mail-smtp-reports',
        'filebird-settings',
        'hcaptcha',
        'hcaptcha-integrations'
    );

    // Vérifier si on est sur une page bloquée
    $current_page = isset($_GET['page']) ? sanitize_text_field($_GET['page']) : '';
    
    if (in_array($current_page, $blocked_pages)) {
        wp_die('Vous n\'avez pas les permissions nécessaires pour accéder à cette page.');
    }

    // Bloquer l'accès aux pages principales
    global $pagenow;
    $blocked_pagenow = array('plugins.php', 'tools.php', 'options-general.php');
    
    if (in_array($pagenow, $blocked_pagenow)) {
        wp_die('Vous n\'avez pas les permissions nécessaires pour accéder à cette page.');
    }
}

/**
 * @snippet  Supprimer tous les widgets par défaut du tableau de bord
 * @author   Emmanuel Claude
 */
add_action('wp_dashboard_setup', 'remove_default_dashboard_widgets', 999);

function remove_default_dashboard_widgets() {
    global $wp_meta_boxes;
    
    // Widgets WordPress par défaut
    remove_meta_box('dashboard_right_now', 'dashboard', 'normal');              // En un coup d'œil
    remove_meta_box('dashboard_activity', 'dashboard', 'normal');               // Activité
    remove_meta_box('dashboard_quick_press', 'dashboard', 'side');              // Brouillon rapide
    remove_meta_box('dashboard_primary', 'dashboard', 'side');                  // Événements et nouveautés WordPress
    remove_meta_box('dashboard_site_health', 'dashboard', 'normal');            // État de santé du site
    
    // Widgets de plugins populaires
    remove_meta_box('wpseo-dashboard-overview', 'dashboard', 'normal');         // Yoast SEO
    remove_meta_box('wpseo-wincher-dashboard-overview', 'dashboard', 'normal'); // Yoast SEO / Wincher : Expressions clés principales
    remove_meta_box('rg_forms_dashboard', 'dashboard', 'normal');               // Gravity Forms
    remove_meta_box('wp_mail_smtp_reports_widget_lite', 'dashboard', 'normal'); // WP Mail SMTP
    remove_meta_box('dashboard_incoming_links', 'dashboard', 'normal');         // Liens entrants
    remove_meta_box('dashboard_plugins', 'dashboard', 'normal');                // Plugins
    remove_meta_box('dashboard_recent_drafts', 'dashboard', 'side');            // Brouillons récents
    remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');        // Commentaires récents
    remove_meta_box('dashboard_secondary', 'dashboard', 'side');                // Autres actualités WordPress
    
    // Widget Smush (visible dans votre capture)
    remove_meta_box('smush_dashboard_widget', 'dashboard', 'normal');           // Smush
    
    // Widget Duplicator
    remove_meta_box('duplicator_dashboard_widget', 'dashboard', 'normal');      // Duplicator
    
    // Widget du thème Aiko
    remove_meta_box('mediapilote_theme_info', 'dashboard', 'normal');           // Thème Aiko - Informations
}

/**
 * @snippet  Masquer toutes les notices admin sauf celles du thème actif
 * @author   Emmanuel Claude
 */
add_action('admin_notices', 'hide_all_admin_notices_except_theme', 0);
add_action('network_admin_notices', 'hide_all_admin_notices_except_theme', 0);
add_action('user_admin_notices', 'hide_all_admin_notices_except_theme', 0);

function hide_all_admin_notices_except_theme() {
    // Récupérer le thème actif
    $current_theme = wp_get_theme();
    $theme_slug = $current_theme->get_stylesheet();
    
    // Liste des hooks de notices à vérifier
    $notice_hooks = array(
        'admin_notices',
        'network_admin_notices',
        'user_admin_notices',
        'all_admin_notices'
    );
    
    global $wp_filter;
    
    foreach ($notice_hooks as $hook) {
        if (isset($wp_filter[$hook])) {
            foreach ($wp_filter[$hook]->callbacks as $priority => $callbacks) {
                foreach ($callbacks as $callback_name => $callback_data) {
                    // Identifier la source du callback
                    $callback = $callback_data['function'];
                    $source = '';
                    
                    if (is_array($callback) && is_object($callback[0])) {
                        // Méthode de classe
                        $reflection = new ReflectionClass($callback[0]);
                        $source = $reflection->getFileName();
                    } elseif (is_string($callback) && function_exists($callback)) {
                        // Fonction simple
                        $reflection = new ReflectionFunction($callback);
                        $source = $reflection->getFileName();
                    }
                    
                    // Vérifier si la source provient du thème actif
                    $is_from_theme = false;
                    if ($source) {
                        $theme_dir = get_stylesheet_directory();
                        $is_from_theme = (strpos($source, $theme_dir) !== false);
                    }
                    
                    // Supprimer la notice si elle ne provient pas du thème
                    if (!$is_from_theme && $source) {
                        remove_action($hook, $callback, $priority);
                    }
                }
            }
        }
    }
}

/**
 * @snippet  Ajouter des widgets d'actions rapides au tableau de bord
 * @author   Emmanuel Claude
 */
add_action('wp_dashboard_setup', 'add_custom_dashboard_widgets');
add_action('admin_head', 'add_dashboard_widgets_styles');
add_action('admin_enqueue_scripts', 'disable_dashboard_drag_drop');

function add_custom_dashboard_widgets() {
    // Widget Ajouter une page
    wp_add_dashboard_widget(
        'dashboard_add_page',
        '📄 Ajouter une page',
        'display_add_page_widget'
    );
    
    // Widget Voir les pages
    wp_add_dashboard_widget(
        'dashboard_view_pages',
        '📋 Voir les pages',
        'display_view_pages_widget'
    );
    
    // Widget Ajouter une actualité
    wp_add_dashboard_widget(
        'dashboard_add_news',
        '📰 Ajouter une actualité',
        'display_add_news_widget'
    );
    
    // Widget Voir les actualités
    wp_add_dashboard_widget(
        'dashboard_view_news',
        '📚 Voir les actualités',
        'display_view_news_widget'
    );
    
    // Widget Gérer l'apparence
    wp_add_dashboard_widget(
        'dashboard_customize',
        '🎨 Gérer l\'apparence',
        'display_customize_widget'
    );
    
    // Widget Médiathèque
    wp_add_dashboard_widget(
        'dashboard_media',
        '🖼️ Médiathèque',
        'display_media_widget'
    );
    
    // Widget Mettre à jour le thème
    wp_add_dashboard_widget(
        'dashboard_theme_update',
        '🔄 Mettre à jour le thème',
        'display_theme_update_widget'
    );
    
    // Widget Contacter le support
    wp_add_dashboard_widget(
        'dashboard_support',
        '💬 Contacter le support',
        'display_support_widget'
    );
}

// Widget Ajouter une page
function display_add_page_widget() {
    ?>
    <div class="mediapilote-dashboard-widget">
        <a href="<?php echo admin_url('post-new.php?post_type=page'); ?>" class="dashboard-widget-action">
            <div class="widget-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M14 2H6C5.46957 2 4.96086 2.21071 4.58579 2.58579C4.21071 2.96086 4 3.46957 4 4V20C4 20.5304 4.21071 21.0391 4.58579 21.4142C4.96086 21.7893 5.46957 22 6 22H18C18.5304 22 19.0391 21.7893 19.4142 21.4142C19.7893 21.0391 20 20.5304 20 20V8L14 2Z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M14 2V8H20" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M12 18V12" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M9 15H15" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <div class="widget-content">
                <p class="widget-description">Créer une nouvelle page sur votre site web</p>
                <span class="widget-button">Créer une page →</span>
            </div>
        </a>
    </div>
    <?php
}

// Widget Voir les pages
function display_view_pages_widget() {
    $pages_count = wp_count_posts('page');
    ?>
    <div class="mediapilote-dashboard-widget">
        <a href="<?php echo admin_url('edit.php?post_type=page'); ?>" class="dashboard-widget-action">
            <div class="widget-icon" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M14 2H6C5.46957 2 4.96086 2.21071 4.58579 2.58579C4.21071 2.96086 4 3.46957 4 4V20C4 20.5304 4.21071 21.0391 4.58579 21.4142C4.96086 21.7893 5.46957 22 6 22H18C18.5304 22 19.0391 21.7893 19.4142 21.4142C19.7893 21.0391 20 20.5304 20 20V8L14 2Z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M14 2V8H20" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M16 13H8" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M16 17H8" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M10 9H9H8" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <div class="widget-content">
                <p class="widget-description">Vous avez <strong><?php echo $pages_count->publish; ?> page(s)</strong> publiée(s)</p>
                <span class="widget-button">Gérer les pages →</span>
            </div>
        </a>
    </div>
    <?php
}

// Widget Ajouter une actualité
function display_add_news_widget() {
    ?>
    <div class="mediapilote-dashboard-widget">
        <a href="<?php echo admin_url('post-new.php?post_type=news'); ?>" class="dashboard-widget-action">
            <div class="widget-icon" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M19 3H5C3.89543 3 3 3.89543 3 5V19C3 20.1046 3.89543 21 5 21H19C20.1046 21 21 20.1046 21 19V5C21 3.89543 20.1046 3 19 3Z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M12 8V16" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M8 12H16" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <div class="widget-content">
                <p class="widget-description">Publier une nouvelle actualité</p>
                <span class="widget-button">Créer une actualité →</span>
            </div>
        </a>
    </div>
    <?php
}

// Widget Voir les actualités
function display_view_news_widget() {
    $news_count = wp_count_posts('news');
    ?>
    <div class="mediapilote-dashboard-widget">
        <a href="<?php echo admin_url('edit.php?post_type=news'); ?>" class="dashboard-widget-action">
            <div class="widget-icon" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M2 3H8C9.06087 3 10.0783 3.42143 10.8284 4.17157C11.5786 4.92172 12 5.93913 12 7V21C12 20.2044 11.6839 19.4413 11.1213 18.8787C10.5587 18.3161 9.79565 18 9 18H2V3Z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M22 3H16C14.9391 3 13.9217 3.42143 13.1716 4.17157C12.4214 4.92172 12 5.93913 12 7V21C12 20.2044 12.3161 19.4413 12.8787 18.8787C13.4413 18.3161 14.2044 18 15 18H22V3Z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <div class="widget-content">
                <p class="widget-description">Vous avez <strong><?php echo $news_count->publish; ?> actualité(s)</strong> publiée(s)</p>
                <span class="widget-button">Gérer les actualités →</span>
            </div>
        </a>
    </div>
    <?php
}

// Widget Gérer l'apparence
function display_customize_widget() {
    $customize_url = admin_url('customize.php?return=' . urlencode(admin_url('index.php')));
    ?>
    <div class="mediapilote-dashboard-widget">
        <a href="<?php echo esc_url($customize_url); ?>" class="dashboard-widget-action">
            <div class="widget-icon" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 2C13.0609 2 14.0783 2.42143 14.8284 3.17157C15.5786 3.92172 16 4.93913 16 6C16 7.06087 15.5786 8.07828 14.8284 8.82843C14.0783 9.57857 13.0609 10 12 10C10.9391 10 9.92172 9.57857 9.17157 8.82843C8.42143 8.07828 8 7.06087 8 6C8 4.93913 8.42143 3.92172 9.17157 3.17157C9.92172 2.42143 10.9391 2 12 2Z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M7 22C8.06087 22 9.07828 21.5786 9.82843 20.8284C10.5786 20.0783 11 19.0609 11 18C11 16.9391 10.5786 15.9217 9.82843 15.1716C9.07828 14.4214 8.06087 14 7 14C5.93913 14 4.92172 14.4214 4.17157 15.1716C3.42143 15.9217 3 16.9391 3 18C3 19.0609 3.42143 20.0783 4.17157 20.8284C4.92172 21.5786 5.93913 22 7 22Z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M17 22C18.0609 22 19.0783 21.5786 19.8284 20.8284C20.5786 20.0783 21 19.0609 21 18C21 16.9391 20.5786 15.9217 19.8284 15.1716C19.0783 14.4214 18.0609 14 17 14C15.9391 14 14.9217 14.4214 13.1716 15.1716C12.4214 15.9217 12 16.9391 12 18C12 19.0609 12.4214 20.0783 13.1716 20.8284C14.9217 21.5786 15.9391 22 17 22Z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <div class="widget-content">
                <p class="widget-description">Personnaliser couleurs, logos et menus</p>
                <span class="widget-button">Personnaliser →</span>
            </div>
        </a>
    </div>
    <?php
}

// Widget Médiathèque
function display_media_widget() {
    $media_count = wp_count_posts('attachment');
    ?>
    <div class="mediapilote-dashboard-widget">
        <a href="<?php echo admin_url('upload.php'); ?>" class="dashboard-widget-action">
            <div class="widget-icon" style="background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M19 3H5C3.89543 3 3 3.89543 3 5V19C3 20.1046 3.89543 21 5 21H19C20.1046 21 21 20.1046 21 19V5C21 3.89543 20.1046 3 19 3Z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M8.5 10C9.32843 10 10 9.32843 10 8.5C10 7.67157 9.32843 7 8.5 7C7.67157 7 7 7.67157 7 8.5C7 9.32843 7.67157 10 8.5 10Z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M21 15L16 10L5 21" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <div class="widget-content">
                <p class="widget-description">Accéder à vos <strong><?php echo $media_count->inherit; ?> fichier(s)</strong></p>
                <span class="widget-button">Voir la médiathèque →</span>
            </div>
        </a>
    </div>
    <?php
}

// Widget Mettre à jour le thème
function display_theme_update_widget() {
    $current_theme = wp_get_theme();
    ?>
    <div class="mediapilote-dashboard-widget">
        <a href="<?php echo admin_url('themes.php'); ?>" class="dashboard-widget-action">
            <div class="widget-icon" style="background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M21.5 2V8H15.5" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M2.5 22V16H8.5" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M19.13 12.87C18.7097 14.3391 17.8744 15.6523 16.7241 16.6508C15.5738 17.6492 14.1597 18.2901 12.6567 18.4937C11.1537 18.6973 9.62644 18.4547 8.25759 17.7951C6.88873 17.1355 5.73517 16.0859 4.93 14.77L2.5 12" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M21.5 12L19.07 9.23C18.2648 7.91408 17.1113 6.86448 15.7424 6.20488C14.3736 5.54528 12.8463 5.3027 11.3433 5.50631C9.84035 5.70993 8.42623 6.35078 7.27589 7.34923C6.12555 8.34767 5.29024 9.66095 4.87 11.13" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <div class="widget-content">
                <p class="widget-description">Version actuelle : <strong><?php echo $current_theme->get('Version'); ?></strong></p>
                <span class="widget-button">Vérifier les mises à jour →</span>
            </div>
        </a>
    </div>
    <?php
}

// Widget Contacter le support
function display_support_widget() {
    ?>
    <div class="mediapilote-dashboard-widget">
        <a href="https://assistance-mediapilote-laval.com/" target="_blank" rel="noopener noreferrer" class="dashboard-widget-action">
            <div class="widget-icon" style="background: linear-gradient(135deg, #30cfd0 0%, #330867 100%);">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M21 11.5C21.0034 12.8199 20.6951 14.1219 20.1 15.3C19.3944 16.7118 18.3098 17.8992 16.9674 18.7293C15.6251 19.5594 14.0782 19.9994 12.5 20C11.1801 20.0034 9.87812 19.6951 8.7 19.1L3 21L4.9 15.3C4.30493 14.1219 3.99656 12.8199 4 11.5C4.00061 9.92176 4.44061 8.37485 5.27072 7.03255C6.10083 5.69025 7.28825 4.60557 8.7 3.9C9.87812 3.30493 11.1801 2.99656 12.5 3H13C15.0843 3.11499 17.053 3.99476 18.5291 5.47086C20.0052 6.94696 20.885 8.91565 21 11V11.5Z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <div class="widget-content">
                <p class="widget-description">Besoin d'aide ? Notre équipe est là pour vous</p>
                <span class="widget-button">Accéder au support →</span>
            </div>
        </a>
    </div>
    <?php
}

// Styles pour les widgets
function add_dashboard_widgets_styles() {
    ?>
    <style>
        /* Styles pour les widgets d'actions rapides */
        .mediapilote-dashboard-widget {
            margin: 0;
        }
        
        .dashboard-widget-action {
            display: flex;
            align-items: center;
            gap: 20px;
            padding: 24px;
            background: #ffffff;
            border: none;
            border-radius: 16px;
            text-decoration: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }
        
        .dashboard-widget-action::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0) 0%, rgba(255,255,255,0.1) 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .dashboard-widget-action:hover {
            transform: translateY(-6px) scale(1.02);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }
        
        .dashboard-widget-action:hover::before {
            opacity: 1;
        }
        
        .widget-icon {
            width: 72px;
            height: 72px;
            min-width: 72px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease;
        }
        
        .dashboard-widget-action:hover .widget-icon {
            transform: scale(1.1) rotate(5deg);
        }
        
        .widget-icon svg {
            width: 36px;
            height: 36px;
        }
        
        .widget-content {
            flex: 1;
        }
        
        .widget-description {
            margin: 0 0 12px 0;
            font-size: 15px;
            color: #4b5563;
            line-height: 1.6;
        }
        
        .widget-description strong {
            color: #1f2937;
            font-weight: 600;
        }
        
        .widget-button {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 14px;
            font-weight: 600;
            color: #6366f1;
            transition: gap 0.3s ease;
        }
        
        .dashboard-widget-action:hover .widget-button {
            gap: 10px;
        }
        
        /* Styles des titres de widgets */
        #dashboard_add_page h2,
        #dashboard_view_pages h2,
        #dashboard_add_news h2,
        #dashboard_view_news h2,
        #dashboard_customize h2,
        #dashboard_media h2,
        #dashboard_theme_update h2,
        #dashboard_support h2 {
            font-size: 17px;
            font-weight: 600;
            color: #1f2937;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 12px;
            margin-bottom: 0 !important;
        }
        
        /* Supprimer les padding par défaut */
        #dashboard_add_page .inside,
        #dashboard_view_pages .inside,
        #dashboard_add_news .inside,
        #dashboard_view_news .inside,
        #dashboard_customize .inside,
        #dashboard_media .inside,
        #dashboard_theme_update .inside,
        #dashboard_support .inside {
            margin: 0;
            padding: 0;
        }
        
        /* Désactiver les styles par défaut des postbox */
        #dashboard_add_page.postbox,
        #dashboard_view_pages.postbox,
        #dashboard_add_news.postbox,
        #dashboard_view_news.postbox,
        #dashboard_customize.postbox,
        #dashboard_media.postbox,
        #dashboard_theme_update.postbox,
        #dashboard_support.postbox {
            border: none !important;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08) !important;
            background: transparent !important;
            border-radius: 12px !important;
            overflow: visible !important;
        }
        
        #dashboard_add_page .postbox-header,
        #dashboard_view_pages .postbox-header,
        #dashboard_add_news .postbox-header,
        #dashboard_view_news .postbox-header,
        #dashboard_customize .postbox-header,
        #dashboard_media .postbox-header,
        #dashboard_theme_update .postbox-header,
        #dashboard_support .postbox-header {
            display: none !important;
        }
        
        #dashboard_add_page .inside,
        #dashboard_view_pages .inside,
        #dashboard_add_news .inside,
        #dashboard_view_news .inside,
        #dashboard_customize .inside,
        #dashboard_media .inside,
        #dashboard_theme_update .inside,
        #dashboard_support .inside {
            background: #ffffff !important;
            border-radius: 12px !important;
        }
        
        /* Masquer les boutons toggle par défaut */
        #dashboard_add_page .handle-actions,
        #dashboard_view_pages .handle-actions,
        #dashboard_add_news .handle-actions,
        #dashboard_view_news .handle-actions,
        #dashboard_customize .handle-actions,
        #dashboard_media .handle-actions,
        #dashboard_theme_update .handle-actions,
        #dashboard_support .handle-actions {
            display: none !important;
        }
        
        /* Désactiver le drag & drop et aligner les widgets en grille */
        #dashboard_add_page,
        #dashboard_view_pages,
        #dashboard_add_news,
        #dashboard_view_news,
        #dashboard_customize,
        #dashboard_media,
        #dashboard_theme_update,
        #dashboard_support {
            cursor: default !important;
        }
        
        #dashboard_add_page .hndle,
        #dashboard_view_pages .hndle,
        #dashboard_add_news .hndle,
        #dashboard_view_news .hndle,
        #dashboard_customize .hndle,
        #dashboard_media .hndle,
        #dashboard_theme_update .hndle,
        #dashboard_support .hndle {
            cursor: default !important;
        }
        
        /* Forcer l'affichage en grille */
        #dashboard-widgets-wrap {
            display: grid !important;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)) !important;
            gap: 20px !important;
        }
        
        #dashboard-widgets {
            display: contents !important;
        }
        
        #dashboard-widgets .meta-box-sortables {
            display: contents !important;
        }
        
        #dashboard-widgets #normal-sortables,
        #dashboard-widgets #side-sortables,
        #dashboard-widgets #column3-sortables,
        #dashboard-widgets #column4-sortables {
            display: contents !important;
        }
        
        #dashboard-widgets .postbox {
            margin: 0 !important;
            width: 100% !important;
            float: none !important;
        }
        
        .wp-admin #dashboard-widgets .postbox-container {
            width: 100% !important;
            display: contents !important;
        }
        
        /* Responsive */
        @media (max-width: 782px) {
            .dashboard-widget-action {
                flex-direction: column;
                text-align: center;
                gap: 16px;
                padding: 20px;
            }
            
            .widget-icon {
                width: 64px;
                height: 64px;
                min-width: 64px;
            }
            
            .widget-icon svg {
                width: 32px;
                height: 32px;
            }
            
            #dashboard-widgets-wrap {
                grid-template-columns: 1fr !important;
            }
        }
        
        @media (min-width: 783px) and (max-width: 1200px) {
            #dashboard-widgets-wrap {
                grid-template-columns: repeat(2, 1fr) !important;
            }
        }
        
        @media (min-width: 1201px) {
            #dashboard-widgets-wrap {
                grid-template-columns: repeat(3, 1fr) !important;
            }
        }
    </style>
    <?php
}

/**
 * @snippet  Désactiver le drag & drop du tableau de bord
 * @author   Emmanuel Claude
 */
function disable_dashboard_drag_drop($hook) {
    if ('index.php' === $hook) {
        wp_dequeue_script('dashboard');
        wp_deregister_script('dashboard');
    }
}

/**
 * @snippet  Supprimer complètement certains rôles
 * @author   Emmanuel Claude
 */
add_action('init', 'remove_specific_roles', 999);

function remove_specific_roles() {
    // Liste des rôles à supprimer
    $roles_to_remove = array(
        'wpseo_editor',     // SEO Editor (Yoast)
        'wpseo_manager',    // SEO Manager (Yoast)
        'subscriber',       // Abonné / abonnée
        'contributor',      // Contributeur / contributrice
        'author',           // Auteur/autrice
        'editor'            // Editeur/éditrice
    );
    
    // Supprimer chaque rôle s'il existe
    foreach ($roles_to_remove as $role) {
        if (get_role($role)) {
            remove_role($role);
        }
    }
}

/**
 * @snippet  Cacher les rôles Yoast SEO dans l'interface
 * @author   Emmanuel Claude
 */
add_filter('editable_roles', 'hide_yoast_roles');

function hide_yoast_roles($roles) {
    // Supprimer les rôles Yoast de la liste
    if (isset($roles['wpseo_editor'])) {
        unset($roles['wpseo_editor']);
    }
    if (isset($roles['wpseo_manager'])) {
        unset($roles['wpseo_manager']);
    }
    
    return $roles;
}

/**
 * @snippet  Charger de scripts personnalisés
 * @author   Emmanuel Claude
 */
function mediapilote_scripts() {
    $scripts = get_field('dev_scripts', 'option');
    
    // Vérifier que $scripts est un tableau, sinon utiliser un tableau vide
    if (!is_array($scripts)) {
        $scripts = array();
    }

    $script_data = array(
        "Jquery" => array(
            'script' => 'https://code.jquery.com/jquery-3.7.1.js',
            'dependencies' => false,
            'version' => '3.7.1',
            'enqueue_script' => false
        ),
        "Owl" => array(
            'script' => 'https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js',
            'dependencies' => array(),
            'enqueue_script' => false,
            'style' => 'https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css',
            'extra_styles' => array(
                'https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css'
            )
        ),
        "Scroll Reveal JS" => array(
            'script' => 'https://unpkg.com/scrollreveal/dist/scrollreveal.min.js',
            'dependencies' => array(),
            'enqueue_script' => true
        ),
        "Bootstrap Grid" => array(
            'style' => 'https://cdn.jsdelivr.net/npm/bootstrap-v4-grid-only@1.0.0/dist/bootstrap-grid.css'
        ),
        "Splide" => array(
            'script' => 'https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js',
            'dependencies' => array(),
            'enqueue_script' => true,
            'style' => 'https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css'
        ),
        "Slick" => array(
            'script' => 'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js',
            'dependencies' => array(),
            'enqueue_script' => true,
            'style' => 'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.css'
        ),
        "Magnific Popup" => array(
            'script' => 'https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js',
            'dependencies' => array(),
            'enqueue_script' => true,
            'style' => 'https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.min.css'
        ),
        "Lightbox" => array(
            'script' => 'https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox.min.js',
            'dependencies' => array(),
            'enqueue_script' => true,
            'style' => 'https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/css/lightbox.css',
        )
    );
    
    foreach ($script_data as $script_name => $data) {
        if (in_array($script_name, $scripts)) {
            if (isset($data['script']) && isset($data['enqueue_script'])) {
                wp_enqueue_script($script_name . '-js', $data['script'], $data['dependencies'], false, $data['enqueue_script']);
                if (isset($data['version'])) {
                    wp_script_add_data($script_name . '-js', 'version', $data['version']);
                }
            }
    
            if (isset($data['style'])) {
                wp_enqueue_style($script_name . '-css', $data['style']);
            }
    
            if (isset($data['extra_styles'])) {
                foreach ($data['extra_styles'] as $extra_style) {
                    wp_enqueue_style($script_name . '-extra-css', $extra_style);
                }
            }
        }
    }
    

    wp_enqueue_style('reset', get_template_directory_uri() .'/css/reset.css');
    wp_enqueue_style('utilitary', get_template_directory_uri() .'/css/utilitary.css');
    wp_enqueue_style('template', get_template_directory_uri() .'/css/template.css');
    wp_enqueue_style('footer', get_template_directory_uri() .'/css/footer.css');
    wp_enqueue_style('tarteaucitron', get_template_directory_uri() .'/css/tarteaucitron.css');

    // Préconnect Google Fonts pour Figtree
    add_action('wp_head', function() {
        echo '<link rel="preconnect" href="https://fonts.googleapis.com">';
        echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>';
    }, 1);
    // Charger Figtree toutes graisses et styles
    wp_enqueue_style('figtree-font', 'https://fonts.googleapis.com/css2?family=Figtree:ital,wght@0,300..900;1,300..900&display=swap', [], null);
    // Charger Reddit Sans toutes graisses et styles
    wp_enqueue_style('reddit-sans-font', 'https://fonts.googleapis.com/css2?family=Reddit+Sans:ital,wght@0,300..900;1,300..900&display=swap', [], null);

    wp_enqueue_script( 'main-js', get_template_directory_uri() . '/js/main.js', array(), _S_VERSION, true );
    // wp_enqueue_script( 'slider-js', get_template_directory_uri() . '/js/slider.js', array(), _S_VERSION, true );

    wp_enqueue_script( 'mediapilote-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true );
    
    // Script pour les dropdowns des sous-menus
    wp_enqueue_script( 'mediapilote-dropdown', get_template_directory_uri() . '/js/dropdown-menu.js', array(), _S_VERSION, true );
    
    // Script pour le menu burger responsive
    wp_enqueue_script( 'mediapilote-burger-menu', get_template_directory_uri() . '/js/burger-menu.js', array(), _S_VERSION, true );

    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }
}
add_action( 'wp_enqueue_scripts', 'mediapilote_scripts' );



/**
 * @snippet  Duplicate posts and pages without plugins
 * @author   Misha Rudrastyh
 * @url      https://rudrastyh.com/wordpress/duplicate-post.html
 */

// Add the duplicate link to action list for post_row_actions
// for "post" and custom post types
add_filter( 'post_row_actions', 'rd_duplicate_post_link', 10, 2 );
// for "page" post type
add_filter( 'page_row_actions', 'rd_duplicate_post_link', 10, 2 );


function rd_duplicate_post_link( $actions, $post ) {

    if( ! current_user_can( 'edit_posts' ) ) {
        return $actions;
    }

    $url = wp_nonce_url(
        add_query_arg(
            array(
                'action' => 'rd_duplicate_post_as_draft',
                'post' => $post->ID,
            ),
            'admin.php'
        ),
        basename(__FILE__),
        'duplicate_nonce'
    );

    $actions[ 'duplicate' ] = '<a href="' . $url . '" title="Dupliquer cet item" rel="permalink">Dupliquer</a>';

    return $actions;
}

/*
 * Function creates post duplicate as a draft and redirects then to the edit post screen
 */
add_action( 'admin_action_rd_duplicate_post_as_draft', 'rd_duplicate_post_as_draft' );

function rd_duplicate_post_as_draft(){

    // check if post ID has been provided and action
    if ( empty( $_GET[ 'post' ] ) ) {
        wp_die( 'No post to duplicate has been provided!' );
    }

    // Nonce verification
    if ( ! isset( $_GET[ 'duplicate_nonce' ] ) || ! wp_verify_nonce( $_GET[ 'duplicate_nonce' ], basename( __FILE__ ) ) ) {
        return;
    }

    // Get the original post id
    $post_id = absint( $_GET[ 'post' ] );

    // And all the original post data then
    $post = get_post( $post_id );

    /*
     * if you don't want current user to be the new post author,
     * then change next couple of lines to this: $new_post_author = $post->post_author;
     */
    $current_user = wp_get_current_user();
    $new_post_author = $current_user->ID;

    // if post data exists (I am sure it is, but just in a case), create the post duplicate
    if ( $post ) {

        // new post data array
        $args = array(
            'comment_status' => $post->comment_status,
            'ping_status'    => $post->ping_status,
            'post_author'    => $new_post_author,
            'post_content'   => $post->post_content,
            'post_excerpt'   => $post->post_excerpt,
            'post_name'      => $post->post_name,
            'post_parent'    => $post->post_parent,
            'post_password'  => $post->post_password,
            'post_status'    => 'draft',
            'post_title'     => $post->post_title,
            'post_type'      => $post->post_type,
            'to_ping'        => $post->to_ping,
            'menu_order'     => $post->menu_order
        );

        // insert the post by wp_insert_post() function
        $new_post_id = wp_insert_post( $args );

        /*
         * get all current post terms ad set them to the new post draft
         */
        $taxonomies = get_object_taxonomies( get_post_type( $post ) ); // returns array of taxonomy names for post type, ex array("category", "post_tag");
        if( $taxonomies ) {
            foreach ( $taxonomies as $taxonomy ) {
                $post_terms = wp_get_object_terms( $post_id, $taxonomy, array( 'fields' => 'slugs' ) );
                wp_set_object_terms( $new_post_id, $post_terms, $taxonomy, false );
            }
        }

        // duplicate all post meta
        $post_meta = get_post_meta( $post_id );
        if( $post_meta ) {

            foreach ( $post_meta as $meta_key => $meta_values ) {

                if( '_wp_old_slug' == $meta_key ) { // do nothing for this meta key
                    continue;
                }

                foreach ( $meta_values as $meta_value ) {
                    add_post_meta( $new_post_id, $meta_key, $meta_value );
                }
            }
        }

        // finally, redirect to the edit post screen for the new draft
        // wp_safe_redirect(
        // 	add_query_arg(
        // 		array(
        // 			'action' => 'edit',
        // 			'post' => $new_post_id
        // 		),
        // 		admin_url( 'post.php' )
        // 	)
        // );
        // exit;
        // or we can redirect to all posts with a message
        wp_safe_redirect(
            add_query_arg(
                array(
                    'post_type' => ( 'post' !== get_post_type( $post ) ? get_post_type( $post ) : false ),
                    'saved' => 'post_duplication_created' // just a custom slug here
                ),
                admin_url( 'edit.php' )
            )
        );
        exit;

    } else {
        wp_die( 'Post creation failed, could not find original post.' );
    }

}

/*
 * In case we decided to add admin notices
 */
add_action( 'admin_notices', 'rudr_duplication_admin_notice' );

function rudr_duplication_admin_notice() {

    // Get the current screen
    $screen = get_current_screen();

    if ( 'edit' !== $screen->base ) {
        return;
    }

    //Checks if settings updated
    if ( isset( $_GET[ 'saved' ] ) && 'post_duplication_created' == $_GET[ 'saved' ] ) {

         echo '<div class="notice notice-success is-dismissible"><p>Contenu dupliqué.</p></div>';
         
    }
}

/**
 * @snippet  Gérer les couleurs dans Wordpress
 * @author   Emmanuel Claude
 */


function modify_theme_json_with_acf_fields() {
    // Récupérer les valeurs depuis les champs ACF
    $colorPaletteOptions = get_field('option_palette', 'option');

    // Récupérer le contenu actuel du fichier theme.json
    $theme_json_path = get_template_directory() . '/theme.json';
    $theme_json = json_decode(file_get_contents($theme_json_path), true);

    // Mettre à jour la palette de couleurs dans le fichier theme.json
    if ($colorPaletteOptions && is_array($colorPaletteOptions)) {
        $newColorPalette = [];
        foreach ($colorPaletteOptions as $option) {
            $name = isset($option['plt_name']) ? esc_attr($option['plt_name']) : '';
            $slug = isset($option['plt_slug']) ? sanitize_title($option['plt_slug']) : '';
            $color = isset($option['plt_color']) ? $option['plt_color'] : '';

            if ($name && $slug && $color) {
                $newColorPalette[] = [
                    'name'  => $name,
                    'slug'  => $slug,
                    'color' => $color,
                ];
            }
        }

        // Conserver les paramètres existants dans le fichier theme.json
        if (!empty($newColorPalette)) {
            $theme_json['settings']['color']['palette'] = $newColorPalette;

            // Ajouter ou mettre à jour les paramètres de layout
            $theme_json['settings']['layout'] = [
                'contentSize' => '840px',
                'wideSize'    => '1100px',
            ];

            // Ajouter ou mettre à jour la version
            $theme_json['version'] = 2; // ajustez la version selon vos besoins

            // Enregistrer le fichier theme.json avec la nouvelle configuration
            file_put_contents($theme_json_path, json_encode($theme_json, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        }
    }
}

add_action('after_setup_theme', 'modify_theme_json_with_acf_fields');

/**
 * @snippet  Créer une page paramètres
 * @author   Emmanuel Claude
 */
if( function_exists( 'acf_add_options_page' ) ) {
    


    acf_add_options_sub_page( array(
        'page_title' 	=> 'Réseaux sociaux',
        'menu_title'	=> 'Réseaux sociaux',
        'parent_slug'	=> 'theme-general-settings',
    ) );
    acf_add_options_sub_page( array(
        'page_title' 	=> 'Coordonnées',
        'menu_title'	=> 'Coordonnées',
        'parent_slug'	=> 'theme-general-settings',
    ) );



    acf_add_options_page( array(
        'page_title' 	=> 'Développeur',
        'menu_title'	=> 'Développeur',
        'menu_slug' 	=> 'dev-settings',
        'capability'	=> 'edit_posts',
        'redirect'		=> false,
        'position'    	=> 3
    ) );
    acf_add_options_sub_page( array(
        'page_title' 	=> 'Tarte au citron',
        'menu_title'	=> 'Tarte au citron',
        'parent_slug' 	=> 'dev-settings',
    ) );

}
/**
 * @snippet  Cacher des fonctions pour les modérateurs
 * @author   Emmanuel Claude
 */

 function hide_menu() {

    if ( !current_user_can('administrator') ) {
         remove_menu_page( 'plugins.php' ); //Extensions
         remove_menu_page( 'users.php' ); //Users
         remove_menu_page( 'tools.php' ); //Tools
         remove_menu_page( 'dev-settings' ); //Développeur
         remove_menu_page( 'edit.php?post_type=acf-field-group' ); //ACF
         remove_submenu_page('themes.php', 'theme-editor.php'); // Theme Editor
        }
          }
    add_action('admin_head', 'hide_menu', 5 );

/**
 * @snippet  Déclaration des menus
 * @author   Emmanuel Claude
 */
// Enregistrer les menus
function register_custom_menus() {
    register_nav_menus(
        array(
            'footer-menu'   => 'Bas de page',
            'top-left-menu'   => 'Haut de page - gauche',
            'top-right-menu'   => 'Haut de page - droite',
            'main-menu'     => 'Menu principal',
            'legal-menu'     => 'Menu des mentions légales',
            'big-menu'      => 'Menu large',
            'quick-access-menu' => 'Accès rapide',
        )
    );
}
add_action('after_setup_theme', 'register_custom_menus');

// Afficher les menus
function display_footer_menu() {
    wp_nav_menu(array(
        'theme_location' => 'footer-menu',
        'menu_class'     => 'footer-menu-class',
        'container'      => 'nav',
        'container_class'=> 'footer-menu-container',
    ));
}

function display_header_menu() {
    wp_nav_menu(array(
        'theme_location' => 'header-menu',
        'menu_class'     => 'header-menu-class',
        'container'      => 'nav',
        'container_class'=> 'header-menu-container',
    ));
}

function display_main_menu() {
    wp_nav_menu(array(
        'theme_location' => 'main-menu',
        'menu_class'     => 'main-menu-class',
        'container'      => 'nav',
        'container_class'=> 'main-menu-container',
    ));
}

function display_quick_access_menu() {
    wp_nav_menu(array(
        'theme_location' => 'quick-access-menu',
        'menu_class'     => 'quick-access-menu-class',
        'container'      => 'nav',
        'container_class'=> 'quick-access-menu-container',
    ));
}

/**
 * @snippet  Autoriser les SVGs
 * @author   Emmanuel Claude
 */

 // Wp v4.7.1 and higher
add_filter( 'wp_check_filetype_and_ext', function($data, $file, $filename, $mimes) {
    $filetype = wp_check_filetype( $filename, $mimes );
    return [
        'ext'             => $filetype['ext'],
        'type'            => $filetype['type'],
        'proper_filename' => $data['proper_filename']
    ];
  
  }, 10, 4 );
  
  function cc_mime_types( $mimes ){
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
  }
  add_filter( 'upload_mimes', 'cc_mime_types' );
  
  function fix_svg() {
    echo '<style type="text/css">
          .attachment-266x266, .thumbnail img {
               width: 100% !important;
               height: auto !important;
          }
          </style>';
  }

/**
 * @snippet  Gestion des blocs
 * @author   Grégory Chuine
 */
require_once dirname( __FILE__ ) . '/../blocks/autoload_blocks.php';

/**
 * @snippet  Logo sur la page de connexion
 * @author   Emmanuel Claude
 */

 function my_login_logo() { 
    global $logo;
    $image_url = $logo["url"];
    $login_bg_color = get_field( 'login_bg_color', 'option' );
    ?>

<style type="text/css">
.login {
    background-color: <?php echo $login_bg_color;
    ?> !important;
}

#login h1 a,
.login h1 a {
    background-image: url("<?php echo $image_url ?>") !important;
    height: 65px;
    width: 320px;
    background-size: 320px 65px;
    background-repeat: no-repeat;
    padding-bottom: 30px;
}
</style>
<?php }
add_action( 'login_enqueue_scripts', 'my_login_logo' );


function tarteaucitron_init() {
    ?>
<script src="<?php get_stylesheet_directory_uri() ?>/wp-content/themes/mediapilote/js/tarteaucitron/tarteaucitron.js">
</script>
<script type="text/javascript">
tarteaucitron.init({
    "privacyUrl": "<?php echo get_field('tac_privacyurl', 'option'); ?>",
    /* Privacy policy url */
    "hashtag": "<?php echo get_field('tac_hashtag', 'option'); ?>",
    /* Open the panel with this hashtag */
    "cookieName": "<?php echo get_field('tac_cookieName', 'option'); ?>",
    /* Cookie name */
    "orientation": "<?php echo get_field('tac_banner', 'option'); ?>",
    /* Banner position (top - bottom) */
    "showAlertSmall": <?php echo get_field('tac_showalertsmall', 'option'); ?>,
    /* Show the small banner on bottom right */
    "cookieslist": <?php echo get_field('tac_cookieslist', 'option'); ?>,
    /* Show the cookie list */
    "adblocker": <?php echo get_field('tac_adblocker', 'option'); ?>,
    /* Show a Warning if an adblocker is detected */
    "AcceptAllCta": <?php echo get_field('tac_acceptallcta', 'option'); ?>,
    /* Show the accept all button when highPrivacy on */
    "highPrivacy": <?php echo get_field('tac_highprivacy', 'option'); ?>,
    /* Disable auto consent */
    "handleBrowserDNTRequest": <?php echo get_field('tac_handleBrowserdntrequest', 'option'); ?>,
    /* If Do Not Track == 1, accept all */
    "removeCredit": <?php echo get_field('tac_removecredit', 'option'); ?>,
    /* Remove credit link */
    "moreInfoLink": <?php echo get_field('tac_moreinfolink', 'option'); ?>,
    /* Show more info link */
    "iconSrc": "<?php echo get_field('tac_icon', 'option'); ?>",
});

tarteaucitron.user.gtagUa = '<?php echo get_field('tac_GA4', 'option'); ?>';
// tarteaucitron.user.gtagCrossdomain = ['example.com', 'example2.com'];
tarteaucitron.user.gtagMore = function() {
    /* add here your optionnal gtag() */
};
(tarteaucitron.job = tarteaucitron.job || []).push('gtag');
</script>
<?php
}
$tac_enable = get_field('tac_enable', 'option');
if ($tac_enable == "oui") {
    add_action('wp_head', 'tarteaucitron_init');
}

/**
 * @snippet  Déclarer de nouvelles zones de widget pour le footer
 * @author   Emmanuel Claude
 */
if ( function_exists('register_sidebar') )
register_sidebar( array(
 'name'       => __( 'Footer - Colonne 1', 'mediapilote' ),
 'id'     => 'footer_one',
 'description'    => __( 'Glisser-déposez ici les widgets que vous souhaitez faire apparaître dans la section 1 du footer.', 'mediapilote' ),
)
);
if ( function_exists('register_sidebar') )
register_sidebar( array(
 'name'       => __( 'Footer - Colonne 2', 'mediapilote' ),
 'id'     => 'footer_two',
 'description'    => __( 'Glisser-déposez ici les widgets que vous souhaitez faire apparaître dans la section 2 du footer.', 'mediapilote' ),
)
);
if ( function_exists('register_sidebar') )
register_sidebar( array(
 'name'       => __( 'Footer - Colonne 3', 'mediapilote' ),
 'id'     => 'footer_three',
 'description'    => __( 'Glisser-déposez ici les widgets que vous souhaitez faire apparaître dans la section 3 du footer.', 'mediapilote' ),
)
);


/**
 * @snippet  Masquer page en brouillon des menus
 * @author   Emmanuel Claude
 */
function masquer_pages_en_brouillon($items, $args) {
    foreach ($items as $key => $item) {
        // Vérifier si la page est en brouillon
        if ($item->object == 'page' && get_post_status($item->object_id) == 'draft') {
            // Supprimer l'élément du menu
            unset($items[$key]);
        }
    }

    return $items;
}

// Ajoutez le filtre pour appliquer la fonction
add_filter('wp_nav_menu_objects', 'masquer_pages_en_brouillon', 10, 2);

/**
 * @snippet  Activer les alignements dans les blocs WP
 * @author   Emmanuel Claude
 */
function themeprefix_setup() {
    add_theme_support( 'align-wide' );
}
add_action( 'after_setup_theme', 'themeprefix_setup', 10, 2 );
remove_filter('the_category', 'wptexturize');

/**
 * @snippet  Charger admin.css dans le BO
 * @author   Emmanuel Claude
 */
function load_admin_styles() {
    // Vérifie si c'est le back office de WordPress
    if (is_admin()) {
        // Enqueue la feuille de style admin.css
        wp_enqueue_style('admin-styles', get_template_directory_uri() . '/css/admin.css');
        // Préconnect Google Fonts pour Figtree dans le BO
        add_action('admin_head', function() {
            echo '<link rel="preconnect" href="https://fonts.googleapis.com">';
            echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>';
        }, 1);
        // Charger Figtree toutes graisses et styles dans le BO
        wp_enqueue_style('figtree-font', 'https://fonts.googleapis.com/css2?family=Figtree:ital,wght@0,300..900;1,300..900&display=swap', [], null);
    }
}
add_action('admin_enqueue_scripts', 'load_admin_styles');

/**
 * @snippet  Remplacer des noms du menu BO Wordpress
 * @author   Emmanuel Claude
 */
// Fonction pour modifier le nom du menu
function modifier_nom_menu() {
    global $menu;

    // Parcourir le tableau des menus
    foreach ( $menu as $key => $value ) {
        // Trouver l'index du menu Flamingo
        if ( $value[0] == 'Flamingo' ) {
            // Remplacer le nom du menu Flamingo par Messagerie
            $menu[ $key ][0] = 'Messagerie';
            break;
        }
    }
}

// Action pour appeler la fonction lors de l'initialisation de l'administration
add_action( 'admin_menu', 'modifier_nom_menu' );

/**
 * @snippet  Restreindre l'usage des blocs
 * @author   Grégory Chuine
 */
// function chronos_allowed_block_types( $allowed_blocks, $editor_context ) {
//     return array(
//         'core/paragraph',
//         'core/heading',
//         'core/list',
//         'core/image',
//         'core/gallery',
//         'core/media-text',
//         'core/button',
//         'core/buttons',
//         'core/columns',
//         'core/column',
//         'core/group',
//         'core/shortcode',
//         'core/embed',
//         'wpcf7/form-selector',
//         'acf/bloc-actualites',
//         'acf/bloc-partenaires',
//         'acf/bloc-coordonnees',
//         'acf/bloc-slider-accueil',
//         'acf/bloc-sommaire',
//         'acf/bloc-logo',
//     );
// }
// add_filter('allowed_block_types_all', 'chronos_allowed_block_types', 25, 2);


/**
 * @snippet  Nom du site dans un shortcode
 * @author   Emmanuel Claude
 */

 function get_site_name_shortcode() {
    return get_bloginfo('name');
}

// Enregistrez le shortcode
add_shortcode('site_name', 'get_site_name_shortcode');


/**
 * @snippet  Catégorie de bloc Mediapilote
 * @author   Grégory Chuine
 */
function filter_block_categories_when_post_provided( $block_categories, $editor_context ) {
    if ( ! empty( $editor_context->post ) ) {
        $custom_category = array(
            'slug'  => 'mediapilote',
            'title' => 'Mediapilote'
        );
        array_unshift( $block_categories, $custom_category );
    }
    return $block_categories;
}
add_filter( 'block_categories_all', 'filter_block_categories_when_post_provided', 10, 2 );

/**
 * @snippet  Catégorie de composition Mediapilote
 * @author   Grégory Chuine
 */
function register_mediapilote_pattern_category() {
    register_block_pattern_category(
        'mediapilote-patterns',
        array( 'label' => __( 'Composition Mediapilote', 'text-domain' ) )
    );
}
add_action( 'init', 'register_mediapilote_pattern_category' );

/**
 * @snippet  Slugify
 * @author   Emmanuel Claude
 */
function custom_slugify($input_string) {
    // Utiliser la fonction sanitize_title pour slugifier la chaîne
    $slug = sanitize_title($input_string);

    // Retourner le slug généré
    return $slug;
}



/**
 * @snippet  Yoast toujours en dessous d'ACF
 * @author   Emmanuel Claude
 */
function move_yoast_seo_metabox() {
    $priority = 'low'; // Priorité du placement du Yoast SEO metabox
    $acf_metabox_slug = 'acf-group'; // Slug du metabox ACF

    remove_meta_box('wpseo_meta', 'post', 'normal'); // Supprimer le metabox Yoast SEO
    add_meta_box('wpseo_meta', 'Yoast SEO', 'wpseo_metabox', 'post', 'normal', $priority); // Ajouter le metabox Yoast SEO après le metabox ACF

    // Mettre à jour la priorité du metabox ACF pour s'assurer qu'il apparaît avant le metabox Yoast SEO
    if ($acf_metabox = get_user_option('meta-box-order_post')) {
        $acf_metabox = explode(',', $acf_metabox);
        $acf_metabox = array_diff($acf_metabox, array($acf_metabox_slug));
        array_push($acf_metabox, $acf_metabox_slug);
        $acf_metabox = implode(',', $acf_metabox);
        update_user_option(get_current_user_id(), 'meta-box-order_post', $acf_metabox, true);
    }
}
add_action('add_meta_boxes', 'move_yoast_seo_metabox', 100);

/**
 * @snippet  Empêcher l'édition des slugs pour les modérateurs
 * @author   Emmanuel Claude
 */


// Restreindre l'édition des slugs pour les éditeurs
if (current_user_can('moderator')) {
    add_filter('editable_slug', 'disable_editable_slug');
    
    function disable_editable_slug($slug) {
        // Retourne le slug actuel sans modification
        return $slug;
    }
}

/**
 * @snippet  Désactiver les articles
 * @author   Emmanuel Claude
 */


function disable_posts() {
    // Désactiver l'accès public aux articles
    global $wp_post_types;
    if ( isset( $wp_post_types[ 'post' ] ) ) {
        $wp_post_types['post']->public = false;
    }
}

// Cacher les menus concernant les articles
function hide_posts_menu() {
    // Cacher le menu des articles
    remove_menu_page('edit.php');
}

if (get_field('dev_disable_posts', 'option')) {
    add_action('init', 'disable_posts');
    add_action('admin_menu', 'hide_posts_menu');
}

/**
 * @snippet  Désactiver les commentaires et les menus de commentaires
 * @author   Emmanuel Claude
 */

// Désactiver les commentaires
function disable_comments() {
    // Désactiver l'accès public aux commentaires
    global $wp_post_types;
    foreach ($wp_post_types as $post_type) {
        if (post_type_supports($post_type->name, 'comments')) {
            remove_post_type_support($post_type->name, 'comments');
            remove_post_type_support($post_type->name, 'trackbacks');
        }
    }
}

// Cacher le menu des commentaires
function hide_comments_menu() {
    // Cacher le menu des commentaires
    remove_menu_page('edit-comments.php');
}

if (get_field('dev_disable_comments', 'option')) {
    add_action('init', 'disable_comments');
    add_action('admin_menu', 'hide_comments_menu');
}

/**
 * Personnaliser les breadcrumbs Yoast pour les CPT "news" et "produits"
 * Ajoute le lien "Blog" pour les actualités et "Produits" pour les produits
 */
function mediapilote_custom_yoast_breadcrumbs($links) {
    // Vérifier si nous sommes sur une page d'actualité (CPT news)
    if (is_singular('news') || is_post_type_archive('news')) {
        // Créer le lien vers la page /blog/
        $blog_link = array(
            'text' => 'Blog',
            'url' => home_url('/blog/'),
            'allow_html' => true
        );
        
        // Insérer le lien "Blog" après le premier élément (nom du site)
        if (count($links) > 1) {
            array_splice($links, 1, 0, array($blog_link));
        }
    }
    
    // Vérifier si nous sommes sur une page de produit (CPT produits)
    if (is_singular('produits') || is_post_type_archive('produits')) {
        // Créer le lien vers la page /produits/
        $produits_link = array(
            'text' => 'Nos produits',
            'url' => home_url('/nos-produits/'),
            'allow_html' => true
        );
        
        // Insérer le lien "Produits" après le premier élément (nom du site)
        if (count($links) > 1) {
            array_splice($links, 1, 0, array($produits_link));
        }
    }
    
    return $links;
}
add_filter('wpseo_breadcrumb_links', 'mediapilote_custom_yoast_breadcrumbs');


/**
 * Ajouter la taxonomie Catégorie au CPT news existant
 */
function mediapilote_add_category_to_news() {
    register_taxonomy_for_object_type('category', 'news');
    register_taxonomy_for_object_type('post_tag', 'news');
}
add_action('init', 'mediapilote_add_category_to_news');

/**
 * @snippet  Précharger le block Bannière pour les nouveaux posts "news"
 * @author   Emmanuel Claude
 */
function mediapilote_preload_banniere_block_for_news($content, $post) {
    // Vérifier que c'est un nouveau post "news" (pas encore sauvegardé)
    if ($post->post_type === 'news' && $post->post_status === 'auto-draft' && empty($content)) {
        // Contenu par défaut du block Bannière avec verrouillage
        $default_content = '<!-- wp:mediapilote/banniere {"title":"","description":"","buttonText":"","buttonUrl":"","backgroundColor":"#ffffff","textColor":"#000000","lock":{"move":true,"remove":true}} /-->';
        
        return $default_content;
    }
    
    return $content;
}
add_filter('default_content', 'mediapilote_preload_banniere_block_for_news', 10, 2);

/**
 * @snippet  Verrouiller le block Bannière dans les posts "news" via JavaScript
 * @author   Emmanuel Claude
 */
function mediapilote_enqueue_banniere_lock_script() {
    global $post;
    
    // Vérifier si on est dans l'éditeur d'un post "news"
    if (is_admin() && isset($post) && $post->post_type === 'news') {
        wp_enqueue_script(
            'mediapilote-lock-banniere',
            get_template_directory_uri() . '/js/lock-banniere-news.js',
            array('wp-blocks', 'wp-dom-ready', 'wp-data', 'wp-edit-post'),
            filemtime(get_template_directory() . '/js/lock-banniere-news.js'),
            true
        );
    }
}
add_action('enqueue_block_editor_assets', 'mediapilote_enqueue_banniere_lock_script');

/**
 * @snippet  Mises à jour via GitHub (dépôt public)
 * @author   Emmanuel Claude
 */
function mediapilote_github_theme_updater() {
    // Configuration
    $github_user = 'MediapiloteLAVAL';
    $github_repo = 'mediapilote';
    $theme_slug = 'mediapilote';
    
    add_filter('pre_set_site_transient_update_themes', function($transient) use ($github_user, $github_repo, $theme_slug) {
        if (empty($transient->checked)) {
            return $transient;
        }
        
        // Appel API GitHub (dépôt public - pas besoin de token)
        $remote = wp_remote_get(
            "https://api.github.com/repos/{$github_user}/{$github_repo}/releases/latest",
            array(
                'timeout' => 10,
                'headers' => array(
                    'Accept' => 'application/vnd.github.v3+json',
                    'User-Agent' => 'WordPress-Theme-Updater'
                )
            )
        );
        
        if (is_wp_error($remote)) {
            error_log('Erreur GitHub update: ' . $remote->get_error_message());
            return $transient;
        }
        
        $response_code = wp_remote_retrieve_response_code($remote);
        if (200 !== $response_code) {
            error_log('Code HTTP GitHub: ' . $response_code);
            return $transient;
        }
        
        $body = wp_remote_retrieve_body($remote);
        $release_data = json_decode($body);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            error_log('Erreur JSON GitHub: ' . json_last_error_msg());
            return $transient;
        }
        
        // Nettoyer le tag (enlever le 'v' si présent)
        $new_version = ltrim($release_data->tag_name, 'v');
        
        $theme_data = wp_get_theme($theme_slug);
        $current_version = $theme_data->get('Version');
        
        if (version_compare($current_version, $new_version, '<')) {
            $transient->response[$theme_slug] = array(
                'theme'       => $theme_slug,
                'new_version' => $new_version,
                'url'         => $release_data->html_url,
                'package'     => $release_data->zipball_url, // Pas de token pour dépôt public
            );
        }
        
        return $transient;
    });
}
add_action('init', 'mediapilote_github_theme_updater');

// Force la vérification des mises à jour
function mediapilote_force_github_check() {
    delete_site_transient('update_themes');
}
add_action('load-themes.php', 'mediapilote_force_github_check');
?>