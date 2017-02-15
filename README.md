Taxonomy Term Authoring Information
===================================

* Project site: https://www.drupal.org/project/term_authoring_info
* Code: https://www.drupal.org/project/term_authoring_info/git-instructions
* Issues: https://www.drupal.org/project/issues/term_authoring_info

Description
-----------
The Taxonomy Term Authoring Information module adds new fields (author,
creation date) to a vocabulary, so authoring information can be saved for
each term. The fields include a user entity reference field ("Authored by")
and a date field ("Authored on"), both of which get automatically populated
upon submission if the fields are left blank.

Because the fields belong to the term object, they are immediately available
to the Views module as well.

Add authoring information to a vocabulary
-----------------------------------------
* After installing this module, visit the configuration page
  (Administration » Configuration » Content authoring »
  Taxonomy term authoring information).
* To add authoring information to a specific vocabulary,
  click the corresponding *Edit* button.
* On the *Edit vocabulary* screen, check the "Display authoring information"
  box and click *Save*.
* You should now see the fields "Authored On" and "Authored by" listed on
  the tabs *Manage fields*, *Manage form display* and *Manage display*.

NOTE: Any term that existed *before* this module was installed will not
contain any authoring data until the term is saved again.

Remove authoring information from a vocabulary
----------------------------------------------
* On the *Edit vocabulary* screen, uncheck the "Display authoring information"
  box and click *Save*.
* This will automatically delete the "Authored On" and "Authored by" fields,
  as well as their corresponding data.

Installation
------------
Install this module by following standard procedure:
https://www.drupal.org/docs/8/extending-drupal-8/installing-modules

Dependencies
------------
* Taxonomy
* Field Group

Attributions
------------
* Pijus Kanti Roy (pijus) <pijus DOT k DOT roy AT gmail DOT com> - Creator: Drupal 7
* Stefan Langer (slanger) of Worthington Libraries
  <slanger AT worthingtonlibraries DOT org> - Original port to Drupal 8
