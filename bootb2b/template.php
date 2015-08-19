<?php

/**
 * @file keeps theme functions overrides
 */

/**
 * Overrides theme_status_messages()
 *
 * Bootstrap uses different classes to mark messages (based on alert class).
 *
 * @param $vars
 *
 * @return string
 *
 * @see theme_status_messages()
 * @see drupal_set_message()
 */
function bootb2b_status_messages(&$vars) {
  $display = $vars["display"];
  $output = "";

  // Keep these statuses, as they are used for assistive technologies
  $status_heading = [
    "status" => t("Status message"),
    "error" => t("Error message"),
    "warning" => t("Warning message"),
  ];

  // Mapping of Drupal statuses to Bootstrap.
  $bootstrap_types = [
    "status" => "success",
    "error" => "danger",
    "warning" => "warning",
    "info" => "info",
  ];

  foreach (drupal_get_messages($display) as $type => $messages) {
    $classes = ["alert"];
    if (isset($bootstrap_types[$type])) {
      $classes[] = "alert-$bootstrap_types[$type]";
    }
    else {
      $classes[] = "alert-$type";
    }
    if (count($messages) > 1 && BOOTSTRAP3_ALERT_GROUP) {
      if (BOOTSTRAP3_ALERT_BLOCK) {
        $classes[] = "alert-block";
      }
      $class = implode(" ", $classes);
      $output .= "<div class=\"$class\">\n";
      if (!empty($status_heading[$type])) {
        $output .= _bootstrap3_element_invisible($status_heading[$type]);
      }
      $output .= _bootstrap3_close_button("alert");
      if (count($messages) > 1) {
        $output .= " <ul>\n";
        foreach ($messages as $message) {
          $output .= "  <li>" . $message . "</li>\n";
        }
        $output .= " </ul>\n";
      }
      else {
        $output .= $messages[0];
      }
      $output .= "</div>\n";
    }
    else {
      if (BOOTSTRAP3_ALERT_BLOCK) {
        $classes[] = "alert-block";
      }
      $class = implode(" ", $classes);
      foreach ($messages as $message) {
        $output .= "<div class=\"$class\">\n";
        if (!empty($status_heading[$type])) {
          $output .= _bootstrap3_element_invisible($status_heading[$type]);
        }
        $output .= _bootstrap3_close_button("alert");
        $output .= $message;
        $output .= "</div>\n";
      }
    }
  }

  return "<div id=\"status-messages\">" . $output . "</div>";
}

/**
 * @param $variables
 */
function bootb2b_preprocess_block_menu(&$variables) {
  if ($variables["name"] == "menu_main_menu") {
    if (!empty($variables["context"]) && $variables["context"] == "header") {
      if ($variables["content"] == "<div class=\"container-fluid\"></div>") {
        $variables["hide_empty_block"] = TRUE;
        $variables["title"] = "";
        $variables["content"] = "";
      }
    }
  }
}

/**
 * Implements hook_form_FORM_ID_alter() for xdruple_favorites_add_to_favorites_form
 *
 * @see xdruple_favorites_add_to_favorites_form()
 *
 * @param $form
 * @param $form_state
 */
function bootb2b_form_xdruple_favorites_add_to_favorites_form_alter(&$form, &$form_state) {
  $form["submit"]["#value"] = "<i class=\"glyphicon glyphicon-star-empty\"></i>";
  $form["submit"]["#attributes"]["class"]["btn-add-favorites"] = "btn-add-favorites";
}

/**
 * Implements hook_form_FORM_ID_alter() for xdruple_favorites_remove_from_favorites
 *
 * @see xdruple_favorites_remove_from_favorites_form()
 *
 * @param $form
 * @param $form_state
 */
function bootb2b_form_xdruple_favorites_remove_from_favorites_form_alter(&$form, &$form_state) {
  $form["submit"]["#value"] = "<i class=\"glyphicon glyphicon-star\"></i>";
  $form["submit"]["#attributes"]["class"]["btn-remove-favorites"] = "btn-remove-favorites";
}


/**
 * Implements hook_xdruple_favorites_favorites_form_submit_ajax_commands_alter()
 *
 * @see xdruple_favorites_favorites_form_submit_ajax_commands()
 *
 * @param $commands
 * @param $form
 * @param $form_state
 * @param $new_form
 */
function bootb2b_xdruple_favorites_favorites_form_submit_ajax_commands_alter(&$commands, $form, $form_state, $new_form) {
  if ($form["#page_url"] == "products/favorites") {
    if (!empty($commands["replace"])) {
      $commands["remove"] = ajax_command_remove("tr:has(#{$new_form["#id"]})");
    }
  }
}
