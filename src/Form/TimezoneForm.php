<?php

namespace Drupal\custom_timezone\Form;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\ConfigFormBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Configure timezone settings for this site.
 *
 * @internal
 */
class TimezoneForm extends ConfigFormBase {

  /**
   * Stores the list of zones from Utility function.
   *
   * @var \Drupal\custom_timezone\Utils
   */
  protected $zonesList;

  /**
   * Constructs a \Drupal\custom_timezone\ConfigFormBase object.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The factory for configuration objects.
   * @param mixed $zones_list
   *   The list of custom timezones.
   */
  public function __construct(ConfigFactoryInterface $config_factory, $zones_list) {
    $this->configFactory = $config_factory;
    $this->zonesList = $zones_list;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('custom_timezone.zones')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'custom_timezone_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['custom_timezone.location'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $custom_timezone_config = $this->config('custom_timezone.location');

    $form['timezone_config'] = [
      '#type' => 'details',
      '#title' => $this->t('Time zone configuration'),
      '#open' => TRUE,
    ];

    $form['timezone_config']['country'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Country'),
      '#default_value' => $custom_timezone_config->get('country'),
      '#description' => $this->t("Enter the country code, for example, USA."),
      '#maxlength' => 5,
      '#required' => TRUE,
    ];

    $form['timezone_config']['city'] = [
      '#type' => 'textfield',
      '#title' => $this->t('City'),
      '#default_value' => $custom_timezone_config->get('city'),
      '#description' => $this->t("Enter the city name in short format, for example, NY."),
      '#maxlength' => 5,
      '#required' => TRUE,
    ];

    $zones = $this->zonesList->getZones();

    $form['timezone_config']['site_timezone'] = [
      '#type' => 'select',
      '#title' => $this->t('Time zone'),
      '#default_value' => $custom_timezone_config->get('site_timezone.default') ?: 'America/New_York',
      '#options' => $zones,
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('custom_timezone.location')
      ->set('country', $form_state->getValue('country'))
      ->set('city', $form_state->getValue('city'))
      ->set('site_timezone.default', $form_state->getValue('site_timezone'))
      ->save();

    parent::submitForm($form, $form_state);
  }

}
