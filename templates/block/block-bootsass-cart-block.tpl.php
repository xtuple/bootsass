<?php
/**
 * @var array   $attributes_array
 * @var array   $title_attributes_array
 * @var string  $title
 * @var array   $content_attributes_array
 * @var string  $content
 * @var boolean $hide_empty_title
 * @var boolean $hide_empty_content
 * @var boolean $hide_empty_block
 */
?>
<?php if (!$hide_empty_block || !empty($content) || !empty($title)): ?>
  <div<?php print drupal_attributes($attributes_array) ?>>
    <?php if (!empty($title) || !$hide_empty_title): ?>
      <div<?php print drupal_attributes($title_attributes_array) ?>>
        <?php if (!empty($title)) {
          print $title;
        } ?>
      </div>
    <?php endif; ?>
    <?php if (!empty($content) || !$hide_empty_content): ?>
      <div<?php print drupal_attributes($content_attributes_array) ?>>
        <?php if (!empty($content)) {
          print $content;
        } ?>
      </div>
    <?php endif; ?>
  </div>
<?php endif; ?>
