<div id="header-wrap" class="container">
  <div class="row">
    <div class="col-lg-12">
      <?php if (!empty($order_defaults_form)): ?>
        <?php print $order_defaults_form; ?>
      <?php endif; ?>
    </div>
    <div class="col-lg-3">
      <?php if (!empty($site_phone)): ?>
        <?php print $site_phone; ?>
      <?php endif; ?>
      <?php if (!empty($logo)): ?>
        <?php print $logo; ?>
      <?php endif; ?>
    </div>
    <div class="col-lg-9">
      <?php if (!empty($user_menu)): ?>
        <?php print $user_menu; ?>
      <?php endif; ?>
      <?php if (!empty($cart_dropdown)): ?>
        <?php print $cart_dropdown; ?>
      <?php endif; ?>
      <?php if (!empty($search_box)): ?>
        <?php print $search_box; ?>
      <?php endif; ?>
      <?php if (!empty($main_menu)): ?>
        <?php print $main_menu; ?>
      <?php endif; ?>
    </div>
  </div>
</div>
