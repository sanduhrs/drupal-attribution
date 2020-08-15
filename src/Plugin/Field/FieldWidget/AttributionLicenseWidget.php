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
 *   id = "attribution_attribution_license",
 *   label = @Translation("License"),
 *   field_types = {"attribution_attribution"},
 * )
 */
class AttributionLicenseWidget extends WidgetBase {

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $settings = $this->getSettings();

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
      if ($value['license'] === '') {
        $values[$delta]['license'] = NULL;
      }
    }
    return $values;
  }

}
