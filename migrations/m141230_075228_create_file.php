<?php

use yii\db\Schema;
use app\migrations\Migration;

class m141230_075228_create_file extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%file}}', [
            'id' => Schema::TYPE_PK,
            'userId' => Schema::TYPE_INTEGER . " NOT NULL DEFAULT 0",
            'ownerId' => Schema::TYPE_INTEGER . " NOT NULL DEFAULT 0",
            'ownerType' => "tinyint(1) NOT NULL DEFAULT 0",
            'title' => Schema::TYPE_STRING . " NOT NULL",
            'name' => Schema::TYPE_STRING . " NOT NULL",
            'size' => Schema::TYPE_INTEGER . " NOT NULL DEFAULT 0",
            'mime' => Schema::TYPE_STRING . "(100) NOT NULL",
            'dateCreate' => Schema::TYPE_TIMESTAMP . " NOT NULL DEFAULT '0000-00-00 00:00:00'",
            'dateUpdate' => Schema::TYPE_TIMESTAMP . " NOT NULL DEFAULT '0000-00-00 00:00:00'",
            'ip' => Schema::TYPE_BIGINT . "(20) NOT NULL DEFAULT 0",
            'tmp' => "tinyint(1) NOT NULL DEFAULT 0",
            'position' => Schema::TYPE_INTEGER . " NOT NULL DEFAULT 0",
        ], $this->tableOptions);
        
        $this->createIndex('owner', '{{%file}}', 'ownerId,ownerType');
    }

    public function safeDown()
    {
        $this->dropTable('{{%file}}');
    }
}
