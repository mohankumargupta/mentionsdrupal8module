<?php

/**
 * Implements hook_views_data()
 */
function mentions_views_data() {
    $data = [];
    $data['mentions']['table']['group'] = t('Mentions');
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