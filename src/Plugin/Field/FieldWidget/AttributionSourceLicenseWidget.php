<?php

namespace Drupal\attribution\Plugin\Field\FieldWidget;

use Drupal\attribution\Entity\AttributionLicense;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\Validator\ConstraintViolationInterface;

/**
 * Defines the 'attribution' field widget.
 *
 * @FieldWidget(
 *   id = "attribution_source_license",
 *   label = @Translation("Source & License"),
 *   field_types = {"attribution"},
 * )
 */
class AttributionSourceLicenseWidget extends WidgetBase {

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $field_settings = $this->getFieldSettings();
    $element += [
      '#type' => 'details',
      '#summary_attributes' => [],
      '#open' => TRUE,
    ];
    $element['source'] = [
      '#type' => 'container',
      '#attributes' => [
        'class' => ['container-inline'],
      ],
    ];
    $element['source']['source_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Source'),
      '#default_value' => $items[$delta]->source_name ?? NULL,
      '#size' => 20,
      '#placeholder' => $this->t('Untitled'),
    ];
    $element['source']['source_link'] = [
      '#type' => 'url',
      '#title' => $this->t('Link'),
      '#default_value' => $items[$delta]->source_link ?? NULL,
      '#size' => 20,
      '#placeholder' => $this->t('https://example.org/source'),
    ];

    /** @var \Drupal\attribution\Entity\AttributionLicense $license */
    $licenses = AttributionLicense::loadMultiple();
    foreach ($licenses as $license) {
      $options[$license->getId()] = $license->getName();
    }
    $element['license'] = [
      '#type' => 'select',
      '#title' => $this->t('License'),
      '#default_value' => $items[$delta]->license ?? NULL,
      '#options' => $field_settings['licenses'] ? array_intersect_key($options, $field_settings['licenses']) : $options,

    ];
    $element['#attributes']['class'][] = 'attribution-elements';
    $element['#attached']['library'][] = 'attribution/attribution';
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
      if ($value['license'] === '') {
        $values[$delta]['license'] = NULL;
      }
    }
    return $values;
  }

}
