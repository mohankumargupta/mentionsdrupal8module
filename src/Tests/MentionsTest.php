<?php

namespace Drupal\mentions\Tests;
/**
 * @file
 * Contains simpletests for the Mentions module.
 */

use Drupal\filter\Entity\FilterFormat;
use Drupal\simpletest\WebTestBase;

/**
 * Test Mentions filter and tracking.
 *
 * @group mentions
 */
class MentionsTest extends WebTestBase {
  protected $admin_user;
  protected $authUser = array();

  /**
   * Implementation of setUp().
   */
  public function setUp() {
    // Install required modules.
    $modules = array_merge(func_get_args(), array('mentions', 'views'));
    call_user_func_array(array($this, 'parent::setUp'), $modules);

    // Create and login user.
    $this->admin_user = $this->drupalCreateUser(array('access administration pages', 'administer modules'));
    $this->drupalLogin($this->admin_user);

    // Enable the Mentions filter for the Filtered HTML Input format.
    $this->drupalPost('admin/config/content/formats/manage/basic_html', array('filters[filter_mentions][status]' => TRUE), t('Save configuration'));

    // Enable comments on Page node type.
    //$this->drupalPost('admin/content/node-type/page', array('comment' => 2, 'comment_preview' => 0), t('Save content type'));
  }

    /**
     * Get Node ID (nid).
     */
    function getNodeID() {
        $matches = array();
        preg_match('/node\/([0-9]+)/', $this->getUrl(), $matches);
        return isset($matches[1]) ? $matches[1] : FALSE;
    }


    function assertLinkByHrefAndLabel($href, $label, $index = 0, $message = '', $group = 'Other') {
        $links   = $this->xpath('//a[contains(@href, :href)][normalize-space(text())=:label]', array(
            ':href'  => $href,
            ':label' => $label,
        ));
        $message = ($message ? $message : t('Link with href %href and label %label found.', array(
            '%href'  => $href,
            '%label' => $label,
        )));

        return $this->assert(isset($links[$index]), $message, $group);
    }

    function assertNoMentionExists($conditions = array(), $message = '') {
        $query = \Drupal::entityQuery('mentions')
                 ->condition('entity_type', $conditions['entity_type'])
                 ->condition('entity_id', $conditions['entity_id']);
        $node_ids = $query->execute();
        return $this->assertFalse(!empty($node_ids), $message);
    }

    function assertMentionExists($conditions = array(), $message = '') {
        $query = \Drupal::entityQuery('mentions')
            ->condition('entity_type', $conditions['entity_type'])
            ->condition('entity_id', $conditions['entity_id']);
        $node_ids = $query->execute();
        return $this->assertTrue(!empty($node_ids), $message);
    }

    /**
     * Test core Mentions functionality.
     */
    function testMentionsCore() {
        $this->drupalLogin($this->adminUser);

        // Ensure Mentions filter is available.
        $this->drupalGet('admin/config/content/formats/basic_html');
        $this->assertFieldByName('filters[filter_mentions][status]', NULL, 'Mentions filter is available.');

        // Enable Mentions filter.
        $edit = array('filters[filter_mentions][status]' => TRUE);
        $this->drupalPost('admin/config/content/formats/basic_html', $edit, t('Save configuration'));

        // Ensure Mentions filter is enabled.
        //$filters = filter_list_format('basic_html');
        $base_html_format = FilterFormat::load('basic_html');
        $filters = $base_html_format->filters();
        $this->assertTrue($filters['filter_mentions']->status, 'Mentions filter is enabled on Filtered HTML text format.');

        // Ensure Mentions filter tip is in place.
        /*
        $this->drupalGet('node/add/article');
        $this->assertText(t("Converts !username and !uid into a link the user's profile page.", array(
            '!username' => '[@username]',
            '!uid'      => '[@#uid]',
        )));
        */

        // Create content with a mention to admin user by username.

        $settings = array(
            'type'  => 'article',
            'title' => $this->randomString(),
            'body'  => array(LANGUAGE_NONE => array(array('value' => "[@{$this->adminUser->name}]"))),
        );
        $node     = $this->drupalCreateNode($settings);

        // Ensure Mention was correctly created and is linked to user profile.
        $this->drupalGet("node/{$node->nid}");
        $this->assertLinkByHrefAndLabel("user/{$this->adminUser->uid}", "@{$this->adminUser->name}");
        $this->assertMentionExists(array(
            'entity_type' => 'node',
            'entity_id'   => $node->nid,
            'uid'         => $this->adminUser->uid,
            'auid'        => $this->adminUser->uid
        ), 'Mention by username created successfully.');

        // Create content with a mention to admin user by #uid.
        $settings = array(
            'type'  => 'article',
            'title' => $this->randomString(),
            'body'  => array(LANGUAGE_NONE => array(array('value' => "[@#{$this->adminUser->uid}]")))
        );
        $node     = $this->drupalCreateNode($settings);

        // Ensure Mention was correctly created and is linked to user profile.
        $this->drupalGet("node/{$node->nid}");
        $this->assertLinkByHrefAndLabel("user/{$this->adminUser->uid}", "@{$this->adminUser->name}");
        $this->assertMentionExists(array(
            'entity_type' => 'node',
            'entity_id'   => $node->nid,
            'uid'         => $this->adminUser->uid,
            'auid'        => $this->adminUser->uid
        ), 'Mention by UID created successfully.');

        // Update Mention from admin user to auth user.
        $edit = array('body[und][0][value]' => "[@{$this->authUser->name}]");
        $this->drupalPost("node/$node->nid/edit", $edit, t('Save'));

        // Ensure old mention removed and new mention created.
        $this->assertNoMentionExists(array(
            'entity_type' => 'node',
            'entity_id'   => $node->nid,
            'uid'         => $this->adminUser->uid,
            'auid'        => $this->adminUser->uid
        ), 'Old mention no longer exists.');
        $this->assertMentionExists(array(
            'entity_type' => 'node',
            'entity_id'   => $node->nid,
            'uid'         => $this->authUser->uid,
            'auid'        => $this->adminUser->uid
        ), 'New mention created.');

        // Ensure mentions removed when node deleted.
        $this->drupalPost("node/{$node->nid}/delete", array(), t('Delete'));
        $this->assertNoMentionExists(array(
            'entity_type' => 'node',
            'entity_id'   => $node->nid,
        ), 'Mentions on deleted node removed successfully.');
    }
}




