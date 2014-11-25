<?php

/**
 * @file keeps theme functions overrides
 */

/**
 * Implements hook_theme()
 */
function bootsass_theme() {
  $items = array();

  $items['layout_header'] = array(
    'template' => 'templates/layout/layout-header',
  );
  $items['layout_body_top'] = array(
    'template' => 'templates/layout/layout-body-top',
  );
  $items['layout_body_middle'] = array(
    'template' => 'templates/layout/layout-body-middle',
  );
  $items['layout_body_bottom'] = array(
    'template' => 'templates/layout/layout-body-bottom',
  );
  $items['layout_content_top'] = array(
    'template' => 'templates/layout/layout-content-top',
  );
  $items['layout_content_bottom'] = array(
    'template' => 'templates/layout/layout-content-bottom',
  );
  $items['layout_content_context'] = array(
    'template' => 'templates/layout/layout-content-context',
  );
  $items['layout_footer'] = array(
    'template' => 'templates/layout/layout-footer',
  );

  return $items;
}

/**
 * Preprocess function for page.tpl.php
 */
function bootsass_preprocess_page(&$variables) {
  $variables['header'] = theme('layout_header');
  $variables['body_top'] = theme('layout_body_top');

  $variables['body_middle'] = theme('layout_body_middle', array(
    'content_middle' => $variables['page']['content'],
    'content_top' => theme('layout_content_top'),
    'content_bottom' => theme('layout_content_bottom'),
    'context' => theme('layout_content_context'),
  ));
  $variables['body_bottom'] = theme('layout_body_bottom');
  $variables['footer'] = theme('layout_footer');
}

function bootsass_preprocess_block_menu(&$variables) {
  if ($variables['name'] == 'menu_main_menu') {
    if (!empty($variables['context']) && $variables['context'] == 'header') {
      $variables['attributes_array']['class']['pull-right'] = 'pull-right';

      $variables['content_attributes_array']['class']['navbar'] = 'navbar';
      $variables['content_attributes_array']['class']['navbar-default'] = 'navbar-default';
      $variables['content_attributes_array']['class']['pull-right'] = 'pull-right';

      $variables['hide_empty_title'] = TRUE;

      $variables['content'] = '<div class="container-fluid">' . $variables['content'] . '</div>';
    }
  }
}

function bootsass_preprocess_links(&$variables) {
  if (!empty($variables['menu'])) {
    if ($variables['menu'] == 'menu_main_menu') {
      if (!empty($variables['context']) && $variables['context'] == 'header') {
        $variables['attributes']['class']['nav'] = 'nav';
        $variables['attributes']['class']['navbar-nav'] = 'navbar-nav';
        $variables['attributes']['class']['navbar-right'] = 'navbar-right';
      }
    }
    if ($variables['menu'] == 'user-menu') {
      if (!empty($variables['context']) && $variables['context'] == 'header') {
        $variables['attributes']['class']['pull-right'] = 'pull-right';
      }
    }
    if ($variables['menu'] == 'menu-social-menu') {
      foreach ($variables['links'] as &$link) {
        if ($host = parse_url($link['href'], PHP_URL_HOST)) {
          $parts = array_reverse(explode('.', $host));
          $class = strtolower($parts[1]);
        }
        elseif (strpos($link['href'], 'mailto:') === 0) {
          $class = 'mailto';
        }
        else {
          $temp = explode('/', $link['href']);
          $temp = explode('.', array_pop($temp));
          $class = drupal_clean_css_identifier(array_shift($temp));
        }
        $link['attributes']['class']['social'] = $class;
      }
    }
  }
  
  if (!empty($variables['links'])) {
    $links = array();
    foreach ($variables['links'] as $key => &$link) {
      if (!empty($link['attributes']['class'])) {
        if (in_array('active-trail', $link['attributes']['class'])) {
          $link['attributes']['class'][] = 'active';
          $key .= ' active';
        }
      }
      $links[$key] = $link;
    }
    $variables['links'] = $links;
  }
}

/**
 * @see theme_date_all_day_label()
 * @see date_all_day.module
 *
 * @return string
 */
function bootsass_date_all_day_label() {
  return '';
}

/**
 * @see template_preprocess_field()
 */
