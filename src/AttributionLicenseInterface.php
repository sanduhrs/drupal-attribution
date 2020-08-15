<?php

namespace Drupal\attribution;

use Drupal\Core\Config\Entity\ConfigEntityInterface;

/**
 * Provides an interface defining a license entity type.
 */
interface AttributionLicenseInterface extends ConfigEntityInterface {

  /**
   * Gets the id.
   *
   * @return string
   */
  public function getId():string;

  /**
   * Sets the id.
   *
   * @param string $id
   *   The license id.
   *
   * @return $this
   */
  public function setId(string $id):self;

  /**
   * Gets the identifier.
   *
   * @return string
   */
  public function getIdentifier():string;

  /**
   * Sets the identifier.
   *
   * @param string $identifier
   *   The short license identifier.
   *
   * @return $this
   */
  public function setIdentifier(string $identifier):self;

  /**
   * Gets the name.
   *
   * @return string
   */
  public function getName():string;

  /**
   * Sets the name.
   *
   * @param string $name
   *   The license name.
   *
   * @return $this
   */
  public function setName(string $name):self;

  /**
   * Gets OSI certification status.
   *
   * @return bool
   */
  public function isOsiCertified():bool;

  /**
   * Sets the OSI certification status.
   *
   * @param bool $status
   *   The OSI certification status.
   *
   * @return $this
   */
  public function setOsiCertified(bool $status):self;

  /**
   * Gets deprecation status.
   *
   * @return bool
   */
  public function isDeprecated():bool;

  /**
   * Sets the deprecation status.
   *
   * @param bool $status
   *   The deprecation status.
   *
   * @return $this
   */
  public function setDeprecated(bool $status):self;

  /**
   * Gets the link.
   *
   * @return string
   */
  public function getLink():string;

  /**
   * Sets the link.
   *
   * @param string $link
   *   The link to the license.
   *
   * @return $this
   */
  public function setLink(string $link):self;

}
