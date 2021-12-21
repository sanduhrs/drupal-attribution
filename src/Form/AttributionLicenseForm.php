<?php

namespace Drupal\attribution\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * License form.
 *
 * @property \Drupal\attribution\AttributionLicenseInterface $entity
 */
class AttributionLicenseForm extends EntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);
    $form['name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Name'),
      '#maxlength' => 255,
      '#default_value' => $this->entity->getName(),
      '#description' => $this->t('The human readable license name.'),
      '#required' => TRUE,
    ];
    $form['identifier'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Identifier'),
      '#maxlength' => 255,
      '#default_value' => $this->entity->getIdentifier(),
      '#description' => $this->t('Shorthand for the license name.'),
      '#required' => TRUE,
    ];
    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $this->entity->getId(),
      '#machine_name' => [
        'source' => ['identifier'],
        'exists' => '\Drupal\attribution\Entity\AttributionLicense::load',
      ],
      '#disabled' => !$this->entity->isNew(),
    ];
    $form['osiCertified'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('OSI certified'),
      '#default_value' => $this->entity->isOsiCertified(),
      '#description' => $this->t('OSI certification status of the license.'),
    ];
    $form['deprecated'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Deprecated'),
      '#default_value' => $this->entity->isDeprecated(),
      '#description' => $this->t('Deprecation status of the license.'),
    ];
    $form['link'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Link'),
      '#maxlength' => 255,
      '#default_value' => $this->entity->getLink(),
      '#description' => $this->t('License URI, if applicable.'),
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $result = parent::save($form, $form_state);
    $message_args = ['%label' => $this->entity->label()];
    $message = $result == SAVED_NEW
      ? $this->t('Created new license %label.', $message_args)
      : $this->t('Updated license %label.', $message_args);
    $this->messenger()->addStatus($message);
    $form_state->setRedirectUrl($this->entity->toUrl('collection'));
    return $result;
  }

}
