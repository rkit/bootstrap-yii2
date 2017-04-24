<?php

class m141230_081319_create_news extends app\migrations\Migration
{
    public function safeUp()
    {
        $this->createTable('{{%news}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull()->defaultValue(''),
            'text' => 'longtext',
            'preview' => $this->string()->notNull()->defaultValue(''),
            'date_create' => $this->timestamp()->null(),
            'date_update' => $this->timestamp()->null(),
            'date_pub' => $this->timestamp()->null(),
            'status' => 'tinyint NOT NULL DEFAULT 0',
        ], $this->tableOptions);

        $this->createIndex('title', '{{%news}}', 'title');
        $this->createIndex('status', '{{%news}}', 'status');
    }

    public function safeDown()
    {
        $this->dropTable('{{%news}}');
    }
}
