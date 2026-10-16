<?php

return [
    /*
    |--------------------------------------------------------------------------
    | ITPEC IT Passport Default Settings
    |--------------------------------------------------------------------------
    |
    | Default configuration values for the IT Passport examination review platform.
    | These can be overridden dynamically via AppSettings service stored in app_settings table.
    |
    */

    'require_pretest' => true,
    'skip_pretest_gate' => false,
    'pretest_source_package_id' => null,
    'pretest_question_count' => 100,
    'time_limit_minutes' => 120,
    'allow_pause_resume' => true,
    'resume_window_days' => 7,
    'shuffle_choices' => false,
    'pass_mark_percent' => 60,
    'field_minimum_percent' => 30,
    'retake_policy' => 'unlimited',
    'weak_topic_threshold_percent' => 50,
    'roadmap_order' => 'weakest_field_first',
];
