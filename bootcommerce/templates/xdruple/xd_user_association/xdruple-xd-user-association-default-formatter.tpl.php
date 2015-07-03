<?php
/**
 * @var string $attributes
 * @var string $classes
 * @var array  $rows
 */
?>
<?php if (!empty($rows)): ?>
  <div class="<?php print $classes ?>"<?php print $attributes ?>>
    <?php foreach ($rows as $row): ?>
      <div<?php print $row["attributes"] ?>>
        <?php if (!empty($row["primary_contact"])): ?>
          <div class="xdruple-xd-user-association--primary-contact col-lg-3 col-md-4">
            <h4><?php print t("Primary contact") ?></h4>
            <?php print drupal_render($row["primary_contact"]); ?>
          </div>
        <?php endif; ?>
        <?php if (!empty($row["billing_contact"])): ?>
          <div class="xdruple-xd-user-association--billing-contact col-lg-3 col-md-4">
            <h4><?php print t("Billing contact") ?></h4>
            <?php print drupal_render($row["billing_contact"]); ?>
          </div>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>
