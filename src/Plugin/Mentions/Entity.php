<?php
namespace Drupal\mentions\Plugin\Mentions;

use Drupal\mentions\MentionsPluginInterface;
use Drupal\Core\Utility\Token;
use Symfony\Component\DependencyInjection\ContainerInterface;


/**
 * @Mention(
 *  id = "entity",
 *  name = @Translation("Entity")
 * )
 */
class Entity implements MentionsPluginInterface {
    private $token_service;
    private $entity_manager;

    public function __construct(array $configuration, $plugin_id, $plugin_definition, Token $token, EntityManager $entity_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->token_service = $token;
    $this->entity_manager = $entity_manager;
  }

  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    $token = $container->get('token');
    $entity_manager = $container->get('entity.manager');
    return new static(
      $token,
      $entity_manager      
    );
  }
    
  public function entityOutput($mention, $settings) {
    $entity = $this->entity_manager->getStorage($mention['target']['entity_type'])->load($mention['target']['entity_id']);
    $output['value'] = $this->token_service->replace($settings['value'], array($mention['target']['entity_type'] => $entity));
    $output['link'] = $this->token_service->replace($settings['link'], array($mention['target']['entity_type'] => $entity));
    return $output;
  }
}
