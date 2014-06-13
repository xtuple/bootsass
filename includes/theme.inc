<?php

/**
 * Preprocess function for layout-header.tpl.php
 */
function bootsass_preprocess_layout_header(&$variables) {
  $variables['site_phone'] = theme('block_block', array(
    'name'    => 'site-phone',
    'title'   => variable_get('header_site_phone_title'),
    'content' => variable_get('site_phone_value'),
  ));

  $variables['logo'] = '<div class="b-logo">' . l('Logo', '<front>') . '</div>';

  $variables['main_menu'] = theme('block_menu', array(
    'name'    => 'menu_main_menu',
    'context' => 'header',
  ));

  $variables['search_box'] = theme('block_form', array(
    'name' => 'xdruple_search_search_form',
  ));

  $variables['user_menu'] = theme('block_menu', array(
    'name'    => 'user-menu',
    'context' => 'header',
  ));

  $variables['cart_dropdown'] = theme('block_cart_dropdown', array(
    'name' => 'cart-dropdown',
  ));

  $variables['order_defaults_form'] = theme('block_order_defaults_form', array(
    'name' => 'order-defaults_form',
  ));
}


/**
 * Preprocess function for layout-body-top.tpl.php
 */
function bootsass_preprocess_layout_body_top(&$variables) {
  $variables['messages'] = theme('status_messages');
}

/**
 * Preprocess function for layout-body-middle.tpl.php
 */
function bootsass_preprocess_layout_body_middle(&$variables) {
  if (!empty($variables['content_middle']['#theme_wrappers'])) {
    if (FALSE !== ($key = array_search('region', $variables['content_middle']['#theme_wrappers']))) {
      unset($variables['content_middle']['#theme_wrappers'][$key]);
    }
  }

  $variables['show_content_context'] = _bootsass_show_content_context();
  if ($variables['show_content_context'] == FALSE) {
    $col_lg = 'col-lg-12';
  }
  else {
    $col_lg                                          = 'col-lg-9';
    $variables['context_wrap_attributes']['class'][] = 'col-lg-3';
  }
  $variables['content_wrap_attributes']['class'][] = $col_lg;
}

/**
 * Preprocess function for layout-body-bottom.tpl.php
 */
function bootsass_preprocess_layout_body_bottom(&$variables) {

}

/**
 * Preprocess function for layout-footer.tpl.php
 */
function bootsass_preprocess_layout_footer(&$variables) {
  $variables['main_menu'] = theme('block_menu', array(
    'name'  => 'menu_main_menu',
    'title' => variable_get('footer_main_menu_title', 'Main menu'),
  ));

  $variables['secondary_menu'] = theme('block_menu', array(
    'name'  => 'menu_secondary_menu',
    'title' => variable_get('footer_secondary_menu_title', 'Secondary menu'),
  ));

  $variables['user_menu'] = theme('block_menu', array(
    'name'  => 'user-menu',
    'title' => variable_get('footer_user_menu_title', 'User menu'),
  ));

  $variables['contact_info'] = theme('block_block', array(
    'name'    => 'contact-info',
    'title'   => variable_get('footer_contacts_title'),
    'content' => format_text_variable_get('footer_contacts_value'),
  ));

  $variables['site_phone'] = theme('block_block', array(
    'name'    => 'site-phone',
    'title'   => variable_get('footer_site_phone_title'),
    'content' => variable_get('site_phone_value'),
  ));

  $variables['social_menu'] = theme('block_menu', array(
    'name'  => 'menu-social-menu',
    'title' => variable_get('footer_social_menu_title', 'Social menu'),
  ));

  $variables['copyright'] = theme('block_block', array(
    'name'             => 'site-copyright',
    'content'          => format_text_variable_get('site_copyright'),
    'attributes_array' => array(
      'class' => array('well b-copyright'),
    ),
  ));
}

/**
 * Preprocess function for layout-content-top.tpl.php
 */
function bootsass_preprocess_layout_content_top(&$variables) {
  $variables['tabs'] = theme('menu_local_tasks', array(
    'primary'   => menu_primary_local_tasks(),
    'secondary' => menu_secondary_local_tasks(),
  ));

  $variables['title'] = drupal_get_title();
  $variables['help']  = menu_get_active_help();
}

/**
 * Preprocess function for layout-content-bottom.tpl.php
 */
function bootsass_preprocess_layout_content_bottom(&$variables) {

}

/**
 * Preprocess function for layout-content-context.tpl.php
 */
function bootsass_preprocess_layout_content_context(&$variables) {
  $blocks = array();

  $panel    = new \CDD\Bootstrap\Drupal\Panel('categories-tree', xdruple_queries_categories_tree_block(), t('Categories'));
  $blocks[] = $panel->theme();

  /** @var stdClass $user */
  global $user;
  if ($user->uid == 0) {
    $panel    = new \Xtuple\Xcommerce\Panels\FormPanel('user_login_block', 'Login');
    $blocks[] = $panel->theme();
  }

  $variables['blocks'] = $blocks;
}

function bootsass_process_layout_content_context(&$variables) {
  if (!empty($variables['blocks']['categories_tree'])) {
    $variables['categories_tree'] = $variables['blocks']['categories_tree'];
    unset($variables['blocks']['categories_tree']);
  }
}

function _bootsass_show_content_context() {
  return !drupal_match_menu_path(menu_get_original_path(), _bootsass_page_without_content_context());
}

function _bootsass_page_without_content_context() {
  return array(
    'cart',
    'checkout',
    'checkout/%commerce_order',
    'checkout/%commerce_order/%commerce_checkout_page',
    'user/*',
    'user/*/*',
  );
}
