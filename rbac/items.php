<?php

return [
    'admin' => [
        'type' => 1,
        'children' => [
            'viewUsers',
            'updateOrders',
            'viewOrders',
            'createOrders',
            'createUser',
        ],
    ],
    'manager' => [
        'type' => 1,
        'children' => [
            'viewUsers',
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
    'viewUsers' => [
        'type' => 2,
        'description' => 'View users',
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
