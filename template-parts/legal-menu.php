<?php $nom = get_field('ent_name', 'option'); ?>
<nav class="legal-menu-container legal-menu d-flex flex-row">
    <ul class="d-flex flex-row">
        <li>© <?php echo get_bloginfo("name") ?> - <?php echo date('Y'); ?></li>
        <li class="menu-divider self">|</li>
    </ul>
    <?php
    wp_nav_menu(array(
        'theme_location' => 'legal-menu',
        'menu_class'     => 'legal-menu d-flex flex-row',
        'container'      => false,
        'after'             => '<li class="menu-divider">|</li>'
    ));
    ?>
</nav>