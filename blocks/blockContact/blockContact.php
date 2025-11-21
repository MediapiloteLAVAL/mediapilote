<?php
/**
 * Bloc Contact
 * Bloc Gutenberg natif FSE pour afficher un formulaire de contact maison
 *
 * @package MediaPilote
 * @since 1.0.0
 */

// Empêcher l'accès direct
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Enregistrer le bloc Contact
 */
function mediapilote_register_block_contact() {
    // Vérifier si la fonction existe
    if (!function_exists('register_block_type')) {
        return;
    }

    // Enregistrer le script du bloc
    wp_register_script(
        'mediapilote-block-contact-editor',
        get_template_directory_uri() . '/blocks/blockContact/block.js',
        array('wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-i18n'),
        filemtime(get_template_directory() . '/blocks/blockContact/block.js')
    );

    // Enregistrer le style du bloc (frontend et editor)
    wp_register_style(
        'mediapilote-block-contact-style',
        get_template_directory_uri() . '/blocks/blockContact/style.css',
        array('utilitary'),
        filemtime(get_template_directory() . '/blocks/blockContact/style.css')
    );

    // Enregistrer le style de l'éditeur
    wp_register_style(
        'mediapilote-block-contact-editor-style',
        get_template_directory_uri() . '/blocks/blockContact/editor.css',
        array('wp-edit-blocks'),
        filemtime(get_template_directory() . '/blocks/blockContact/editor.css')
    );

    // Enregistrer le script frontend pour AJAX
    wp_register_script(
        'mediapilote-block-contact-frontend',
        get_template_directory_uri() . '/blocks/blockContact/frontend.js',
        array('jquery'),
        filemtime(get_template_directory() . '/blocks/blockContact/frontend.js'),
        true
    );

    // Passer les variables AJAX au script
    wp_localize_script('mediapilote-block-contact-frontend', 'mediapiloteContact', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('mediapilote_contact_nonce')
    ));

    // Enregistrer le bloc
    register_block_type('mediapilote/contact', array(
        'editor_script' => 'mediapilote-block-contact-editor',
        'style' => 'mediapilote-block-contact-style',
        'editor_style' => 'mediapilote-block-contact-editor-style',
        'script' => 'mediapilote-block-contact-frontend',
        'render_callback' => 'mediapilote_render_block_contact',
        'attributes' => array(
            'title' => array(
                'type' => 'string',
                'default' => 'Lorem ipsum'
            ),
            'subtitle' => array(
                'type' => 'string',
                'default' => 'Loren - H3 - The quick brown'
            ),
            'description' => array(
                'type' => 'string',
                'default' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.'
            ),
            'backgroundColor' => array(
                'type' => 'string',
                'default' => '#ffffff'
            ),
            'textColor' => array(
                'type' => 'string',
                'default' => '#2D3037'
            ),
            'imageId' => array(
                'type' => 'number',
                'default' => 0
            ),
            'imageUrl' => array(
                'type' => 'string',
                'default' => ''
            ),
            'align' => array(
                'type' => 'string',
                'default' => 'full'
            )
        ),
        'supports' => array(
            'align' => array('full'),
            'html' => false
        )
    ));
}
add_action('init', 'mediapilote_register_block_contact');

/**
 * Rendu du bloc côté frontend
 */
