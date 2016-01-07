<?php
/**
 * @var array $element
 */
?>
<div class="commerce-payment-credit-card">
  <div class="well">
    <div class="row">
      <div class="col-lg-12 col-md-12">
        <?php print drupal_render($element["type"]) ?>
      </div>
      <div class="col-lg-12 col-md-12">
        <div class="commerce-payment-credit-card--data">
          <?php print drupal_render($element["number"]) ?>
          <?php print drupal_render($element["exp_month"]) ?>
          <?php print drupal_render($element["exp_year"]) ?>
          <?php print drupal_render($element["code"]) ?>
        </div>
      </div>
    </div>
    <?php print drupal_render_children($element); ?>
  </div>
</div>
