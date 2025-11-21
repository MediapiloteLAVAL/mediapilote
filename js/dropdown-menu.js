/**
 * Gestion des dropdowns pour les sous-menus de navigation
 * Améliore l'expérience utilisateur sur desktop et mobile
 */

document.addEventListener('DOMContentLoaded', function() {
    // Sélectionner tous les éléments de menu avec des sous-menus
    const menuItemsWithChildren = document.querySelectorAll('.menu-item-has-children');
    
    menuItemsWithChildren.forEach(function(menuItem) {
        const link = menuItem.querySelector('a');
        const subMenu = menuItem.querySelector('.sub-menu');
        
        if (!link || !subMenu) return;
        
        // Gestion du hover sur desktop
        let hoverTimeout;
        
        menuItem.addEventListener('mouseenter', function() {
            clearTimeout(hoverTimeout);
            // Ajouter une classe pour l'animation
            subMenu.classList.add('dropdown-visible');
        });
        
        menuItem.addEventListener('mouseleave', function() {
            hoverTimeout = setTimeout(function() {
                subMenu.classList.remove('dropdown-visible');
            }, 150); // Délai pour éviter la fermeture trop rapide
        });
        
        // Gestion du focus pour l'accessibilité
        link.addEventListener('focus', function() {
            subMenu.classList.add('dropdown-visible');
        });
        
        // Gestion du clic sur mobile pour ouvrir/fermer
        if (window.innerWidth <= 768) {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                subMenu.classList.toggle('dropdown-visible');
                
                // Fermer les autres sous-menus ouverts
                const otherSubMenus = document.querySelectorAll('.sub-menu.dropdown-visible');
                otherSubMenus.forEach(function(otherSubMenu) {
                    if (otherSubMenu !== subMenu) {
                        otherSubMenu.classList.remove('dropdown-visible');
                    }
                });
            });
        }
        
        // Fermer le dropdown si on clique ailleurs
        document.addEventListener('click', function(e) {
            if (!menuItem.contains(e.target)) {
                subMenu.classList.remove('dropdown-visible');
            }
        });
    });
    
    // Gestion du redimensionnement de la fenêtre
    window.addEventListener('resize', function() {
        const isMobile = window.innerWidth <= 768;
        const subMenus = document.querySelectorAll('.sub-menu');
        
        subMenus.forEach(function(subMenu) {
            if (isMobile) {
                subMenu.classList.remove('dropdown-visible');
            }
        });
    });
    
    // Amélioration de l'accessibilité avec les touches clavier
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const openSubMenus = document.querySelectorAll('.sub-menu.dropdown-visible');
            openSubMenus.forEach(function(subMenu) {
                subMenu.classList.remove('dropdown-visible');
            });
        }
    });
});
