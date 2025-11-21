<?php
/**
 * Contournement du système de backup WordPress pour les mises à jour de thèmes
 * À utiliser uniquement en développement local
 */

if (!defined('ABSPATH')) {
    exit;
}

// Hook pour modifier le processus de mise à jour des thèmes
add_filter('upgrader_pre_install', function($result, $hook_extra) {
    global $wp_filesystem;
    
    // Seulement pour les mises à jour de thèmes
    if (isset($hook_extra['theme']) && $hook_extra['theme'] === 'mediapilote') {
        
        // Initialiser le système de fichiers
        if (!WP_Filesystem()) {
            return new WP_Error('fs_unavailable', 'Could not access filesystem.');
        }
        
        $theme_root = get_theme_root();
        $theme_dir = $theme_root . '/mediapilote';
        
        // Si le thème existe, le supprimer directement au lieu de le déplacer
        if ($wp_filesystem->exists($theme_dir)) {
            if (!$wp_filesystem->delete($theme_dir, true)) {
                return new WP_Error('remove_old_failed', 'Could not remove the old theme.');
            }
        }
        
        // Retourner true pour indiquer que la préparation est terminée
        return true;
    }
    
    return $result;
}, 10, 2);

// Hook pour nettoyer après la mise à jour
add_action('upgrader_process_complete', function($upgrader, $hook_extra) {
    // Nettoyer le cache après la mise à jour du thème
    if (isset($hook_extra['theme']) && $hook_extra['theme'] === 'mediapilote') {
        delete_site_transient('update_themes');
        wp_clean_themes_cache();
    }
}, 10, 2);
?>