<?php

use yii\db\Schema;

class m161026_103038_create_user_profiles_files extends \yii\db\Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user_profiles_files}}', [
            'id' => Schema::TYPE_PK,
            'user_id' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0',
            'file_id' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0',
        ], $tableOptions);

        $this->createIndex('link', '{{%user_profiles_files}}', 'user_id, file_id');
    }

    public function safeDown()
    {
        $this->dropTable('{{%user_profiles_files}}');
    }
}
