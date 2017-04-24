<?php

class m141230_043248_create_user extends app\migrations\Migration
{
    public function up()
    {
        /**
         * User
         */
        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string(40)->null()->unique(),
            'email' => $this->string()->null()->unique(),
            'password' => $this->string()->notNull()->defaultValue(''),
            'password_reset_token' => $this->string()->null()->unique(),
            'email_confirm_token' => $this->string()->null()->unique(),
            'auth_key' => $this->string(32)->notNull()->defaultValue(''),
            'date_confirm' => $this->timestamp()->null(),
            'date_create' => $this->timestamp()->null(),
            'date_update' => $this->timestamp()->null(),
            'date_login' => $this->timestamp()->null(),
            'ip' => $this->bigInteger(20)->notNull()->defaultValue(0),
            'role' => $this->string(64)->notNull()->defaultValue(''),
            'status' => 'tinyint NOT NULL DEFAULT 0',
        ], $this->tableOptions);

        $this->createIndex('role', '{{%user}}', 'role');
        $this->createIndex('status', '{{%user}}', 'status');

        /**
         * Profile
         */
        $this->createTable('{{%user_profile}}', [
            'user_id' => $this->primaryKey(),
            'full_name' => $this->string(40)->notNull()->defaultValue(''),
            'photo' => $this->string()->notNull()->defaultValue(''),
            'birth_day' => $this->date()->null(),
        ], $this->tableOptions);

        $this->addForeignKey(
            'fk_user_profile',
            '{{%user_profile}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE',
            'CASCADE'
        );

        //
        // Provider
        //
        $this->createTable('{{%user_provider}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull()->defaultValue(0),
            'type' => 'tinyint NOT NULL DEFAULT 0',
            'profile_id' => $this->string()->notNull()->defaultValue(''),
            'profile_url' => $this->string()->notNull()->defaultValue(''),
            'access_token' => $this->string()->notNull()->defaultValue(''),
            'access_token_secret' => $this->string()->notNull()->defaultValue(''),
        ], $this->tableOptions);

        $this->createIndex('user_id', '{{%user_provider}}', 'user_id');
        $this->createIndex('provider_profile', '{{%user_provider}}', 'type, profile_id');
        $this->createIndex('user_provider', '{{%user_provider}}', 'user_id, type');

        $this->addForeignKey(
            'fk_user_provider',
            '{{%user_provider}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    public function down()
    {
        $this->dropTable('{{%user_provider}}');
        $this->dropTable('{{%user_profile}}');
        $this->dropTable('{{%user}}');
    }
}
