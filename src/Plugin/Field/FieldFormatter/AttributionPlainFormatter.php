<?php

namespace Drupal\attribution\Plugin\Field\FieldFormatter;

use Drupal\attribution\Entity\AttributionLicense;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;

/**
 * Plugin implementation of the 'Plain' formatter.
 *
 * @FieldFormatter(
 *   id = "attribution_plain",
 *   label = @Translation("Plain"),
 *   field_types = {
 *     "attribution"
 *   }
 * )
 */
class AttributionPlainFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $element = [];

    /** @var \Drupal\attribution\Plugin\Field\FieldType\AttributionItem $item */
    foreach ($items as $delta => $item) {
      $values = $item->getValue();
      /** @var \Drupal\attribution\Entity\AttributionLicense $license */
      $license = AttributionLicense::load($values['license']);
      if ($license) {
        $element[$delta] = [
          '#theme' => 'attribution_plain',
          "#attributes" => [
            'class' => [
              'attribution',
              'attribution--license-' . $license->getId(),
              'attribution--license-' . ($license->isOsiCertified() ? 'is-osi-approved' : 'not-osi-approved'),
              'attribution--license-' . ($license->isDeprecated() ? 'is-deprecated' : 'not-deprecated'),
            ],
          ],
        ];
        $element[$delta]["#license"] = $license;
      }
      if ($values['source_name'] || $values['source_link']) {
        $element[$delta]['#source'] = [
          'name' => $values['source_name'],
          'link' => $values['source_link'],
        ];
      }
      if ($values['author_name'] || $values['author_link']) {
        $element[$delta]['#author'] = [
          'name' => $values['author_name'],
          'link' => $values['author_link'],
        ];
      }
    }

    return $element;
  }

}
