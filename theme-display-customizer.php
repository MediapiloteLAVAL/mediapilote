<?php
/**
 * Personnalisation de l'affichage des mises à jour de thème
 * 
 * Ce script améliore l'affichage des notifications de mise à jour
 * pour montrer le vrai nom du thème au lieu de "mediapilote/style.css"
 * 
 * @author Emmanuel Claude / Mediapilote
 */

// Hook pour personnaliser l'affichage des mises à jour
add_action('admin_notices', 'mediapilote_custom_update_notice');
add_action('network_admin_notices', 'mediapilote_custom_update_notice');

function mediapilote_custom_update_notice() {
    if (get_template() !== 'mediapilote') {
        return;
    }
    
    $current_screen = get_current_screen();
    
    // Afficher seulement sur les pages pertinentes
    if (!in_array($current_screen->id, ['themes', 'appearance_page_mediapilote-updates'])) {
        return;
    }
    
    $update_themes = get_site_transient('update_themes');
    $theme_key = 'mediapilote/style.css';
    
    if (!$update_themes || !isset($update_themes->response[$theme_key])) {
        return;
    }
    
    $update_info = $update_themes->response[$theme_key];
    $theme = wp_get_theme('mediapilote');
    $theme_name = $theme->get('Name') ?: 'Aiko by Mediapilote';
    
    ?>
    <div class="notice notice-warning is-dismissible" style="border-left-color: #0073aa;">
        <p><strong>🎨 Mise à jour disponible pour <?php echo esc_html($theme_name); ?></strong></p>
        <p>
            Une nouvelle version (<strong><?php echo esc_html($update_info['new_version'] ?? 'inconnue'); ?></strong>) 
            est disponible pour votre thème <em><?php echo esc_html($theme_name); ?></em>.
        </p>
        <p>
            <a href="<?php echo admin_url('themes.php'); ?>" class="button button-primary">
                Gérer les mises à jour
            </a>
            <a href="<?php echo admin_url('themes.php?page=mediapilote-updates'); ?>" class="button">
                Informations détaillées
            </a>
        </p>
        <p><small><em>Développé par Mediapilote - Version actuelle : <?php echo esc_html($theme->get('Version')); ?></em></small></p>
    </div>
    <?php
}

// Personnaliser le texte dans la page des thèmes
add_filter('gettext', 'mediapilote_customize_update_text', 10, 3);

function mediapilote_customize_update_text($translated, $text, $domain) {
    if ($domain !== 'default' || get_template() !== 'mediapilote') {
        return $translated;
    }
    
    // Remplacer les textes par défaut
    $replacements = [
        'mediapilote/style.css' => 'Aiko by Mediapilote',
        'Theme updated successfully.' => 'Le thème Aiko by Mediapilote a été mis à jour avec succès !',
        'Update Theme' => 'Mettre à jour Aiko',
    ];
    
    foreach ($replacements as $original => $replacement) {
        if (strpos($text, $original) !== false) {
            $translated = str_replace($original, $replacement, $translated);
        }
    }
    
    return $translated;
}

// Ajouter du CSS pour améliorer l'affichage
add_action('admin_head', 'mediapilote_update_admin_css');

function mediapilote_update_admin_css() {
    if (get_template() !== 'mediapilote') {
        return;
    }
    
    ?>
    <style>
        /* Améliorer l'affichage des mises à jour de thème */
        .theme-browser .theme .theme-update-message {
            background: #0073aa;
            color: white;
            padding: 8px 12px;
            border-radius: 3px;
            margin-top: 8px;
        }
        
        .theme-browser .theme .theme-update-message a {
            color: white;
            text-decoration: underline;
        }
        
        /* Style pour notre thème spécifiquement */
        .theme-browser .theme[data-slug="mediapilote"] .theme-name {
            font-weight: 600;
            color: #0073aa;
        }
        
        /* Améliorer la visibilité du badge de mise à jour */
        .theme-browser .theme .update-message {
            background: #d63638;
            color: white;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
            position: absolute;
            top: 8px;
            right: 8px;
            z-index: 10;
        }
    </style>
    <?php
}

// JavaScript pour améliorer l'expérience utilisateur
add_action('admin_footer', 'mediapilote_update_admin_js');

function mediapilote_update_admin_js() {
    if (get_template() !== 'mediapilote') {
        return;
    }
    
    ?>
    <script>
        jQuery(document).ready(function($) {
            // Remplacer "mediapilote/style.css" par le vrai nom du thème
            $('.theme-browser .theme').each(function() {
                var $theme = $(this);
                
                if ($theme.data('slug') === 'mediapilote') {
                    // Améliorer l'affichage du nom
                    $theme.find('.theme-name').text('Aiko by Mediapilote');
                    
                    // Ajouter un badge si une mise à jour est disponible
                    if ($theme.hasClass('has-update')) {
                        $theme.find('.theme-screenshot').append(
                            '<div class="update-message">Mise à jour disponible</div>'
                        );
                    }
                }
            });
            
            // Améliorer les messages de mise à jour
            $('.theme-overlay .theme-actions').on('click', 'a', function() {
                var href = $(this).attr('href');
                if (href && href.indexOf('mediapilote') > -1) {
                    console.log('Mise à jour du thème Aiko by Mediapilote en cours...');
                }
            });
        });
    </script>
    <?php
}
?>