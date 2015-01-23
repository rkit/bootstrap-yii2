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
            'typeId' => Schema::TYPE_INTEGER . " NOT NULL DEFAULT 0",
            'title' => Schema::TYPE_STRING . " NOT NULL",
            'text' => "longtext NOT NULL",
            'preview' => Schema::TYPE_STRING . " NOT NULL",
            'dateCreate' => Schema::TYPE_TIMESTAMP . " NOT NULL DEFAULT '0000-00-00 00:00:00'",
            'dateUpdate' => Schema::TYPE_TIMESTAMP . " NOT NULL DEFAULT '0000-00-00 00:00:00'",
            'datePub' => Schema::TYPE_TIMESTAMP . " NOT NULL DEFAULT '0000-00-00 00:00:00'",
            'reference' => Schema::TYPE_STRING . " NOT NULL",
            'status' => "tinyint(1) NOT NULL DEFAULT 0",
        ], $this->tableOptions);
        
        $this->createIndex('title', '{{%news}}', 'title');
        $this->createIndex('typeId', '{{%news}}', 'typeId');
        $this->createIndex('status', '{{%news}}', 'status');
        
        //
        // NewsToTags
        //
        $this->createTable('{{%newsToTag}}', [
            'newsId' => Schema::TYPE_INTEGER . " NOT NULL DEFAULT 0",
            'tagId' => Schema::TYPE_INTEGER . " NOT NULL DEFAULT 0",
        ], $this->tableOptions);
        
        $this->addPrimaryKey('key', '{{%newsToTag}}', 'newsId,tagId');
        
        $this->addForeignKey(
            'fk_newsToTag_news', '{{%newsToTag}}', 'newsId', '{{%news}}', 'id', 'CASCADE', 'CASCADE'
        );
        
        //
        // NewsType
        //
        $this->createTable('{{%newsType}}', [
            'id' => Schema::TYPE_PK,
            'title' => Schema::TYPE_STRING . " NOT NULL",
        ], $this->tableOptions);
    }

    public function safeDown()
    {
        $this->dropTable('{{%newsToTag}}');
        $this->dropTable('{{%newsType}}');
        $this->dropTable('{{%news}}');
    }
}
