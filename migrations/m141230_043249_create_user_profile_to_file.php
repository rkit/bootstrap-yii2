<?php

use app\migrations\Migration;

class m141230_043249_create_user_profile_to_file extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%user_profile_to_file}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull()->defaultValue(0),
            'file_id' => $this->integer()->notNull()->defaultValue(0),
        ], $this->tableOptions);

        $this->createIndex('link', '{{%user_profile_to_file}}', 'user_id, file_id');

        $this->addForeignKey(
            'fk_user_profile_to_file__user_id__user_id',
            '{{%user_profile_to_file}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%user_profile_to_file}}');
    }
}
