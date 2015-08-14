(function ($) {
  Drupal.behaviors.bootb2b = {
    attach: function (context, settings) {
      var $qty = $('.commerce-add-to-cart .form-item-quantity input.form-text');
      $qty.on('focusin', function () {
        this.select();
      });
      $qty.on('keypress', function (eventData) {
        var char = String.fromCharCode(eventData.charCode).toLowerCase();
        if (char >= "a" && char <= "z") {
          eventData.preventDefault();
        }
      });
    }
  };
})(jQuery);
