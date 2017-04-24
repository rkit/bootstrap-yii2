<?php

class m161026_103038_create_user_profiles_files extends app\migrations\Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user_profiles_files}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull()->defaultValue(0),
            'file_id' => $this->integer()->notNull()->defaultValue(0),
        ], $tableOptions);

        $this->createIndex('link', '{{%user_profiles_files}}', 'user_id, file_id');
    }

    public function safeDown()
    {
        $this->dropTable('{{%user_profiles_files}}');
    }
}
