<?php
/**
 * @var string  $classes
 * @var string  $attributes
 * @var boolean $page
 * @var string  $title_attributes
 * @var string  $title
 * @var string  $content_attributes
 * @var string  $context_attributes
 * @var string  $bottom_attributes
 */
?>
<div class="<?php print $classes; ?> clearfix"<?php print $attributes; ?>>

  <?php if (!$page): ?>
    <h2<?php print $title_attributes; ?>>
      <?php if ($url): ?>
        <a href="<?php print $url; ?>"><?php print $title; ?></a>
      <?php else: ?>
        <?php print $title; ?>
      <?php endif; ?>
    </h2>
  <?php endif; ?>

  <div<?php print $content_attributes; ?>>
    <?php print render($content); ?>
  </div>

  <?php if (!empty($context)): ?>
    <div<?php print $context_attributes; ?>>
      <?php print render($context); ?>
    </div>
  <?php endif; ?>

  <?php if (!empty($bottom)): ?>
    <div<?php print $bottom_attributes; ?>>
      <?php print render($bottom); ?>
    </div>
  <?php endif; ?>
</div>
