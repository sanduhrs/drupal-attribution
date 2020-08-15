<?php

namespace Drupal\attribution\Plugin\Field\FieldFormatter;

use Drupal\attribution\Entity\AttributionLicense;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'attribution_attribution_default' formatter.
 *
 * @FieldFormatter(
 *   id = "attribution_attribution_default",
 *   label = @Translation("Default"),
 *   field_types = {"attribution_attribution"}
 * )
 */
class AttributionDefaultFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return ['foo' => 'bar'] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $settings = $this->getSettings();
    $element['foo'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Foo'),
      '#default_value' => $settings['foo'],
    ];
    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $settings = $this->getSettings();
    $summary[] = $this->t('Foo: @foo', ['@foo' => $settings['foo']]);
    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $element = [];
    foreach ($items as $delta => $item) {
      /** @var \Drupal\attribution\Entity\AttributionLicense $license */
      $license = AttributionLicense::load($item->license);

      if ($item->source_name
        && $item->source_link) {
        $element[$delta]['attribution'] = [
          '#type' => 'item',
          '#markup' => t('<a href="@source_link">@source_name</a> is licensed under <a href="@license_link">@license_name</a>', [
            '@source_name' => $item->source_name,
            '@source_link' => $item->source_link,
            '@license_name' => $license->getName(),
            '@license_link' => $license->getLink(),
          ]),
        ];
      }

      if ($item->author_name
        && $item->author_link) {
        $element[$delta]['attribution'] = [
          '#type' => 'item',
          '#markup' => t('Work by <a href="@author_link">@author_name</a> is licensed under <a href="@license_link">@license_name</a>', [
            '@author_name' => $item->author_name,
            '@author_link' => $item->author_link,
            '@license_name' => $license->getName(),
            '@license_link' => $license->getLink(),
          ]),
        ];
      }

      if ($item->source_name
          && $item->source_link
          && $item->author_name
          && $item->author_link) {
        $element[$delta]['attribution'] = [
          '#type' => 'item',
          '#markup' => t('<a href="@source_link">@source_name</a> by <a href="@author_link">@author_name</a> is licensed under <a href="@license_link">@license_name</a>', [
            '@source_name' => $item->source_name,
            '@source_link' => $item->source_link,
            '@author_name' => $item->author_name,
            '@author_link' => $item->author_link,
            '@license_name' => $license->getName(),
            '@license_link' => $license->getLink(),
          ]),
        ];
      }

    }

    return $element;
  }

}
