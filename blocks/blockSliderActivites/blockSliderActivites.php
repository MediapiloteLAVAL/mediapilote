<?php
/**
 * Bloc Slider d'Activités
 * Bloc Gutenberg pour afficher un slider des posts récents
 *
 * @package MediaPilote
 * @since 1.0.0
 */

// Empêcher l'accès direct
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Enregistrer le bloc Slider d'Activités
 */
function mediapilote_register_block_slider_activites() {
    // Vérifier si la fonction existe
    if (!function_exists('register_block_type')) {
        return;
    }

    // Enregistrer le script du bloc
    wp_register_script(
        'mediapilote-block-slider-activites-editor',
        get_template_directory_uri() . '/blocks/blockSliderActivites/block.js',
        array('wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-i18n'),
        filemtime(get_template_directory() . '/blocks/blockSliderActivites/block.js')
    );

    // Enregistrer le style du bloc (frontend et editor)
    wp_register_style(
        'mediapilote-block-slider-activites-style',
        get_template_directory_uri() . '/blocks/blockSliderActivites/style.css',
        array(),
        filemtime(get_template_directory() . '/blocks/blockSliderActivites/style.css')
    );

    // Enregistrer le style de l'éditeur
    wp_register_style(
        'mediapilote-block-slider-activites-editor-style',
        get_template_directory_uri() . '/blocks/blockSliderActivites/editor.css',
        array('wp-edit-blocks'),
        filemtime(get_template_directory() . '/blocks/blockSliderActivites/editor.css')
    );

    // Enregistrer le bloc
    register_block_type('mediapilote/slider-activites', array(
        'editor_script' => 'mediapilote-block-slider-activites-editor',
        'style' => 'mediapilote-block-slider-activites-style',
        'editor_style' => 'mediapilote-block-slider-activites-editor-style',
        'render_callback' => 'mediapilote_render_block_slider_activites',
        'attributes' => array(
            'title' => array(
                'type' => 'string',
                'default' => 'Activités récentes'
            ),
            'description' => array(
                'type' => 'string',
                'default' => 'Découvrez nos dernières actualités et activités.'
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
                'default' => 'Voir tout'
            ),
            'buttonLink' => array(
                'type' => 'string',
                'default' => '#'
            ),
            'postsPerPage' => array(
                'type' => 'number',
                'default' => 6
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
add_action('init', 'mediapilote_register_block_slider_activites');

/**
 * Rendu du bloc côté frontend
 */
function mediapilote_render_block_slider_activites($attributes) {
    $title = isset($attributes['title']) ? $attributes['title'] : 'Activités récentes';
    $description = isset($attributes['description']) ? $attributes['description'] : 'Découvrez nos dernières actualités et activités.';
    $backgroundColor = isset($attributes['backgroundColor']) ? $attributes['backgroundColor'] : '#ffffff';
    $textColor = isset($attributes['textColor']) ? $attributes['textColor'] : '#2d3037';
    $buttonText = isset($attributes['buttonText']) ? $attributes['buttonText'] : 'Voir tout';
    $buttonLink = isset($attributes['buttonLink']) ? $attributes['buttonLink'] : '#';
    $postsPerPage = isset($attributes['postsPerPage']) ? $attributes['postsPerPage'] : 6;

    // Query les posts
    $args = array(
        'post_type' => 'post',
        'posts_per_page' => $postsPerPage,
        'post_status' => 'publish',
        'orderby' => 'date',
        'order' => 'DESC'
    );
    $posts_query = new WP_Query($args);

    if (!$posts_query->have_posts()) {
        return '<p>Aucun post trouvé.</p>';
    }

    // Enqueue Swiper
    wp_enqueue_style('swiper', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css');
    wp_enqueue_script('swiper', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', array(), '11', true);

    ob_start();
    ?>
    <div class="wp-block-mediapilote-slider-activites alignfull" style="background-color: <?php echo esc_attr($backgroundColor); ?>; color: <?php echo esc_attr($textColor); ?>; padding: 60px 0;">
        <div class="container">
            <h2 style="font-size: 70px; margin-bottom: 20px; color: <?php echo esc_attr($textColor); ?>;"><?php echo esc_html($title); ?></h2>
            <p style="font-size: 18px; line-height: 28px; margin-bottom: 40px; color: <?php echo esc_attr($textColor); ?>;"><?php echo esc_html($description); ?></p>

            <div class="swiper activites-slider">
                <div class="swiper-wrapper">
                    <?php while ($posts_query->have_posts()) : $posts_query->the_post(); ?>
                        <div class="swiper-slide">
                            <div class="activite-item" style="max-width: 398px; margin: 0 auto;">
                                <?php if (has_post_thumbnail()) : ?>
                                    <img src="<?php echo esc_url(get_the_post_thumbnail_url(get_the_ID(), 'medium')); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" style="width: 100%; height: 299px; object-fit: cover; margin-bottom: 20px;">
                                <?php else : ?>
                                    <div style="width: 100%; height: 299px; background-color: #ddd; margin-bottom: 20px;"></div>
                                <?php endif; ?>
                                <h3 style="font-size: 28px; margin-bottom: 10px; color: <?php echo esc_attr($textColor); ?>;"><?php echo esc_html(get_the_title()); ?></h3>
                                <p style="font-size: 20px; line-height: 28px; margin-bottom: 10px; color: <?php echo esc_attr($textColor); ?>;">
                                    <?php echo esc_html(wp_trim_words(get_the_excerpt(), 20, '...')); ?>
                                </p>
                                <a href="<?php echo esc_url(get_permalink()); ?>" style="font-size: 20px; color: <?php echo esc_attr($textColor); ?>; text-decoration: underline;">Lire la suite</a>
                            </div>
                        </div>
                    <?php endwhile; wp_reset_postdata(); ?>
                </div>
                <div class="swiper-pagination" style="margin-top: 20px;"></div>
            </div>

            <div class="activites-button" style="text-align: center; margin-top: 40px;">
                <a href="<?php echo esc_url($buttonLink); ?>" style="display: inline-block; padding: 15px 30px; background-color: <?php echo esc_attr($textColor); ?>; color: <?php echo esc_attr($backgroundColor); ?>; text-decoration: none; font-size: 20px; font-weight: bold; text-transform: uppercase; letter-spacing: 3px;">
                    <?php echo esc_html($buttonText); ?>
                </a>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const swiper = new Swiper('.activites-slider', {
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
    <?php
    return ob_get_clean();
}