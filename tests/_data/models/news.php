<?php

use \app\models\News;

return [
    [
        'id' => 1,
        'type_id' => 1,
        'title' => 'News-1',
        'text' => 'Text-1',
        'date_pub' => '2016-01-01',
        'status' => News::STATUS_BLOCKED,
    ],
    [
        'id' => 2,
        'type_id' => 1,
        'title' => 'News-2',
        'text' => 'Text-2',
        'date_pub' => '2016-01-02',
        'status' => News::STATUS_ACTIVE,
    ],
    [
        'id' => 3,
        'type_id' => 2,
        'title' => 'News-3',
        'text' => 'Text-3',
        'date_pub' => '2016-01-03',
        'status' => News::STATUS_ACTIVE,
    ],
];
