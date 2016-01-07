<div id="body-wrap">
  <?php if (!empty($header)): ?>
    <?php print $header ?>
  <?php endif; ?>
  <?php if (!empty($body_top)): ?>
    <?php print $body_top ?>
  <?php endif; ?>
  <?php if (!empty($body_middle)): ?>
    <?php print $body_middle ?>
  <?php endif; ?>
  <?php if (!empty($body_bottom)): ?>
    <?php print $body_bottom ?>
  <?php endif; ?>
  <div id="pseudo-footer" class="hidden-print"></div>
</div>

<div id="footer-wrap" class="hidden-print">
  <?php if (!empty($footer)): ?>
    <?php print $footer ?>
  <?php endif; ?>
</div>
