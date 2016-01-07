<div id="footer" class="container">
  <div class="footer-top row">
    <div class="col-lg-2">
      <?php if (!empty($main_menu)): ?>
        <?php print $main_menu ?>
      <?php endif; ?>
    </div>
    <div class="col-lg-2">
      <?php if (!empty($secondary_menu)): ?>
        <?php print $secondary_menu ?>
      <?php endif; ?>
    </div>
    <div class="col-lg-2">
      <?php if (!empty($user_menu)): ?>
        <?php print $user_menu ?>
      <?php endif; ?>
    </div>
    <div class="col-lg-3">
      <?php if (!empty($contact_info)): ?>
        <?php print $contact_info ?>
      <?php endif; ?>
      <?php if (!empty($site_phone)): ?>
        <?php print $site_phone ?>
      <?php endif; ?>
    </div>
    <div class="col-lg-3">
      <?php if (!empty($social_menu)): ?>
        <?php print $social_menu ?>
      <?php endif; ?>
    </div>
  </div>
  <div class="footer-bottom row">
    <div class="col-lg-12">
      <?php if (!empty($copyright)): ?>
        <?php print $copyright ?>
      <?php endif; ?>
    </div>
  </div>
</div>
