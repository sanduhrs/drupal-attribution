<?php

namespace Drupal\attribution\Plugin\Field\FieldWidget;

use Drupal\attribution\Entity\AttributionLicense;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\Validator\ConstraintViolationInterface;

/**
 * Defines the 'attribution_attribution' field widget.
 *
 * @FieldWidget(
 *   id = "attribution_attribution",
 *   label = @Translation("Attribution"),
 *   field_types = {"attribution_attribution"},
 * )
 */
class AttributionWidget extends WidgetBase {

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
      'author' => TRUE,
      'source' => TRUE,
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $settings = $this->getSettings();
    $element['author'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Author'),
      '#default_value' => $settings['author'],
    ];
    $element['source'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Source'),
      '#default_value' => $settings['source'],
    ];
    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $settings = $this->getSettings();
    $summary[] = $this->t('Enable author: @author', ['@author' => $settings['author'] ? t('yes') : t('no')]);
    $summary[] = $this->t('Enable source: @source', ['@source' => $settings['source'] ? t('yes') : t('no')]);
    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $settings = $this->getSettings();
    if ($settings['source']) {
      $element['source'] = [
        '#type' => 'container',
      ];
      $element['source']['source_name'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Source name'),
        '#default_value' => isset($items[$delta]->source_name) ? $items[$delta]->source_name : NULL,
        '#size' => 20,
      ];
      $element['source']['source_link'] = [
        '#type' => 'url',
        '#title' => $this->t('Source link'),
        '#default_value' => isset($items[$delta]->source_link) ? $items[$delta]->source_link : NULL,
        '#size' => 20,
      ];
    }

    if ($settings['author']) {
      $element['author'] = [
        '#type' => 'container',
      ];
      $element['author']['author_name'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Author name'),
        '#default_value' => isset($items[$delta]->author_name) ? $items[$delta]->author_name : NULL,
        '#size' => 20,
      ];
      $element['author']['author_link'] = [
        '#type' => 'url',
        '#title' => $this->t('Author link'),
        '#default_value' => isset($items[$delta]->author_link) ? $items[$delta]->author_link : NULL,
        '#size' => 20,
      ];
    }

    /** @var \Drupal\attribution\Entity\AttributionLicense $license */
    $licenses = AttributionLicense::loadMultiple();
    foreach ($licenses as $license) {
      $options[$license->getId()] = $license->getName();
    }
    $element['license'] = [
      '#type' => 'select',
      '#title' => $this->t('License'),
      '#default_value' => isset($items[$delta]->license) ? $items[$delta]->license : NULL,
      '#options' => $options,
    ];

    $element['#theme_wrappers'] = ['container', 'form_element'];
    $element['#attributes']['class'][] = 'container-inline';
    $element['#attributes']['class'][] = 'attribution-attribution-elements';
    $element['#attached']['library'][] = 'attribution/attribution_attribution';

    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function errorElement(array $element, ConstraintViolationInterface $violation, array $form, FormStateInterface $form_state) {
    return isset($violation->arrayPropertyPath[0]) ? $element[$violation->arrayPropertyPath[0]] : $element;
  }

  /**
   * {@inheritdoc}
   */
  public function massageFormValues(array $values, array $form, FormStateInterface $form_state) {
    foreach ($values as $delta => $value) {
      if ($value['source']['source_name'] === '') {
        $values[$delta]['source']['source_name'] = NULL;
      }
      else {
        $values[$delta]['source_name'] = $values[$delta]['source']['source_name'];
      }
      if ($value['source']['source_link'] === '') {
        $values[$delta]['source']['source_link'] = NULL;
      }
      else {
        $values[$delta]['source_link'] = $values[$delta]['source']['source_link'];
      }
      if ($value['author']['author_name'] === '') {
        $values[$delta]['author']['author_name'] = NULL;
      }
      else {
        $values[$delta]['author_name'] = $values[$delta]['author']['author_name'];
      }
      if ($value['author']['author_link'] === '') {
        $values[$delta]['author']['author_link'] = NULL;
      }
      else {
        $values[$delta]['author_link'] = $values[$delta]['author']['author_link'];
      }
      if ($value['license'] === '') {
        $values[$delta]['license'] = NULL;
      }
    }
    return $values;
  }

}
