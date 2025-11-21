/**
 * Verrouiller le block Bannière dans les posts "news"
 * 
 * @package MediaPilote
 * @since 1.0.0
 */

(function() {
    // Attendre que l'éditeur soit prêt
    wp.domReady(function() {
        // Obtenir les blocks de l'éditeur
        const { select, dispatch } = wp.data;
        const { getBlocks } = select('core/block-editor');
        const { updateBlockAttributes } = dispatch('core/block-editor');
        
        // Fonction pour verrouiller tous les blocks Bannière
        function lockBanniereBlocks() {
            const blocks = getBlocks();
            
            blocks.forEach(function(block) {
                if (block.name === 'mediapilote/banniere') {
                    // Vérifier si le block n'est pas déjà verrouillé
                    if (!block.attributes.lock || !block.attributes.lock.remove) {
                        updateBlockAttributes(block.clientId, {
                            lock: {
                                move: true,
                                remove: true
                            }
                        });
                    }
                }
            });
        }
        
        // Verrouiller immédiatement
        lockBanniereBlocks();
        
        // Observer les changements de blocks (ajout/suppression)
        wp.data.subscribe(function() {
            lockBanniereBlocks();
        });
    });
})();
