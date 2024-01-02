<?php

namespace Drupal\attribution\EventSubscriber;

use Drupal\attribution\Entity\AttributionLicense;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Url;
use Drupal\webfinger\Event\WebfingerResponseEvent;
use Drupal\webfinger\JsonRdLink;
use Drupal\webfinger\WebfingerEvents;
use Drupal\webfinger\WebfingerParameters;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Webfinger subscriber.
 */
class WebfingerAttributionSubscriber implements EventSubscriberInterface {

  /**
   * The current user.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $account;

  /**
   * The node storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $nodeStorage;

  /**
   * The user storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $userStorage;

  /**
   * A logger instance.
   *
   * @var \Psr\Log\LoggerInterface
   */
  protected $logger;

  /**
   * Class constructor.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The current user.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager service.
   * @param \Psr\Log\LoggerInterface $logger
   *   A logger instance.
   */
  public function __construct(
      AccountInterface $account,
      EntityTypeManagerInterface $entity_type_manager,
      LoggerInterface $logger
  ) {
    $this->account = $account;
    $this->nodeStorage = $entity_type_manager->getStorage('node');
    $this->userStorage = $entity_type_manager->getStorage('user');
    $this->logger = $logger;
  }

  /**
   * Builds a profile response.
   *
   * @param \Drupal\webfinger\Event\WebfingerResponseEvent $event
   *   The event to process.
   */
  public function onBuildResponse(WebfingerResponseEvent $event) {
    /** @var \Drupal\webfinger\JsonRd $json_rd */
    $json_rd = $event->getJsonRd();
    $request = $event->getRequest();
    $params = $event->getParams();
    $response_cacheability = $event->getResponseCacheability();

    // Subject should always be set.
    $subject = $request->query->get('resource') ?: '';
    if (!empty($subject)) {
      $json_rd->setSubject($subject);
    }

    // Determine if there is a user account path for a requested name.
    if ($node = $this->getEnityBySubject($subject)) {
      // Determine if the current user has access to the requested node.
      // Access differs by user so cache per user.
      $response_cacheability->addCacheContexts(['user']);

      if ($node->access('view', $this->account)) {
        $language_na = \Drupal::languageManager()->getLanguage('zxx');

        $json_rd->addAlias($node->toUrl('canonical')->setAbsolute()->toString(TRUE)->getGeneratedUrl());
        $json_rd->addAlias($node->toUrl('canonical', ['alias' => TRUE])->setAbsolute()->toString(TRUE)->getGeneratedUrl());

        $config = \Drupal::config('attribution.settings');
        if ($path = $config->get('site_copyright')) {
          $copyright_link = new JsonRdLink();
          $copyright_link->setRel('copyright')
            ->setType('text/html')
            ->setHref(Url::fromUserInput($path)->setAbsolute()->toString(TRUE)->getGeneratedUrl());
          $json_rd->addLink($copyright_link);
        }

        foreach ($node->getFieldDefinitions() as $field) {
          if ($field->getType() === 'attribution') {
            foreach ($node->get($field->getName())->getValue() as $field_values) {
              $license_link = new JsonRdLink();
              if ($license = AttributionLicense::load($field_values['license'])) {
                $license_link->setRel('license')
                  ->setType('text/html')
                  ->setHref($license->getLink());
                $json_rd->addLink($license_link);
              }

              $author_link = new JsonRdLink();
              if ($author = $field_values['author_link']) {
                $author_link->setRel('author')
                  ->setType('text/html')
                  ->setHref($author);
                $json_rd->addLink($author_link);
              }
            }
          }
        }
      }
      else {
        $this->logger->notice('Access denied for requested node "%name".', ['%name' => $params[WebfingerParameters::ACCOUNT_KEY_NAME]]);
      }

    }

  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    // Run early so other implementations can add and alter.
    $events[WebfingerEvents::WEBFINGER_BUILD_RESPONSE][] = [
      'onBuildResponse',
      1000,
    ];
    return $events;
  }

  /**
   * Get a node by webfinger subject.
   *
   * Example subjectts are:
   * The default canonical node URL https://domain.tld/node/{id} or
   * a custom title slug path alias https://domain.tld/article/slug-the-title.
   *
   * @param string $subject
   *   The subject string.
   *
   * @return \Drupal\node\Entity\NodeInterface|false
   *   A node entity or false.
   */
  private function getEnityBySubject($subject) {
    $parts = parse_url($subject);
    if (!$parts['scheme']
      || !$parts['host']
      || !$parts['path']) {
      return FALSE;
    }

    $path = \Drupal::service('path_alias.manager')
      ->getPathByAlias($parts['path']);
    $path = $path ?: $parts['path'];
    if (preg_match('/node\/(\d+)/', $path, $matches)) {
      return $this->nodeStorage->load($matches[1]);
    }

    return FALSE;
  }

}
