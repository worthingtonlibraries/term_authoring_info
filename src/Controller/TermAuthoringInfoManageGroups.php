<?php

namespace Drupal\term_authoring_info\Controller;

/**
 * Controller for creating and deleting authoring info field groups.
 */
class TermAuthoringInfoManageGroups {

  /**
   * Add an authoring info group to the appropriate vocabulary.
   *
   * @param string $vid
   *   Vocabulary machine-name.
   */
  static function createGroup($vid) {

    // Ensure the 'Field Group' module is installed.
    if (\Drupal::service('module_handler')->moduleExists('field_group')) {

      // Load the 'Manage form display' (form) field group.
      $group = field_group_load_field_group('group_term_authoring_info', 'taxonomy_term', $vid, 'form', 'default');

      // Ensure the form display group doesn't exist yet.
      if (empty($group)) {

        // Build the 'Manage form display' (form) field group.
        $field_group = (object) [
          'group_name' => 'group_term_authoring_info',
          'entity_type' => 'taxonomy_term',
          'bundle' => $vid,
          'mode' => 'default',
          'context' => 'form',
          'children' =>[
            'field_term_authoring_info_uid',
            'field_term_authoring_datecreated',
          ],
          'parent_name' => '',
          'weight' => 50,
          'format_type' => 'details',
        ];

        $field_group->format_settings = [
          'open' => FALSE,
          'required_fields' => TRUE,
          'id' => 'term-authoring-info-field-group-form',
        ];

        $field_group->label = t('Authoring information');

        // Create the 'Manage form display' (form) field group.
        field_group_group_save($field_group);

        \Drupal::logger('term_authoring_info')->notice('Vocabulary %vid: Form field group %group_name was added.',
          [
            '%vid' => $vid,
            '%group_name' => $field_group->label,
          ]
        );

      }


      // Load the 'Manage display' (view) field group.
      $group = field_group_load_field_group('group_term_authoring_info', 'taxonomy_term', $vid, 'view', 'default');

      // Ensure the display group doesn't exist yet.
      if (empty($group)) {

        // Build the 'Manage display' (view) field group.
        $field_group = (object) [
          'group_name' => 'group_term_authoring_info',
          'entity_type' => 'taxonomy_term',
          'bundle' => $vid,
          'mode' => 'default',
          'context' => 'view',
          'children' =>[
            'field_term_authoring_info_uid',
            'field_term_authoring_datecreated',
          ],
          'parent_name' => '',
          'weight' => 20,
          'format_type' => 'fieldset',
        ];

        $field_group->format_settings = [
          'id' => 'term-authoring-info-field-group-view',
        ];

        $field_group->label = t('Authoring information');

        // Create the 'Manage display' (view) field group.
        field_group_group_save($field_group);

        \Drupal::logger('term_authoring_info')->notice('Vocabulary %vid: Display field group %group_name was added.',
          [
            '%vid' => $vid,
            '%group_name' => $field_group->label,
          ]
        );

      }

    }

  }

  /**
   * Delete the authoring info group from the appropriate vocabulary.
   *
   * @param string $vid
   *   Vocabulary machine-name.
   */
  static function deleteGroup($vid) {

    // Ensure the 'Field Group' module is installed.
    if (\Drupal::service('module_handler')->moduleExists('field_group')) {

      // Load the 'Manage form display' (form) field group.
      $group = field_group_load_field_group('group_term_authoring_info', 'taxonomy_term', $vid, 'form', 'default');

      if (!empty($group)) {

        $label = $group->label;

        // Delete the 'Manage form display' (form) field group.
        field_group_group_delete($group);

        \Drupal::logger('term_authoring_info')->notice('Vocabulary %vid: Form field group %group_name was deleted.',
          [
            '%vid' => $vid,
            '%group_name' => $label,
          ]
        );

      }

      // Load the 'Manage display' (view) field group.
      $group = field_group_load_field_group('group_term_authoring_info', 'taxonomy_term', $vid, 'view', 'default');

      if (!empty($group)) {

        $label = $group->label;

        // Delete the 'Manage display' (view) field group.
        field_group_group_delete($group);

        \Drupal::logger('term_authoring_info')->notice('Vocabulary %vid: Display field group %group_name was deleted.',
          [
            '%vid' => $vid,
            '%group_name' => $label,
          ]
        );

      }

    }

  }

}
