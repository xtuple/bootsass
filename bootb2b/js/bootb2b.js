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

      var $cartBlockRemoveBtn = $('.commerce-line-item-views-form form .delete-line-item');
      $cartBlockRemoveBtn.each(function () {
        var $this = $(this);
        if (Drupal.ajax[$this.attr('id')] !== undefined) {
          var removeButton = Drupal.ajax[$this.attr('id')];

          var dialogProcessed = true;
          removeButton.eventResponse = function (button, eventData) {
            eventData.preventDefault();

            var $dialog = $('#removeLineItemDialog');
            $dialog.modal();

            $dialog.find('.btn-cancel').one('click', function () {
              dialogProcessed = false;
            });

            $dialog.find('.btn-remove').one('click', function () {
              dialogProcessed = true;
              Drupal.ajax.prototype.eventResponse.call(removeButton, button, eventData);
            });
          }
        }
      });
    }
  };
})(jQuery);
