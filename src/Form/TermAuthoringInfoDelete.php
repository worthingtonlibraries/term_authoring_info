<?php

/**
 * @file
 * Contains \Drupal\term_authoring_info\Form\TermAuthoringInfoDelete.
 */

namespace Drupal\term_authoring_info\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\Core\Link;
use Drupal\term_authoring_info\Controller\TermAuthoringInfoManageFields;
use Drupal\term_authoring_info\Controller\TermAuthoringInfoManageGroups;

/**
 * Create an interstitial page warning users that disabling
 * authoring information will delete corresponding data permanently.
 * After unchecking 'Display authoring information,' this form appears.
 */
class TermAuthoringInfoDelete extends FormBase {

  /**
   * Returns a title that includes the appropriate vocabulary name.
   *
   * @return string
   *   Revised title of this page.
   */
  public function getTitle() {

    $vocab_name = NULL;

    // Set a base title, in case the vocabulary name can't be found.
    $title = t('Are you sure you want to delete all authoring informaton');

    // Attempt to load the appropriate vocabulary object.
    $vocab = $this::getVocabFromReferrer();

    // Ensure a vocabulary object was loaded.
    if (!empty($vocab)) {

      $vocab_name = $vocab->label();

      // Update the title with the vocabulary name.
      $title = t('@base_text from %vocab_name?',
        [
          '@base_text' => $title,
          '%vocab_name' => $vocab_name,
        ]
      );

    }
    // Otherwise, no vocabulary information was retrieved.
    else {
      // End the original title with a question mark. 
      $title .= '?';
    }

    return $title;
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $url_previous = \Drupal::request()->server->get('HTTP_REFERER');

    // Attempt to load the appropriate vocabulary object.
    $vocab = $this::getVocabFromReferrer();

    $vid        = ((!empty($vocab)) ? $vocab->id() : 0); 
    $vocab_name = ((!empty($vocab)) ? $vocab->label() : NULL);

    // No referring URL was found.
    if (empty($url_previous)) {
      // No referring URL means that this page is being accessed
      // erroneously. Prevent the buttons from being rendered.
      return;
    }

    $form['notice'] = array(
      '#markup' => t('<p>You just unchecked the <em>Display authoring information</em> option.</p> <p>By clicking <em>Continue</em>, authoring information will be permanently deleted from all taxonomy terms in the %vocab_name vocabulary. This action cannot be undone.</p>',
        [
          '%vocab_name' => $vocab_name,
        ]
      ),
    );

    $form['vocab_id'] = array(
      '#type' => 'hidden',
      '#default_value' => $vid,
    );

    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Continue'),
      '#button_type' => 'primary',
    );

    // Create the 'Cancel' link/button only if a referring URL was found.
    if (!empty($url_previous)) {
      $form['actions']['cancel'] = array(
        '#markup' => t("@cancel",
          array('@cancel' => Link::fromTextAndUrl('Cancel',
                               Url::fromUri($url_previous, 
                                 array(
                                   'attributes' => array(
                                     'id' => 'edit-cancel',
                                     'class' => array(
                                       'button',
                                     ),
                                   ),
                                 )
                               )
                             )->toString(),
          )
        ),
      );
    }

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'term_authoring_info_delete_interstitial';
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    $vid = $form_state->getValue('vocab_id');

    $vocab = taxonomy_vocabulary_load($vid);
    $vocab_name = ((!empty($vocab)) ? $vocab->label() : NULL);

    $message = t('Authoring information has been deleted for the %vocab_name vocabulary.',
      ['%vocab_name' => $vocab_name]
    );


    // Attempt to load the entity configuration value for this vocabulary.
    $entity = \Drupal::entityTypeManager()->getStorage('vocab_display_status')->load($vid);

    // Update the entity with the new values.
    $entity->id = $vid;
    $entity->state = 0;

    // Save the changes. 
    $entity->save();


    // Delete the 'Authoring Information' fields.
    TermAuthoringInfoManageFields::deleteFields($vid);

    // Delete the 'Authoring Information' field group.
    TermAuthoringInfoManageGroups::deleteGroup($vid);

    drupal_set_message($message);

    // Redirect the user back to main settings page.
    $form_state->setRedirect('term_authoring_info.settings');

  }

  /**
   * Pulling the vocab ID from a referring URL, return a vocabulary object.
   *
   * @return object
   *   Vocabulary object.
   */
  protected function getVocabFromReferrer() {
    $vocab = NULL;
    $url_previous = \Drupal::request()->server->get('HTTP_REFERER');

    // Ensure a referring URL was found.
    if (!empty($url_previous)) {
      $arg = explode('/', $url_previous);
      $vid = $arg[count($arg) - 1];
      // Retrieve the vocabulary ID from the end of the referring URL.
      $vocab = taxonomy_vocabulary_load($vid);
    }

    return $vocab;
  }

}
