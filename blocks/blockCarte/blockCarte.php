<?php
/**
 * Bloc Carte
 * Bloc Gutenberg natif FSE pour afficher une carte Leaflet avec des adresses d'entreprises
 *
 * @package MediaPilote
 * @since 1.0.0
 */

// Empêcher l'accès direct
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Enregistrer le bloc Carte
 */
function mediapilote_register_block_carte() {
    // Vérifier si la fonction existe
    if (!function_exists('register_block_type')) {
        return;
    }

    // Enregistrer le script du bloc
    wp_register_script(
        'mediapilote-block-carte-editor',
        get_template_directory_uri() . '/blocks/blockCarte/block.js',
        array('wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-i18n'),
        filemtime(get_template_directory() . '/blocks/blockCarte/block.js')
    );

    // Enregistrer le style du bloc (frontend et editor)
    wp_register_style(
        'mediapilote-block-carte-style',
        get_template_directory_uri() . '/blocks/blockCarte/style.css',
        array(),
        filemtime(get_template_directory() . '/blocks/blockCarte/style.css')
    );

    // Enregistrer le style de l'éditeur
    wp_register_style(
        'mediapilote-block-carte-editor-style',
        get_template_directory_uri() . '/blocks/blockCarte/editor.css',
        array('wp-edit-blocks'),
        filemtime(get_template_directory() . '/blocks/blockCarte/editor.css')
    );

    // Enregistrer le bloc
    register_block_type('mediapilote/carte', array(
        'editor_script' => 'mediapilote-block-carte-editor',
        'style' => 'mediapilote-block-carte-style',
        'editor_style' => 'mediapilote-block-carte-editor-style',
        'render_callback' => 'mediapilote_render_block_carte',
        'attributes' => array(
            'height' => array(
                'type' => 'number',
                'default' => 400
            ),
            'markerImage' => array(
                'type' => 'object',
                'default' => array(
                    'url' => '',
                    'id' => 0
                )
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
add_action('init', 'mediapilote_register_block_carte');

/**
 * Rendu du bloc côté frontend
 */
function mediapilote_render_block_carte($attributes) {
    $height = isset($attributes['height']) ? $attributes['height'] : 400;
    $markerImage = isset($attributes['markerImage']) ? $attributes['markerImage'] : array('url' => '', 'id' => 0);

    // Récupérer les entreprises depuis ACF options
    $entreprises = get_field('ent', 'option');
    $addresses = array();

    if ($entreprises) {
        foreach ($entreprises as $entreprise) {
            if (!empty($entreprise['ent_lat']) && !empty($entreprise['ent_lon'])) {
                // Construire le contenu du popup
                $popup_content = '<div class="carte-popup">';
                $popup_content .= '<h3>' . esc_html($entreprise['ent_name']) . '</h3>';
                
                if (!empty($entreprise['ent_address'])) {
                    $popup_content .= '<p><strong>Adresse :</strong><br>' . esc_html($entreprise['ent_address']) . '</p>';
                }
                
                if (!empty($entreprise['ent_city']) || !empty($entreprise['ent_zipcode'])) {
                    $address_line = '';
                    if (!empty($entreprise['ent_zipcode'])) $address_line .= $entreprise['ent_zipcode'] . ' ';
                    if (!empty($entreprise['ent_city'])) $address_line .= $entreprise['ent_city'];
                    $popup_content .= '<p>' . esc_html($address_line) . '</p>';
                }
                
                if (!empty($entreprise['ent_phone'])) {
                    $popup_content .= '<p><strong>Téléphone :</strong><br>';
                    foreach ($entreprise['ent_phone'] as $phone) {
                        if (!empty($phone['number'])) {
                            $popup_content .= esc_html($phone['number']) . '<br>';
                        }
                    }
                    $popup_content .= '</p>';
                }
                
                if (!empty($entreprise['ent_mail'])) {
                    $popup_content .= '<p><strong>Email :</strong><br><a href="mailto:' . esc_attr($entreprise['ent_mail']) . '">' . esc_html($entreprise['ent_mail']) . '</a></p>';
                }
                
                if (!empty($entreprise['ent_open'])) {
                    $popup_content .= '<p><strong>Horaires :</strong><br>' . nl2br(esc_html($entreprise['ent_open'])) . '</p>';
                }
                
                if (!empty($entreprise['ent_contact'])) {
                    $contact_page = get_post($entreprise['ent_contact']);
                    if ($contact_page) {
                        $popup_content .= '<p><a href="' . esc_url(get_permalink($contact_page)) . '" target="_blank">Page de contact</a></p>';
                    }
                }
                
                $popup_content .= '</div>';

                $addresses[] = array(
                    'lat' => floatval($entreprise['ent_lat']),
                    'lng' => floatval($entreprise['ent_lon']),
                    'popup' => $popup_content
                );
            }
        }
    }

    // Enqueue Leaflet
    wp_enqueue_style('leaflet', 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css');
    wp_enqueue_script('leaflet', 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js', array(), '1.9.4', true);

    $block_id = 'carte-' . uniqid();

    ob_start();
    ?>
    <div class="wp-block-mediapilote-carte alignfull">
        <div id="<?php echo esc_attr($block_id); ?>" style="overflow: hidden; height: <?php echo esc_attr($height); ?>px;"></div>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            var map = L.map('<?php echo esc_js($block_id); ?>', {scrollWheelZoom: false, zoomControl: false}).setView([46.603354, 1.888334], 6);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: ''
            }).addTo(map);

            var markerIcon = L.icon({
                iconUrl: '<?php echo !empty($markerImage['url']) ? esc_js($markerImage['url']) : 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png'; ?>',
                shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41]
            });

            <?php foreach ($addresses as $address) : ?>
            L.marker([<?php echo esc_js($address['lat']); ?>, <?php echo esc_js($address['lng']); ?>], {icon: markerIcon})
                .addTo(map)
                .bindPopup(`<?php echo $address['popup']; ?>`, {maxWidth: 350});
            <?php endforeach; ?>
        });
        </script>
    </div>
    <?php
    return ob_get_clean();
}