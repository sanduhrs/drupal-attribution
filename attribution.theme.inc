<?php

/**
 * Theme handlers for the attribution module.
 */


/**
 * Returns HTML for the attribution text.
 *
 * @param array $variables
 *   An associative array containing:
 *   - attribution_text: HTML markup, having passed through the appropriate
 *     filter formatter.
 *
 * @ingroup themeable
 */
function theme_attribution_text($variables) {
  $output = array();
  $output[] = '<footer class="attribution_text">';
  $output[] = $variables['attribution_text'];
  $output[] = '</footer>';
  return implode('', $output);
}
