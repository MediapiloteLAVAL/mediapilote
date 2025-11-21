<?php
/**
 * Correctif spécifique pour la version actuelle dans les mises à jour
 */

add_action('admin_footer-update-core.php', function() {
    ?>
    <script>
    jQuery(document).ready(function($) {
        function fixCurrentVersion() {
            // Cibler spécifiquement le texte de version vide pour notre thème
            $('.update-themes-table tr, .theme-update-message').each(function() {
                var $row = $(this);
                
                // Vérifier si cette ligne concerne Aiko by Mediapilote
                if ($row.text().indexOf('Aiko by Mediapilote') !== -1) {
                    // Remplacer le texte de version vide
                    $row.find('*').contents().filter(function() {
                        return this.nodeType === 3 && 
                               this.textContent.indexOf('Vous utilisez la version .') !== -1;
                    }).each(function() {
                        this.textContent = this.textContent.replace(
                            'Vous utilisez la version .', 
                            'Vous utilisez la version 1.0.0.'
                        );
                    });
                    
                    // Alternative: modifier directement le HTML si nécessaire
                    var html = $row.html();
                    if (html.indexOf('Vous utilisez la version .') !== -1) {
                        html = html.replace('Vous utilisez la version .', 'Vous utilisez la version 1.0.0.');
                        $row.html(html);
                    }
                }
            });
            
            console.log('Version actuelle corrigée pour Aiko by Mediapilote');
        }
        
        // Exécuter après un délai pour s'assurer que le DOM est complètement chargé
        setTimeout(fixCurrentVersion, 100);
        setTimeout(fixCurrentVersion, 500);
        setTimeout(fixCurrentVersion, 1000);
    });
    </script>
    <style>
    /* S'assurer que les mises à jour de thème sont visibles */
    .update-themes-table {
        display: table !important;
    }
    </style>
    <?php
});
?>