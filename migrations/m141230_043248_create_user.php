<?php

use yii\db\Schema;
use app\migrations\Migration;

class m141230_043248_create_user extends Migration
{
    public function up()
    {
        //
        // User
        //
        $this->createTable('{{%user}}', [
            'id' => Schema::TYPE_PK,
            'username' => Schema::TYPE_STRING . "(40) DEFAULT NULL",
            'email' => Schema::TYPE_STRING . " DEFAULT NULL",
            'password' => Schema::TYPE_STRING,
            'password_reset_token' => Schema::TYPE_STRING,
            'email_confirm_token' => Schema::TYPE_STRING,
            'auth_key' => Schema::TYPE_STRING . '(32) NOT NULL',
            'date_confirm' => Schema::TYPE_TIMESTAMP . " NULL DEFAULT NULL",
            'date_create' => Schema::TYPE_TIMESTAMP . " NULL DEFAULT NULL",
            'date_update' => Schema::TYPE_TIMESTAMP . " NULL DEFAULT NULL",
            'date_login' => Schema::TYPE_TIMESTAMP . " NULL DEFAULT NULL",
            'ip' => Schema::TYPE_BIGINT . "(20) NOT NULL DEFAULT 0",
            'role' => Schema::TYPE_STRING . "(64) NOT NULL DEFAULT ''",
            'status' => "tinyint(1) NOT NULL DEFAULT 0",
        ], $this->tableOptions);

        $this->createIndex('email', '{{%user}}', 'email', true);
        $this->createIndex('username', '{{%user}}', 'username', true);
        $this->createIndex('role', '{{%user}}', 'role');
        $this->createIndex('status', '{{%user}}', 'status');

        //
        // Profile
        //
        $this->createTable('{{%user_profile}}', [
            'user_id' => Schema::TYPE_PK,
            'full_name' => Schema::TYPE_STRING . "(40) NOT NULL DEFAULT ''",
            'photo' => Schema::TYPE_STRING . " NOT NULL DEFAULT ''",
            'birth_day' => Schema::TYPE_DATE . " NULL DEFAULT NULL",
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
            'id' => Schema::TYPE_PK,
            'user_id' => Schema::TYPE_INTEGER . " NOT NULL DEFAULT 0",
            'type' => "tinyint(1) NOT NULL DEFAULT 0",
            'profile_id' => Schema::TYPE_STRING . " NOT NULL DEFAULT ''",
            'profile_url' => Schema::TYPE_STRING . " NOT NULL DEFAULT ''",
            'access_token' => Schema::TYPE_STRING . " NOT NULL DEFAULT ''",
            'access_token_secret' => Schema::TYPE_STRING . " NOT NULL DEFAULT ''",
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
