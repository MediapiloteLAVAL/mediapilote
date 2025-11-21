<?php
/**
 * SOLUTION SÛRE - Remplacement ciblé uniquement pour l'affichage des noms de thème
 * Sans output buffering qui peut casser les pages
 */

// Hook sécurisé pour modifier seulement l'affichage du nom dans les listes de thèmes
add_filter('wp_prepare_themes_for_js', function($prepared_themes) {
    if (isset($prepared_themes['mediapilote'])) {
        $prepared_themes['mediapilote']['name'] = 'Aiko by Mediapilote';
        $prepared_themes['mediapilote']['title'] = 'Aiko by Mediapilote';
    }
    return $prepared_themes;
});

// Hook pour les mises à jour dans le tableau de bord
add_filter('pre_set_site_transient_update_themes', function($transient) {
    if (!empty($transient->response['mediapilote'])) {
        if (is_array($transient->response['mediapilote'])) {
            $transient->response['mediapilote']['theme'] = 'Aiko by Mediapilote';
            $transient->response['mediapilote']['name'] = 'Aiko by Mediapilote';
        } elseif (is_object($transient->response['mediapilote'])) {
            $transient->response['mediapilote']->theme = 'Aiko by Mediapilote';
            $transient->response['mediapilote']->name = 'Aiko by Mediapilote';
        }
    }
    return $transient;
});

// Hook après la vérification des mises à jour
add_filter('site_transient_update_themes', function($transient) {
    if (!empty($transient->response['mediapilote'])) {
        if (is_array($transient->response['mediapilote'])) {
            $transient->response['mediapilote']['theme'] = 'Aiko by Mediapilote';
            $transient->response['mediapilote']['name'] = 'Aiko by Mediapilote';
        } elseif (is_object($transient->response['mediapilote'])) {
            $transient->response['mediapilote']->theme = 'Aiko by Mediapilote';
            $transient->response['mediapilote']->name = 'Aiko by Mediapilote';
        }
    }
    return $transient;
});

// JavaScript robuste pour remplacer l'affichage
add_action('admin_footer', function() {
    $screen = get_current_screen();
    if (!$screen || !in_array($screen->id, ['themes', 'update-core'])) {
        return;
    }
    ?>
    <script>
    jQuery(function($) {
        function replaceThemeName() {
            // Remplacer dans tout le contenu textuel
            $('*').contents().filter(function() {
                return this.nodeType === 3 && 
                       (this.textContent.indexOf('mediapilote/style.css') !== -1 || 
                        this.textContent.indexOf('mediapilote') !== -1);
            }).each(function() {
                var text = this.textContent;
                text = text.replace(/mediapilote\/style\.css/g, 'Aiko by Mediapilote');
                text = text.replace(/Sélectionner mediapilote/g, 'Sélectionner Aiko by Mediapilote');
                this.textContent = text;
            });
            
            // Remplacer dans les labels et options
            $('label, option, .theme-name, .update-message, h2, h3').each(function() {
                var $el = $(this);
                var text = $el.text();
                if (text.indexOf('mediapilote') !== -1) {
                    text = text.replace(/mediapilote\/style\.css/g, 'Aiko by Mediapilote');
                    text = text.replace(/mediapilote/g, 'Aiko by Mediapilote');
                    $el.text(text);
                }
            });
            
            // Correction spécifique pour la version vide
            $('.update-message, .theme-update-message').each(function() {
                var $el = $(this);
                var text = $el.html();
                
                // Remplacer "Vous utilisez la version ." par la vraie version
                if (text.indexOf('Vous utilisez la version .') !== -1) {
                    text = text.replace('Vous utilisez la version .', 'Vous utilisez la version 1.0.0.');
                    $el.html(text);
                }
                
                // Remplacer "You are using version ." par la vraie version (si en anglais)
                if (text.indexOf('You are using version .') !== -1) {
                    text = text.replace('You are using version .', 'You are using version 1.0.0.');
                    $el.html(text);
                }
            });
        }
        
        // Exécuter immédiatement
        replaceThemeName();
        
        // Exécuter après chargement
        setTimeout(replaceThemeName, 100);
        setTimeout(replaceThemeName, 500);
        setTimeout(replaceThemeName, 1000);
        
        // Observer les changements DOM pour les requêtes AJAX
        if (window.MutationObserver) {
            var observer = new MutationObserver(function(mutations) {
                var shouldReplace = false;
                mutations.forEach(function(mutation) {
                    if (mutation.type === 'childList' && mutation.addedNodes.length > 0) {
                        shouldReplace = true;
                    }
                });
                if (shouldReplace) {
                    setTimeout(replaceThemeName, 100);
                }
            });
            observer.observe(document.body, { childList: true, subtree: true });
        }
    });
    </script>
    <?php
});

// Hook simple pour les textes traduits
add_filter('gettext', function($translation, $text, $domain) {
    if ($text === 'mediapilote' || $text === 'mediapilote/style.css') {
        return 'Aiko by Mediapilote';
    }
    return $translation;
}, 10, 3);

// Hook spécifique pour corriger le message de version
add_filter('gettext', function($translation, $text, $domain) {
    // Corriger le message de version vide
    if (strpos($translation, 'Vous utilisez la version .') !== false) {
        return str_replace('Vous utilisez la version .', 'Vous utilisez la version 1.0.0.', $translation);
    }
    if (strpos($translation, 'You are using version .') !== false) {
        return str_replace('You are using version .', 'You are using version 1.0.0.', $translation);
    }
    return $translation;
}, 20, 3);
?>