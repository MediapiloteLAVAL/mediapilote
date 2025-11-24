// Transforme les checkboxes du customizer en switches stylisés
jQuery(document).ready(function ($) {
  // Cibler tous les checkboxes du customizer
  $('.customize-control input[type="checkbox"]').each(function () {
    var $checkbox = $(this);
    if (!$checkbox.parent().hasClass('switch')) {
      // Créer le markup switch
      var $switch = $('<label class="switch"></label>');
      var $slider = $('<span class="slider"></span>');
      $checkbox.after($slider);
      $checkbox.wrap($switch);
    }
  });
});
