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
  $blocks = array();

  if (drupal_match_menu_path(array(
    'products',
    'products/*'
  ))
  ) {
    $panel    = new \CDD\Bootstrap\Drupal\Panel('categories-tree', xdruple_queries_categories_tree_block(), t('Categories'));
    $blocks[] = $panel->render(-10);
  }

  /** @var stdClass $user */
  global $user;
  if ($user->uid == 0) {
    $panel    = new \CDD\OpenCDD\Panels\FormPanel('user_login_block', 'Login');
    $blocks[] = $panel->render();
  }

  $variables['blocks'] = $blocks;
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
}
