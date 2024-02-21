<?php

namespace Drupal\attribution\Plugin\Block;

/**
 * Provides an attribution block.
 *
 * @Block(
 *   id = "attribution_copyright",
 *   admin_label = @Translation("Copyright"),
 *   category = @Translation("Legal")
 * )
 */
final class CopyrightBlock extends AttributionBaseBlock {

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'license' => 'all_rights_reserved',
      'disclaimer' => 'Copyright © [current-date:html_year] [site:name]. @name.',
    ];
  }

}
