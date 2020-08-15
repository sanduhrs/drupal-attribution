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

}
