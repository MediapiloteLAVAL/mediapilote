/**
 * Gestion du menu burger responsive
 */
document.addEventListener('DOMContentLoaded', function() {
    // Gestion des sous-menus dans le menu burger
    const burgerMenuItems = document.querySelectorAll('.burger-menu-list .menu-item-has-children > a');
    
    burgerMenuItems.forEach(function(menuItem) {
        menuItem.addEventListener('click', function(e) {
            e.preventDefault();
            
            const parentLi = this.parentElement;
            const subMenu = parentLi.querySelector('.sub-menu');
            
            // Toggle de la classe active
            parentLi.classList.toggle('active');
            
            // Animation du sous-menu
            if (subMenu) {
                if (parentLi.classList.contains('active')) {
                    subMenu.style.display = 'block';
                    subMenu.style.opacity = '0';
                    subMenu.style.transform = 'translateY(-10px)';
                    
                    setTimeout(() => {
                        subMenu.style.opacity = '1';
                        subMenu.style.transform = 'translateY(0)';
                    }, 10);
                } else {
                    subMenu.style.opacity = '0';
                    subMenu.style.transform = 'translateY(-10px)';
                    
                    setTimeout(() => {
                        subMenu.style.display = 'none';
                    }, 300);
                }
            }
        });
    });
    
    // Fermer le menu en cliquant sur l'overlay (sauf sur le contenu)
    const burgerOverlay = document.querySelector('.burger-menu-overlay');
    const burgerContent = document.querySelector('.burger-menu-content');
    
    if (burgerOverlay && burgerContent) {
        burgerOverlay.addEventListener('click', function(e) {
            if (e.target === burgerOverlay) {
                closeBurgerMenu();
            }
        });
    }
    
    // Fermer le menu avec la touche Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeBurgerMenu();
        }
    });
    
    // Fonction pour fermer le menu burger
    function closeBurgerMenu() {
        const burgerButton = document.querySelector('.menu-burger');
        const body = document.body;
        
        if (burgerButton && body.classList.contains('menu-open')) {
            burgerButton.classList.remove('opened');
            burgerButton.setAttribute('aria-expanded', 'false');
            body.classList.remove('menu-open');
            
            // Fermer tous les sous-menus ouverts
            const openSubMenus = document.querySelectorAll('.burger-menu-list .menu-item-has-children.active');
            openSubMenus.forEach(function(item) {
                item.classList.remove('active');
                const subMenu = item.querySelector('.sub-menu');
                if (subMenu) {
                    subMenu.style.display = 'none';
                }
            });
        }
    }
    
    // Gestion du redimensionnement de la fenêtre
    window.addEventListener('resize', function() {
        if (window.innerWidth > 1279) {
            closeBurgerMenu();
        }
    });
});
