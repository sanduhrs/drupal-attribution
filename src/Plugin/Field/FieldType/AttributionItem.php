<?php

namespace Drupal\attribution\Plugin\Field\FieldType;

use Drupal\attribution\Entity\AttributionLicense;
use Drupal\Component\Utility\Random;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\TypedData\DataDefinition;

/**
 * Defines the 'attribution' field type.
 *
 * @FieldType(
 *   id = "attribution",
 *   label = @Translation("Attribution"),
 *   category = @Translation("General"),
 *   default_widget = "attribution_source_author_license",
 *   default_formatter = "attribution_creative_commons"
 * )
 */
class AttributionItem extends FieldItemBase {

  /**
   * {@inheritdoc}
   */
  public static function defaultFieldSettings() {
    return [
        'licenses' => [],
      ] + parent::defaultFieldSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function fieldSettingsForm(array $form, FormStateInterface $form_state) {
    $options = [];
    /** @var \Drupal\attribution\Entity\AttributionLicense $license */
    $licenses = AttributionLicense::loadMultiple();
    foreach ($licenses as $license) {
      $options[$license->getId()] = $license->getName();
    }
    $elements['licenses'] = [
      '#type' => 'select',
      '#title' => $this->t('Available licenses'),
      '#default_value' => $this->getSetting('licenses'),
      '#options' => $options,
      '#multiple' => TRUE,
    ];
    return $elements;
  }

  /**
   * {@inheritdoc}
   */
  public function isEmpty() {
    if ($this->source_name !== NULL) {
      return FALSE;
    }
    elseif ($this->source_link !== NULL) {
      return FALSE;
    }
    elseif ($this->author_name !== NULL) {
      return FALSE;
    }
    elseif ($this->author_link !== NULL) {
      return FALSE;
    }
    elseif ($this->license !== NULL) {
      return FALSE;
    }
    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {
    $properties['source_name'] = DataDefinition::create('string')
      ->setLabel(t('Source name'));
    $properties['source_link'] = DataDefinition::create('uri')
      ->setLabel(t('Source link'));
    $properties['author_name'] = DataDefinition::create('string')
      ->setLabel(t('Author name'));
    $properties['author_link'] = DataDefinition::create('uri')
      ->setLabel(t('Author link'));
    $properties['license'] = DataDefinition::create('string')
      ->setLabel(t('License'));
    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public function getConstraints() {
    $constraints = parent::getConstraints();
    // @todo Add more constraints here.
    return $constraints;
  }

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {
    $columns = [
      'source_name' => [
        'type' => 'varchar',
        'length' => 255,
      ],
      'source_link' => [
        'type' => 'varchar',
        'length' => 2048,
      ],
      'author_name' => [
        'type' => 'varchar',
        'length' => 255,
      ],
      'author_link' => [
        'type' => 'varchar',
        'length' => 2048,
      ],
      'license' => [
        'type' => 'varchar',
        'length' => 255,
      ],
    ];
    $schema = [
      'columns' => $columns,
      // @DCG Add indexes here if necessary.
    ];
    return $schema;
  }

  /**
   * {@inheritdoc}
   */
  public static function generateSampleValue(FieldDefinitionInterface $field_definition) {
    $random = new Random();
    $tlds = [
      'academy', 'barefoot', 'coffee', 'digital', 'earth', 'foo', 'gives',
      'host', 'international', 'joy', 'kim', 'lgbt', 'meme', 'ngo', 'online',
      'pizza', 'qpon', 'rocks', 'software', 'team', 'university', 'vacations',
      'wtf', 'xyz', 'yoga', 'zone',
    ];

    $values['source_name'] = $random->word(mt_rand(3, 10)) . ' ' . $random->word(mt_rand(3, 10));

    $domain_length = mt_rand(7, 15);
    $protocol = mt_rand(0, 1) ? 'https' : 'http';
    $www = mt_rand(0, 1) ? 'www' : '';
    $domain = $random->word($domain_length);
    $tld = $tlds[mt_rand(0, (count($tlds) - 1))];
    $values['source_link'] = "$protocol://$www.$domain.$tld";

    $values['author_name'] = $random->word(mt_rand(3, 10)) . ' ' . $random->word(mt_rand(3, 10));

    $domain_length = mt_rand(7, 15);
    $protocol = mt_rand(0, 1) ? 'https' : 'http';
    $www = mt_rand(0, 1) ? 'www' : '';
    $domain = $random->word($domain_length);
    $tld = $tlds[mt_rand(0, (count($tlds) - 1))];
    $values['author_link'] = "$protocol://$www.$domain.$tld";

    $licenses = [
      'cc0_1_0',
      'cc_by_4_0',
      'cc_by_nc_4_0',
      'cc_by_nc_nd_4_0',
      'cc_by_nc_sa_4_0',
      'cc_by_nd_4_0',
      'cc_by_sa_4_0',
      'gpl_2_0_or_later',
    ];
    $values['license'] = $licenses[rand(0, 7)];
    return $values;
  }

}
