<?php
/**
 * @var array $blocks
 */
?>
<?php if (!empty($categories_tree)): ?>
  <?php print $categories_tree ?>
<?php endif; ?>
<?php foreach ($blocks as $block): ?>
  <?php print $block ?>
<?php endforeach; ?>
