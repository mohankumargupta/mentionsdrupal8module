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
      'field' => [
        'id' => 'numeric'
      ]

    ];

    $data['mentions']['entity_type'] = [
        'title' => t('Entity type'),
          'help' => t('Entity type of entity that contains mention'),
          'field' => [
              'id' => 'standard'
          ],
          'filter' => [
              'id' => 'standard'
          ]

      ];

    $data['mentions']['title'] = [
        'title' => t('Title'),
        'help' => t('Title of entity containing mention'),
        'real field' => 'mid',
        'field' => [
            'id' => 'mentions_title'
        ],
        'relationship' => [
            'base' => 'node_field_data',
            'base field' => 'title',
            'relationship field' => 'nid',
            'title' => t('Mention Title'),
            'help' => t('Mention Title'),
            'id' => 'standard',
            'label' => t('Mention Title'),
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
      'title' => t('Mentioned user uid'),
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
      'title' => t('Date'),
      'help' => t('Date'),
      'field' => [
        'id' => 'date',
      ],
      'sort' => [
        'id' => 'date',
      ],
      'argument' => [
        'id' => 'date',
      ],
    ];

    $data['mentions']['entity_id'] = [
      'title' => t('Entity id'),
      'help' => t('The unique ID of the object that contains mention'),
      'field' => [
        'id' => 'standard'
      ],
      'sort' => [
        'id' => 'standard',
      ],
      'argument' => [
        'id' => 'numeric',
      ],
    ];

    $data['mentions']['link'] = [
        'title' => t('Link'),
        'real field' => 'mid',
        'help' => t('Link to entity that contains mention'),
        'field' => [
            'id' => 'mentions_link'
        ]
    ];

    return $data;
  }
}