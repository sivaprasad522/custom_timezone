<?php

namespace Drupal\custom_timezone\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * The class to be called from ajax to update the template.
 */
class SiteLocationController extends ControllerBase {

  /**
   * Stores the siteLocationResolver service.
   *
   * @var \Drupal\custom_timezone
   */
  protected $siteLocationResolver;

  /**
   * Creates a SiteLocationController instance.
   *
   * @param mixed $site_location_resolver
   *   The plugin siteLocationResolver definition.
   */
  public function __construct($site_location_resolver) {
    $this->siteLocationResolver = $site_location_resolver;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
        $container->get('custom_timezone.site_location_resolver')
    );
  }

  /**
   * This function to get current time.
   */
  public function getTime() {

    $build = [
      'data' => [
        'time' => $this->siteLocationResolver->getCurrentDateTime('g:i a'),
        'date' => $this->siteLocationResolver->getCurrentDateTime('l, j F Y'),
      ],
    ];

    return new JsonResponse(($build));
  }

}
