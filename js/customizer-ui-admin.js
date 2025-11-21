// Transforme les checkboxes du customizer en switches stylisés dans l'admin
jQuery(document).ready(function ($) {
  // Cibler tous les checkboxes du customizer
  $('.customize-control input[type="checkbox"]').each(function () {
    var $checkbox = $(this);
    // Éviter de doubler le markup
    if (!$checkbox.parent().hasClass('switch')) {
      var $switch = $('<label class="switch"></label>');
      var $slider = $('<span class="slider"></span>');
      $checkbox.wrap($switch);
      $checkbox.after($slider);
    }
  });
});
