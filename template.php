<?php

/**
 * @file keeps theme functions overrides
 */

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
