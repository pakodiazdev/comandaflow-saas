<?php

return [
    /*
    |--------------------------------------------------------------------------
    | GitHub Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for GitHub integration
    |
    */
    'github' => [
        'token' => env('GITHUB_TOKEN', env('PATH_GITHUB')),
        'repo' => env('GITHUB_REPO'),
        'owner' => env('GITHUB_OWNER'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Task Storage Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for task storage
    |
    */
    'task_path' => env('PATH_TASK', '/tasks'),

    /*
    |--------------------------------------------------------------------------
    | Time Tracking Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for time tracking behavior
    |
    */
    'time_tracking' => [
        'default_closure_time' => 0, // minutes
        'auto_upload' => false,
        'time_format' => 'H:i:s',
        'timezone' => env('TIME_TRACKER_TIMEZONE', 'UTC'),
    ],
];
