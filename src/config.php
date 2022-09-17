<?php

return [
    'default' => 'json',

    'driver' => [
        'default' => 'json',
        'json' => [
            'status_file' => storage_path('module_status.json')
        ]
    ],
    'base_path' => base_path('modules'),
];