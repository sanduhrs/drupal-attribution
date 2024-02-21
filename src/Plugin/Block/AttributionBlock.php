<?php

namespace Drupal\attribution\Plugin\Block;

/**
 * Provides an attribution block.
 *
 * @Block(
 *   id = "attribution",
 *   admin_label = @Translation("Attribution"),
 *   category = @Translation("Legal")
 * )
 */
final class AttributionBlock extends AttributionBaseBlock {

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'license' => 'gpl_2_0_or_later',
      'disclaimer' => 'Except where otherwise noted, content on this site is licensed under a <a href="@link">@name</a> license.',
    ];
  }

}
