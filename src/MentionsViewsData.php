<?php

namespace Drupal\mentions;

use Drupal\views\EntityViewsData;
use Drupal\views\EntityViewsDataInterface;

/**
 * Provides the views data for the mentions entity type
 */
class MentionsViewsData extends EntityViewsData {

  public function getViewsData() {
    $data = array();

    $data['mentions']['table']['group'] = t('Mentions');
    $data['mentions']['table']['entity type'] = 'mentions';
    $data['mentions']['table']['base']['field'] = 'mid';
    $data['mentions']['table']['base']['title'] = t('Mentions');
    $data['mentions']['table']['base']['help'] = t('Mentions entry');
    $data['mentions']['table']['base']['weight'] = 1;
    $data['mentions']['table']['base']['defaults']['field'] = 'mid';
    $data['mentions']['table']['wizard_id'] = 'mention';

    $data['mentions']['mid'] = [
      'title' => t('Mention ID'),
      'help' => t('Mention ID'),
      'filter' => [
        'id' => 'numeric'
      ],
      'argument' => [
        'id' => 'numeric'
      ],
      'field' => [
        'id' => 'numeric'
      ]

    ];


    $data['mentions']['auid'] = [
        'title' => t('Author user id'),
        'help' => t('Author user id'),
        'filter' => [
            'id' => 'numeric',
        ],
        'argument' => [
            'id' => 'numeric',
        ],
        'field' => [
            'id' => 'user',
        ],
        'relationship' => [
            'base' => 'users',
            'title' => t('User'),
            'help' => t('The user that authored the mention'),
            'id' => 'standard',
            'label' => t('Mentions user'),
        ]
    ];


    $data['mentions']['uid'] = [
      'title' => t('User uid'),
      'help' => t('The user that is mentioned'),
      'relationship' => [
        'base' => 'users',
        'title' => t('User'),
        'help' => t('The user that is mentioned'),
        'id' => 'standard',
        'label' => t('Mentions user'),
      ],
      'filter' => [
        'id' => 'numeric',
      ],
      'argument' => [
        'id' => 'numeric',
      ],
      'field' => [
        'id' => 'user',
      ],
    ];

    $data['mentions']['created'] = [
      'title' => t('Last Flagged Time'),
      'help' => t('Display latest time the content was flagged by a user.'),
      'field' => [
        'id' => 'date',
      ],
      'sort' => [
        'id' => 'date',
      ],
      'filter' => [
        'id' => 'date',
      ],
      'argument' => [
        'id' => 'date',
      ],
    ];

    $data['mentions']['entity_id'] = [
      'title' => t('Entity ID of entity containing mention'),
      'help' => t('The unique ID of the object that contains mention'),
      'sort' => [
        'id' => 'standard',
      ],
      'argument' => [
        'id' => 'numeric',
      ],
    ];

    return $data;
  }
}