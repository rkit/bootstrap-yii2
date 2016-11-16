<?php

use yii\db\Schema;
use app\migrations\Migration;

class m141230_081319_create_news extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%news}}', [
            'id' => Schema::TYPE_PK,
            'title' => Schema::TYPE_STRING . " NOT NULL DEFAULT ''",
            'text' => "longtext",
            'preview' => Schema::TYPE_STRING . " NOT NULL DEFAULT ''",
            'date_create' => Schema::TYPE_TIMESTAMP . " NULL DEFAULT NULL",
            'date_update' => Schema::TYPE_TIMESTAMP . " NULL DEFAULT NULL",
            'date_pub' => Schema::TYPE_TIMESTAMP . " NULL DEFAULT NULL",
            'status' => "tinyint(1) NOT NULL DEFAULT 0",
        ], $this->tableOptions);

        $this->createIndex('title', '{{%news}}', 'title');
        $this->createIndex('status', '{{%news}}', 'status');
    }

    public function safeDown()
    {
        $this->dropTable('{{%news}}');
    }
}
