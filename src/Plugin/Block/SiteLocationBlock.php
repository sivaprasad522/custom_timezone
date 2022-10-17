<?php

namespace Drupal\custom_timezone\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a block to display 'Site Location and current time' elements.
 *
 * @Block(
 *   id = "site_location_current_time_block",
 *   admin_label = @Translation("Site location and current time"),
 *   category = @Translation("Custom"),
 * )
 */
class SiteLocationBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The config name.
   *
   * @var string
   */
  protected $configName = 'custom_timezone.location';

  /**
   * Stores the configuration factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * Stores the siteLocationResolver service.
   *
   * @var \Drupal\custom_timezone
   */
  protected $siteLocationResolver;

  /**
   * Stores the list of zones from Utility function.
   *
   * @var \Drupal\custom_timezone\Utils
   */
  protected $zonesList;

  /**
   * Creates a SiteLocationBlock instance.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The factory for configuration objects.
   * @param mixed $site_location_resolver
   *   The plugin siteLocationResolver definition.
   * @param mixed $zones_list
   *   The Utility Helper function.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, ConfigFactoryInterface $config_factory, $site_location_resolver, $zones_list) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->configFactory = $config_factory;
    $this->siteLocationResolver = $site_location_resolver;
    $this->zonesList = $zones_list;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('config.factory'),
      $container->get('custom_timezone.site_location_resolver'),
      $container->get('custom_timezone.zones'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];
    $site_location_config = $this->configFactory->get($this->configName);

    $zones = $this->zonesList->getZones();
    $timezone = $site_location_config->get('site_timezone.default');
    $timezone_continent = explode("/", $timezone);
    $zone_name = $zones[$timezone_continent[0]][$timezone]->render();

    $build = [
      '#theme' => 'site_location_block',
      '#data' => [
        'country' => $site_location_config->get('country'),
        'city' => $site_location_config->get('city'),
        'timezone_name' => $zone_name,
        'time' => $this->siteLocationResolver->getCurrentDateTime('g:i a'),
        'date' => $this->siteLocationResolver->getCurrentDateTime('l, j F Y'),
      ],
      '#cache' => [
        'tags' => ['config:custom_timezone.location'],
      ],
    ];

    return $build;
  }

}
