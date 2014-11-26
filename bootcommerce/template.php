<?php

/**
 * @file keeps theme functions overrides
 */

function bootcommerce_preprocess_layout_header(&$variables) {
  $variables['search_box'] = theme('block_form', array(
    'name' => 'xdruple_search_search_form',
  ));

  $variables['cart_dropdown'] = theme('block_cart_dropdown', array(
    'name' => 'cart-dropdown',
  ));

  $variables['order_defaults_form'] = theme('block_order_defaults_form', array(
    'name' => 'order-defaults_form',
  ));
}

/**
 * Preprocess function for layout-content-context.tpl.php
 */
function bootcommerce_preprocess_layout_content_context(&$variables) {
  $blocks = &$variables['blocks'];

  if (drupal_match_menu_path(array(
    'products',
    'products/*'
  ))
  ) {
    $panel = new \Xtuple\XdrupleQueries\Theme\CategoriesDropdown();
    $blocks['categories_dropdown'] = $panel->render(-10);
  }
}

/**
 * @see template_preprocess_xdruple_commerce_similar_product_link_default()
 */
function bootcommerce_preprocess_xdruple_commerce_similar_product_link_default(&$variables) {
  $variables['thumbnail_attributes_array']['class'][] = 'thumbnail';
}

/**
 * @see template_preprocess_field()
 */
function bootcommerce_preprocess_field(&$variables) {
  if ($variables['element']['#field_name'] == 'substitutes') {
    $variables['content_attributes_array']['class']['row'] = 'row';

    foreach ($variables['items'] as $i => $item) {
      $variables['item_attributes_array'][$i]['class']['col-lg'] = 'col-lg-4';
    }
    $variables['row_by'] = 3;
  }

  if ($variables['element']['#field_name'] == 'xt_url_image_field') {
    $variables['classes_array'][] = 'clearfix';
    foreach ($variables['items'] as $i => &$item) {
      if ($i == 0) {
        $item['#settings']['preview'] = 'ft_commerce_product_page_thumb';
      }
      $item['#class'][] = 'img-thumbnail';
    }
  }

  if ($variables['element']['#bundle'] == 'shipping'
    || $variables['element']['#bundle'] == 'billing'
  ) {
    $variables['classes_array']['col-lg-4'] = 'col-lg-4';
  }
}

/**
 * @see template_preprocess_entity()
 */
function bootcommerce_preprocess_entity(&$variables) {
  $variables['context_attributes_array'] = array();
  $variables['bottom_attributes_array'] = array();
  $variables['characteristics_attributes_array'] = array();

  if ($variables['view_mode'] == 'full'
    && $variables['entity_type'] == 'commerce_product'
  ) {
    $variables['classes_array']['row'] = 'row';
    $variables['content_attributes_array']['class']['col-lg'] = 'col-lg-8';
    $variables['context_attributes_array'] = array(
      'class' => array(
        'col-lg' => 'col-lg-4',
      ),
    );

    $variables['content']['inventoryUnit']['#access'] = TRUE;
    $variables['content']['priceUnit']['#access'] = FALSE;
    $variables['content']['inventoryUnit']['#weight'] = $variables['content']['priceUnit']['#weight'];

    $variables['characteristics_attributes_array']['class']['xt-chars-wrapper'] = 'xt-chars-wrapper';
    $variables['characteristics_attributes_array']['class']['col-lg'] = 'col-lg-12';
    $variables['bottom_attributes_array']['class']['col-lg'] = 'col-lg-12';

    $variables['characteristics_title'] = t('Characteristics');

    if (!empty($variables['content']['product_price']['#weight'])) {
      $variables['content']['product_price']['#prefix'] = '<div class="field field-product-price field-label-above">';
      $variables['content']['product_price']['#suffix'] = '</div>';
      $variables['content']['product_price']['#weight'] = -11;
      $markup = '';
      $markup .= '<div class="field-label">' . t('Price') . ':&nbsp;</div>';
      $unit_key = $variables['content']['inventoryUnit']['#items'][0]['value'];
      $units = xdruple_fields_get_uom_list();
      $unit = $units[$unit_key];
      $variables['unit'] = $unit;
      $markup .= '<div class="field-item"><span>' . $variables['content']['product_price']['#markup'] .
        '</span><small> / ' . $variables['unit'] . '</small>' . '</div>';
      $variables['content']['product_price']['#markup'] = $markup;
    }

    if (!empty($variables['content']['add_to_cart']['#weight'])) {
      $variables['content']['add_to_cart']['#weight'] = -10;
    }

    $context_fields = array(
      'product_price',
      'add_to_cart',
      'xt_url_image_field',
      'xt_url_file_field',
      'xt_url_link_field',
    );

    if (module_exists('xdruple_favorites')) {
      if (($customer = xdruple_rescued_session_get('customer'))
        && ($ship_to = xdruple_rescued_session_get('ship_to'))
      ) {
        $product_id = $variables['content']['product_id']['#items'][0]['value'];
        $form = xdruple_favorites_get_favorites_form($product_id, $customer, $ship_to);
        $variables['content']['add_to_standard']['#markup'] = drupal_render($form);
        $variables['content']['add_to_standard']['#weight'] = '-9';
      }
      $context_fields[] = 'add_to_standard';
    }

    foreach ($context_fields as $field) {
      if (!empty($variables['content'][$field])) {
        $variables['context'][$field] = $variables['content'][$field];
        unset($variables['content'][$field]);
      }
    }

    if (!empty($variables['content']['xt_char'])) {
      $variables['characteristics'] = $variables['content']['xt_char'];
      $variables['characteristics']['#access'] = TRUE;
      unset($variables['content']['xt_chars']);
    }

    $bottom_fields = array(
      'substitutes',
    );
    foreach ($bottom_fields as $field) {
      if (!empty($variables['content'][$field])) {
        $variables['bottom'][$field] = $variables['content'][$field];
        unset($variables['content'][$field]);
      }
    }
  }

  if ($variables['entity_type'] == 'xtuple_xdaddress') {
    $variables['content']['#prefix'] = '<address>';
    $variables['content']['#suffix'] = '</address>';
  }
}

