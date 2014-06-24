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

  $panel    = new \CDD\Bootstrap\Drupal\Panel('categories-tree', xdruple_queries_categories_tree_block(), t('Categories'));
  $blocks[] = $panel->render(-10);

  /** @var stdClass $user */
  global $user;
  if ($user->uid == 0) {
    $panel    = new \Xtuple\Xcommerce\Panels\FormPanel('user_login_block', 'Login');
    $blocks[] = $panel->render();
  }

  $variables['blocks'] = $blocks;
}
