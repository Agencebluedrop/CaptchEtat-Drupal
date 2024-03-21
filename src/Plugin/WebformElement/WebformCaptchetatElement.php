<?php

namespace Drupal\captchetat\Plugin\WebformElement;

use Drupal\Core\Form\FormStateInterface;
use Drupal\webform\Plugin\WebformElementBase;
use Drupal\webform\WebformSubmissionInterface;

/**
 * Provides a 'captchetat' element.
 *
 * @WebformElement(
 *   id = "captchetat",
 *   label = @Translation("CAPTCHETAT"),
 *   description = @Translation("Provides a CAPTCHETAT form element that determines wheter the user is human."),
 *   category = @Translation("Advanced elements"),
 * )
 *
 * @see \Drupal\captchetat\Element\WebformCaptchetatElement
 * @see \Drupal\webform\Plugin\WebformElementBase
 * @see \Drupal\webform\Plugin\WebformElementInterface
 * @see \Drupal\webform\Annotation\WebformElement
 */
class WebformCaptchetatElement extends WebformElementBase {

  /**
   * {@inheritdoc}
   */
  protected function defineDefaultProperties() {
    // Here you define your webform element's default properties,
    // which can be inherited.
    return [
      // Flexbox.
      'flex' => 1,
    ];
  }

  /* ************************************************************************ */

  /**
   * {@inheritdoc}
   */
  public function prepare(array &$element, WebformSubmissionInterface $webform_submission = NULL) {
    parent::prepare($element, $webform_submission);

    // Here you can customize the webform element's properties.
    // You can also customize the form/render element's properties via the
    // FormElement.
    //
    // @see \Drupal\captchetat\Element\WebformCaptchetatElement::processWebformCaptchetat
  }

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);
    // Here you can define and alter a webform element's properties UI.
    // Form element property visibility and default values are defined via
    // ::defaultProperties.
    //
    // @see \Drupal\webform\Plugin\WebformElementBase::form
    // @see \Drupal\webform\Plugin\WebformElement\TextBase::form
    // Create a custom field set for the example element.
    return $form;
  }

}
