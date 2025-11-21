// Slider frontend (changement d'image de fond avec transition et autoplay)
document.addEventListener('DOMContentLoaded', function() {
    var sliders = document.querySelectorAll('.hero-banner__slider');
    sliders.forEach(function(slider) {
        var currentContainer = slider.querySelector('.hero-banner__container--current');
        var nextContainer = slider.querySelector('.hero-banner__container--next');
        var navLines = slider.querySelectorAll('.hero-banner__line[data-slide-nav]');
        var images = JSON.parse(slider.getAttribute('data-images'));
        var current = 0;
        var autoplayInterval;

        function showSlide(index) {
            if (images[index]) {
                nextContainer.style.backgroundImage = 'url(' + images[index] + ')';
                nextContainer.style.opacity = '1';
                setTimeout(function() {
                    currentContainer.style.backgroundImage = 'url(' + images[index] + ')';
                    nextContainer.style.opacity = '0';
                }, 500); // Durée de la transition
            }
            navLines.forEach(function(line, i) {
                line.classList.toggle('is-active', i === index);
            });
            current = index;
        }

        function nextSlide() {
            var nextIndex = (current + 1) % images.length;
            showSlide(nextIndex);
        }

        function startAutoplay() {
            autoplayInterval = setInterval(nextSlide, 5000); // Changement toutes les 5 secondes
        }

        function stopAutoplay() {
            clearInterval(autoplayInterval);
        }

        navLines.forEach(function(line, i) {
            line.addEventListener('click', function() {
                stopAutoplay();
                showSlide(i);
                startAutoplay(); // Redémarrer l'autoplay après interaction manuelle
            });
        });

        // Initialisation
        showSlide(0);
        startAutoplay();

        // Pause autoplay au survol
        slider.addEventListener('mouseenter', stopAutoplay);
        slider.addEventListener('mouseleave', startAutoplay);
    });
});