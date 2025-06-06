<?php

return [
    'admin' => [
        'type' => 1,
        'children' => [
            'updateOrders',
            'viewOrders',
            'createOrders',
            'createUser',
        ],
    ],
    'manager' => [
        'type' => 1,
        'children' => [
            'viewOrders',
            'updateOrders',
        ],
    ],
    'customer' => [
        'type' => 1,
        'children' => [
            'createOrders',
        ],
    ],
    'updateOrders' => [
        'type' => 2,
        'description' => 'Edit orders',
    ],
    'viewOrders' => [
        'type' => 2,
        'description' => 'View orders',
    ],
    'createOrders' => [
        'type' => 2,
        'description' => 'Create orders',
    ],
    'createUser' => [
        'type' => 2,
        'description' => 'Create users',
    ],
];
