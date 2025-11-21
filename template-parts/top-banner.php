<?php 
$bannertop_txt = get_field('bannertop_txt' , 'option');
$bannertop_color = get_field('bannertop_color' , 'option');
if ($bannertop_txt) { ?>
<div class="banner-top" style="background-color:<?php echo $bannertop_color; ?>">
    <?php echo $bannertop_txt; ?>
</div>
<?php } ?>