(function ($) {
  $(document).ready(function () {
    var view$ = $('.view-ft-commerce-xd-products');
    view$.find('.views-columns').each(function () {
      var parent$ = $(this);
      equalHeights(parent$, '.xdruple-product-subtitle');
      equalHeights(parent$, '.views-field-title');
    });
  });

  var equalHeights = function (parent$, selector) {
    var blocks$ = parent$.find(selector);
    var maxHeight = blocks$.height();

    blocks$.each(function () {
      var h = $(this).height();
      if (h > maxHeight) {
        maxHeight = h;
      }
    });
    blocks$.height(maxHeight);
  }
})(jQuery);
