<nav class="main-menu-container header-menu">
    <?php
    wp_nav_menu(array(
        'theme_location' => 'main-menu',
        'menu_class'     => 'main-menu-class d-flex flex-row',
        'container'      => false,
    ));
    ?>
</nav>