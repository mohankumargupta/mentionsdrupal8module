<?php

use Drupal\Core\Plugin\ContainerFactoryPluginInterface;

interface MentionsPluginInterface extends ContainerFactoryPluginInterface
{
    public function entityOutput($mention, $settings);
}

