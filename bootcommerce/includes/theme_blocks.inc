<?php

function bootcommerce_preprocess_block_bootcommerce_cart_block(&$variables) {
  if (!empty($variables["name"])) {
    $base_class = "b-block";
    $instance_class = drupal_clean_css_identifier($base_class . "-" . $variables["name"]);
    $variables["attributes_array"] = [
      "class" => [
        $base_class,
        $instance_class,
        "panel",
        "panel-default",
      ],
    ];
    $variables["title_attributes_array"] = [
      "class" => [
        "$base_class--title",
        "$instance_class--title",
        "panel-heading",
        "panel-title",
      ],
    ];
    $variables["content_attributes_array"] = [
      "class" => [
        "$base_class--content",
        "$instance_class--content",
        "panel-body",
      ],
    ];
    $content = "";
    if (user_access("access content")) {
      /** @var \Xtuple\Drupal7\Proxy\User\UserProxyInterface $user */
      global $user;
      if ($order = commerce_cart_order_load($user->uid())) {
        $content .= commerce_embed_view("ft_commerce_cart_block", "default", [$order->order_id]);
        $content .= "<div class=\"links\">";
        $content .= "<div class=\"link\">" . l("Checkout", "checkout") . "</div>";
        $content .= "<div class=\"link\">" . l("Cart", "cart") . "</div>";
        $content .= "</div>";
      }
    }
    if (empty($content)) {
      $content = "<div class=\"empty-cart\">" . t("Your cart is empty") . "</div>";
    }
    $variables["content"] = $content;
  }
}
