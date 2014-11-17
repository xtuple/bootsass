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

/**
 * Implements hook_form_FORM_ID_alter() for xdruple_favorites_add_to_favorites_form
 *
 * @see xdruple_favorites_add_to_favorites_form()
 */
function bootb2b_form_xdruple_favorites_add_to_favorites_form_alter(&$form, &$form_state) {
  $form['submit']['#value'] = '<i class="glyphicon glyphicon-star-empty"></i>';
  $form['submit']['#attributes']['class']['btn-add-favorites'] = 'btn-add-favorites';
}

/**
 * Implements hook_form_FORM_ID_alter() for xdruple_favorites_remove_from_favorites
 *
 * @see xdruple_favorites_remove_from_favorites_form()
 */
function bootb2b_form_xdruple_favorites_remove_from_favorites_form_alter(&$form, &$form_state) {
  $form['submit']['#value'] = '<i class="glyphicon glyphicon-star"></i>';
  $form['submit']['#attributes']['class']['btn-remove-favorites'] = 'btn-remove-favorites';
}

/**
 * Implements hook_xdruple_favorites_favorites_form_submit_ajax_commands_alter()
 *
 * @see xdruple_favorites_favorites_form_submit_ajax_commands()
 */
function bootb2b_xdruple_favorites_favorites_form_submit_ajax_commands_alter(&$commands, $form, $form_state, $new_form) {
  if ($form['#page_url'] == 'products/favorites') {
    if (!empty($commands['replace'])) {
      $commands['remove'] = ajax_command_remove("tr:has(#{$new_form['#id']})");
    }
  }
}

/**
 * Implements hook_form_FORM_ID_later for commerce_cart_add_to_cart_form
 */
function bootb2b_form_commerce_cart_add_to_cart_form_alter(&$form, &$form_state) {
  $form['quantity']['#attributes'] = array(
    'tabindex' => 1,
  );

  global $user;
  $products = array();
  if ($order = commerce_cart_order_load($user->uid)) {
    if (!empty($order->commerce_line_items[LANGUAGE_NONE])) {
      foreach ($order->commerce_line_items[LANGUAGE_NONE] as $item) {
        $line_item = commerce_line_item_load($item['line_item_id']);

        $product_id = $line_item->commerce_product[LANGUAGE_NONE][0]['product_id'];
        $quantity = $line_item->quantity;

        $products[$product_id] = (int) $quantity;
      }
    }
  }
  if (!empty($products[$form['product_id']['#value']])) {
    $form['quantity']['#default_value'] = $products[$form['product_id']['#value']];
  }
  if (!empty($form_state['values']['quantity'])) {
    $form['quantity']['#default_value'] = $form_state['values']['quantity'];
  }

  $form['quantity']['#ajax'] = array(
    'callback' => '_bootb2b_add_to_cart_form_ajax_handler',
    'event' => 'change',
    'effect' => 'fade',
    'trigger_as' => array(
      'name' => 'op',
    ),
  );
  $form['submit']['#ajax'] = array(
    'callback' => '_bootb2b_add_to_cart_form_ajax_handler',
  );
  $form['#validate'] = array('bootb2b_commerce_cart_add_to_cart_form_validate_override');
  $form['#submit'] = array(
    'bootb2b_form_commerce_cart_add_to_cart_form_submit',
    'bootb2b_commerce_cart_add_to_cart_form_submit_override'
  );
}

/**
 * Validation handler for commerce_cart_add_to_cart_form; overrides default
 * commerce_cart_add_to_cart_form_validate();
 */
function bootb2b_commerce_cart_add_to_cart_form_validate_override($form, &$form_state) {
  // Check to ensure the quantity is valid.
  if ($form_state['values']['quantity'] == '') {
    $form_state['values']['quantity'] = 0;
  }
  if (!is_numeric($form_state['values']['quantity']) || $form_state['values']['quantity'] < 0) {
    form_set_error('quantity', t('You must specify a valid quantity to add to the cart.'));
  }

  // If the custom data type attribute of the quantity element is integer,
  // ensure we only accept whole number values.
  if ($form['quantity']['#datatype'] == 'integer' &&
    (int) $form_state['values']['quantity'] != $form_state['values']['quantity']
  ) {
    form_set_error('quantity', t('You must specify a whole number for the quantity.'));
  }

  // If the attributes matching product selector was used, set the value of the
  // product_id field to match; this will be fixed on rebuild when the actual
  // default product will be selected based on the product selector value.
  if (!empty($form_state['values']['attributes']['product_select'])) {
    form_set_value($form['product_id'], $form_state['values']['attributes']['product_select'], $form_state);
  }

  // Validate any line item fields that may have been included on the form.
  field_attach_form_validate('commerce_line_item', $form_state['line_item'], $form['line_item_fields'], $form_state);
}

/**
 * Submit handler for commerce_cart_add_to_cart_form
 */
