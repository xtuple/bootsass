<div id="header-wrap" class="container l-header hidden-print">
  <div class="row">
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
      <?php if (!empty($main_menu)): ?>
        <?php print $main_menu; ?>
      <?php endif; ?>
    </div>
  </div>
</div>
