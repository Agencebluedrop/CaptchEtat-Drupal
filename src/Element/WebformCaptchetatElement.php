<?php

namespace Drupal\captchetat\Element;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element\RenderElement;
use Drupal\Core\Url;
use Http\Client\Exception;

/**
 * Provides an element for the Captchetat.
 *
 * @RenderElement("captchetat")
 */
class WebformCaptchetatElement extends RenderElement {

  /**
   * {@inheritdoc}
   */
  public function getInfo() {
    $class = get_class($this);
    return [
      '#input' => FALSE,
      '#process' => [
        [$class, 'processWebformCaptchetat'],
        [$class, 'processAjaxForm'],
      ],
      '#element_validate' => [
        [$class, 'validateWebformCaptchetatElement'],
      ],
      '#pre_render' => [
        [$class, 'preRenderWebformCaptchetatElement'],
      ],
      '#theme_wrappers' => ['container'],
    ];
  }

  /**
   * Processes a 'captchetat' element.
   */
  public static function processWebformCaptchetat(&$element, FormStateInterface $form_state, &$complete_form) {
    // Here you can add and manipulate your element's properties and callbacks.
    return $element;
  }

  /**
   * Webform element validation handler for #type 'captchetat'.
   */
  public static function validateWebformCaptchetatElement(&$element, FormStateInterface $form_state, &$complete_form) {
    // Here you can add custom validation logic.
    $user_inputs = $form_state->getUserInput();
    $userEnteredCaptchaCode = strtoupper($user_inputs['captchetat']);
    $captchaId = $user_inputs['BDC_VCID_captchaFR'];

    // Create an HTTP client.
    $client = \Drupal::httpClient();
    // Prepare data for the POST request.
    $postData = [
      'userEnteredCaptchaCode' => $userEnteredCaptchaCode,
      'captchaId' => $captchaId,
    ];
    // Convert data to JSON format.
    $jsonData = json_encode($postData);
    // Generate the full URL for the captcha validation endpoint.
    $validation_url = \Drupal::request()->getSchemeAndHttpHost() . Url::fromRoute('captchetat.validatecaptcha')->toString();
    // Make a POST request to the captcha validation endpoint.
    try {
      $response = $client->post($validation_url, [
        'headers' => [
          'Content-Type' => 'application/json',
        ],
        'body' => $jsonData,
      ]);
      // Check if the response is successful.
      if (
        $response->getStatusCode() == 200 &&
        $response->getBody()->getContents() === 'false'
      ) {
        $form_state->setErrorByName($element['#name'], t('CAPTCHA validation failed.'));
      }
    }
    catch (Exception $e) {
      // Handle exceptions, if any.
      \Drupal::logger('captchetat')->error(t('Error validating captcha: @error', ['@error' => $e->getMessage()]));
    }
  }

  /**
   * Prepares a container render element for theme_element().
   */
  public static function preRenderWebformCaptchetatElement(array $element) {

    $element['#attached']['library'][] = 'captchetat/captchetat';

    $element['captchetat'] = [
      '#type' => 'container',
      '#attributes' => ['id' => 'botdetect-captcha', 'data-captchastylename' => 'captchaFR'],
    ];

    $element['captchetat_input'] = [
      '#type' => 'textfield',
      '#title' => t('CAPTCHA'),
      '#placeholder' => t('Copy the security code'),
      '#required' => TRUE,
      '#attributes' => [
        'id' => 'captchaFormulaireExtInput',
        'name' => 'captchetat',
      ],
      '#title_display' => 'invisible',
    ];
    return $element;
  }

}
