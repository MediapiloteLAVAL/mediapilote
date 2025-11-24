<?php
/**
 * Bloc Slider Confiance
 * Bloc Gutenberg pour afficher un slider de partenaires/clients
 *
 * @package MediaPilote
 * @since 1.0.0
 */

// Empêcher l'accès direct
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Enregistrer le bloc Slider Confiance
 */
function mediapilote_register_block_slider_confiance() {
    // Vérifier si la fonction existe
    if (!function_exists('register_block_type')) {
        return;
    }

    // Enregistrer le script du bloc
    wp_register_script(
        'mediapilote-block-slider-confiance-editor',
        get_template_directory_uri() . '/blocks/blockSliderConfiance/block.js',
        array('wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-i18n'),
        filemtime(get_template_directory() . '/blocks/blockSliderConfiance/block.js')
    );

    // Enregistrer le style du bloc (frontend et editor)
    wp_register_style(
        'mediapilote-block-slider-confiance-style',
        get_template_directory_uri() . '/blocks/blockSliderConfiance/style.css',
        array(),
        filemtime(get_template_directory() . '/blocks/blockSliderConfiance/style.css')
    );

    // Enregistrer le style de l'éditeur
    wp_register_style(
        'mediapilote-block-slider-confiance-editor-style',
        get_template_directory_uri() . '/blocks/blockSliderConfiance/editor.css',
        array('wp-edit-blocks'),
        filemtime(get_template_directory() . '/blocks/blockSliderConfiance/editor.css')
    );

    // Enregistrer le bloc
    register_block_type('mediapilote/slider-confiance', array(
        'editor_script' => 'mediapilote-block-slider-confiance-editor',
        'style' => 'mediapilote-block-slider-confiance-style',
        'editor_style' => 'mediapilote-block-slider-confiance-editor-style',
        'render_callback' => 'mediapilote_render_block_slider_confiance',
        'attributes' => array(
            'title' => array(
                'type' => 'string',
                'default' => 'Ils nous font confiance'
            ),
            'description' => array(
                'type' => 'string',
                'default' => ''
            ),
            'backgroundColor' => array(
                'type' => 'string',
                'default' => '#d9d9d9'
            ),
            'textColor' => array(
                'type' => 'string',
                'default' => '#2d3037'
            ),
            'items' => array(
                'type' => 'array',
                'default' => array(
                    array(
                        'image' => '',
                        'subtitle' => '',
                        'description' => ''
                    ),
                    array(
                        'image' => '',
                        'subtitle' => '',
                        'description' => ''
                    ),
                    array(
                        'image' => '',
                        'subtitle' => '',
                        'description' => ''
                    )
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
add_action('init', 'mediapilote_register_block_slider_confiance');

/**
 * Rendu du bloc côté frontend
 */
function mediapilote_render_block_slider_confiance($attributes) {
    $title = isset($attributes['title']) ? $attributes['title'] : 'Ils nous font confiance';
    $description = isset($attributes['description']) ? $attributes['description'] : '';
    $backgroundColor = isset($attributes['backgroundColor']) ? $attributes['backgroundColor'] : '#d9d9d9';
    $textColor = isset($attributes['textColor']) ? $attributes['textColor'] : '#2d3037';
    $items = isset($attributes['items']) ? $attributes['items'] : array();

    // Si pas d'items, on en crée des exemples
    if (empty($items)) {
        $items = array(
            array('image' => '', 'subtitle' => 'Partenaire 1', 'description' => 'Description du premier partenaire'),
            array('image' => '', 'subtitle' => 'Partenaire 2', 'description' => 'Description du deuxième partenaire'),
            array('image' => '', 'subtitle' => 'Partenaire 3', 'description' => 'Description du troisième partenaire')
        );
    }

    // Enqueue Swiper
    wp_enqueue_style('swiper', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css');
    wp_enqueue_script('swiper', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', array(), '11', true);

    ob_start();
    ?>
    <div class="wp-block-mediapilote-slider-confiance alignfull" style="background-color: <?php echo esc_attr($backgroundColor); ?>; color: <?php echo esc_attr($textColor); ?>;">
        <div class="slider-confiance-section__wrapper">
            <div class="slider-confiance-container">
                <div class="slider-confiance-content">
                <?php if (!empty($title)) : ?>
                    <h2 class="slider-confiance-title"><?php echo esc_html($title); ?></h2>
                <?php endif; ?>
                
                <?php if (!empty($description)) : ?>
                    <p class="slider-confiance-description"><?php echo esc_html($description); ?></p>
                <?php endif; ?>

                <div class="slider-confiance-wrapper">
                    <!-- Navigation -->
                    <div class="slider-confiance-navigation">
                        <button class="slider-confiance-prev" aria-label="Précédent">
                            <svg width="24" height="47" viewBox="0 0 24 47" fill="none">
                                <path d="M23 1L1 23.5L23 46" stroke="currentColor" stroke-width="2"/>
                            </svg>
                        </button>
                        <button class="slider-confiance-next" aria-label="Suivant">
                            <svg width="24" height="47" viewBox="0 0 24 47" fill="none">
                                <path d="M1 1L23 23.5L1 46" stroke="currentColor" stroke-width="2"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Slider -->
                    <div class="swiper slider-confiance-swiper">
                        <div class="swiper-wrapper">
                            <?php foreach ($items as $item) : ?>
                                <div class="swiper-slide">
                                    <div class="slider-confiance-item">
                                        <?php if (!empty($item['image'])) : ?>
                                            <div class="slider-confiance-image">
                                                <img src="<?php echo esc_url($item['image']); ?>" alt="<?php echo esc_attr($item['subtitle'] ?? ''); ?>">
                                            </div>
                                        <?php else : ?>
                                            <div class="slider-confiance-image-placeholder">
                                                <svg width="100" height="100" viewBox="0 0 100 100" fill="none">
                                                    <rect width="100" height="100" fill="#0A3C33"/>
                                                </svg>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <?php if (!empty($item['subtitle'])) : ?>
                                            <h3 class="slider-confiance-subtitle"><?php echo esc_html($item['subtitle']); ?></h3>
                                        <?php endif; ?>
                                        
                                        <?php if (!empty($item['description'])) : ?>
                                            <p class="slider-confiance-item-description"><?php echo esc_html($item['description']); ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const swiper = new Swiper('.slider-confiance-swiper', {
            slidesPerView: 1,
            spaceBetween: 30,
            loop: true,
            autoplay: {
                delay: 4000,
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
            navigation: {
                nextEl: '.slider-confiance-next',
                prevEl: '.slider-confiance-prev',
            },
        });
    });
    </script>
    <?php
    return ob_get_clean();
}