<?php

/**
 * @file keeps theme functions overrides
 */

function bootcommerce_theme() {
  $items = [];
  $templates = drupal_get_path("theme", "bootcommerce") . "/templates";
  $items["bootcommerce_commerce_payment_credit_cart"] = [
    "render element" => "element",
    "template" => "bootcommerce-commerce-payment-credit-card",
    "path" => "$templates/commerce_payment",
  ];
  $items += \CDD\OpenCDD\Theme\Block::themeDefinition("bootcommerce_cart_block", [
    "hide_empty_title" => FALSE,
    "hide_empty_content" => FALSE,
    "hide_empty_block" => FALSE,
  ]);
  return $items;
}

function bootcommerce_preprocess_layout_header(&$variables) {
  $variables['search_box'] = theme('block_form', [
    'name' => 'xdruple_search_search_form',
  ]);

  $variables['cart_dropdown'] = theme('block_cart_dropdown', [
    'name' => 'cart-dropdown',
  ]);
  /** @var \Xtuple\Drupal7\Proxy\User\CommerceUserProxy $user */
  global $user;
  if ($user->xdUserAssociation()) {
    if ($user->xdUserAssociation()->xdUserAssociation()) {
      if ($user->xdUserAssociation()->xdUserAssociation()->userAssociation()->isCustomer()
        || $user->xdUserAssociation()->xdUserAssociation()->userAssociation()->isSalesRep()
      ) {
        $variables['order_defaults_form'] = theme('block_order_defaults_form', [
          'name' => 'order-defaults_form',
        ]);
      }
    }
  }
  elseif ($user->uid() == 1) {
    $variables['order_defaults_form'] = theme('block_order_defaults_form', [
      'name' => 'order-defaults_form',
    ]);
  }
}

/**
 * Preprocess function for layout-content-context.tpl.php
 */
function bootcommerce_preprocess_layout_content_context(&$variables) {
  $blocks = &$variables['blocks'];

  if (drupal_match_menu_path([
    'products',
    'products/*'
  ])
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
        $item['#settings']['preview'] = 'commerce_product_thumbnail_style';
      }
      $item['#class'][] = 'img-thumbnail';
    }
  }

  if ($variables['element']['#bundle'] == 'shipping'
    || $variables['element']['#bundle'] == 'billing'
  ) {
    $variables['classes_array']['col-lg-4'] = 'col-lg-4';
  }

  if ($variables['element']['#bundle'] == 'xtuple_xdshipto'
    && $variables['element']['#field_type'] == 'entityreference'
  ) {
    $variables['classes_array']['col-lg-4'] = 'col-lg-4';
  }
}

/**
 * @see theme_file_link()
 */
