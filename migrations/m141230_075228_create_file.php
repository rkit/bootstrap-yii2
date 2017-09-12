<?php

use app\migrations\Migration;

class m141230_075228_create_file extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%file}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull()->defaultValue(0),
            'title' => $this->string()->notNull()->defaultValue(''),
            'name' => $this->string()->notNull()->defaultValue(''),
            'date_create' => $this->timestamp()->null(),
            'date_update' => $this->timestamp()->null(),
            'ip' => $this->bigInteger(20)->notNull()->defaultValue(0),
        ], $this->tableOptions);
    }

    public function safeDown()
    {
        $this->dropTable('{{%file}}');
    }
}
