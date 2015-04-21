<?php

/**
 * @file
 * Contains \Drupal\mentions\Plugin\CKEditorPlugin\MentionsAutocomplete.
 */

namespace Drupal\mentions\Plugin\CKEditorPlugin;

use Drupal\ckeditor\CKEditorPluginBase;
use Drupal\editor\Entity\Editor;
use Drupal\ckeditor\CKEditorPluginContextualInterface;

/**
 * Defines the "Mentions Autocomplete" plugin.
 *
 * @CKEditorPlugin(
 *   id = "mentionsautocomplete",
 *   label = @Translation("Mentions Autocomplete"),
 *   module = "mentions"
 * )
 */
class MentionsAutocomplete extends CKEditorPluginBase implements CKEditorPluginContextualInterface {
	public function getButtons() {
		return array();
	}

	public function getConfig(Editor $editor) {
		return array();
	}

	public function getFile() {
		return drupal_get_path('module', 'mentions').'/js/plugins/mentionsautocomplete/plugin.js';
	}
	
	public function isInternal() {
    return FALSE;
  }

	public function isEnabled(Editor $editor) {
		return TRUE;
	}

}

