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
 *   id = "attribution_source_author_license",
 *   label = @Translation("Source, Author & License"),
 *   field_types = {"attribution"},
 * )
 */
class AttributionSourceAuthorLicenseWidget extends WidgetBase {

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
    $element['author'] = [
      '#type' => 'container',
      '#attributes' => [
        'class' => ['container-inline'],
      ],
    ];
    $element['author']['author_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Author'),
      '#default_value' => $items[$delta]->author_name ?? NULL,
      '#size' => 20,
      '#placeholder' => $this->t('First Middle Lastname'),
    ];
    $element['author']['author_link'] = [
      '#type' => 'url',
      '#title' => $this->t('Link'),
      '#default_value' => $items[$delta]->author_link ?? NULL,
      '#size' => 20,
      '#placeholder' => $this->t('https://author.example.org'),
    ];

    /** @var \Drupal\attribution\Entity\AttributionLicense $license */
    $licenses = AttributionLicense::loadMultiple();
    foreach ($licenses as $license) {
      $options[$license->getId()] = $license->getName();
    }

    $options = $field_settings['licenses'] ? array_intersect_key($options, $field_settings['licenses']) : $options;
    if (!$this->fieldDefinition->isRequired()) {
      $options = [
        '' => $this->t('- Please choose -'),
      ] + $options;
    }
    $element['license'] = [
      '#type' => 'select',
      '#title' => $this->t('License'),
      '#default_value' => $items[$delta]->license ?? NULL,
      '#options' => $options,
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
