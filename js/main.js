/*************************************/
/*************************************/
/*************************************/
/* Classe responsive */
/*************************************/
/*************************************/
/*************************************/

function updateResponsiveClass() {
  if (window.innerWidth < 1000) {
    jQuery("body").addClass("responsive");
  } else {
    jQuery("body").removeClass("responsive");
  }
}

// Initialiser la classe responsive au chargement de la page
jQuery(document).ready(function ($) {
  updateResponsiveClass();

  // Ajouter un écouteur d'événement pour le redimensionnement de la fenêtre
  jQuery(window).on("resize", function () {
    updateResponsiveClass();
  });

  // Effet de scroll sur le header
  function handleHeaderScroll() {
    var $header = $('.site-header');
    var headerHeight = $header.outerHeight();
    var scrollPosition = $(window).scrollTop();
    
    if (scrollPosition >= headerHeight) {
      $header.addClass('scrolled');
    } else {
      $header.removeClass('scrolled');
    }
  }

  // Initialiser l'état du header au chargement
  handleHeaderScroll();

  // Écouter l'événement de scroll
  $(window).on('scroll', handleHeaderScroll);

  // Menu Burger Toggle
  $('.menu-burger-icon').on('click', function() {
    $(this).toggleClass('active');
    $('.header-right-menu').toggleClass('burger-menu-open');
    $('.burger-menu-overlay').toggleClass('active');
    $('body').toggleClass('burger-menu-visible');
  });

  // Fermer le menu burger en cliquant sur l'overlay
  $('.burger-menu-overlay').on('click', function() {
    $('.menu-burger-icon').removeClass('active');
    $('.header-right-menu').removeClass('burger-menu-open');
    $(this).removeClass('active');
    $('body').removeClass('burger-menu-visible');
  });

  // Sticky menu selon la classe ajoutée dynamiquement
  if ($('.site-header').hasClass('menu-sticky')) {
    var header = $('.site-header');
    var headerHeight = header.outerHeight();
    // Si menu superposé, ne pas ajouter l'offset
    if (!$('.site-header').hasClass('menu-overlay')) {
      if (!$('#masthead').hasClass('has-sticky-offset')) {
        $('#masthead').addClass('has-sticky-offset');
        $('<div class="header-offset"></div>').height(headerHeight).insertAfter(header);
      }
    } else {
      // Si menu superposé, retirer l'offset si présent
      $('#masthead .header-offset').remove();
      $('#masthead').removeClass('has-sticky-offset');
    }
  } else {
    // Nettoyage si sticky désactivé
    $('#masthead .header-offset').remove();
    $('#masthead').removeClass('has-sticky-offset');
  }

  // Gestion de la bannière avec localStorage
  // Vérifier si la bannière a été fermée
  if (localStorage.getItem('banner_dismissed') === 'true') {
    $('body').addClass('banner-dismissed');
  }

  // Bouton de fermeture de la bannière
  $('.banner-close').on('click', function() {
    $('.site-banner').fadeOut(300, function() {
      $('body').addClass('banner-dismissed');
      localStorage.setItem('banner_dismissed', 'true');
    });
  });
});

/*************************************/
/*************************************/
/*************************************/
/* Nouvel onglet pour les liens externes */
/*************************************/
/*************************************/
/************************************/

function targetBlank() {
  let internal = location.host.replace("www.", "");
  internal = new RegExp(internal, "i");
  let a = document.getElementsByTagName("a");
  let links = document.querySelectorAll("body a");
  links.forEach((link) => {
    let linkHref = link.getAttribute("href");
    if (linkHref && linkHref.indexOf(".pdf") != -1) {
      link.setAttribute("target", "_blank");
    }
    if (link.hostname != location.hostname) {
      a.rel = "noopener";
      a.target = "_blank";
      link.setAttribute("target", "_blank");
      link.setAttribute("rel", "noopener");
    }
  });
}
targetBlank();



//************************************************************//

//************************************************************//
//************************************************************//
//************************************************************//
//************************************************************//
//______________Hauteur fixe pour tous les sous-menus_______________//
//************************************************************//
//************************************************************//
//************************************************************//
//************************************************************//

// document.addEventListener('DOMContentLoaded', function() {
//   // Sélectionnez toutes les balises avec la classe .sub-menu dans .menu-responsive
//   var subMenus = document.querySelectorAll('.menu-responsive .sub-menu');

