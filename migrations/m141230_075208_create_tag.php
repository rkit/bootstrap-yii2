<?php

use yii\db\Schema;
use yii\db\Migration;

class m141230_075208_create_tag extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        
        $this->createTable('{{%tag}}', [
            'id' => Schema::TYPE_PK,
            'userId' => Schema::TYPE_INTEGER . " NOT NULL",
            'title' => Schema::TYPE_STRING . " NOT NULL",
            'count' => Schema::TYPE_INTEGER . " NOT NULL DEFAULT 0",
        ], $tableOptions);
        
        $this->createIndex('title', '{{%tag}}', 'title', true);
    }

    public function safeDown()
    {
        $this->dropTable('{{%tag}}');
    }
}
