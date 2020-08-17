<?php

namespace Drupal\attribution;

use Drupal\Core\Config\Entity\ConfigEntityListBuilder;
use Drupal\Core\Entity\EntityInterface;

/**
 * Provides a listing of licenses.
 */
class AttributionLicenseListBuilder extends ConfigEntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['name'] = $this->t('Name');
    $header['identifier'] = $this->t('Identifier');
    $header['osi'] = $this->t('OSI-approved');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /** @var \Drupal\attribution\AttributionLicenseInterface $entity */
    $row['name'] = $entity->getName();
    $row['identifier'] = $entity->getIdentifier();
    $row['osi'] = $entity->isOsiCertified() ? $this->t('Yes, is OSI certified') : $this->t('No, is not OSI certified');
    return $row + parent::buildRow($entity);
  }

  /**
   * {@inheritdoc}
   */
  public function render() {
    $build['intro'] = [
      '#markup' => '<p>' . $this->t('The following table provides an overview of the installed licenses and whether they are approved by the <a href="https://opensource.org/">Open Source Initiative</a> (OSI) to conform to the <a href="https://opensource.org/docs/osd">Open Source Definition</a> and provide software freedom.') . '</p>',
    ];
    $build = $build + parent::render();
    return $build;
  }

}
