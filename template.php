<?php

/**
 * @file keeps theme functions overrides
 */

/**
 * Implements hook_theme()
 */
function bootsass_theme() {
  $templates = drupal_get_path("theme", "bootsass") . "/templates";
  $items = [];
  $items['layout_header'] = [
    'template' => 'templates/layout/layout-header',
  ];
  $items['layout_body_top'] = [
    'template' => 'templates/layout/layout-body-top',
  ];
  $items['layout_body_middle'] = [
    'template' => 'templates/layout/layout-body-middle',
  ];
  $items['layout_body_bottom'] = [
    'template' => 'templates/layout/layout-body-bottom',
  ];
  $items['layout_content_top'] = [
    'template' => 'templates/layout/layout-content-top',
  ];
  $items['layout_content_bottom'] = [
    'template' => 'templates/layout/layout-content-bottom',
  ];
  $items['layout_content_context'] = [
    'template' => 'templates/layout/layout-content-context',
  ];
  $items['layout_footer'] = [
    'template' => 'templates/layout/layout-footer',
  ];
  $items["bootsass_commerce_payment_credit_cart"] = [
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

/**
 * Preprocess function for page.tpl.php
 *
 * @param $variables
 *
 * @throws \Exception
 */
function bootsass_preprocess_page(&$variables) {
  $variables['header'] = theme('layout_header');
  $variables['body_top'] = theme('layout_body_top');

  $variables['body_middle'] = theme('layout_body_middle', [
    'content_middle' => $variables['page']['content'],
    'content_top' => theme('layout_content_top'),
    'content_bottom' => theme('layout_content_bottom'),
    'context' => theme('layout_content_context'),
  ]);
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
    $links = [];
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
 *
 * @param $variables
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
  if ($variables['element']['#field_name'] == 'substitutes') {
    $variables['content_attributes_array']['class']['row'] = 'row';

    foreach ($variables['items'] as $i => $item) {
      $variables['item_attributes_array'][$i]['class']['col-lg'] = 'col-lg-4';
    }
    $variables['row_by'] = 3;
  }

  if ($variables['element']['#bundle'] == 'shipping'
    || $variables['element']['#bundle'] == 'billing'
  ) {
    $variables['classes_array']['col-lg-4'] = 'col-lg-4';
  }
}

/**
 * @see template_process_field()
 *
 * @param $variables
 */
function bootsass_process_field(&$variables) {
  $variables['group_attributes'] = drupal_attributes($variables['group_attributes_array']);
}

/**
 * @see template_preprocess_views_view_field()
 *
 * @param $variables
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
 *
 * @param $variables
 */
function bootsass_preprocess_layout_header(&$variables) {
  $variables['site_phone_array'] = [
    '#theme' => 'block_block',
    '#name' => 'site-phone',
    '#title' => variable_get('header_site_phone_title', 'Call us:'),
    '#content' => variable_get('site_phone_value', "(800) 555-1234"),
  ];

  $variables['logo'] = '<div class="b-logo">' . l('Logo', '<front>') . '</div>';

  $variables['main_menu_array'] = [
    '#theme' => 'block_menu',
    '#name' => 'menu_main_menu',
    '#context' => 'header',
  ];

  $variables['user_menu_array'] = [
    '#theme' => 'block_menu',
    '#name' => 'user-menu',
    '#context' => 'header',
  ];

  if (user_access("view products")) {
    $variables["search_box"] = drupal_get_form("xdruple_commerce_product_search_form");
  }
  if (user_access("view any commerce_product entity")) {
    $variables['cart_dropdown'] = theme('block_cart_dropdown', [
      'name' => 'cart-dropdown',
    ]);
  }
  if (user_access("access order defaults form")) {
    $variables['order_defaults_form'] = theme('block_order_defaults_form', [
      'name' => 'order-defaults_form',
    ]);
  }
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
  if (!empty($variables["search_box"])) {
    $variables["search_box"] = drupal_render($variables["search_box"]);
  }
}

/**
 * Preprocess function for layout-body-top.tpl.php
 *
 * @param $variables
 *
 * @throws \Exception
 */
function bootsass_preprocess_layout_body_top(&$variables) {
  $variables['messages'] = theme('status_messages');
  $variables['breadcrumb'] = theme('breadcrumb', [
    'breadcrumb' => drupal_get_breadcrumb(),
  ]);
}

/**
 * Preprocess function for layout-body-middle.tpl.php
 *
 * @param $variables
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
 *
 * @param $variables
 */
function bootsass_preprocess_layout_body_bottom(&$variables) {
}

/**
 * Preprocess function for layout-footer.tpl.php
 *
 * @param $variables
 */
function bootsass_preprocess_layout_footer(&$variables) {
  $variables['main_menu_array'] = [
    '#theme' => 'block_menu',
    '#name' => 'menu_main_menu',
    '#title' => variable_get('footer_main_menu_title', 'Main menu'),
    '#title_tag' => 'h4',
  ];

  $variables['secondary_menu_array'] = [
    '#theme' => 'block_menu',
    '#name' => 'menu_secondary_menu',
    '#title' => variable_get('footer_secondary_menu_title', 'Secondary menu'),
    '#title_tag' => 'h4',
  ];

  $variables['user_menu_array'] = [
    '#theme' => 'block_menu',
    '#name' => 'user-menu',
    '#title' => variable_get('footer_user_menu_title', 'User menu'),
    '#title_tag' => 'h4',
  ];

  $variables['contact_info_array'] = [
    '#theme' => 'block_block',
    '#name' => 'contact-info',
    '#title' => variable_get('footer_contacts_title', 'Contact information'),
    '#title_tag' => 'h4',
    '#content' => format_text_variable_get('footer_contacts_value', [
      'value' => 'Site built by xTuple.',
      'format' => 'htmlpurifier_basic',
    ]),
  ];

  $variables['site_phone_array'] = [
    '#theme' => 'block_block',
    '#name' => 'site-phone',
    '#title' => variable_get('footer_site_phone_title', 'Call us now toll free:'),
    '#title_tag' => 'h4',
    '#content' => variable_get('site_phone_value'),
  ];

  $variables['social_menu_array'] = [
    '#theme' => 'block_menu',
    '#name' => 'menu-social-menu',
    '#title' => variable_get('footer_social_menu_title', 'Social menu'),
    '#title_tag' => 'h4',
  ];

  $variables['copyright_array'] = [
    '#theme' => 'block_block',
    '#name' => 'site-copyright',
    '#title_tag' => 'h4',
    '#content' => format_text_variable_get('footer_message', [
      'value' => '<p>© ' . date('Y') . ', ' . variable_get('site_name') . '</p>',
      'format' => 'htmlpurifier_basic',
    ]),
    '#attributes_array' => [
      'class' => ['well b-copyright'],
    ],
  ];
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
 *
 * @param $variables
 *
 * @throws \Exception
 */
function bootsass_preprocess_layout_content_top(&$variables) {
  $variables['tabs'] = theme('menu_local_tasks', [
    'primary' => menu_primary_local_tasks(),
    'secondary' => menu_secondary_local_tasks(),
  ]);

  $variables['title'] = drupal_get_title();
  $variables['help'] = menu_get_active_help();
}

/**
 * Preprocess function for layout-content-bottom.tpl.php
 *
 * @param $variables
 */
function bootsass_preprocess_layout_content_bottom(&$variables) {
}

/**
 * Preprocess function for layout-content-context.tpl.php
 *
 * @param $variables
 */
function bootsass_preprocess_layout_content_context(&$variables) {
  $blocks = [];
  /** @var stdClass $user */
  global $user;
  if ($user->uid == 0) {
    $panel = new \CDD\OpenCDD\Panels\FormPanel('user_login_block', 'Login');
    $blocks['user_login_block'] = $panel->render();
  }
  if (drupal_match_menu_path([
    'products',
    'products/*',
  ])
  ) {
    $blocks["categories"] = [
      "#theme" => "xdruple_categories_dropdown",
    ];
  }
  $variables['blocks'] = $blocks;
}

function bootsass_show_content_context() {
  return !drupal_match_menu_path(bootsass_page_exclude_content_context());
}

function bootsass_page_exclude_content_context() {
  $exclude_context = [
    'cart',
    'checkout',
    'checkout/%commerce_order',
    'checkout/%commerce_order/%commerce_checkout_page',
    'user/*',
    'user/*/*',
    'bootstrap',
    'bootstrap/*',
  ];

  drupal_alter('bootsass_page_exclude_content_context', $exclude_context);

  return $exclude_context;
}

/**
 * @see theme_file_link()
 */
function bootsass_file_link(&$variables) {
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
function bootsass_form_alter(&$form, &$form_state, $form_id) {
  if (!empty($form['#attributes']['class'][0])) {
    if ($form['#attributes']['class'][0] == 'commerce-add-to-cart') {
      $form['#attributes']['class']['clearfix'] = 'clearfix';
      $form['quantity']['#title_display'] = 'invisible';
    }
  }
}

/**
 * @param $form
 * @param $form_state
 */
function bootsass_form_commerce_checkout_form_alter(&$form, &$form_state) {
  if (!empty($form["buttons"])) {
    foreach ($form["buttons"] as $key => &$button) {
      if (is_array($button)
        && !empty($button["#type"])
        && $button["#type"] == "submit"
      ) {
        if (!empty($button["#attributes"]["class"])) {
          // Remove Commerce assigned classes to avoid theming conflicts
          $class_key = array_search("checkout-{$key}", $button["#attributes"]["class"]);
          if ($class_key !== FALSE) {
            unset($button["#attributes"]["class"][$class_key]);
          }
          if (in_array($key, ["cancel", "back"])) {
            // Assign btn-danger for cancel and back buttons
            $class_key = array_search("btn-primary", $button["#attributes"]["class"]);
            if ($class_key !== FALSE) {
              unset($button["#attributes"]["class"][$class_key]);
            }
            $button["#attributes"]["class"]["btn-danger"] = "btn-danger";
          }
        }
        $button["#attributes"]["data-loading-text"] = "Processing…";
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
function bootsass_form_commerce_checkout_form_review_alter(&$form, &$form_state) {
  $form['help']['#prefix'] = '<div class="checkout-help-wrapper well well-sm">';
  $form['help']['#suffix'] = '</div>';

  if (!empty($form["commerce_payment"]["payment_method"]["#options"])
    && sizeof($form["commerce_payment"]["payment_method"]["#options"]) == 1
  ) {
    $payment_method = &$form["commerce_payment"]["payment_method"];
    $payment_method["#type"] = "value";
    $payment_method["#value"] = $payment_method["#default_value"];
  }
  if (!empty($form["commerce_payment"]["payment_details"]["credit_card"])) {
    $credit_card = &$form["commerce_payment"]["payment_details"]["credit_card"];
    $credit_card["#theme"] = "bootsass_commerce_payment_credit_cart";
    $credit_card["#element_validate"] = [
      "bootsass_commerce_payment_credit_cart_validate",
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
    unset($credit_card["exp_month"]["#options"]);
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
function bootsass_commerce_payment_credit_cart_validate($element, &$form_state, $form) {
  $input = drupal_array_get_nested_value($form_state["values"], $element["#parents"]);
  if (!empty($input["exp_year"])) {
    $input["exp_year"] = substr(date('Y'), 0, 2) . $input["exp_year"];
  }
  preg_match_all('!\d+!', $input["number"], $matches);
  if (!empty($matches[0])) {
    $input["number"] = implode("", $matches[0]);
  }
  drupal_array_set_nested_value($form_state["values"], $element["#parents"], $input);
}

function bootsass_preprocess_xdruple_xd_user_association_default_formatter(&$variables) {
  foreach ($variables["rows"] as &$row) {
    $row["attributes_array"]["class"]["row"] = "row";
  }
}
