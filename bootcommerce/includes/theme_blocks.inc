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
    if (user_access("access content")) {
      /** @var \Xtuple\Drupal7\Proxy\User\UserProxyInterface $user */
      global $user;
      if ($order = commerce_cart_order_load($user->uid())) {
        $result = views_get_view_result("ft_commerce_cart_block", "default", $order->order_id);
        if (!empty($result)) {
          $variables["view"] = commerce_embed_view("ft_commerce_cart_block", "default", [$order->order_id]);
          $variables["links"] = [
            "checkout" => l("Checkout", "checkout"),
            "cart" => l("Cart", "cart"),
          ];
        }
      }
    }
    if (empty($result)) {
      $variables["view"] = "<div class=\"empty-cart\">" . t("Your cart is empty") . "</div>";
    }
  }
}

function bootcommerce_process_block_bootcommerce_cart_block(&$variables) {
  $variables["content"] = $variables["view"];
  $links = [];
  if (!empty($variables["links"])) {
    foreach ($variables["links"] as $link) {
      $links[] = "<div class=\"link\">{$link}</div>";
    }
  }
  if (!empty($links)) {
    $variables["content"] .= strtr("<div class=\"links\">%links</div>", [
      "%links" => implode("", $links),
    ]);
  }
}
