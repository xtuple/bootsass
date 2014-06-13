<?php
/**
 * @var boolean $show_content_context
 * @var array   $context_wrap_attributes
 * @var array   $content_wrap_attributes
 */
?>
<div id="body-middle-wrap" class="container">
  <div id="body-middle" class="row">
    <?php if ($show_content_context): ?>
      <div id="context-wrap"<?php print drupal_attributes($context_wrap_attributes) ?>>
        <?php if (!empty($context)): ?>
          <?php print render($context); ?>
        <?php endif; ?>
      </div>
    <?php endif; ?>
    <div id="content-wrap"<?php print drupal_attributes($content_wrap_attributes) ?>>
      <?php if (!empty($content_top)): ?>
        <?php print render($content_top); ?>
      <?php endif; ?>
      <div id="content-middle-wrap">
        <?php if (!empty($content_middle)): ?>
          <?php print render($content_middle); ?>
        <?php endif; ?>
      </div>
      <?php if (!empty($content_bottom)): ?>
        <?php print render($content_bottom); ?>
      <?php endif; ?>
    </div>
  </div>
</div>
