<?php

/**
 * @file
 * Contains \Drupal\relaxed\RelaxedServiceProvider.
 */

namespace Drupal\relaxed;

use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Core\DependencyInjection\ServiceModifierInterface;

/**
 * Defines a service profiler for the relaxed module.
 */
class RelaxedServiceProvider implements ServiceModifierInterface {

  /**
   * {@inheritdoc}
   */
  public function alter(ContainerBuilder $container) {
    // Adds mixed as known format.
    if ($container->has('http_middleware.negotiation')) {
      $container->getDefinition('http_middleware.negotiation')->addMethodCall('registerFormat', ['mixed', ['multipart/mixed']]);
    }

    // Override the access_check.rest.csrf class with a new class.
    // @todo Revisit this before beta release: https://www.drupal.org/node/2470691
    try {
      $definition = $container->getDefinition('access_check.rest.csrf');
      $definition->setClass('Drupal\relaxed\Access\CSRFAccessCheck');
    }
    catch (\InvalidArgumentException $e) {
      // Do nothing, rest module is not installed.
    }
  }

}