function bootsass_preprocess_field(&$variables) {
  if (!empty($variables['element']['#field_type'])
    && $variables['element']['#field_type'] == 'text_with_summary'
  ) {
    $variables['classes_array'][] = 'htmlpurified';
  }

  $variables['row_by'] = 1;
  $variables['content_attributes_array']['class']['field-items'] = 'field-items';
  $variables['group_attributes_array']['class']['field-items-group'] = 'field-items-group';
  $variables['group_attributes_array']['class']['clearfix'] = 'clearfix';

  foreach ($variables['items'] as $i => $item) {
    $variables['item_attributes_array'][$i]['class']['field-item'] = 'field-item';
    $count = 'even';
    if ($i % 2) {
      $count = 'odd';
    }
    $variables['item_attributes_array'][$i]['class']['count'] = $count;
  }
}

/**
 * @see template_process_field()
 */
function bootsass_process_field(&$variables) {
  $variables['group_attributes'] = drupal_attributes($variables['group_attributes_array']);
}

/**
 * @see template_preprocess_views_view_field()
 */
function bootsass_preprocess_views_view_field(&$variables) {
  if (!empty($variables['field']->field_info['type'])
    && $variables['field']->field_info['type'] == 'text_with_summary'
  ) {
    $variables['field']->options['element_wrapper_class'] .= ' htmlpurified';
  }
}

/**
 * Preprocess function for layout-header.tpl.php
 */
function bootsass_preprocess_layout_header(&$variables) {
  $variables['site_phone_array'] = array(
    '#theme' => 'block_block',
    '#name' => 'site-phone',
    '#title' => variable_get('header_site_phone_title'),
    '#content' => variable_get('site_phone_value'),
    '#cache' => array(
      'keys' => array(
        'header',
        'site-phone-block',
      ),
      'expire' => time() + 24 * 60 * 60,
    ),
  );

  $variables['logo'] = '<div class="b-logo">' . l('Logo', '<front>') . '</div>';

  $variables['main_menu_array'] = array(
    '#theme' => 'block_menu',
    '#name' => 'menu_main_menu',
    '#context' => 'header',
    '#cache' => array(
      'keys' => array(
        'header',
        'main-menu',
      ),
      'granularity' => DRUPAL_CACHE_PER_PAGE | DRUPAL_CACHE_PER_USER,
      'expire' => time() + 24 * 60 * 60,
    ),
  );

  $variables['user_menu_array'] = array(
    '#theme' => 'block_menu',
    '#name' => 'user-menu',
    '#context' => 'header',
    '#cache' => array(
      'keys' => array(
        'header',
        'user-menu',
      ),
      'granularity' => DRUPAL_CACHE_PER_PAGE | DRUPAL_CACHE_PER_USER,
      'expire' => time() + 24 * 60 * 60,
    ),
  );
}

/**
 * Preprocess function for layout-header.tpl.php
 *
 * @param $variables
 */
function bootsass_process_layout_header(&$variables) {
  $variables['site_phone'] = drupal_render($variables['site_phone_array']);
  $variables['main_menu'] = drupal_render($variables['main_menu_array']);
  $variables['user_menu'] = drupal_render($variables['user_menu_array']);
}

/**
 * Preprocess function for layout-body-top.tpl.php
 */
