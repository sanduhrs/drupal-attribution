CONTENTS OF THIS FILE
---------------------

 * Introduction
 * Requirements
 * Installation
 * Configuration
 * Similar projects
 * Maintainers

INTRODUCTION
------------

The attribution module provides content attribution and license configuration
for the site and for any fieldable entity as nodes and media. It provides a
set of default licenses and allows for import of additional licenses from the
list of SPDX license list and custom defined licenses.

The list of licenses includes more than 400 licenses including the various
flavours and versions of the Creative Commons and of course the
GNU General Public License.

 * For a list of all supported licenses
   https://spdx.org/licenses/

 * For a full description of the module, visit the project page:
   https://www.drupal.org/project/attribution

 * To submit bug reports and feature suggestions, or track changes:
   https://www.drupal.org/project/issues/attribution

REQUIREMENTS
------------

This module requires no modules outside of Drupal core.

INSTALLATION
------------

 * Install as you would normally install a contributed Drupal module. Visit
   https://www.drupal.org/node/1897420 for further information.

CONFIGURATION
-------------

 * Configure the user permissions in Administration » People » Permissions:

   - Use the Attributions Licenses page

     List and import licenses for usage with the attribution block and fields.

 * Configure an attribution block in Administration » Structure » Block layout

   - Use the attribution block and configure a license and disclaimer for
     the site.

 * Configure a field in Administration
   » Structure » Content types » Article » Add field

   - Select field type attribution and configure display and form
     display accordingly.

SIMILAR PROJECTS
----------------

 * Media Attribution
   Attach attribution and license information to media images.
   https://www.drupal.org/project/media_attribution

 * Creative Commons
   Allows users to select and assign a Creative Commons license to a node and
   any attached content, or to the entire site.
   https://www.drupal.org/project/creativecommons

 * License field
   This field can be used on node content types as well as Media entities.
   https://www.drupal.org/project/license_field

 * Creative Commons Widget
   Turns a regular CCK text field into a field for selecting and displaying
   Creative Commons licenses.
   https://www.drupal.org/project/cc_widget

 * Creative Commons Lite
   This module allows users to add creativecommons license to any type of
   drupal node.
   https://www.drupal.org/project/creativecommons_lite

 * Licenses Vocabulary
   This module takes the inspiration from media_attribution but is not limited
   to media.
   https://www.drupal.org/project/licenses_vocabulary

 * Creative commons field
   Defines a field type for attaching creative commons licence types.
   https://www.drupal.org/project/creative_commons

MAINTAINERS
-----------

Current maintainers:
 * Stefan Auditor (sanduhrs) - https://www.drupal.org/user/28074

This project has been sponsored by:
 * Stefan Auditor <stefan@auditor.email>
