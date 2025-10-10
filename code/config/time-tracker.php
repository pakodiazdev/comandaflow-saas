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
        'token' => env('GITHUB_TOKEN'),
        'repo' => env('GITHUB_REPO'),
        'owner' => env('GITHUB_OWNER'),
        'project_id' => env('GITHUB_PROJECT_ID'),
        'project_status_field_id' => env('GITHUB_PROJECT_STATUS_FIELD_ID'),
        'project_number' => env('GITHUB_PROJECT_NUMBER'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Task Storage Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for task storage
    |
    */
    'task_path' => env('PATH_TASK', '/app/docs/tasks'),

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

    /*
    |--------------------------------------------------------------------------
    | Status Configuration
    |--------------------------------------------------------------------------
    |
    | GitHub Project status IDs for different states
    |
    */
    'statuses' => [
        'waiting' => env('TIME_TRACKER_WAITING'),
        'in_progress' => env('TIME_TRACKER_IN_PROGRESS'),
        'complete' => env('TIME_TRACKER_COMPLETE'),
    ],
];
