<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package mediapilote
 */

// Inclure le bloc Articles Recommandés
if (file_exists(get_template_directory() . '/blocks/blockArticlesRecommandes/blockArticlesRecommandes.php')) {
    require_once get_template_directory() . '/blocks/blockArticlesRecommandes/blockArticlesRecommandes.php';
}

get_header(); 

// Définir la variable de langue
$is_english = mediapilote_is_english();
?>
<main>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <?php the_content(); ?>
            </div>
        </div>
        
        <!-- Bloc Articles Recommandés -->
    <?php
    // Fonction simple pour récupérer les articles connexes par catégorie
    function get_articles_connexes_par_categorie($post_id, $limit = 2) {
        // Récupérer les catégories du post actuel
        $categories = wp_get_post_categories($post_id);
        
        if (empty($categories)) {
            return array();
        }
        
        // Requête simple basée uniquement sur la catégorie
        $args = array(
            'post_type' => 'news',
            'post_status' => 'publish',
            'posts_per_page' => $limit,
            'post__not_in' => array($post_id),
            'category__in' => $categories,
            'orderby' => 'date',
            'order' => 'DESC'
        );
        
        $query = new WP_Query($args);
        $articles = array();
        
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $article = array(
                    'id' => get_the_ID(),
                    'title' => get_the_title(),
                    'excerpt' => get_the_excerpt(),
                    'url' => get_permalink(),
                    'image' => get_the_post_thumbnail_url(get_the_ID(), 'medium'),
                    'tags' => wp_get_post_tags(get_the_ID())
                );
                $articles[] = $article;
            }
            wp_reset_postdata();
        }
        
        return $articles;
    }
    
    // Récupérer et afficher les articles connexes
    $articles = get_articles_connexes_par_categorie(get_the_ID(), 2);
    
    if (!empty($articles)) {
        ?>
            <div class="row">
                <div class="col-12">
                    <section class="block-articles-recommandes">
                        <div class="block-articles-recommandes__inner">
                            
                            <!-- Titre du bloc -->
                            <h2 class="block-articles-recommandes__title"><?php echo mediapilote_get_text('Articles recommandés', 'Recommended articles'); ?></h2>
                            
                            <!-- Grille des articles -->
                            <div class="block-articles-recommandes__grid">
                                <?php foreach ($articles as $article) : ?>
                                    <article class="block-articles-recommandes__article">
                                        <div class="block-articles-recommandes__article-inner">
                                            
                                            <!-- Image de l'article -->
                                            <?php if ($article['image']) : ?>
                                                <div class="block-articles-recommandes__article-image">
                                                    <a href="<?php echo esc_url($article['url']); ?>" title="<?php echo esc_attr($article['title']); ?>">
                                                        <img src="<?php echo esc_url($article['image']); ?>" 
                                                             alt="<?php echo esc_attr($article['title']); ?>"
                                                             class="block-articles-recommandes__article-img">
                                                    </a>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <!-- Contenu de l'article -->
                                            <div class="block-articles-recommandes__article-content">
                                                
                                                <!-- Labels (mots-clés) -->
                                                <?php if (!empty($article['tags'])) : ?>
                                                    <div class="block-articles-recommandes__article-labels">
                                                        <?php foreach (array_slice($article['tags'], 0, 3) as $tag) : ?>
                                                            <span class="block-articles-recommandes__article-label">
                                                                <?php echo esc_html($tag->name); ?>
                                                            </span>
                                                        <?php endforeach; ?>
                                                    </div>
                                                <?php endif; ?>
                                                
                                                <!-- Titre de l'article -->
                                                <h3 class="block-articles-recommandes__article-title">
                                                    <a class="title" href="<?php echo esc_url($article['url']); ?>" 
                                                       title="<?php echo esc_attr($article['title']); ?>">
                                                        <?php echo esc_html($article['title']); ?>
                                                    </a>
                                                </h3>
                                                
                                                <!-- Extrait de l'article -->
                                                <?php if ($article['excerpt']) : ?>
                                                    <div class="block-articles-recommandes__article-excerpt">
                                                        <?php echo wp_kses_post($article['excerpt']); ?>
                                                    </div>
                                                <?php endif; ?>
                                                
                                                <!-- Lien "Lire la suite" -->
                                                <div class="block-articles-recommandes__article-link">
                                                    <a href="<?php echo esc_url($article['url']); ?>" 
                                                       class="block-articles-recommandes__article-more"
                                                       title="<?php echo esc_attr($article['title']); ?>">
                                                       <?php echo $is_english ? 'Read more' : 'Lire la suite'; ?>
                                                        <svg class="block-articles-recommandes__arrow" width="21" height="9" viewBox="0 0 21 9" fill="none">
                                                            <path d="M0 4.5H19.5M19.5 4.5L15 0M19.5 4.5L15 9" stroke="#1D1D1B" stroke-width="1.5"/>
                                                        </svg>
                                                    </a>
                                                </div>
                                                
                                            </div>
                                        </div>
                                    </article>
                                <?php endforeach; ?>
                            </div>
                            
                        </div>
                    </section>
                </div>
            </div>
        <?php
    } else {
        // Message si aucun article connexe trouvé
        ?>
            <div class="row">
                <div class="col-12">
                    <section class="block-articles-recommandes">
                        <div class="block-articles-recommandes__inner">
                            <h2 class="block-articles-recommandes__title"><?php echo $is_english ? 'Recommended articles' : 'Articles recommandés'; ?></h2>
                            <div class="block-articles-recommandes__no-articles">
                                <p class="block-articles-recommandes__no-articles-text">
                                    <?php echo $is_english ? 'No related articles found at the moment.' : 'Aucun article connexe n\'a été trouvé pour le moment.'; ?>
                                </p>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        <?php
    }
    ?>
    
</main>
<?php get_footer(); ?>