<?php

use yii\db\Schema;
use app\migrations\Migration;

class m141230_043535_create_settings extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%settings}}', [
            'key'   => Schema::TYPE_STRING . ' NOT NULL',
            'value' => Schema::TYPE_TEXT . ' NOT NULL',
        ], $this->tableOptions);
        
        $this->addPrimaryKey('key', '{{%settings}}', 'key');
    }

    public function safeDown()
    {
        $this->dropTable('{{%settings}}');
    }
}
