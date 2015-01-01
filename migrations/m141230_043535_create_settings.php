<?php

use yii\db\Schema;
use yii\db\Migration;

class m141230_043535_create_settings extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        
        $this->createTable('{{%settings}}', [
            'key'   => Schema::TYPE_STRING . ' NOT NULL',
            'value' => Schema::TYPE_TEXT . ' NOT NULL',
        ], $tableOptions);
        
        $this->addPrimaryKey('key', '{{%settings}}', 'key');
    }

    public function safeDown()
    {
        $this->dropTable('{{%settings}}');
    }
}
