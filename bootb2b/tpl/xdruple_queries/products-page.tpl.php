<div class="view-ft-commerce-xd-products">
  <div class="view-content">
    <?php if (!empty($products)): ?>
      <table class="views-table col-3 table table-striped">
        <thead>
          <tr>
            <th class="views-field views-field-sku"><?php print t('Item #') ?></th>
            <th class="views-field views-field-title"><?php print t('Description') ?></th>
            <th class="views-field views-field-pack"><?php print t('Pack') ?></th>
            <th class="views-field views-field-unit-price"><?php print t('Unit price') ?></th>
            <th class="views-field views-field-price"><?php print t('Price') ?></th>
            <th class="views-field views-field-add-to-cart-form"><?php print t('Qty') ?></th>
            <th class="views-field views-field-add-to-standard-order"><?php print t('Std') ?></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($products as $product): ?>
            <tr><?php print drupal_render($product); ?></tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </div>
</div>
