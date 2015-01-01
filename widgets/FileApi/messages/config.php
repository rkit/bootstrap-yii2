<?php

return [
    'sourcePath' => dirname(__DIR__),
    'languages' => ['en', 'ru'],
    'translator' => 'Yii::t',
    'sort' => false,
    'removeUnused' => true,
    'format' => 'php',
    'messagePath' => __DIR__,
    'overwrite' => true,
    'only' => ['*.php'],
    'except' => [
        '.svn',
        '.git',
        '.gitignore',
        '.gitkeep',
        '.hgignore',
        '.hgkeep',
        '/messages',
    ]
];
