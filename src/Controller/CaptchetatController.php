<?php

namespace Drupal\captchetat\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class CaptchetatController.
 */
class CaptchetatController extends ControllerBase {

  /**
   * Returns true if API is up or false if API is down.
   *
   * @return \Symfony\Component\HttpFoundation\Response
   */
  public function healthCheck() {
    $token = $this->generateApiToken();
    $config = \Drupal::configFactory()->getEditable('captchetat.settings');
    $url = $config->get('api_url') . '/piste/captcha/healthcheck';

    $ch = curl_init($url);
    $headers = [
      "Authorization: Bearer $token",
    ];

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
      echo 'Curl error: ' . curl_error($ch);
    }

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
    curl_close($ch);

    return new Response($response);
  }

  /**
   * Returns the captchetat token.
   *
   * @return string
   */
  public function generateApiToken() {
    $config = \Drupal::configFactory()->getEditable('captchetat.settings');
    $client_id = $config->get('client_id');
    $client_secret = $config->get('client_secret');
    $url = $config->get('oauth_url') . '/api/oauth/token';

    $data = [
      'grant_type' => 'client_credentials',
      'client_id' => $client_id,
      'client_secret' => $client_secret,
      'scope' => 'piste.captchetat',
    ];
    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
    curl_setopt($ch, CURLOPT_TIMEOUT, 3600);

    $response = curl_exec($ch);
    $result = json_decode($response);
    $token = $result->access_token;

    if (curl_errno($ch)) {
      echo 'Curl error: ' . curl_error($ch);
    }

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
    curl_close($ch);

    return $token;
  }

  /**
   * Gets Captcha from API Captchetat.
   *
   * @return \Symfony\Component\HttpFoundation\Response
   */
  public function getCaptcha() {
    $token = $this->generateApiToken();
    $config = \Drupal::configFactory()->getEditable('captchetat.settings');
    $request_uri = \Drupal::request()->getRequestUri();
    $query_params = explode('?', $request_uri)[1];
    $url = $config->get('api_url') . '/piste/captcha/simple-captcha-endpoint?' . $query_params;
    $ch = curl_init();
    $headers = [
      "Authorization: Bearer $token",
    ];

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
      echo 'Curl error: ' . curl_error($ch);
    }

    curl_close($ch);

    return new Response($response);
  }

  /**
   * Validates the user submission.
   *
   * @return \Symfony\Component\HttpFoundation\Response
   */
  public function validationCaptcha() {
    $token = $this->generateApiToken();
    $config = \Drupal::configFactory()->getEditable('captchetat.settings');
    $request = \Drupal::request();
    $payload = $request->getContent();
    $decoded_payload = json_decode($payload, TRUE);
    $url = $config->get('api_url') . '/piste/captcha/valider-captcha';

    $data = [
      'id' => $decoded_payload['captchaId'],
      'code' => $decoded_payload['userEnteredCaptchaCode'],
    ];

    $jsonData = json_encode($data);

    $headers = [
      "Authorization: Bearer $token",
      'Content-Type: application/json',
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
      echo 'Curl error: ' . curl_error($ch);
    }

    curl_close($ch);

    return new Response($response);
  }

}
