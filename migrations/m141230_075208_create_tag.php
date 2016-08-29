<?php

use yii\db\Schema;
use app\migrations\Migration;

class m141230_075208_create_tag extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%tag}}', [
            'id' => Schema::TYPE_PK,
            'user_id' => Schema::TYPE_INTEGER . " NOT NULL",
            'title' => Schema::TYPE_STRING . " NOT NULL",
            'count' => Schema::TYPE_INTEGER . " NOT NULL DEFAULT 0",
        ], $this->tableOptions);

        $this->createIndex('title', '{{%tag}}', 'title', true);
    }

    public function safeDown()
    {
        $this->dropTable('{{%tag}}');
    }
}
