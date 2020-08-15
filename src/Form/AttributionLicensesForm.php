<?php

namespace Drupal\attribution\Form;

use Composer\Spdx\SpdxLicenses;
use Drupal\attribution\Entity\AttributionLicense;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Language\LanguageInterface;

/**
 * Provides a Attribution form.
 */
class AttributionLicensesForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'attribution_licenses_add';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $licenses = [];
    $spdx = new SpdxLicenses();
    foreach ($spdx->getLicenses() as $identifier => $license) {
      $licenses[$identifier] = $license[1];
    }

    $form['licenses'] = [
      '#type' => 'select',
      '#title' => $this->t('Available licenses'),
      '#options' => $licenses,
      '#multiple' => TRUE,
      '#size' => 10,
    ];
    $form['actions']['#type'] = 'actions';
    $form['actions']['import'] = [
      '#type' => 'submit',
      '#button_type' => 'primary',
      '#name' => 'import',
      '#value' => $this->t('Add'),
      '#submit' => ['::submitForm'],
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $licenses = $form_state->getValue('licenses');
    $spdx_licenses = (new SpdxLicenses())->getLicenses();
    foreach ($licenses as $identifier) {
      $entity = AttributionLicense::create([
        'id' => $this->getMachineName($spdx_licenses[$identifier][0]),
        'identifier' => $spdx_licenses[$identifier][0],
        'name' => $spdx_licenses[$identifier][1],
        'osiCertified' => $spdx_licenses[$identifier][2],
        'deprecated' => $spdx_licenses[$identifier][3],
        'link' => (new SpdxLicenses())->getLicenseByIdentifier($identifier)[2],
      ]);
      try {
        $entity->save();
        $this->messenger()->addMessage($this->t('The license %license_name has been added.', [
          '%license_name' => $spdx_licenses[$identifier][1],
        ]));
      }
      catch (\Exception $e) {
        $this->messenger()->addError($this->t('The license %license_name already exists.', [
          '%license_name' => $spdx_licenses[$identifier][1],
        ]));
      }
    }
    $form_state->setRedirect('entity.attribution_license.collection');
  }

  /**
   * Generates a machine name from a string.
   *
   * This is basically the same as what is done in
   * \Drupal\Core\Block\BlockBase::getMachineNameSuggestion() and
   * \Drupal\system\MachineNameController::transliterate(), but it seems
   * that so far there is no common service for handling this.
   *
   * @param string $string
   *   The string to transliterate.
   *
   * @return string
   *   The transliterated string.
   *
   * @see \Drupal\Core\Block\BlockBase::getMachineNameSuggestion()
   * @see \Drupal\system\MachineNameController::transliterate()
   */
  protected function getMachineName($string) {
    $transliterated = \Drupal::transliteration()
      ->transliterate($string, LanguageInterface::LANGCODE_DEFAULT, '_');
    $transliterated = mb_strtolower($transliterated);

    $transliterated = preg_replace('@[^a-z0-9_]+@', '_', $transliterated);
    $transliterated = str_replace('.', '_', $transliterated);

    return $transliterated;
  }

}
