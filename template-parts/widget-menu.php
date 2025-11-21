<?php 
    $widgets = get_field('widget', 'option');
?>
<section class="widget-menu">
    <div class="short-menu">
        <?php
            if ($widgets) {
                foreach ($widgets as $widget) {
                    $txt = $widget['widget_txt'];
                    $img  = $widget['widget_img'];
                    $lien = $widget['widget_url'];
                    $class = $widget['widget_class'];

        ?>
        <a href="<?php echo $lien['url']; ?>" class="button <?php echo $class; ?>">
            <img src="<?php echo $img['url']; ?>" alt="<?php echo $txt; ?>" title="<?php echo $txt; ?>">
            <p class="bold text-tertiary"><?php echo $txt; ?></p>
        </a>
        <?php }
            } ?>
    </div>
</section>