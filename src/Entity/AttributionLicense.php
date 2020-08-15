<?php

namespace Drupal\attribution\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;
use Drupal\attribution\AttributionLicenseInterface;

/**
 * Defines the license entity type.
 *
 * @ConfigEntityType(
 *   id = "attribution_license",
 *   label = @Translation("License"),
 *   label_collection = @Translation("Licenses"),
 *   label_singular = @Translation("license"),
 *   label_plural = @Translation("licenses"),
 *   label_count = @PluralTranslation(
 *     singular = "@count license",
 *     plural = "@count licenses",
 *   ),
 *   handlers = {
 *     "list_builder" = "Drupal\attribution\AttributionLicenseListBuilder",
 *     "form" = {
 *       "add" = "Drupal\attribution\Form\AttributionLicenseForm",
 *       "edit" = "Drupal\attribution\Form\AttributionLicenseForm",
 *       "delete" = "Drupal\Core\Entity\EntityDeleteForm"
 *     }
 *   },
 *   config_prefix = "attribution_license",
 *   admin_permission = "administer attribution_license",
 *   links = {
 *     "collection" = "/admin/structure/attribution-license",
 *     "add-form" = "/admin/structure/attribution-license/add",
 *     "edit-form" = "/admin/structure/attribution-license/{attribution_license}",
 *     "delete-form" = "/admin/structure/attribution-license/{attribution_license}/delete"
 *   },
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name",
 *     "uuid" = "uuid"
 *   },
 *   config_export = {
 *     "id",
 *     "identifier",
 *     "name",
 *     "osiCertified",
 *     "deprecated",
 *     "link"
 *   }
 * )
 */
class AttributionLicense extends ConfigEntityBase implements AttributionLicenseInterface {

  /**
   * The license id.
   *
   * @var string
   */
  protected $id;

  /**
   * The short license identifier.
   *
   * @var string
   */
  protected $identifier;

  /**
   * The full license name.
   *
   * @var string
   */
  protected $name;

  /**
   * Osi certification status.
   *
   * @var bool
   */
  protected $osiCertified;

  /**
   * Deprecation status.
   *
   * @var bool
   */
  protected $deprecated;

  /**
   * Link to the license text.
   *
   * @var string
   */
  protected $link;

  /**
   * {@inheritdoc }
   */
  public function getId(): string {
    return (string) $this->id;
  }

  /**
   * {@inheritdoc }
   */
  public function setId(string $id): AttributionLicenseInterface {
    $this->id = $id;
    return $this;
  }

  /**
   * {@inheritdoc }
   */
  public function getIdentifier(): string {
    return (string) $this->identifier;
  }

  /**
   * {@inheritdoc }
   */
  public function setIdentifier(string $identifier): AttributionLicenseInterface {
    $this->identifier = $identifier;
    return $this;
  }

  /**
   * {@inheritdoc }
   */
  public function getName(): string {
    return (string) $this->name;
  }

  /**
   * {@inheritdoc }
   */
  public function setName(string $name): AttributionLicenseInterface {
    $this->name = $name;
    return $this;
  }

  /**
   * {@inheritdoc }
   */
  public function isOsiCertified(): bool {
    return (bool) $this->osiCertified;
  }

  /**
   * {@inheritdoc }
   */
  public function setOsiCertified(bool $status): AttributionLicenseInterface {
    $this->osiCertified = $status;
    return $this;
  }

  /**
   * {@inheritdoc }
   */
  public function isDeprecated(): bool {
    return (bool) $this->deprecated;
  }

  /**
   * {@inheritdoc }
   */
  public function setDeprecated(bool $status): AttributionLicenseInterface {
    $this->deprecated = $status;
    return $this;
  }

  /**
   * {@inheritdoc }
   */
  public function getLink(): string {
    return (string) $this->link;
  }

  /**
   * {@inheritdoc }
   */
  public function setLink(string $link): AttributionLicenseInterface {
    $this->link = $link;
    return $this;
  }

}
