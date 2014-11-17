<?php

/**
 * @file keeps theme functions overrides
 */

/**
 * Overrides theme_status_messages()
 *
 * Bootstrap uses different classes to mark messages (based on alert class).
 *
 * @param $vars
 *
 * @return string
 *
 * @see theme_status_messages()
 * @see drupal_set_message()
 */
function bootb2b_status_messages(&$vars) {
  $display = $vars['display'];
  $output = '';

  // Keep these statuses, as they are used for assistive technologies
  $status_heading = array(
    'status' => t('Status message'),
    'error' => t('Error message'),
    'warning' => t('Warning message'),
  );

  // Mapping of Drupal statuses to Bootstrap.
  $bootstrap_types = array(
    'status' => 'success',
    'error' => 'danger',
    'warning' => 'warning',
    'info' => 'info',
  );

  foreach (drupal_get_messages($display) as $type => $messages) {
    $classes = array('alert');
    if (isset($bootstrap_types[$type])) {
      $classes[] = "alert-$bootstrap_types[$type]";
    }
    else {
      $classes[] = "alert-$type";
    }
    if (count($messages) > 1 && BOOTSTRAP3_ALERT_GROUP) {
      if (BOOTSTRAP3_ALERT_BLOCK) {
        $classes[] = "alert-block";
      }
      $class = implode(' ', $classes);
      $output .= "<div class=\"$class\">\n";
      if (!empty($status_heading[$type])) {
        $output .= _bootstrap3_element_invisible($status_heading[$type]);
      }
      $output .= _bootstrap3_close_button('alert');
      if (count($messages) > 1) {
        $output .= " <ul>\n";
        foreach ($messages as $message) {
          $output .= '  <li>' . $message . "</li>\n";
        }
        $output .= " </ul>\n";
      }
      else {
        $output .= $messages[0];
      }
      $output .= "</div>\n";
    }
    else {
      if (BOOTSTRAP3_ALERT_BLOCK) {
        $classes[] = "alert-block";
      }
      $class = implode(' ', $classes);
      foreach ($messages as $message) {
        $output .= "<div class=\"$class\">\n";
        if (!empty($status_heading[$type])) {
          $output .= _bootstrap3_element_invisible($status_heading[$type]);
        }
        $output .= _bootstrap3_close_button('alert');
        $output .= $message;
        $output .= "</div>\n";
      }
    }
  }

  return '<div id="status-messages">' . $output . '</div>';
}

function bootb2b_preprocess_block_menu(&$variables) {
  if ($variables['name'] == 'menu_main_menu') {
    if (!empty($variables['context']) && $variables['context'] == 'header') {
      if ($variables['content'] == '<div class="container-fluid"></div>') {
        $variables['hide_empty_block'] = TRUE;
        $variables['title'] = '';
        $variables['content'] = '';
      }
    }
  }
}

/**
 * Extends template_preprocess_products_page
 */
function bootb2b_preprocess_products_page(&$variables) {
  unset($variables['columns']);
}

/**
 * Extends template_preprocess_products_page_item
 */
function bootb2b_preprocess_products_page_item(&$variables) {
  unset($variables['subtitle']);
  unset($variables['img']);
  unset($variables['read_more']);

  $product = $variables['product'];
  $variables['sku'] = $product->sku;
  $variables['pack'] = "{$product->productWeight} {$product->weightUnit}/{$product->inventoryUnit}";
  $unit_price = _xdruple_queries_price($product, 1, 0, FALSE) / $product->uomRatio;
  $unit_price = commerce_currency_format($unit_price, 'USD');
  $variables['unit_price'] = "{$unit_price}/{$product->priceUnit}";
  $variables['price'] = "{$variables['price']}/{$product->inventoryUnit}";

  if (($customer = xdruple_rescued_session_get('customer'))
    && ($ship_to = xdruple_rescued_session_get('ship_to'))
  ) {
    $form = xdruple_favorites_get_favorites_form($variables['product']->product_id, $customer, $ship_to);
    $variables['add_to_standard'] = drupal_render($form);
  }
}
