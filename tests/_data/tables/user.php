<?php

use \app\models\entity\User;

return [
    'user-1' => [
        'username' => 'superuser',
        'email' => 'superuser@example.com',
        'auth_key' => 'dKz8PzyduJUDyrrhAC05-Mn53IvaXvoA',
        // password: fghfgh
        'password' => '$2y$13$1hW57Qext3hd0jwNFl7pQuDcd0bIBo4h4KXF.3Uwxt/yI77Yqvx82',
        'password_reset_token' => 'aPDnNGI85L4va3dYJ_0xoz-Kw7NtzloS_' . time(),
        'email_confirm_token' => 'a7QKA1EkFe0cNoUJ7hIwDPDtdQtrQ7JY_' . time(),
        'date_create' => '2015-01-01 12:02:00',
        'date_update' => '2015-01-01 12:02:00',
        'ip' => '2130706431',
        'status' => User::STATUS_ACTIVE,
        'role_name' => User::ROLE_SUPERUSER
    ],
    'user-2' => [
        'username' => 'user-2',
        'email' => 'user-2@example.com',
        'auth_key' => 'xFK_r79Q976mtxqccblijO-SmqjBwbN2',
        // password: 123123
        'password' => '$2y$13$2c0xt9QwWVq1yBUPmWl3ZeD/poVF8cyrwWrX87suGrYyRbP47Y1Mq',
        'password_reset_token' => 'bPDnNGI85L4va3dYJ_0xoz-Kw7NtzloS_' . time(),
        'email_confirm_token' => 'b7QKA1EkFe0cNoUJ7hIwDPDtdQtrQ7JY_1471109451',
        'date_create' => '2015-01-02 12:02:00',
        'date_update' => '2015-01-02 12:02:00',
        'ip' => '2130706432',
        'status' => User::STATUS_ACTIVE
    ],
    'user-3' => [
        'username' => 'user-3',
        'email' => 'user-3@example.com',
        'auth_key' => 'xFK_r79Q976mtxqccblijO-SmqjBwbN3',
        // password: 123123
        'password' => '$2y$13$2c0xt9QwWVq1yBUPmWl3ZeD/poVF8cyrwWrX87suGrYyRbP47Y1Mq',
        'password_reset_token' => 'cPDnNGI85L4va3dYJ_0xoz-Kw7NtzloS_' . time(),
        'email_confirm_token' => 'c7QKA1EkFe0cNoUJ7hIwDPDtdQtrQ7JY_' . time(),
        'date_create' => '2015-01-03 12:02:00',
        'date_update' => '2015-01-03 12:02:00',
        'ip' => '2130706433',
        'status' => User::STATUS_BLOCKED
    ],
    'user-4' => [
        'username' => 'user-4',
        'email' => 'user-4@example.com',
        'auth_key' => 'xFK_r79Q976mtxqccblijO-SmqjBwbN4',
        // password: 123123
        'password' => '$2y$13$2c0xt9QwWVq1yBUPmWl3ZeD/poVF8cyrwWrX87suGrYyRbP47Y1Mq',
        'password_reset_token' => 'dPDnNGI85L4va3dYJ_0xoz-Kw7Ntzlo4_' . time(),
        'email_confirm_token' => 'd7QKA1EkFe0cNoUJ7hIwDPDtdQtrQ7J4_' . time(),
        'date_create' => '2015-01-04 12:02:00',
        'date_update' => '2015-01-04 12:02:00',
        'ip' => '2130706434',
        'status' => User::STATUS_DELETED
    ],
    'user-5' => [
        'username' => 'user-5',
        'email' => 'user-5@example.com',
        'auth_key' => 'xFK_r79Q976mtxqccblijO-SmqjBwbN5',
        // password: 123123
        'password' => '$2y$13$2c0xt9QwWVq1yBUPmWl3ZeD/poVF8cyrwWrX87suGrYyRbP47Y1Mq',
        'password_reset_token' => '123_0',
        'email_confirm_token' => 'd7QKA1EkFe0cNoUJ7hIwDPDtdQtrQ7J5_' . time(),
        'date_create' => '2015-01-05 12:02:00',
        'date_update' => '2015-01-05 12:02:00',
        'ip' => '2130706435',
        'status' => User::STATUS_ACTIVE,
        'role_name' => 'Editor',
    ],
    'user-6' => [
        'username' => 'user-6',
        'email' => 'user-6@example.com',
        'auth_key' => 'xFK_r79Q976mtxqccblijO-SmqjBwbN6',
        // password: 123123
        'password' => '$2y$13$2c0xt9QwWVq1yBUPmWl3ZeD/poVF8cyrwWrX87suGrYyRbP47Y1Mq',
        'password_reset_token' => 'dPDnNGI85L4va3dYJ_0xoz-Kw7Ntzlo6_' . time(),
        'email_confirm_token' => 'd7QKA1EkFe0cNoUJ7hIwDPDtdQtrQ7J6_' . time(),
        'date_create' => '2015-01-06 12:02:00',
        'date_update' => '2015-01-06 12:02:00',
        'ip' => '2130706436',
        'status' => User::STATUS_ACTIVE
    ],
];
