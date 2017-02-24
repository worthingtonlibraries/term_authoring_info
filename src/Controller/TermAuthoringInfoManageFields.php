<?php

namespace Drupal\term_authoring_info\Controller;

use Drupal\field\Entity\FieldConfig;
use Drupal\field\Entity\FieldStorageConfig;

/**
 * Controller for creating and deleting authoring info form fields.
 */
class TermAuthoringInfoManageFields {

  /**
   * Add authoring info fields to the appropriate vocabulary.
   *
   * @param string $vid
   *   Vocabulary machine-name.
   */
  static function createFields($vid) {

    /**** AUTHORED BY ****/

    $field_id = 'taxonomy_term.' . $vid . '.field_term_authoring_info_uid';
    $field = \Drupal::entityManager()->getStorage('field_config')->load($field_id);

    // Ensure the 'Authored by' (user) field doesn't exist yet.
    if (empty($field)) {

      // Ensure the field storage configuration entity hasn't already been defined.
      if (empty(FieldStorageConfig::load('taxonomy_term.field_term_authoring_info_uid'))) {

        // Authored by: Create field storage.
        FieldStorageConfig::create([
          'field_name' => 'field_term_authoring_info_uid',
          'entity_type' => 'taxonomy_term',
          'type' => 'entity_reference',
          'cardinality' => 1,
          'settings' => [
            'target_type' => 'user',
            'default_value' => 0,
          ],
        ])->save();

      }

      // Authored by: Attach an instance of the field to the vocabulary.
      FieldConfig::create([
        'field_name' => 'field_term_authoring_info_uid',
        'entity_type' => 'taxonomy_term',
        'bundle' => $vid,
        'label' =>  t('Authored by'),
        'description' => t('The username of the content author. If left blank, the current logged in user will be used.'),
      ])->save();

      // Authored by: Set form display.
      entity_get_form_display('taxonomy_term', $vid, 'default')
        ->setComponent('field_term_authoring_info_uid', [
          'type' => 'entity_reference_autocomplete',
      ])->save();

      // Authored by: Set display.
      entity_get_display('taxonomy_term', $vid, 'default')
        ->setComponent('field_term_authoring_info_uid', [
          'type' => 'author',
      ])->save();

      \Drupal::logger('term_authoring_info')->notice('Vocabulary %vid: User entity reference field %field_name was added.',
        [
          '%vid' => $vid,
          '%field_name' => t('Authored by'),
        ]
      );

    }

    /**** AUTHORED ON ****/

    $field_id = 'taxonomy_term.' . $vid . '.field_term_authoring_datecreated';
    $field = \Drupal::entityManager()->getStorage('field_config')->load($field_id);

    // Ensure the 'Authored on' (date) field doesn't exist yet.
    if (empty($field)) {

      // Ensure the field storage configuration entity hasn't already been defined.
      if (empty(FieldStorageConfig::load('taxonomy_term.field_term_authoring_datecreated'))) {

        // Authored on: Create field storage.
        FieldStorageConfig::create([
          'field_name' => 'field_term_authoring_datecreated',
          'entity_type' => 'taxonomy_term',
          'type' => 'datetime',
          'cardinality' => 1,
          'settings' => [
            'datetime_type' => 'datetime',
          ],
        ])->save();

      }

      // Authored on: Attach an instance of the field to the vocabulary.
      FieldConfig::create([
        'field_name' => 'field_term_authoring_datecreated',
        'entity_type' => 'taxonomy_term',
        'bundle' => $vid,
        'label' =>  t('Authored on'),
        'description' => t('Format: %format. Leave blank to use the time of form submission.',
          ['%format' => 'yyyy-mm-dd hh:mm:ss']
        ),
        'default_value' => [
          0 => [
            'default_date_type' => 'now',
            'default_date' => 'now',
          ],
        ],
      ])->save();

      // Authored on: Set form display.
      entity_get_form_display('taxonomy_term', $vid, 'default')
        ->setComponent('field_term_authoring_datecreated', [
          'type' => 'datetime_default',
      ])->save();

      //Authored on: Create field storage.
      entity_get_display('taxonomy_term', $vid, 'default')
        ->setComponent('field_term_authoring_datecreated', [
          'type' => 'datetime_default',
      ])->save();

      \Drupal::logger('term_authoring_info')->notice('Vocabulary %vid: Date field %field_name was added.',
        [
          '%vid' => $vid,
          '%field_name' => t('Authored on'),
        ]
      );

    }

  }

  /**
   * Delete authoring info fields from the appropriate vocabulary.
   *
   * @param string $vid
   *   Vocabulary machine-name.
   */
  static function deleteFields($vid) {

    // Delete the 'Authored by' field.
    $field_id = 'taxonomy_term.' . $vid . '.field_term_authoring_info_uid';
    $field = \Drupal::entityManager()->getStorage('field_config')->load($field_id);

    if (!empty($field)) {
      $label = $field->label();
      $field->delete();

      \Drupal::logger('term_authoring_info')->notice('Vocabulary %vid: User entity reference field %field_name was deleted.',
        [
          '%vid' => $vid,
          '%field_name' => $label,
        ]
      );
    }

    // Delete the 'Authored on' field.
    $field_id = 'taxonomy_term.' . $vid . '.field_term_authoring_datecreated';
    $field = \Drupal::entityManager()->getStorage('field_config')->load($field_id);

    if (!empty($field)) {
      $label = $field->label();
      $field->delete();

      \Drupal::logger('term_authoring_info')->notice('Vocabulary %vid: Date field %field_name was deleted.',
        [
          '%vid' => $vid,
          '%field_name' => $label,
        ]
      );
    }

  }

}
