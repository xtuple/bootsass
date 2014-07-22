<?php

/**
 * @file keeps theme functions overrides
 */

function bootsass_theme() {
  $items = array();

  $items += \CDD\OpenCDD\Theme\Layout::themeDefinition('header');
  $items += \CDD\OpenCDD\Theme\Layout::themeDefinition('body_top');
  $items += \CDD\OpenCDD\Theme\Layout::themeDefinition('body_middle');
  $items += \CDD\OpenCDD\Theme\Layout::themeDefinition('body_bottom');
  $items += \CDD\OpenCDD\Theme\Layout::themeDefinition('content_top');
  $items += \CDD\OpenCDD\Theme\Layout::themeDefinition('content_bottom');
  $items += \CDD\OpenCDD\Theme\Layout::themeDefinition('content_context');
  $items += \CDD\OpenCDD\Theme\Layout::themeDefinition('footer');

  return $items;
}

/**
 * Preprocess function for page.tpl.php
 */
function bootsass_preprocess_page(&$variables) {
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

function bootsass_preprocess_block_menu(&$variables) {
  if ($variables['name'] == 'menu_main_menu') {
    if (!empty($variables['context']) && $variables['context'] == 'header') {
      $variables['attributes_array']['class']['pull-right'] = 'pull-right';

      $variables['content_attributes_array']['class']['navbar']         = 'navbar';
      $variables['content_attributes_array']['class']['navbar-default'] = 'navbar-default';
      $variables['content_attributes_array']['class']['pull-right']     = 'pull-right';

      $variables['hide_empty_title'] = TRUE;

      $variables['content'] = '<div class="container-fluid">' . $variables['content'] . '</div>';
    }
  }
}

function bootsass_preprocess_links(&$variables) {
  if (!empty($variables['menu'])) {
    if ($variables['menu'] == 'menu_main_menu') {
      if (!empty($variables['context']) && $variables['context'] == 'header') {
        $variables['attributes']['class']['nav']          = 'nav';
        $variables['attributes']['class']['navbar-nav']   = 'navbar-nav';
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
          $temp  = explode('/', $link['href']);
          $temp  = explode('.', array_pop($temp));
          $class = drupal_clean_css_identifier(array_shift($temp));
        }
        $link['attributes']['class']['social'] = $class;
      }
    }
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
