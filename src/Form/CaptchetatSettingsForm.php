<?php

namespace Drupal\captchetat\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure example settings for this site.
 */
class CaptchetatSettingsForm extends ConfigFormBase {

  const SETTINGS = 'captchetat.settings';

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'captchetat_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      static::SETTINGS,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config(static::SETTINGS);

    $form['api_settings'] = [
      '#type' => 'details',
      '#title' => t('API CaptchEtat Configuration'),
      '#weight' => 0,
      '#collapsible' => TRUE,
    ];
    $form['api_settings']['api_url'] = [
      '#type' => 'textfield',
      '#title' => $this->t('API URL'),
      '#default_value' => $config->get('api_url'),
      '#required' => TRUE,
    ];
    $form['api_settings']['oauth_url'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Oauth URL'),
      '#default_value' => $config->get('oauth_url'),
      '#required' => TRUE,
    ];
    $form['api_settings']['client_id'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Client ID'),
      '#default_value' => $config->get('client_id'),
      '#required' => TRUE,
    ];
    $form['api_settings']['client_secret'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Client Secret'),
      '#default_value' => $config->get('client_secret'),
      '#required' => TRUE,
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->configFactory->getEditable(static::SETTINGS)
      ->set('api_url', $form_state->getValue('api_url'))
      ->set('client_id', $form_state->getValue('client_id'))
      ->set('client_secret', $form_state->getValue('client_secret'))
      ->set('oauth_url', $form_state->getValue('oauth_url'))
      ->save(TRUE);

    parent::submitForm($form, $form_state);
  }

}