function bootsass_preprocess_layout_body_top(&$variables) {
  $variables['messages'] = theme('status_messages');
  $variables['breadcrumb'] = theme('breadcrumb', array(
    'breadcrumb' => drupal_get_breadcrumb(),
  ));
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

  $variables['show_content_context'] = bootsass_show_content_context();
  if ($variables['show_content_context'] == FALSE) {
    $col_lg = 'col-lg-12';
  }
  else {
    $col_lg = 'col-lg-9';
    $variables['context_wrap_attributes']['class']['col-lg'] = 'col-lg-3';
  }
  $variables['context_wrap_attributes']['class']['hidden-print'] = 'hidden-print';
  
  $variables['content_wrap_attributes']['class']['col-lg'] = $col_lg;
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
  $variables['main_menu_array'] = array(
    '#theme' => 'block_menu',
    '#name' => 'menu_main_menu',
    '#title' => variable_get('footer_main_menu_title', 'Main menu'),
    '#title_tag' => 'h4',
    '#cache' => array(
      'keys' => array(
        'footer',
        'main-menu',
      ),
      'granularity' => DRUPAL_CACHE_PER_PAGE | DRUPAL_CACHE_PER_USER,
      'expire' => time() + 24 * 60 * 60,
    ),
  );

  $variables['secondary_menu_array'] = array(
    '#theme' => 'block_menu',
    '#name' => 'menu_secondary_menu',
    '#title' => variable_get('footer_secondary_menu_title', 'Secondary menu'),
    '#title_tag' => 'h4',
    '#cache' => array(
      'keys' => array(
        'footer',
        'secondary-menu',
      ),
      'granularity' => DRUPAL_CACHE_PER_PAGE | DRUPAL_CACHE_PER_USER,
      'expire' => time() + 24 * 60 * 60,
    ),
  );

  $variables['user_menu_array'] = array(
    '#theme' => 'block_menu',
    '#name' => 'user-menu',
    '#title' => variable_get('footer_user_menu_title', 'User menu'),
    '#title_tag' => 'h4',
    '#cache' => array(
      'keys' => array(
        'footer',
        'user-menu',
      ),
      'granularity' => DRUPAL_CACHE_PER_PAGE | DRUPAL_CACHE_PER_USER,
      'expire' => time() + 24 * 60 * 60,
    ),
  );

  $variables['contact_info_array'] = array(
    '#theme' => 'block_block',
    '#name' => 'contact-info',
    '#title' => variable_get('footer_contacts_title'),
    '#title_tag' => 'h4',
    '#content' => format_text_variable_get('footer_contacts_value'),
    '#cache' => array(
      'keys' => array(
        'footer',
        'contact-info',
      ),
      'expire' => time() + 24 * 60 * 60,
    ),
  );

  $variables['site_phone_array'] = array(
    '#theme' => 'block_block',
    '#name' => 'site-phone',
    '#title' => variable_get('footer_site_phone_title'),
    '#title_tag' => 'h4',
    '#content' => variable_get('site_phone_value'),
    '#cache' => array(
      'keys' => array(
        'footer',
        'site-phone',
      ),
      'expire' => time() + 24 * 60 * 60,
    ),
  );

  $variables['social_menu_array'] = array(
    '#theme' => 'block_menu',
    '#name' => 'menu-social-menu',
    '#title' => variable_get('footer_social_menu_title', 'Social menu'),
    '#title_tag' => 'h4',
    '#cache' => array(
      'keys' => array(
        'footer',
        'social-menu',
      ),
      'expire' => time() + 24 * 60 * 60,
    ),
  );

  $variables['copyright_array'] = array(
    '#theme' => 'block_block',
    '#name' => 'site-copyright',
    '#title_tag' => 'h4',
    '#content' => format_text_variable_get('site_copyright'),
    '#attributes_array' => array(
      'class' => array('well b-copyright'),
    ),
    '#cache' => array(
      'keys' => array(
        'footer',
        'copyright',
      ),
      'expire' => time() + 24 * 60 * 60,
    ),
  );
}

/**
 * Process function for layout-footer.tpl.php
 *
 * @param $variables
 */
function bootsass_process_layout_footer(&$variables) {
  $variables['main_menu'] = drupal_render($variables['main_menu_array']);
  $variables['secondary_menu'] = drupal_render($variables['secondary_menu_array']);
  $variables['user_menu'] = drupal_render($variables['user_menu_array']);

  $variables['contact_info'] = drupal_render($variables['contact_info_array']);
  $variables['site_phone'] = drupal_render($variables['site_phone_array']);
  $variables['social_menu'] = drupal_render($variables['social_menu_array']);
  $variables['copyright'] = drupal_render($variables['copyright_array']);
}

/**
 * Preprocess function for layout-content-top.tpl.php
 */
function bootsass_preprocess_layout_content_top(&$variables) {
  $variables['tabs'] = theme('menu_local_tasks', array(
    'primary' => menu_primary_local_tasks(),
    'secondary' => menu_secondary_local_tasks(),
  ));

  $variables['title'] = drupal_get_title();
  $variables['help'] = menu_get_active_help();
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

  /** @var stdClass $user */
  global $user;
  if ($user->uid == 0) {
    $panel = new \CDD\OpenCDD\Panels\FormPanel('user_login_block', 'Login');
    $blocks['user_login_block'] = $panel->render();
  }

  $variables['blocks'] = $blocks;
}

function bootsass_show_content_context() {
  return !drupal_match_menu_path(bootsass_page_exclude_content_context());
}

function bootsass_page_exclude_content_context() {
  $exclude_context = array(
    'cart',
    'checkout',
    'checkout/%commerce_order',
    'checkout/%commerce_order/%commerce_checkout_page',
    'user/*',
    'user/*/*',
    'bootstrap',
    'bootstrap/*',
  );

  drupal_alter('bootsass_page_exclude_content_context', $exclude_context);

  return $exclude_context;
}
