<?php

namespace Drupal\term_authoring_info\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;

/**
 * Defines the term authoring info entity.
 *
 * The lines below, starting with '@ConfigEntityType,' are a plugin annotation.
 * These define the entity type to the entity type manager.
 *
 * The properties in the annotation are as follows:
 *  - id: The machine name of the entity type.
 *  - label: The human-readable label of the entity type. We pass this through
 *    the "@Translation" wrapper so that the multilingual system may
 *    translate it in the user interface.
 *  - handlers: An array of entity handler classes, keyed by handler type.
 *    - access: The class that is used for access checks.
 *    - list_builder: The class that provides listings of the entity.
 *    - form: An array of entity form classes keyed by their operation.
 *  - entity_keys: Specifies the class properties in which unique keys are
 *    stored for this entity type. Unique keys are properties which you know
 *    will be unique, and which the entity manager can use as unique in database
 *    queries.
 *  - links: entity URL definitions. These are mostly used for Field UI.
 *    Arbitrary keys can set here. For example, User sets cancel-form, while
 *    Node uses delete-form.
 *
 * @see http://previousnext.com.au/blog/understanding-drupal-8s-config-entities
 * @see annotation
 * @see Drupal\Core\Annotation\Translation
 *
 * @ingroup vocab_display_status
 *
 * @ConfigEntityType(
 *   id = "vocab_display_status",
 *   label = @Translation("Term authoring info: display status"),
 *   admin_permission = "administer taxonomy",
 *   handlers = {
 *     "list_builder" = "Drupal\term_authoring_info\Controller\TermAuthoringListBuilderVocabs",
 *     "form" = {
 *       "edit" = "Drupal\taxonomy\VocabularyForm",
 *     }
 *   },
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label"
 *   },
 *   config_export = {
 *     "id",
 *     "state"
 *   },
 *   links = {
 *     "edit-form" = "/admin/structure/taxonomy/manage/{vocab_display_status}",
 *   },
 * )
 */
class VocabDisplayStatus extends ConfigEntityBase {

  /**
   * ID / machine name of the vocabulary (and unique ID for this entity).
   *
   * @var string
   */
  public $id;

  /**
   * "Display authoring information" checkbox: checked (1) or unchecked (0)
   *
   * @var int
   */
  public $state;

}
