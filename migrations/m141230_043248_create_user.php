<?php

use yii\db\Schema;
use yii\db\Migration;

class m141230_043248_create_user extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
                
        //
        // User
        // 
        $this->createTable('{{%user}}', [
            'id' => Schema::TYPE_PK,
            'username' => Schema::TYPE_STRING . "(40) DEFAULT NULL",
            'email' => Schema::TYPE_STRING . " DEFAULT NULL",
            'password' => Schema::TYPE_STRING . " NOT NULL",
            'passwordResetToken' => Schema::TYPE_STRING . " NOT NULL",
            'emailConfirmToken' => Schema::TYPE_STRING . " NOT NULL",
            'authKey' => Schema::TYPE_STRING . '(32) NOT NULL',
            'dateConfirm' => Schema::TYPE_TIMESTAMP . " NOT NULL DEFAULT '0000-00-00 00:00:00'",
            'dateCreate' => Schema::TYPE_TIMESTAMP . " NOT NULL DEFAULT '0000-00-00 00:00:00'",
            'dateUpdate' => Schema::TYPE_TIMESTAMP . " NOT NULL DEFAULT '0000-00-00 00:00:00'",
            'dateLogin' => Schema::TYPE_TIMESTAMP . " NOT NULL DEFAULT '0000-00-00 00:00:00'",
            'ip' => Schema::TYPE_BIGINT . "(20) NOT NULL DEFAULT 0",
            'role' => Schema::TYPE_STRING . "(64) NOT NULL",
            'status' => "tinyint(1) NOT NULL DEFAULT 0",
        ], $tableOptions);

        $this->createIndex('email', '{{%user}}', 'email', true);
        $this->createIndex('username', '{{%user}}', 'username', true);
        $this->createIndex('role', '{{%user}}', 'role');
        $this->createIndex('status', '{{%user}}', 'status');
        
        //
        // Profile
        //
        $this->createTable('{{%userProfile}}', [
            'userId' => Schema::TYPE_PK,
            'fullName' => Schema::TYPE_STRING . "(40) NOT NULL",
            'photo' => Schema::TYPE_STRING . " NOT NULL",
            'birthDay' => Schema::TYPE_DATE . " NOT NULL",
        ], $tableOptions);
        
        $this->addForeignKey(
            'fk_userProfile_user', '{{%userProfile}}', 'userId', '{{%user}}', 'id', 'CASCADE', 'CASCADE'
        );
        
        //
        // Provider
        //
        $this->createTable('{{%userToProvider}}', [
            'id' => Schema::TYPE_PK,
            'userId' => Schema::TYPE_INTEGER . " NOT NULL DEFAULT 0",
            'provider' => "tinyint(1) NOT NULL DEFAULT 0",
            'profileId' => Schema::TYPE_STRING . " NOT NULL",
            'profileUrl' => Schema::TYPE_STRING . " NOT NULL",
            'accessToken' => Schema::TYPE_STRING . " NOT NULL",
            'accessTokenSecret' => Schema::TYPE_STRING . " NOT NULL",
        ], $tableOptions);
        
        $this->createIndex('userId', '{{%userToProvider}}', 'userId');
        $this->createIndex('provider_profileId', '{{%userToProvider}}', 'provider,profileId');
        $this->createIndex('userId_provider', '{{%userToProvider}}', 'userId,provider');
        
        $this->addForeignKey(
            'fk_userToProvider_user', '{{%userToProvider}}', 'userId', '{{%user}}', 'id', 'CASCADE', 'CASCADE'
        );
    }

    public function down()
    {
        $this->dropTable('{{%userToProvider}}');
        $this->dropTable('{{%userProfile}}');
        $this->dropTable('{{%user}}');
    }
}
