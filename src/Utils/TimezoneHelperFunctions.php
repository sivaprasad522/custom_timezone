<?php

namespace Drupal\custom_timezone\Utils;

use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * The Utility Helper class to have the common functions.
 */
class TimezoneHelperFunctions {

  use StringTranslationTrait;

  /**
   * Gives the list of custom timezones.
   *
   * @return array
   *   The array of timezones.
   */
  public function getZones() {

    $zones = [
      'America' => [
        'America/Chicago' => $this->t('Chicago'),
        'America/New_York' => $this->t('New York'),
      ],
      'Asia' => [
        'Asia/Tokyo' => $this->t('Tokyo'),
        'Asia/Dubai' => $this->t('Dubai'),
        'Asia/Kolkata' => $this->t('Kolkata'),
      ],
      'Europe' => [
        'Europe/Amsterdam' => $this->t('Amsterdam'),
        'Europe/Oslo' => $this->t('Oslo'),
        'Europe/London' => $this->t('London'),
      ],
    ];

    return $zones;
  }

}