function mediapilote_render_block_contact($attributes) {
    $title = isset($attributes['title']) ? $attributes['title'] : 'Lorem ipsum';
    $subtitle = isset($attributes['subtitle']) ? $attributes['subtitle'] : 'Loren - H3 - The quick brown';
    $description = isset($attributes['description']) ? $attributes['description'] : '';
    $background_color = isset($attributes['backgroundColor']) ? $attributes['backgroundColor'] : '#ffffff';
    $text_color = isset($attributes['textColor']) ? $attributes['textColor'] : '#2D3037';
    $image_url = isset($attributes['imageUrl']) ? $attributes['imageUrl'] : '';

    $unique_id = 'contact-form-' . uniqid();

    ob_start();
    ?>
    <div class="wp-block-mediapilote-contact contact-section alignfull" style="background-color: <?php echo esc_attr($background_color); ?>; color: <?php echo esc_attr($text_color); ?>;">
        <div class="container-fluid">
            <div class="contact-section__wrapper">
                <!-- Colonne gauche : Image et texte -->
                <div class="contact-section__left">
                    <?php if (!empty($title)) : ?>
                        <h1 class="contact-section__title"><?php echo wp_kses_post($title); ?></h1>
                    <?php endif; ?>
                    
                    <div class="contact-section__content">
                        <?php if (!empty($subtitle)) : ?>
                            <h3 class="contact-section__subtitle"><?php echo wp_kses_post($subtitle); ?></h3>
                        <?php endif; ?>
                        
                        <?php if (!empty($description)) : ?>
                            <div class="contact-section__description">
                                <p><?php echo wp_kses_post($description); ?></p>
                            </div>
                        <?php endif; ?>
                    </div>

                    <?php if (!empty($image_url)) : ?>
                        <div class="contact-section__image">
                            <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($title); ?>" />
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Colonne droite : Formulaire -->
                <div class="contact-section__right">
                    <form id="<?php echo esc_attr($unique_id); ?>" class="contact-form" method="post" action="">
                        <div class="contact-form__messages" style="display: none;"></div>
                        
                        <div class="contact-form__field">
                            <input 
                                type="text" 
                                name="contact_name" 
                                id="contact_name_<?php echo esc_attr($unique_id); ?>" 
                                class="contact-form__input" 
                                placeholder="Votre nom" 
                                required 
                            />
                            <span class="contact-form__line"></span>
                        </div>

                        <div class="contact-form__field">
                            <input 
                                type="email" 
                                name="contact_email" 
                                id="contact_email_<?php echo esc_attr($unique_id); ?>" 
                                class="contact-form__input" 
                                placeholder="Votre email" 
                                required 
                            />
                            <span class="contact-form__line"></span>
                        </div>

                        <div class="contact-form__field contact-form__field--large">
                            <textarea 
                                name="contact_message" 
                                id="contact_message_<?php echo esc_attr($unique_id); ?>" 
                                class="contact-form__textarea" 
                                placeholder="Votre message" 
                                rows="5" 
                                required
                            ></textarea>
                            <span class="contact-form__line"></span>
                        </div>

                        <div class="contact-form__submit">
                            <button type="submit" class="btn btn--contact" style="border: 2px solid <?php echo esc_attr($text_color); ?>; color: <?php echo esc_attr($text_color); ?>;">
                                <span class="btn-text">Nous contacter</span>
                                <svg class="btn-arrow" width="260" height="52" viewBox="0 0 260 52" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M229.293 25.2929C228.902 25.6834 228.902 26.3166 229.293 26.7071L235.657 33.0711C236.047 33.4616 236.681 33.4616 237.071 33.0711C237.462 32.6805 237.462 32.0474 237.071 31.6569L231.414 26L237.071 20.3431C237.462 19.9526 237.462 19.3195 237.071 18.9289C236.681 18.5384 236.047 18.5384 235.657 18.9289L229.293 25.2929ZM260 25H230V27H260V25Z" fill="currentColor"/>
                                    <line x1="0" y1="51" x2="260" y2="51" stroke="currentColor" stroke-width="2"/>
                                </svg>
                            </button>
                        </div>

                        <input type="hidden" name="action" value="mediapilote_contact_form" />
                        <input type="hidden" name="nonce" value="<?php echo wp_create_nonce('mediapilote_contact_nonce'); ?>" />
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

/**
 * Traitement AJAX du formulaire de contact
 */
function mediapilote_handle_contact_form() {
    // Vérifier le nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'mediapilote_contact_nonce')) {
        wp_send_json_error(array('message' => 'Erreur de sécurité.'));
    }

    // Récupérer et nettoyer les données
    $name = isset($_POST['contact_name']) ? sanitize_text_field($_POST['contact_name']) : '';
    $email = isset($_POST['contact_email']) ? sanitize_email($_POST['contact_email']) : '';
    $message = isset($_POST['contact_message']) ? sanitize_textarea_field($_POST['contact_message']) : '';

    // Validation
    if (empty($name) || empty($email) || empty($message)) {
        wp_send_json_error(array('message' => 'Veuillez remplir tous les champs.'));
    }

    if (!is_email($email)) {
        wp_send_json_error(array('message' => 'Adresse email invalide.'));
    }

    // Préparer l'email
    $to = get_option('admin_email');
    $subject = 'Nouveau message de contact depuis ' . get_bloginfo('name');
    $headers = array(
        'Content-Type: text/html; charset=UTF-8',
        'From: ' . get_bloginfo('name') . ' <' . get_option('admin_email') . '>',
        'Reply-To: ' . $name . ' <' . $email . '>'
    );

    $email_body = '<html><body>';
    $email_body .= '<h2>Nouveau message de contact</h2>';
    $email_body .= '<p><strong>Nom :</strong> ' . esc_html($name) . '</p>';
    $email_body .= '<p><strong>Email :</strong> ' . esc_html($email) . '</p>';
    $email_body .= '<p><strong>Message :</strong></p>';
    $email_body .= '<p>' . nl2br(esc_html($message)) . '</p>';
    $email_body .= '<hr>';
    $email_body .= '<p><small>Envoyé depuis le formulaire de contact de ' . get_bloginfo('url') . '</small></p>';
    $email_body .= '</body></html>';

    // Logger les tentatives d'envoi pour debug
    error_log('Tentative d\'envoi d\'email de contact - Destinataire: ' . $to);
    error_log('Nom: ' . $name . ' - Email: ' . $email);

    // Envoyer l'email
    $sent = wp_mail($to, $subject, $email_body, $headers);

    // Logger le résultat
    if ($sent) {
        error_log('Email de contact envoyé avec succès');
        wp_send_json_success(array('message' => 'Votre message a été envoyé avec succès !'));
    } else {
        error_log('Échec de l\'envoi de l\'email de contact');
        // Vérifier si l'email admin est configuré
        if (empty($to) || !is_email($to)) {
            error_log('Erreur: Email administrateur invalide - ' . $to);
            wp_send_json_error(array('message' => 'Configuration email incorrecte. Contactez l\'administrateur.'));
        }
        wp_send_json_error(array('message' => 'Une erreur est survenue lors de l\'envoi du message.'));
    }
}
add_action('wp_ajax_mediapilote_contact_form', 'mediapilote_handle_contact_form');
add_action('wp_ajax_nopriv_mediapilote_contact_form', 'mediapilote_handle_contact_form');
