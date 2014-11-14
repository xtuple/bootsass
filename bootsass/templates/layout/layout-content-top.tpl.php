<div id="content-top-wrap">
  <?php if (!empty($title)): ?>
    <div class="page-header">
      <h1><?php print $title ?></h1>
    </div>
  <?php endif; ?>
  <?php if (!empty($tabs)): ?>
    <div class="tabs"><?php print $tabs ?></div>
  <?php endif; ?>
  <?php if (!empty($help)): ?>
    <div class="b-system-help"><?php print $help; ?></div>
  <?php endif; ?>
</div>
