<?php

namespace Drupal\attribution\Plugin\Block;

use Drupal\attribution\Entity\AttributionLicense;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Extension\ModuleHandler;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Utility\Token;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides an base attribution block.
 */
class AttributionBaseBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The module handler service.
   *
   * @var \Drupal\Core\Extension\ModuleHandler
   */
  protected $moduleHandler;

  /**
   * The token service.
   *
   * @var \Drupal\Core\Utility\Token
   */
  protected $token;

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
   * @param \Drupal\Core\Extension\ModuleHandler $module_handler
   *   The module handler service.
   * @param \Drupal\Core\Utility\Token $token
   *   The token service.
   */
  public function __construct(
      array $configuration,
      $plugin_id,
      $plugin_definition,
      EntityTypeManagerInterface $entity_type_manager,
      ModuleHandler $module_handler,
      Token $token) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityTypeManager = $entity_type_manager;
    $this->moduleHandler = $module_handler;
    $this->token = $token;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager'),
      $container->get('module_handler'),
      $container->get('token')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'license' => '',
      'disclaimer' => '',
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
    if ($this->moduleHandler->moduleExists('token')) {
      $form['disclaimer'] += [
        '#element_validate' => ['token_element_validate'],
        '#token_types' => [],
      ];
      $form['token_help'] = [
        '#theme' => 'token_tree_link',
        '#token_types' => [],
      ];
    }
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
    //phpcs:ignore Drupal.Semantics.FunctionT.NotLiteralString
    $disclaimer = $this->t($this->configuration['disclaimer'], [
      '@name' => $license->getName(),
      '@link' => $license->getLink(),
    ]);
    $disclaimer = $this->token->replace($disclaimer, []);
    $build['content'] = [
      '#markup' => $disclaimer,
    ];
    return $build;
  }

}
