<?php

use yii\db\Schema;
use app\migrations\Migration;

class m141230_081319_create_news extends Migration
{
    public function safeUp()
    { 
        //
        // News
        //
        $this->createTable('{{%news}}', [
            'id' => Schema::TYPE_PK,
            'type_id' => Schema::TYPE_INTEGER . " NOT NULL DEFAULT 0",
            'title' => Schema::TYPE_STRING . " NOT NULL DEFAULT ''",
            'text' => "longtext",
            'preview' => Schema::TYPE_STRING . " NOT NULL DEFAULT ''",
            'date_create' => Schema::TYPE_TIMESTAMP . " NOT NULL DEFAULT '0000-00-00 00:00:00'",
            'date_update' => Schema::TYPE_TIMESTAMP . " NOT NULL DEFAULT '0000-00-00 00:00:00'",
            'date_pub' => Schema::TYPE_TIMESTAMP . " NOT NULL DEFAULT '0000-00-00 00:00:00'",
            'reference' => Schema::TYPE_STRING . " NOT NULL DEFAULT ''",
            'status' => "tinyint(1) NOT NULL DEFAULT 0",
        ], $this->tableOptions);
        
        $this->createIndex('title', '{{%news}}', 'title');
        $this->createIndex('type_id', '{{%news}}', 'type_id');
        $this->createIndex('status', '{{%news}}', 'status');
        
        //
        // Types
        //
        $this->createTable('{{%news_type}}', [
            'id' => Schema::TYPE_PK,
            'title' => Schema::TYPE_STRING . " NOT NULL",
        ], $this->tableOptions);
        
        //
        // Tags
        //
        $this->createTable('{{%news_tag_assn}}', [
            'news_id' => Schema::TYPE_INTEGER . " NOT NULL",
            'tag_id' => Schema::TYPE_INTEGER . " NOT NULL",
        ], $this->tableOptions);
        
        $this->addPrimaryKey('key', '{{%news_tag_assn}}', 'news_id, tag_id');
        
        $this->addForeignKey(
            'fk_news_tag', '{{%news_tag_assn}}', 'news_id', '{{%news}}', 'id', 'CASCADE', 'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%news_tag_assn}}');
        $this->dropTable('{{%news_type}}');
        $this->dropTable('{{%news}}');
    }
}
