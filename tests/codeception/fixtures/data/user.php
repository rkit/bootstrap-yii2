<?php

use \app\models\User;

return [
    [
        'username' => 'admin',
        'email' => 'admin@example.com',
        'authKey' => 'dKz8PzyduJUDyrrhAC05-Mn53IvaXvoA',
        //fghfgh
        'password' => '$2y$13$1hW57Qext3hd0jwNFl7pQuDcd0bIBo4h4KXF.3Uwxt/yI77Yqvx82',
        'passwordResetToken' => 'aPDnNGI85L4va3dYJ_0xoz-Kw7NtzloS_' . time(),
        'emailConfirmToken' => 'a7QKA1EkFe0cNoUJ7hIwDPDtdQtrQ7JY_' . time(),
        'dateCreate' => '2015-01-01 12:02:00',
        'dateUpdate' => '2015-01-01 12:02:00',
        'status' => User::STATUS_ACTIVE
    ],
    [
        'username' => 'example',
        'email' => 'example@example.com',
        'authKey' => 'xFK_r79Q976mtxqccblijO-SmqjBwbNd',
        //123123
        'password' => '$2y$13$2c0xt9QwWVq1yBUPmWl3ZeD/poVF8cyrwWrX87suGrYyRbP47Y1Mq',
        'passwordResetToken' => 'bPDnNGI85L4va3dYJ_0xoz-Kw7NtzloS_' . time(),
        'emailConfirmToken' => 'b7QKA1EkFe0cNoUJ7hIwDPDtdQtrQ7JY_' . time(),
        'dateCreate' => '2015-01-01 12:02:00',
        'dateUpdate' => '2015-01-01 12:02:00',
        'status' => User::STATUS_ACTIVE
    ],
    [
        'username' => 'example-blocked',
        'email' => 'example-blocked@example.com',
        'authKey' => 'xFK_r79Q976mtxqccblijO-SmqjBwbNd',
        //123123
        'password' => '$2y$13$2c0xt9QwWVq1yBUPmWl3ZeD/poVF8cyrwWrX87suGrYyRbP47Y1Mq',
        'passwordResetToken' => 'cPDnNGI85L4va3dYJ_0xoz-Kw7NtzloS_' . time(),
        'emailConfirmToken' => 'c7QKA1EkFe0cNoUJ7hIwDPDtdQtrQ7JY_' . time(),
        'dateCreate' => '2015-01-01 12:02:00',
        'dateUpdate' => '2015-01-01 12:02:00',
        'status' => User::STATUS_BLOCKED
    ],
    [
        'username' => 'example-deleted',
        'email' => 'example-deleted@example.com',
        'authKey' => 'xFK_r79Q976mtxqccblijO-SmqjBwbNd',
        //123123
        'password' => '$2y$13$2c0xt9QwWVq1yBUPmWl3ZeD/poVF8cyrwWrX87suGrYyRbP47Y1Mq',
        'passwordResetToken' => 'dPDnNGI85L4va3dYJ_0xoz-Kw7NtzloS_' . time(),
        'emailConfirmToken' => 'd7QKA1EkFe0cNoUJ7hIwDPDtdQtrQ7JY_' . time(),
        'dateCreate' => '2015-01-01 12:02:00',
        'dateUpdate' => '2015-01-01 12:02:00',
        'status' => User::STATUS_DELETED
    ],
];