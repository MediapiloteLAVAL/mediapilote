<?php
/**
 * Bloc Remontée d'Actualités
 * Bloc Gutenberg pour afficher les dernières actualités dans un slider
 *
 * @package MediaPilote
 * @since 1.0.0
 */

// Empêcher l'accès direct
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Enregistrer le bloc Remontée d'Actualités
 */
function mediapilote_register_block_remontee_actualites() {
    // Vérifier si la fonction existe
    if (!function_exists('register_block_type')) {
        return;
    }

    // Enregistrer le script du bloc
    wp_register_script(
        'mediapilote-block-remontee-actualites-editor',
        get_template_directory_uri() . '/blocks/blockRemonteeActualites/block.js',
        array('wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-i18n'),
        filemtime(get_template_directory() . '/blocks/blockRemonteeActualites/block.js')
    );

    // Enregistrer le style du bloc (frontend et editor)
    wp_register_style(
        'mediapilote-block-remontee-actualites-style',
        get_template_directory_uri() . '/blocks/blockRemonteeActualites/style.css',
        array(),
        filemtime(get_template_directory() . '/blocks/blockRemonteeActualites/style.css')
    );

    // Enregistrer le style de l'éditeur
    wp_register_style(
        'mediapilote-block-remontee-actualites-editor-style',
        get_template_directory_uri() . '/blocks/blockRemonteeActualites/editor.css',
        array('wp-edit-blocks'),
        filemtime(get_template_directory() . '/blocks/blockRemonteeActualites/editor.css')
    );

    // Enregistrer le bloc
    register_block_type('mediapilote/remontee-actualites', array(
        'editor_script' => 'mediapilote-block-remontee-actualites-editor',
        'style' => 'mediapilote-block-remontee-actualites-style',
        'editor_style' => 'mediapilote-block-remontee-actualites-editor-style',
        'render_callback' => 'mediapilote_render_block_remontee_actualites',
        'attributes' => array(
            'title' => array(
                'type' => 'string',
                'default' => 'Lorem ipsum'
            ),
            'description' => array(
                'type' => 'string',
                'default' => 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est.'
            ),
            'backgroundColor' => array(
                'type' => 'string',
                'default' => '#ffffff'
            ),
            'textColor' => array(
                'type' => 'string',
                'default' => '#2d3037'
            ),
            'buttonText' => array(
                'type' => 'string',
                'default' => 'VOIR TOUT'
            ),
            'buttonLink' => array(
                'type' => 'string',
                'default' => '/blog'
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
add_action('init', 'mediapilote_register_block_remontee_actualites');

/**
 * Rendu du bloc côté frontend
 */
function mediapilote_render_block_remontee_actualites($attributes) {
    $title = isset($attributes['title']) ? $attributes['title'] : 'Lorem ipsum';
    $description = isset($attributes['description']) ? $attributes['description'] : 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est.';
    $backgroundColor = isset($attributes['backgroundColor']) ? $attributes['backgroundColor'] : '#ffffff';
    $textColor = isset($attributes['textColor']) ? $attributes['textColor'] : '#2d3037';
    $buttonText = isset($attributes['buttonText']) ? $attributes['buttonText'] : 'VOIR TOUT';
    $buttonLink = isset($attributes['buttonLink']) ? $attributes['buttonLink'] : '/blog';

    // Query les 10 dernières actualités
    $args = array(
        'post_type' => 'news',
        'posts_per_page' => 10,
        'post_status' => 'publish',
        'orderby' => 'date',
        'order' => 'DESC'
    );
    $posts_query = new WP_Query($args);

    if (!$posts_query->have_posts()) {
        return '<p>Aucune actualité trouvée.</p>';
    }

    // Enqueue Swiper
    wp_enqueue_style('swiper', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css');
    wp_enqueue_script('swiper', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', array(), '11', true);

    ob_start();
    ?>
    <div class="wp-block-mediapilote-remontee-actualites alignfull" style="background-color: <?php echo esc_attr($backgroundColor); ?>; color: <?php echo esc_attr($textColor); ?>; padding: 80px 0;">
        <div class="container-fluid">
            <div class="remontee-actualites-section__wrapper">
                <!-- Titre et description -->
                <div class="header-content" style="margin-bottom: 60px;">
                    <h2 class="actualites-title" style="font-size: 70px; font-weight: normal; line-height: normal; margin-bottom: 30px; color: <?php echo esc_attr($textColor); ?>; font-family: 'Inter', sans-serif;">
                        <?php echo esc_html($title); ?>
                    </h2>
                    <p class="actualites-description" style="font-size: 18px; line-height: 28px; margin-bottom: 0; color: <?php echo esc_attr($textColor); ?>; font-family: 'Inter', sans-serif; font-weight: normal; max-width: 800px;">
                        <?php echo esc_html($description); ?>
                    </p>
                </div>

            <!-- Container navigation + slider -->
            <div class="slider-container">
                <!-- Navigation arrows -->
                <div class="slider-navigation">
                    <div class="slider-arrow slider-prev" style="display: flex; align-items: center; justify-content: center; width: 52px; height: 52px; background-color: <?php echo esc_attr($textColor); ?>; cursor: pointer;">
                        <svg width="12" height="24" viewBox="0 0 12 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M11 1L1 12L11 23" stroke="<?php echo esc_attr($backgroundColor); ?>" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="slider-arrow slider-next" style="display: flex; align-items: center; justify-content: center; width: 52px; height: 52px; background-color: <?php echo esc_attr($textColor); ?>; cursor: pointer; transform: rotate(180deg);">
                        <svg width="12" height="24" viewBox="0 0 12 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M11 1L1 12L11 23" stroke="<?php echo esc_attr($backgroundColor); ?>" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                </div>

                <!-- Slider des actualités -->
                <div class="swiper actualites-slider">
                <div class="swiper-wrapper">
                    <?php while ($posts_query->have_posts()) : $posts_query->the_post(); ?>
                        <div class="swiper-slide">
                            <div class="actualite-item" style="max-width: 398px; margin: 0;">
                                <?php if (has_post_thumbnail()) : ?>
                                    <div class="actualite-image" style="width: 100%; height: 299px; overflow: hidden; margin-bottom: 20px;">
                                        <img src="<?php echo esc_url(get_the_post_thumbnail_url(get_the_ID(), 'medium_large')); ?>" 
                                             alt="<?php echo esc_attr(get_the_title()); ?>" 
                                             style="width: 100%; height: 100%; object-fit: cover;">
                                    </div>
                                <?php else : ?>
                                    <div style="width: 100%; height: 299px; background-color: #ddd; margin-bottom: 20px; display: flex; align-items: center; justify-content: center; color: #999;">
                                        Image
                                    </div>
                                <?php endif; ?>
                                <div class="actualite-content">
                                    <h3 class="actualite-title" style="font-size: 28px; line-height: 37px; margin-bottom: 15px; color: <?php echo esc_attr($textColor); ?>; font-family: 'Inter', sans-serif; font-weight: normal;">
                                        <?php echo esc_html(get_the_title()); ?>
                                    </h3>
                                    <div class="actualite-text" style="font-size: 20px; line-height: 28px; margin-bottom: 10px; color: <?php echo esc_attr($textColor); ?>; font-family: 'Inter', sans-serif; font-weight: normal;">
                                        <p style="margin-bottom: 0;">
                                            <?php echo esc_html(wp_trim_words(get_the_excerpt(), 20, '…')); ?>
                                        </p>
                                        <p style="margin-bottom: 0; margin-top: 0;">
                                            <a href="<?php echo esc_url(get_permalink()); ?>" style="color: <?php echo esc_attr($textColor); ?>; text-decoration: underline; text-decoration-skip-ink: none; text-underline-position: from-font;">
                                                Lire la suite
                                            </a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; wp_reset_postdata(); ?>
                </div>
            </div>
            </div>

            <!-- Bouton Voir tout -->
            <div class="actualites-button" style="text-align: center; margin-top: 60px;">
                <a href="<?php echo esc_url($buttonLink); ?>" class="voir-tout-btn" style="display: inline-block; padding: 0; background: transparent; border: 2px solid <?php echo esc_attr($textColor); ?>; position: relative; text-decoration: none; width: 260px; height: 52px; overflow: hidden;">
                    <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 2px; z-index: 1;"></div>
                    <span style="position: relative; z-index: 2; display: flex; align-items: center; justify-content: center; height: 100%; font-size: 20px; color: <?php echo esc_attr($textColor); ?>; font-weight: normal; font-family: 'Inter', sans-serif; letter-spacing: 3px; text-transform: uppercase; line-height: 60px;">
                        <?php echo esc_html($buttonText); ?>
                    </span>
                </a>
            </div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const swiper = new Swiper('.actualites-slider', {
            slidesPerView: 1,
            spaceBetween: 30,
            loop: true,
            navigation: {
                nextEl: '.slider-next',
                prevEl: '.slider-prev',
            },
            breakpoints: {
                768: {
                    slidesPerView: 2,
                    spaceBetween: 30,
                },
                1024: {
                    slidesPerView: 3,
                    spaceBetween: 30,
                }
            }
        });
    });
    </script>
    <?php
    return ob_get_clean();
}