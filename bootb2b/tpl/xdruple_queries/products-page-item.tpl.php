<td class="views-field views-field-sku">
  <?php if (!empty($sku)): ?>
    <?php print $sku ?>
  <?php endif ?>
</td>
<td class="views-field views-field-title">
  <?php if (!empty($title)): ?>
    <?php print $title ?>
  <?php endif; ?>
</td>
<td class="views-field views-field-pack">
  <?php if (!empty($pack)): ?>
    <?php print $pack ?>
  <?php endif; ?>
</td>
<td class="views-field views-field-unit-price">
  <?php if (!empty($unit_price)): ?>
    <?php print drupal_render($unit_price) ?>
  <?php endif; ?>
</td>
<td class="views-field views-field-price">
  <?php if (!empty($price)): ?>
    <?php print drupal_render($price) ?>
  <?php endif; ?>
</td>
<td class="views-field views-field-add-to-cart-form">
  <?php if (!empty($add_to_cart)): ?>
    <?php print $add_to_cart ?>
  <?php endif; ?>
</td>
<td class="views-field views-field-add-to-standard-order">
  <?php if (!empty($add_to_standard)): ?>
    <?php print $add_to_standard ?>
  <?php endif; ?>
</td>
