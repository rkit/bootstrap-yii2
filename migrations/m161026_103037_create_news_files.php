<?php

class m161026_103037_create_news_files extends app\migrations\Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%news_files}}', [
            'id' => $this->primaryKey(),
            'news_id' => $this->integer()->notNull()->defaultValue(0),
            'file_id' => $this->integer()->notNull()->defaultValue(0),
            'type' => $this->integer()->notNull()->defaultValue(0),
            'position' => $this->integer()->notNull()->defaultValue(0),
        ], $tableOptions);

        $this->createIndex('link', '{{%news_files}}', 'news_id, file_id');
        $this->createIndex('type_news', '{{%news_files}}', 'type, news_id');
    }

    public function safeDown()
    {
        $this->dropTable('{{%news_files}}');
    }
}
