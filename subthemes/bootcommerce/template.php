<?php

/**
 * @file keeps theme functions overrides
 */

function bootcommerce_theme() {
  $items = array();

  $items += \Xtuple\Xcommerce\Theme\Layout::themeDefinition('header');
  $items += \Xtuple\Xcommerce\Theme\Layout::themeDefinition('body_top');
  $items += \Xtuple\Xcommerce\Theme\Layout::themeDefinition('body_middle');
  $items += \Xtuple\Xcommerce\Theme\Layout::themeDefinition('body_bottom');
  $items += \Xtuple\Xcommerce\Theme\Layout::themeDefinition('content_top');
  $items += \Xtuple\Xcommerce\Theme\Layout::themeDefinition('content_bottom');
  $items += \Xtuple\Xcommerce\Theme\Layout::themeDefinition('content_context');
  $items += \Xtuple\Xcommerce\Theme\Layout::themeDefinition('footer');

  return $items;
}

/**
 * Preprocess function for page.tpl.php
 */
function bootcommerce_preprocess_page(&$variables) {
  $variables['header']   = theme('layout_header');
  $variables['body_top'] = theme('layout_body_top');

  $variables['body_middle'] = theme('layout_body_middle', array(
    'content_middle' => $variables['page']['content'],
    'content_top'    => theme('layout_content_top'),
    'content_bottom' => theme('layout_content_bottom'),
    'context'        => theme('layout_content_context'),
  ));
  $variables['body_bottom'] = theme('layout_body_bottom');
  $variables['footer']      = theme('layout_footer');
}

function bootcommerce_preprocess_block_menu(&$variables) {
  if ($variables['name'] == 'menu_main_menu') {
    if (!empty($variables['context']) && $variables['context'] == 'header') {
      $variables['attributes_array']['class'][] = 'pull-right';

      $variables['content_attributes_array']['class'][] = 'navbar';
      $variables['content_attributes_array']['class'][] = 'navbar-default';
      $variables['content_attributes_array']['class'][] = 'pull-right';

      $variables['hide_empty_title'] = TRUE;

      $variables['content'] = '<div class="container-fluid">' . $variables['content'] . '</div>';
    }
  }
}

function bootcommerce_preprocess_links(&$variables) {
  if (!empty($variables['menu'])) {
    if ($variables['menu'] == 'menu_main_menu') {
      if (!empty($variables['context']) && $variables['context'] == 'header') {
        $variables['attributes']['class'][] = 'nav';
        $variables['attributes']['class'][] = 'navbar-nav';
        $variables['attributes']['class'][] = 'navbar-right';
      }
    }
    if ($variables['menu'] == 'user-menu') {
      if (!empty($variables['context']) && $variables['context'] == 'header') {
        $variables['attributes']['class'][] = 'pull-right';
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
          $temp  = explode('/', $link['href']);
          $temp  = explode('.', array_pop($temp));
          $class = drupal_clean_css_identifier(array_shift($temp));
        }
        $link['attributes']['class'][] = $class;
      }
    }
  }
}
