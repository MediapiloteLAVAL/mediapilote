<nav class="top-menu-container header-menu">
    <div class="inner">
        <?php
        wp_nav_menu(array(
            'theme_location' => 'top-menu',
            'menu_class'     => 'top-menu d-flex flex-row',
            'container'      => false,
        ));
        ?>
    </div>
</nav>