<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'anthropic' => [
        'key'   => env('ANTHROPIC_API_KEY'),
        'model' => env('ANTHROPIC_MODEL', 'claude-sonnet-4-6'),
    ],
    'telegram' => [
        'bot_token'      => env('TELEGRAM_BOT_TOKEN'),
        'chat_id'        => env('TELEGRAM_CHAT_ID'),
        'bot_username'   => env('TELEGRAM_BOT_USERNAME'),
        'webhook_secret' => env('TELEGRAM_WEBHOOK_SECRET'),
    ],
    'uphunt' => [
        'webhook_key' => env('UPHUNT_WEBHOOK_KEY'),
        'min_score'   => env('UPHUNT_MIN_SCORE', 7),
    ],
    'proposal' => [
        'profile' => env('PROPOSAL_PROFILE', <<<'TXT'
    Freelance PHP/web developer (chetanbuilds.com), 8+ years. Core stack: Laravel (up to v12/PHP 8.2), CodeIgniter 3, WordPress custom themes & plugins, MySQL, Razorpay/payment integrations, REST APIs.
    Recent work: complete custom CRM from scratch (pure PHP MVC) for a UK consulting client — tickets, site-visit reports with digital signatures, roles/permissions. Building OutraqHQ, my own Laravel 12 HR SaaS (billing, bulk imports, leave management). Student self-service portal (CodeIgniter 3) for a govt org: OTP registration, admissions, exam proctoring, online fee payment. Custom WordPress theme conversion for a cloud-storage company. WooCommerce video-invitation plugin incl. RTL/Hebrew fixes.
    Style: fast communication, daily updates, clean handover. Comfortable in legacy codebases.
    TXT),
    ],

];
