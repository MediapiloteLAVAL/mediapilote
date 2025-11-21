<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package mediapilote
 */

?>

<footer id="colophon" class="site-footer">
    <!-- SECTION PRINCIPALE DU FOOTER -->
    <div class="footer-main-section">
        <div class="container">
            <div class="row footer-content">
                <!-- Colonne Back to top -->
                <div class="col-12 col-md-4">
                    <a href="#page" class="back-to-top-link" id="backToTop">
                        <span class="back-to-top-arrow">↑</span>
                        <span class="back-to-top-text">
                            <?php 
                            $back_to_top_text = get_theme_mod('mediapilote_footer_back_to_top_text', 'Back to the top');
                            echo esc_html($back_to_top_text);
                            ?>
                        </span>
                    </a>
                </div>
                
                <!-- Colonne Coordonnées -->
                <div class="col-12 col-md-4">
                    <?php 
                    $entreprises = get_field('ent', 'option');
                    if ($entreprises && is_array($entreprises)) : 
                        $first_entreprise = $entreprises[0];
                        ?>
                        <div class="footer-contact-info">
                            <?php if (!empty($first_entreprise['ent_name'])) : ?>
                                <p class="entreprise-name"><?php echo esc_html($first_entreprise['ent_name']); ?></p>
                            <?php endif; ?>

                            <?php if (!empty($first_entreprise['ent_address'])) : ?>
                                <p><?php echo nl2br(esc_html($first_entreprise['ent_address'])); ?></p>
                            <?php endif; ?>

                            <?php if (!empty($first_entreprise['ent_zipcode']) || !empty($first_entreprise['ent_city'])) : ?>
                                <p>
                                    <?php 
                                    if (!empty($first_entreprise['ent_zipcode'])) {
                                        echo esc_html($first_entreprise['ent_zipcode']);
                                    }
                                    if (!empty($first_entreprise['ent_city'])) {
                                        echo ' ' . esc_html($first_entreprise['ent_city']);
                                    }
                                    ?>
                                </p>
                            <?php endif; ?>

                            <?php if (!empty($first_entreprise['ent_phone']) && is_array($first_entreprise['ent_phone'])) : ?>
                                <?php foreach ($first_entreprise['ent_phone'] as $phone) : ?>
                                    <?php if (!empty($phone['number'])) : ?>
                                        <p>tél : <a href="tel:<?php echo esc_attr(str_replace(' ', '', $phone['number'])); ?>"><?php echo esc_html($phone['number']); ?></a></p>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    <?php else : ?>
                        <div class="footer-contact-info">
                            <p class="entreprise-name">Centre d'affaires Tivoli</p>
                            <p>5810 Changé</p>
                            <p>tél : 02 02 02 02 02</p>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Colonne Menu footer -->
                <div class="col-12 col-md-4">
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'footer-menu',
                        'menu_class'     => 'footer-menu-list',
                        'container'      => false,
                        'fallback_cb'    => false,
                    ));
                    ?>
                </div>
            </div>
        </div>
    </div>
</footer><!-- #colophon -->

</div><!-- #page -->

<?php wp_footer(); ?>

</body>

</html>