/**
 * Implements hook_form_FORM_ID_alter() for xdruple_favorites_add_to_favorites_form
 *
 * @see xdruple_favorites_add_to_favorites_form()
 */
function bootcommerce_form_xdruple_favorites_add_to_favorites_form_alter(&$form, &$form_state) {
  $form['submit']['#value'] = '<i class="glyphicon glyphicon-star-empty"></i> Add to Favorites';
  $form['submit']['#attributes']['class']['btn-add-favorites'] = 'btn-add-favorites';
}

/**
 * Implements hook_form_FORM_ID_alter() for xdruple_favorites_remove_from_favorites
 *
 * @see xdruple_favorites_remove_from_favorites_form()
 */
function bootcommerce_form_xdruple_favorites_remove_from_favorites_form_alter(&$form, &$form_state) {
  $form['submit']['#value'] = '<i class="glyphicon glyphicon-star"></i> Remove from Favorites';
  $form['submit']['#attributes']['class']['btn-remove-favorites'] = 'btn-remove-favorites';
}

/**
 * @see template_process_entity()
 */
function bootcommerce_process_entity(&$variables) {
  $variables['context_attributes'] = drupal_attributes($variables['context_attributes_array']);
  $variables['bottom_attributes'] = drupal_attributes($variables['bottom_attributes_array']);
  $variables['characteristics_attributes'] = drupal_attributes($variables['characteristics_attributes_array']);
}

/**
 * @see theme_file_link()
 */
function bootcommerce_file_link(&$variables) {
  $file = $variables['file'];
  $icon_directory = $variables['icon_directory'];

  $url = file_create_url($file->uri);
  $icon = theme('file_icon', array('file' => $file, 'icon_directory' => $icon_directory));

  // Set options as per anchor format described at
  // http://microformats.org/wiki/file-format-examples
  $options = array(
    'attributes' => array(
      'type' => $file->filemime . '; length=' . $file->filesize,
      'target' => '_blank',
    ),
  );

  // Use the description as the link text if available.
  if (empty($file->description)) {
    $link_text = $file->filename;
  }
  else {
    $link_text = $file->description;
    $options['attributes']['title'] = check_plain($file->filename);
  }

  return '<span class="file">' . $icon . ' ' . l($link_text, $url, $options) . '</span>';
}

/**
 * Implements hook_form_alter()
 *
 * @param $form
 * @param $form_state
 * @param $form_id
 */
function bootcommerce_form_alter(&$form, &$form_state, $form_id) {
  if (!empty($form['#attributes']['class'][0])) {
    if ($form['#attributes']['class'][0] == 'commerce-add-to-cart') {
      $form['#attributes']['class']['clearfix'] = 'clearfix';
      $form['quantity']['#title_display'] = 'invisible';
    }
  }
}

function bootcommerce_form_commerce_checkout_form_checkout_alter(&$form, &$form_state) {
  $form['customer_profile_shipping']['#wrapper_attributes'] = array(
    'class' => array(
      'row' => 'row',
    ),
  );
  $form['customer_profile_billing']['#wrapper_attributes'] = array(
    'class' => array(
      'row' => 'row',
    ),
  );
  $form['customer_profile_shipping']['xd_ship_to_address']['#attributes']['class']['col-lg-6'] = 'col-lg-6';
  $form['customer_profile_shipping']['xd_ship_to_contact']['#attributes']['class']['col-lg-6'] = 'col-lg-6';

  $form['customer_profile_billing']['xd_bill_to_address']['#attributes']['class']['col-lg-6'] = 'col-lg-6';
  $form['customer_profile_billing']['xd_bill_to_contact']['#attributes']['class']['col-lg-6'] = 'col-lg-6';

  $form['customer_profile_shipping']['xd_ship_to']['#attributes']['class']['col-lg-12'] = 'col-lg-12';
  $form['customer_profile_billing']['xd_customer']['#attributes']['class']['col-lg-12'] = 'col-lg-12';
}

function bootcommerce_form_commerce_checkout_form_review_alter(&$form, &$form_state) {
  $form['help']['#prefix'] = '<div class="checkout-help-wrapper well well-sm">';
  $form['help']['#suffix'] = '</div>';
}
