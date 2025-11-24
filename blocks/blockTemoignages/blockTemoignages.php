<?php
/**
 * Bloc Témoignages
 * Bloc Gutenberg natif FSE pour afficher des témoignages avec image, nom et texte
 *
 * @package MediaPilote
 * @since 1.0.0
 */

// Empêcher l'accès direct
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Enregistrer le bloc Témoignages
 */
function mediapilote_register_block_temoignages() {
    // Vérifier si la fonction existe
    if (!function_exists('register_block_type')) {
        return;
    }

    // Enregistrer le script du bloc
    wp_register_script(
        'mediapilote-block-temoignages-editor',
        get_template_directory_uri() . '/blocks/blockTemoignages/block.js',
        array('wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-i18n'),
        filemtime(get_template_directory() . '/blocks/blockTemoignages/block.js')
    );

    // Enregistrer le style du bloc (frontend et editor)
    wp_register_style(
        'mediapilote-block-temoignages-style',
        get_template_directory_uri() . '/blocks/blockTemoignages/style.css',
        array(),
        filemtime(get_template_directory() . '/blocks/blockTemoignages/style.css')
    );

    // Enregistrer le style de l'éditeur
    wp_register_style(
        'mediapilote-block-temoignages-editor-style',
        get_template_directory_uri() . '/blocks/blockTemoignages/editor.css',
        array('wp-edit-blocks'),
        filemtime(get_template_directory() . '/blocks/blockTemoignages/editor.css')
    );

    // Enregistrer le bloc
    register_block_type('mediapilote/temoignages', array(
        'editor_script' => 'mediapilote-block-temoignages-editor',
        'style' => 'mediapilote-block-temoignages-style',
        'editor_style' => 'mediapilote-block-temoignages-editor-style',
        'render_callback' => 'mediapilote_render_block_temoignages',
        'attributes' => array(
            'title' => array(
                'type' => 'string',
                'default' => 'Témoignages'
            ),
            'testimonials' => array(
                'type' => 'array',
                'default' => array(
                    array(
                        'image' => array('id' => 0, 'url' => ''),
                        'name' => 'Nom du témoin',
                        'testimonial' => 'Témoignage ici...'
                    ),
                    array(
                        'image' => array('id' => 0, 'url' => ''),
                        'name' => 'Nom du témoin',
                        'testimonial' => 'Témoignage ici...'
                    ),
                    array(
                        'image' => array('id' => 0, 'url' => ''),
                        'name' => 'Nom du témoin',
                        'testimonial' => 'Témoignage ici...'
                    )
                )
            ),
            'backgroundColor' => array(
                'type' => 'string',
                'default' => '#f8f8f8'
            ),
            'textColor' => array(
                'type' => 'string',
                'default' => '#2d3037'
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
add_action('init', 'mediapilote_register_block_temoignages');

/**
 * Rendu du bloc côté frontend
 */
function mediapilote_render_block_temoignages($attributes) {
    $title = isset($attributes['title']) ? $attributes['title'] : 'Témoignages';
    $testimonials = isset($attributes['testimonials']) ? $attributes['testimonials'] : array();
    $backgroundColor = isset($attributes['backgroundColor']) ? $attributes['backgroundColor'] : '#f8f8f8';
    $textColor = isset($attributes['textColor']) ? $attributes['textColor'] : '#2d3037';

    $has_slider = count($testimonials) > 3;

    if ($has_slider) {
        wp_enqueue_style('swiper', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css');
        wp_enqueue_script('swiper', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', array(), '11', true);
    }

    ob_start();
    ?>
    <div class="wp-block-mediapilote-temoignages alignfull" style="background-color: <?php echo esc_attr($backgroundColor); ?>; padding: 60px 0;">
        <div class="container">
                        <h2 style="text-align: right; font-size: 28px; margin-bottom: 40px; color: <?php echo esc_attr($textColor); ?>;"><?php echo esc_html($title); ?></h2>
            <hr class="testimonial-separator">
            <?php if ($has_slider) : ?>
                <div class="swiper temoignages-slider">
                    <div class="swiper-wrapper d-flex x-center">
                        <?php foreach ($testimonials as $testimonial) : ?>
                            <div class="swiper-slide">
                                <div class="testimonial-item" style="max-width: 300px; margin: 0 auto;">
                                    <div class="testimonial-header" style="display: flex; align-items: center; justify-content: center; gap: 20px; margin-bottom: 20px;">
                                        <?php if (!empty($testimonial['image']['url'])) : ?>
                                            <img src="<?php echo esc_url($testimonial['image']['url']); ?>" alt="<?php echo esc_attr($testimonial['name']); ?>" style="width: 84px; height: 84px; border-radius: 50%; object-fit: cover;">
                                        <?php else : ?>
                                            <div style="width: 84px; height: 84px; border-radius: 50%; background-color: #ddd;"></div>
                                        <?php endif; ?>
                                        <h3 style="font-size: 20px; margin: 0; color: <?php echo esc_attr($textColor); ?>;"><?php echo esc_html($testimonial['name']); ?></h3>
                                    </div>
                                    <p style="font-size: 16px; line-height: 1.5; color: <?php echo esc_attr($textColor); ?>;"><?php echo esc_html($testimonial['testimonial']); ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="swiper-pagination"></div>
                </div>
                <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const swiper = new Swiper('.temoignages-slider', {
                        slidesPerView: 1,
                        spaceBetween: 30,
                        autoplay: {
                            delay: 5000,
                            disableOnInteraction: false,
                        },
                        breakpoints: {
                            768: {
                                slidesPerView: 2,
                            },
                            1024: {
                                slidesPerView: 3,
                            }
                        },
                        pagination: {
                            el: '.swiper-pagination',
                            clickable: true,
                        },
                    });
                });
                </script>
            <?php else : ?>
                <div class="testimonials-grid" style="display: flex; justify-content: space-around; flex-wrap: wrap; gap: 30px;">
                    <?php foreach ($testimonials as $testimonial) : ?>
                        <div class="testimonial-item" style="flex: 1; min-width: 250px; max-width: 300px;">
                            <div class="testimonial-header" style="display: flex; align-items: center; justify-content: center; gap: 20px; margin-bottom: 20px;">
                                <?php if (!empty($testimonial['image']['url'])) : ?>
                                    <img src="<?php echo esc_url($testimonial['image']['url']); ?>" alt="<?php echo esc_attr($testimonial['name']); ?>" style="width: 84px; height: 84px; border-radius: 50%; object-fit: cover;">
                                <?php else : ?>
                                    <div style="width: 84px; height: 84px; border-radius: 50%; background-color: #ddd;"></div>
                                <?php endif; ?>
                                <h3 style="font-size: 20px; margin: 0; color: <?php echo esc_attr($textColor); ?>;"><?php echo esc_html($testimonial['name']); ?></h3>
                            </div>
                            <p style="font-size: 16px; line-height: 1.5; color: #2d3037;"><?php echo esc_html($testimonial['testimonial']); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <?php
    return ob_get_clean();
}