//   // Parcourez chaque sous-menu
//   subMenus.forEach(function(subMenu) {
//       // Calculer la hauteur du sous-menu
//       var subMenuHeight = subMenu.scrollHeight;

//       // Appliquer la hauteur en tant que style CSS inline
//       subMenu.style.height = subMenuHeight + 'px';
//   });
// });

/*************************************/
/*************************************/
/*************************************/
/* Espacer les numéros de tel */
/*************************************/
/*************************************/
/*************************************/

document.addEventListener("DOMContentLoaded", function () {
  const telephoneElements = document.querySelectorAll(".telephone");

  telephoneElements.forEach(function (element) {
    const originalText = element.textContent;
    const formattedText = originalText.replace(/(\d{2})(?=\d)/g, "$1 ");
    element.textContent = formattedText;
  });
});


// Ajouter des wrappers aux sous-menus
function addSubmenuWrappers() {
  const subMenus = document.querySelectorAll('.header-left-menu .sub-menu');
  
  subMenus.forEach(function(subMenu) {
      // Vérifier si le wrapper existe déjà
      if (!subMenu.querySelector('.submenu-wrapper')) {
          // Créer le wrapper principal
          const wrapper = document.createElement('div');
          wrapper.className = 'submenu-wrapper align80';
          
          // Créer le conteneur d'image
          const imageContainer = document.createElement('div');
          imageContainer.className = 'submenu-image';
          
          // Créer le conteneur des liens
          const linksContainer = document.createElement('div');
          linksContainer.className = 'submenu-links';
          
          // Déplacer tous les li dans le conteneur des liens
          const listItems = Array.from(subMenu.children);
          listItems.forEach(function(li) {
              linksContainer.appendChild(li);
          });
          
          // Assembler la structure : wrapper > (image + links)
          wrapper.appendChild(imageContainer);
          wrapper.appendChild(linksContainer);
          
          // Ajouter le wrapper au sous-menu
          subMenu.appendChild(wrapper);
        }
  });
}

// Appeler la fonction au chargement
document.addEventListener('DOMContentLoaded', addSubmenuWrappers);

/*************************************/
/*************************************/
/*************************************/
/* Gestionnaire pour PolitiqueConfidentialite */
/*************************************/
/*************************************/
/*************************************/

// Gestionnaire pour ouvrir le panneau Tarteaucitron au clic sur .PolitiqueConfidentialite
document.addEventListener('click', function(e) {
    // Vérifier si le clic est sur un élément avec la classe .PolitiqueConfidentialite
    // ou sur un de ses enfants (liens, etc.)
    const clickedElement = e.target.closest('.PolitiqueConfidentialite');
    
    if (clickedElement) {
        e.preventDefault();
        e.stopPropagation();
        
        // Fonction pour ouvrir le panneau Tarteaucitron
        function openTarteaucitronPanel() {
            // Essayer d'abord de cliquer sur l'icône Tarteaucitron si elle existe
            const tarteaucitronIcon = document.getElementById('tarteaucitronManager');
            if (tarteaucitronIcon) {
                tarteaucitronIcon.click();
                return true;
            }
            
            // Sinon, utiliser directement la méthode openPanel si disponible
            if (typeof tarteaucitron !== 'undefined' && tarteaucitron.openPanel) {
                tarteaucitron.openPanel();
                return true;
            }
            
            return false;
        }
        
        // Si Tarteaucitron n'est pas encore chargé, attendre qu'il le soit
        if (typeof tarteaucitron === 'undefined') {
            // Attendre que Tarteaucitron soit disponible (maximum 5 secondes)
            let attempts = 0;
            const maxAttempts = 50; // 50 * 100ms = 5 secondes
            
            function waitForTarteaucitron() {
                if (typeof tarteaucitron !== 'undefined' && tarteaucitron.openPanel) {
                    openTarteaucitronPanel();
                } else if (attempts < maxAttempts) {
                    attempts++;
                    setTimeout(waitForTarteaucitron, 100);
                }
            }
            
            waitForTarteaucitron();
        } else {
            // Tarteaucitron est déjà chargé, ouvrir directement le panneau
            openTarteaucitronPanel();
        }
    }
});