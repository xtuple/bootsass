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
    $panel = new \CDD\Bootstrap\Drupal\Panel('categories-tree', xdruple_queries_categories_tree_block(), t('Categories'));
    $blocks[] = $panel->render(-10);
  }

  /** @var stdClass $user */
  global $user;
  if ($user->uid == 0) {
    $panel = new \CDD\OpenCDD\Panels\FormPanel('user_login_block', 'Login');
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

  if ($variables['element']['#field_name'] == 'xt_url_image_field') {
    $variables['classes_array'][] = 'clearfix';
    foreach ($variables['items'] as $i => &$item) {
      if ($i == 0) {
        $item['#settings']['preview'] = 'ft_commerce_product_page_thumb';
      }
      $item['#class'][] = 'img-thumbnail';
    }
  }
}

/**
 * @see template_preprocess_entity()
 */
function kellyhayes_preprocess_entity(&$variables) {
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

    $variables['bottom_attributes_array']['class']['col-lg'] = 'col-lg-12';

    if (!empty($variables['content']['product_price']['#weight'])) {
      $price = new \CDD\Bootstrap\Drupal\Label($variables['content']['product_price']['#markup'], \CDD\Bootstrap\Common\Context::PRIMARY);
      $variables['content']['product_price'] = $price->render(-11);
      $variables['content']['product_price']['#prefix'] = '<div class="field field-product-price">';
      $variables['content']['product_price']['#suffix'] = '</div>';
      unset($variables['content']['product_price']['#markup']);
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
    foreach ($context_fields as $field) {
      if (!empty($variables['content'][$field])) {
        $variables['context'][$field] = $variables['content'][$field];
        unset($variables['content'][$field]);
      }
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
}

/**
 * @see template_process_entity()
 */
function bootcommerce_process_entity(&$variables) {
  if ($variables['view_mode'] == 'full' && $variables['entity_type'] == 'commerce_product') {
    $variables['context_attributes'] = drupal_attributes($variables['context_attributes_array']);
    $variables['bottom_attributes'] = drupal_attributes($variables['bottom_attributes_array']);
  }
}
