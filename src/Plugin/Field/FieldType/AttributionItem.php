<?php

namespace Drupal\attribution\Plugin\Field\FieldType;

use Drupal\Component\Utility\Random;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\TypedData\DataDefinition;

/**
 * Defines the 'attribution_attribution' field type.
 *
 * @FieldType(
 *   id = "attribution_attribution",
 *   label = @Translation("Attribution"),
 *   category = @Translation("General"),
 *   default_widget = "attribution_attribution",
 *   default_formatter = "attribution_attribution_default"
 * )
 */
class AttributionItem extends FieldItemBase {

  /**
   * {@inheritdoc}
   */
  public static function defaultFieldSettings() {
    return ['foo' => 'bar'] + parent::defaultFieldSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function fieldSettingsForm(array $form, FormStateInterface $form_state) {
    $settings = $this->getSettings();
    $element['foo'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Ask for author'),
      '#default_value' => $settings['foo'],
    ];
    return $element;
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

    $values['source_name'] = $random->word(mt_rand(1, 255));

    $tlds = ['com', 'net', 'gov', 'org', 'edu', 'biz', 'info'];
    $domain_length = mt_rand(7, 15);
    $protocol = mt_rand(0, 1) ? 'https' : 'http';
    $www = mt_rand(0, 1) ? 'www' : '';
    $domain = $random->word($domain_length);
    $tld = $tlds[mt_rand(0, (count($tlds) - 1))];
    $values['source_link'] = "$protocol://$www.$domain.$tld";

    $values['author_name'] = $random->word(mt_rand(1, 255));

    $tlds = ['com', 'net', 'gov', 'org', 'edu', 'biz', 'info'];
    $domain_length = mt_rand(7, 15);
    $protocol = mt_rand(0, 1) ? 'https' : 'http';
    $www = mt_rand(0, 1) ? 'www' : '';
    $domain = $random->word($domain_length);
    $tld = $tlds[mt_rand(0, (count($tlds) - 1))];
    $values['author_link'] = "$protocol://$www.$domain.$tld";

    $values['license'] = $random->word(mt_rand(1, 255));

    return $values;
  }

}
