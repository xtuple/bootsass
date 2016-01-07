<?php
/**
 * @file field.tpl.php
 *
 * @var string  $classes
 * @var string  $attributes
 * @var boolean $label_hidden
 * @var string  $label
 * @var string  $title_attributes
 * @var string  $content_attributes
 * @var array   $items
 * @var array   $item_attributes
 * @var integer $row_by
 * @var string  $group_attributes
 *
 * @see  theme_field()
 * @see  template_preprocess_field()
 */
?>
<div class="<?php print $classes; ?>"<?php print $attributes; ?>>
  <?php if (!$label_hidden): ?>
    <div class="field-label"<?php print $title_attributes; ?>><?php print $label ?>:&nbsp;</div>
  <?php endif; ?>
  <div<?php print $content_attributes; ?>>
    <?php foreach ($items as $delta => $item): ?>
      <?php if (($row_by > 1) && ($delta % $row_by == 0)): ?>
        <?php $not_closed = TRUE; ?>
        <div<?php print $group_attributes ?>>
      <?php endif; ?>
      <div<?php print $item_attributes[$delta]; ?>>
        <?php print render($item); ?>
      </div>
      <?php if (($row_by > 1) && (($delta % $row_by == $row_by - 1) || $delta == sizeof($items) - 1)): ?>
        </div>
        <?php $not_closed = FALSE; ?>
      <?php endif; ?>
    <?php endforeach; ?>
  </div>
</div>