function bootcommerce_file_link(&$variables) {
  $file = $variables['file'];
  $icon_directory = $variables['icon_directory'];

  $url = file_create_url($file->uri);
  $icon = theme('file_icon', ['file' => $file, 'icon_directory' => $icon_directory]);

  // Set options as per anchor format described at
  // http://microformats.org/wiki/file-format-examples
  $options = [
    'attributes' => [
      'type' => $file->filemime . '; length=' . $file->filesize,
      'target' => '_blank',
    ],
  ];

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

/**
 * Implements hook_form_FORM_ID_alter() for commerce_checkout_form_checkout
 *
 * @param $form
 * @param $form_state
 */
function bootcommerce_form_commerce_checkout_form_checkout_alter(&$form, &$form_state) {
  if (!empty($form['buttons']['cancel']['#attributes']['class'])) {
    foreach ($form['buttons']['cancel']['#attributes']['class'] as $i => $value) {
      if ($value == 'checkout-cancel') {
        unset($form['buttons']['cancel']['#attributes']['class'][$i]);
      }
    }
  }
  $form['buttons']['cancel']['#attributes']['class']['btn-danger'] = 'btn-danger';

  foreach (["customer_profile_shipping", "customer_profile_billing"] as $profile_type) {
    if (!empty($form[$profile_type]["xd_contact"])) {
      /** @see preprocess_xdruple_contact_form_element */
      $xd_contact = &$form[$profile_type]["xd_contact"];
      $xd_contact["#attributes"]["class"]["row"] = "row";
      $language = LANGUAGE_NONE;
      if (!empty($xd_contact["#language"])) {
        $language = $xd_contact["#language"];
      }
      if (!empty($xd_contact[$language])) {
        foreach (element_children($xd_contact[$language]) as $delta) {
          $xd_contact[$language][$delta]["value"]["#variables"]["contact_attributes_array"]["class"]["col-lg-6"] = "col-lg-6";
          $xd_contact[$language][$delta]["value"]["#variables"]["address_attributes_array"]["class"]["col-lg-6"] = "col-lg-6";
        }
      }
    }
  }
}

/**
 * Implements hook_form_FORM_ID_alter() for commerce_checkout_form_review
 *
 * @param $form
 * @param $form_state
 */
function bootcommerce_form_commerce_checkout_form_review_alter(&$form, &$form_state) {
  $form['help']['#prefix'] = '<div class="checkout-help-wrapper well well-sm">';
  $form['help']['#suffix'] = '</div>';

  if (!empty($form['buttons']['back']['#attributes']['class'])) {
    foreach ($form['buttons']['back']['#attributes']['class'] as $i => $value) {
      if ($value == 'checkout-back') {
        unset($form['buttons']['back']['#attributes']['class'][$i]);
      }
    }
  }
  $form['buttons']['back']['#attributes']['class']['btn-danger'] = 'btn-danger';

  if (!empty($form["commerce_payment"]["payment_method"]["#options"])
    && sizeof($form["commerce_payment"]["payment_method"]["#options"]) == 1
  ) {
    $payment_method = &$form["commerce_payment"]["payment_method"];
    $payment_method["#type"] = "value";
    $payment_method["#value"] = $payment_method["#default_value"];
  }
  if (!empty($form["commerce_payment"]["payment_details"]["credit_card"])) {
    $credit_card = &$form["commerce_payment"]["payment_details"]["credit_card"];
    $credit_card["#theme"] = "bootcommerce_commerce_payment_credit_cart";
    $credit_card["#element_validate"] = [
      "bootcommerce_commerce_payment_credit_cart_validate",
    ];
    unset($credit_card["#attached"]["css"]);
    $credit_card["type"]["#type"] = "radios";
    $credit_card["type"]["#title"] = "";
    $credit_card["number"]["#size"] = 20;
    $credit_card["exp_month"]["#type"] = "textfield";
    $credit_card["exp_month"]["#title"] = t("Month");
    $credit_card["exp_month"]["#default_value"] = "";
    $credit_card["exp_month"]["#maxlength"] = 2;
    $credit_card["exp_month"]["#size"] = 2;
    $credit_card["exp_month"]["#attributes"]["placeholder"] = "MM";
    unset($credit_card["exp_month"]["#prefix"]);
    unset($credit_card["exp_month"]["#suffix"]);
    $credit_card["exp_year"]["#type"] = "textfield";
    $credit_card["exp_year"]["#title"] = t("Year");
    $credit_card["exp_year"]["#default_value"] = "";
    $credit_card["exp_year"]["#required"] = TRUE;
    $credit_card["exp_year"]["#maxlength"] = 2;
    $credit_card["exp_year"]["#size"] = 2;
    $credit_card["exp_year"]["#attributes"]["placeholder"] = "YY";
    $credit_card["code"]["#title"] = "CVV/CSC";
    $credit_card["code"]["#size"] = 4;
    unset($credit_card["exp_year"]["#prefix"]);
    unset($credit_card["exp_year"]["#suffix"]);
    unset($credit_card["exp_year"]["#options"]);
  }
}

/**
 * Helper function for bootcommerce_form_commerce_checkout_form_review_alter()
 *
 * @param $element
 * @param $form_state
 * @param $form
 */
function bootcommerce_commerce_payment_credit_cart_validate($element, &$form_state, $form) {
  $input = drupal_array_get_nested_value($form_state["values"], $element["#parents"]);
  $input["exp_year"] = substr(date('Y'), 0, 2) . $input["exp_year"];
  preg_match_all('!\d+!', $input["number"], $matches);
  if (!empty($matches[0])) {
    $input["number"] = implode("", $matches[0]);
  }
  drupal_array_set_nested_value($form_state["values"], $element["#parents"], $input);
}

/**
 * Implements hook_form_FORM_ID_alter() for commerce_checkout_form_shipping
 *
 * @param $form
 * @param $form_state
 */
function bootcommerce_form_commerce_checkout_form_shipping_alter(&$form, $form_state) {
  if (!empty($form['buttons']['back']['#attributes']['class'])) {
    foreach ($form['buttons']['back']['#attributes']['class'] as $i => $value) {
      if ($value == 'checkout-back') {
        unset($form['buttons']['back']['#attributes']['class'][$i]);
      }
    }
  }
  $form['buttons']['back']['#attributes']['class']['btn-danger'] = 'btn-danger';
}

function bootcommerce_preprocess_xdruple_xd_user_association_default_formatter(&$variables) {
  foreach ($variables["rows"] as &$row) {
    $row["attributes_array"]["class"]["row"] = "row";
  }
}
