/**
 * Script Frontend pour le Bloc Contact
 * Gestion AJAX du formulaire de contact
 */

(function($) {
    'use strict';

    // Gestionnaire de soumission du formulaire
    $(document).on('submit', '.contact-form', function(e) {
        e.preventDefault();

        var $form = $(this);
        var $messages = $form.find('.contact-form__messages');
        var $submitBtn = $form.find('button[type="submit"]');

        // Récupérer les données du formulaire
        var formData = {
            action: 'mediapilote_contact_form',
            nonce: $form.find('input[name="nonce"]').val(),
            contact_name: $form.find('input[name="contact_name"]').val(),
            contact_email: $form.find('input[name="contact_email"]').val(),
            contact_message: $form.find('textarea[name="contact_message"]').val()
        };

        // Validation côté client
        if (!formData.contact_name || !formData.contact_email || !formData.contact_message) {
            showMessage($messages, 'error', 'Veuillez remplir tous les champs.');
            return;
        }

        // Validation email
        var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(formData.contact_email)) {
            showMessage($messages, 'error', 'Adresse email invalide.');
            return;
        }

        // Désactiver le bouton et ajouter une classe de chargement
        $submitBtn.prop('disabled', true);
        $form.addClass('is-loading');
        $messages.hide();

        // Envoyer la requête AJAX
        $.ajax({
            url: mediapiloteContact.ajaxurl,
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    showMessage($messages, 'success', response.data.message);
                    $form[0].reset(); // Réinitialiser le formulaire
                } else {
                    showMessage($messages, 'error', response.data.message);
                }
            },
            error: function() {
                showMessage($messages, 'error', 'Une erreur est survenue. Veuillez réessayer.');
            },
            complete: function() {
                $submitBtn.prop('disabled', false);
                $form.removeClass('is-loading');
            }
        });
    });

    /**
     * Afficher un message
     */
    function showMessage($container, type, message) {
        $container
            .removeClass('success error')
            .addClass(type)
            .html(message)
            .fadeIn(300);

        // Masquer le message après 5 secondes
        setTimeout(function() {
            $container.fadeOut(300);
        }, 5000);
    }

    // Animation des champs au focus
    $('.contact-form__input, .contact-form__textarea').on('focus', function() {
        $(this).parent().addClass('is-focused');
    }).on('blur', function() {
        if (!$(this).val()) {
            $(this).parent().removeClass('is-focused');
        }
    });

})(jQuery);
