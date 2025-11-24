/**
 * JavaScript Frontend pour le bloc Chiffres Clés
 * Gère l'effet d'incrémentation des chiffres avec Intersection Observer
 *
 * @package MediaPilote
 * @since 1.0.0
 */

(function ($) {
  "use strict";

  /**
   * Fonction d'incrémentation d'un nombre
   */
  function animateCounter(element, target, duration = 2000) {
    const start = 0;
    const startTime = performance.now();

    // Extraire seulement les chiffres du target pour l'animation
    const numericValue = parseInt(target.replace(/[^0-9]/g, "")) || 0;

    function updateCounter(currentTime) {
      const elapsed = currentTime - startTime;
      const progress = Math.min(elapsed / duration, 1);

      // Utiliser une fonction d'easing pour un effet plus naturel
      const easedProgress = easeOutCubic(progress);
      const currentValue = Math.floor(
        start + (numericValue - start) * easedProgress
      );

      // Remplacer les chiffres dans la chaîne originale
      const displayValue = target.replace(/\d+/, currentValue);
      element.textContent = displayValue;

      if (progress < 1) {
        requestAnimationFrame(updateCounter);
      } else {
        element.textContent = target; // S'assurer que la valeur finale est exacte
      }
    }

    requestAnimationFrame(updateCounter);
  }

  /**
   * Fonction d'easing cubique
   */
  function easeOutCubic(t) {
    return 1 - Math.pow(1 - t, 3);
  }

  /**
   * Initialiser l'animation des compteurs
   */
  function initCounterAnimation() {
    const counters = document.querySelectorAll(".bloc-chiffres-cles__number");

    if (counters.length === 0) return;

    // Créer un Intersection Observer pour déclencher l'animation quand l'élément devient visible
    const observerOptions = {
      threshold: 0.3, // Déclencher quand 30% de l'élément est visible
      rootMargin: "0px 0px -50px 0px", // Décaler légèrement le point de déclenchement
    };

    const observer = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (
          entry.isIntersecting &&
          !entry.target.classList.contains("animated")
        ) {
          const element = entry.target;
          const target = element.getAttribute("data-target");

          if (target) {
            // Marquer comme animé pour éviter les répétitions
            element.classList.add("animated");

            // Démarrer l'animation avec un petit délai pour l'effet visuel
            setTimeout(function () {
              animateCounter(element, target, 2000);
            }, 200);
          }

          // Arrêter d'observer cet élément après l'animation
          observer.unobserve(element);
        }
      });
    }, observerOptions);

    // Observer tous les éléments de chiffres
    counters.forEach(function (counter) {
      observer.observe(counter);
    });
  }

  /**
   * Initialiser l'animation des éléments au chargement
   */
  function initElementsAnimation() {
    const items = document.querySelectorAll(".bloc-chiffres-cles__item");

    if (items.length === 0) return;

    const observerOptions = {
      threshold: 0.1,
      rootMargin: "0px 0px -20px 0px",
    };

    const observer = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry, index) {
        if (
          entry.isIntersecting &&
          !entry.target.classList.contains("animate-in")
        ) {
          const item = entry.target;

          // Ajouter un délai progressif pour chaque élément
          setTimeout(function () {
            item.classList.add("animate-in");

            // Animer les sous-éléments
            const number = item.querySelector(".bloc-chiffres-cles__number");
            const icon = item.querySelector(".bloc-chiffres-cles__icon");
            const label = item.querySelector(".bloc-chiffres-cles__label");

            if (number) number.style.animationDelay = "0s";
            if (icon) icon.style.animationDelay = "0.1s";
            if (label) label.style.animationDelay = "0.2s";
          }, index * 150); // Délai progressif de 150ms entre chaque élément

          observer.unobserve(item);
        }
      });
    }, observerOptions);

    items.forEach(function (item) {
      observer.observe(item);
    });
  }

  /**
   * Fonction de validation des chiffres
   */
  function isNumericValue(value) {
    return /\d/.test(value);
  }

  /**
   * Fonction de retry pour les éléments chargés dynamiquement
   */
  function retryInit(maxAttempts = 5, currentAttempt = 1) {
    const counters = document.querySelectorAll(".bloc-chiffres-cles__number");

    if (counters.length > 0) {
      initCounterAnimation();
      initElementsAnimation();
    } else if (currentAttempt < maxAttempts) {
      setTimeout(function () {
        retryInit(maxAttempts, currentAttempt + 1);
      }, 500);
    }
  }

  /**
   * Initialisation principale
   */
  function init() {
    // Vérifier si Intersection Observer est supporté
    if (!("IntersectionObserver" in window)) {
      // Fallback pour les navigateurs plus anciens
      console.log("IntersectionObserver non supporté, utilisation du fallback");

      // Animer immédiatement tous les compteurs
      const counters = document.querySelectorAll(".bloc-chiffres-cles__number");
      counters.forEach(function (counter) {
        const target = counter.getAttribute("data-target");
        if (target && isNumericValue(target)) {
          setTimeout(function () {
            animateCounter(counter, target, 2000);
          }, 500);
        }
      });
      return;
    }

    // Initialisation normale avec Intersection Observer
    retryInit();
  }

  // Initialiser au chargement du DOM
  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", init);
  } else {
    init();
  }

  // Réinitialiser si de nouveaux blocs sont ajoutés dynamiquement (pour les thèmes Ajax)
  if (typeof MutationObserver !== "undefined") {
    const bodyObserver = new MutationObserver(function (mutations) {
      mutations.forEach(function (mutation) {
        if (mutation.type === "childList" && mutation.addedNodes.length > 0) {
          // Vérifier s'il y a de nouveaux blocs chiffres clés
          const hasNewCounters = Array.from(mutation.addedNodes).some(function (
            node
          ) {
            return (
              node.nodeType === Node.ELEMENT_NODE &&
              (node.classList.contains("bloc-chiffres-cles") ||
                node.querySelector(".bloc-chiffres-cles"))
            );
          });

          if (hasNewCounters) {
            setTimeout(init, 100);
          }
        }
      });
    });

    bodyObserver.observe(document.body, {
      childList: true,
      subtree: true,
    });
  }

  // Export pour usage externe si nécessaire
  window.MediaPiloteChiffresCles = {
    init: init,
    animateCounter: animateCounter,
  };
})(
  jQuery ||
    window.jQuery ||
    function (selector) {
      // Fallback minimal si jQuery n'est pas disponible
      if (typeof selector === "function") {
        if (document.readyState === "loading") {
          document.addEventListener("DOMContentLoaded", selector);
        } else {
          selector();
        }
      }
      return {
        ready: function (fn) {
          if (document.readyState === "loading") {
            document.addEventListener("DOMContentLoaded", fn);
          } else {
            fn();
          }
        },
      };
    }
);
