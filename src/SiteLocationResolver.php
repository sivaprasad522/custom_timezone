<?php

namespace Drupal\custom_timezone;

use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Config\ConfigFactoryInterface;

/**
 * Event handler that gives date and time based on site timezone configuration.
 */
class SiteLocationResolver {

  /**
   * The config name.
   *
   * @var string
   */
  protected $configName = 'custom_timezone.location';

  /**
   * The config.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * SiteLocationResolver constructor.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory.
   */
  public function __construct(ConfigFactoryInterface $config_factory) {
    $this->configFactory = $config_factory;

  }

  /**
   * Retrieve a date value containing a date and time.
   *
   * @param string $format
   *   Date format option to format the date object.
   *
   * @return string
   *   The current date and time as per the settings.
   */
  public function getCurrentDateTime($format = 'jS M Y - g:i A') {
    $config = $this->configFactory->get($this->configName);

    $current_time = new DrupalDateTime('now', 'UTC');
    $current_timezone = $config->get('site_timezone.default');

    $siteTimezone = new \DateTimeZone($current_timezone);
    $date_time = new DrupalDateTime();
    $timezone_offset = $siteTimezone->getOffset($date_time->getPhpDateTime());

    $time_interval = \DateInterval::createFromDateString($timezone_offset . ' seconds');
    $current_time->add($time_interval);
    return $current_time->format($format);

  }

}