function bootb2b_form_commerce_cart_add_to_cart_form_submit(&$form, &$form_state) {
  $values = $form_state['values'];
  $product_id = (int) $values['product_id'];
  $uid = $values['uid'];

  $order = commerce_cart_order_load($uid);
  if (empty($order)) {
    $order = commerce_cart_order_new($uid);
  }

  if (!empty($order)) {
    $order_wrapper = entity_metadata_wrapper('commerce_order', $order);
    foreach ($order_wrapper->commerce_line_items as $line_item_wrapper) {
      $line_item_id = $line_item_wrapper->line_item_id->value();
      $line_item = commerce_line_item_load($line_item_id);
      if ($line_item->commerce_product['und'][0]['product_id'] == $product_id) {
        $order = commerce_cart_order_product_line_item_delete($order, $line_item_id, TRUE);
      }
    }
  }
}

/**
 * Submission handler for commerce_cart_add_to_cart_form; overrides default
 * commerce_cart_add_to_cart_form_submit();
 */
function bootb2b_commerce_cart_add_to_cart_form_submit_override($form, &$form_state) {
  $product_id = $form_state['values']['product_id'];
  $product = commerce_product_load($product_id);

  if ($form_state['values']['quantity'] > 0) {
    // If the line item passed to the function is new...
    if (empty($form_state['line_item']->line_item_id)) {
      // Create the new product line item of the same type.
      $line_item = commerce_product_line_item_new($product, $form_state['values']['quantity'], 0, $form_state['line_item']->data, $form_state['line_item']->type);

      // Allow modules to prepare this as necessary. This hook is defined by the
      // Product Pricing module.
      drupal_alter('commerce_product_calculate_sell_price_line_item', $line_item);

      // Remove line item field values the user didn't have access to modify.
      foreach ($form_state['values']['line_item_fields'] as $field_name => $value) {
        // Note that we're checking the Commerce Cart settings that we inserted
        // into this form element array back when we built the form. This means a
        // module wanting to alter a line item field widget to be available must
        // update both its form element's #access value and the field_access value
        // of the #commerce_cart_settings array.
        if (empty($form['line_item_fields'][$field_name]['#commerce_cart_settings']['field_access'])) {
          unset($form_state['values']['line_item_fields'][$field_name]);
        }
      }

      // Unset the line item field values array if it is now empty.
      if (empty($form_state['values']['line_item_fields'])) {
        unset($form_state['values']['line_item_fields']);
      }

      // Add field data to the line item.
      field_attach_submit('commerce_line_item', $line_item, $form['line_item_fields'], $form_state);

      // Process the unit price through Rules so it reflects the user's actual
      // purchase price.
      rules_invoke_event('commerce_product_calculate_sell_price', $line_item);

      // Only attempt an Add to Cart if the line item has a valid unit price.
      $line_item_wrapper = entity_metadata_wrapper('commerce_line_item', $line_item);

      if (!is_null($line_item_wrapper->commerce_unit_price->value())) {
        // Add the product to the specified shopping cart.
        $form_state['line_item'] = commerce_cart_product_add(
          $form_state['values']['uid'],
          $line_item,
          isset($line_item->data['context']['add_to_cart_combine']) ? $line_item->data['context']['add_to_cart_combine'] : TRUE
        );

        if ($form['quantity']['#default_value'] == 0) {
          drupal_set_message(t('%title added to !cart.', array(
            '%title' => $product->title,
            '!cart' => l('your cart', 'cart'),
          )));
        }
        elseif ($form['quantity']['#default_value'] !== $form_state['values']['quantity']) {
          drupal_set_message(t('%title quantity updated in !cart.', array(
            '%title' => $product->title,
            '!cart' => l('your cart', 'cart'),
          )));
        }
      }
      else {
        drupal_set_message(t('%title could not be added to your cart.', array('%title' => $product->title)), 'error');
      }
    }
  }
  else {
    if ($form['quantity']['#default_value'] > 0) {
      drupal_set_message(t('%title removed from your cart.', array('%title' => $product->title)), 'warning');
    }
  }
}

/**
 * AJAX handler for commerce_cart_add_to_cart_form
 */
function _bootb2b_add_to_cart_form_ajax_handler($form, $form_state) {
  $commands = array();

  // Replace the form with the new one
  $selector = "#{$form['#id']}";
  $form = drupal_rebuild_form($form['#form_id'], $form_state, $form);
  // If field was sent as empty, still render 0 on return
  if ($form['quantity']['#default_value'] == 0) {
    $form['quantity']['#value'] = 0;
  }
  $commands[] = ajax_command_replace($selector, drupal_render($form));

  // Show status messages
  $commands[] = ajax_command_insert('#status-messages', theme('status_messages'));

  // Update cart
  if (user_access('access content')) {
    global $user;
    if ($order = commerce_cart_order_load($user->uid)) {
      $cart = commerce_embed_view('ft_commerce_cart_block', 'default', array($order->order_id), 'cart');
      $commands[] = ajax_command_replace('.b-block-core-cart--content .view-ft-commerce-cart-block, .b-block-core-cart--content .empty-cart', $cart, array(
        'effect' => 'fade'
      ));

      $commands[] = ajax_command_replace('.b-block-cart-dropdown', theme('block_cart_dropdown', array(
        'name' => 'cart-dropdown',
      )), array(
        'effect' => 'fade',
      ));
    }
  }

  return array(
    '#type' => 'ajax',
    '#commands' => $commands,
  );
}
