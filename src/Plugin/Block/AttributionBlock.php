<?php

namespace Drupal\attribution\Plugin\Block;

use Drupal\attribution\Entity\AttributionLicense;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides an attribution block.
 *
 * @Block(
 *   id = "attribution",
 *   admin_label = @Translation("Attribution"),
 *   category = @Translation("Legal")
 * )
 */
class AttributionBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Constructs a new AttributionBlock instance.
   *
   * @param array $configuration
   *   The plugin configuration, i.e. an array with configuration values keyed
   *   by configuration option name. The special key 'context' may be used to
   *   initialize the defined contexts by setting it to an array of context
   *   values keyed by context names.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityTypeManagerInterface $entity_type_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'license' => 'gpl_2_0_or_later',
      'disclaimer' => 'Except where otherwise noted, content on this site is licensed under a <a href="@link">@name</a> license.',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $options = [];
    /** @var \Drupal\attribution\Entity\AttributionLicense $license */
    $licenses = AttributionLicense::loadMultiple();
    foreach ($licenses as $license) {
      $options[$license->getId()] = $license->getName();
    }
    $form['license'] = [
      '#type' => 'select',
      '#title' => $this->t('License'),
      '#default_value' => $this->configuration['license'],
      '#options' => $options,
    ];
    $form['disclaimer'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Disclaimer'),
      '#default_value' => $this->configuration['disclaimer'],
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['license'] = $form_state->getValue('license');
    $this->configuration['disclaimer'] = $form_state->getValue('disclaimer');
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    /** @var \Drupal\attribution\Entity\AttributionLicense $license */
    $license = AttributionLicense::load($this->configuration['license']);
    $build['content'] = [
      //phpcs:ignore Drupal.Semantics.FunctionT.NotLiteralString
      '#markup' => $this->t($this->configuration['disclaimer'], [
        '@name' => $license->getName(),
        '@link' => $license->getLink(),
      ]),
    ];
    return $build;
  }

}
