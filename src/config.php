<?php

return [
    'default' => 'json',

    'driver' => [
        'json' => [
            'status_file' => storage_path('module_status.json')
        ]
    ],
    'base_path' => base_path('vendor'),
];