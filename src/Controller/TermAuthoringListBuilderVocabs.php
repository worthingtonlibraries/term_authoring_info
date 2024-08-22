<?php

namespace Drupal\term_authoring_info\Controller;

use Drupal\Core\Config\Entity\ConfigEntityListBuilder;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Render\Markup;
use Drupal\Core\Url;
use Drupal\Core\Link;

/**
 * A listing of vocabularies and whether they're displaying authoring info.
 *
 * List Controllers provide a list of entities in a tabular form. The base
 * class provides most of the rendering logic for us. The key functions
 * we need to override are buildHeader() and buildRow(). These control what
 * columns are displayed in the table, and how each row is displayed
 * respectively.
 *
 * Drupal locates the list controller by looking for the "list" entry under
 * "controllers" in our entity type's annotation. We define the path on which
 * the list may be accessed in our module's *.routing.yml file. The key entry
 * to look for is "_entity_list". In *.routing.yml, "_entity_list" specifies
 * an entity type ID. When a user navigates to the URL for that router item,
 * Drupal loads the annotation for that entity type. It looks for the "list"
 * entry under "controllers" for the class to load.
 *
 * @package Drupal\term_authoring_info\Controller
 *
 * @ingroup vocab_display_status
 */
class TermAuthoringListBuilderVocabs extends ConfigEntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function load() {

    $vocabularies = \Drupal::entityQuery('taxonomy_vocabulary')->execute();

    // Loop through each existing vocabulary.
    foreach ($vocabularies as $vid) {

      // Attempt to load the entity configuration for this vocabulary.
      $entity = \Drupal::entityTypeManager()->getStorage('vocab_display_status')->load($vid);

      // Determine if authoring info for this vocabulary is missing.
      if (empty($entity)) {
        // Create a new entity configuration value.
        $entity = \Drupal::entityTypeManager()->getStorage('vocab_display_status')->create();

        // Update the enity with values.
        $entity->id = $vid;
        $entity->state = 0;

        // Save the changes. 
        $entity->save();
      }

    }

    // Query the entity configuration, sorting by the appropriate column.
    $entity_query = \Drupal::entityQuery('vocab_display_status');
    // $entity_query->pager(50);
    // $entity_query->condition('column_name', $value);
    $header = $this->buildHeader();
    $entity_query->tableSort($header);
    $entity_ids = $entity_query->execute();

    return $this->storage->loadMultiple($entity_ids);
  }

  /**
   * Builds the header row for the entity listing.
   *
   * @return array
   *   A render array structure of header strings.
   *
   * @see Drupal\Core\Entity\EntityListController::render()
   */
  public function buildHeader() {
    $header = [
      'id' => [
        'data' => $this->t('Vocabulary name'),
        'field' => 'id',
        'specifier' => 'id',
        'sort' => 'asc',
      ],
      'state' => [
        'data' => $this->t('Authoring info?'),
        'field' => 'state',
        'specifier' => 'state',
      ],
    ];

    return $header + parent::buildHeader();
  }

  /**
   * Builds a row for an entity in the entity listing.
   *
   * @param EntityInterface $entity
   *   The entity for which to build the row.
   *
   * @return array
   *   A render array of the table row for displaying the entity.
   *
   * @see Drupal\Core\Entity\EntityListController::render()
   */
  public function buildRow(EntityInterface $entity) {

    $vocabulary = \Drupal\taxonomy\Entity\Vocabulary::load($entity->id);

    $row['id'] = $vocabulary->get('name');
    $row['active'] = (($entity->state == 1) ? 'Yes' : Markup::create('<span class="deemphasize">No</span>'));

    return $row + parent::buildRow($entity);
  }

  /**
   * Adds some descriptive text to our entity list.
   *
   * Typically, there's no need to override render(). You may wish to do so,
   * however, if you want to add markup before or after the table.
   *
   * @return array
   *   Renderable array.
   */
  public function render() {
    $build['description'] = [
      '#markup' => $this->t('<p>The following table shows which vocabularies have authoring information activated. To add authoring fields to a vocabulary (including a user entity reference field <em>(Authored by)</em> and a date field <em>(Authored on)</em>), click the corresponding <em>Edit</em> button and check the <em>Display authoring information</em> box.</p> <p><em>Note:</em> Any term that existed <em>before</em> this module was installed will not contain any authoring data until the term is saved again.</p>'),
    ];

    $build['#attached']['library'][] = 'term_authoring_info/term_authoring_info.libraries.main_css';

    $build[] = parent::render();
    return $build;
  }

}
