<?php

use yii\db\Schema;
use yii\db\Migration;

class m141230_075222_create_geo extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        
        //
        // Country
        //
        $this->createTable('{{%country}}', [
            'countryId' => Schema::TYPE_PK,
            'title' => Schema::TYPE_STRING . "(60) NOT NULL",
        ], $tableOptions);
        
        //
        // Region
        //
        $this->createTable('{{%region}}', [
            'regionId' => Schema::TYPE_PK,
            'countryId' => Schema::TYPE_INTEGER . " NOT NULL DEFAULT 0",
            'title' => Schema::TYPE_STRING . "(150) NOT NULL",
        ], $tableOptions);
        
        $this->createIndex('countryId', '{{%region}}', 'countryId');
        
        $this->addForeignKey(
            'fk_region_country', '{{%region}}', 'countryId', '{{%country}}', 'countryId', 'CASCADE', 'CASCADE'
        );
        
        //
        // City
        //
        $this->createTable('{{%city}}', [
            'cityId' => Schema::TYPE_PK,
            'countryId' => Schema::TYPE_INTEGER . " NOT NULL DEFAULT 0",
            'important' => "tinyint(1) NOT NULL DEFAULT 0",
            'regionId' => Schema::TYPE_INTEGER . " NOT NULL DEFAULT 0",
            'title' => Schema::TYPE_STRING . "(150) NOT NULL",
            'area' => Schema::TYPE_STRING . "(150) NOT NULL",
        ], $tableOptions);
        
        $this->createIndex('countryId', '{{%city}}', 'countryId');
        $this->createIndex('title', '{{%city}}', 'title');
        $this->createIndex('country_important', '{{%city}}', 'countryId,important');
        
        $this->addForeignKey(
            'fk_city_country', '{{%city}}', 'countryId', '{{%country}}', 'countryId', 'CASCADE', 'CASCADE'
        );
        
        $this->addForeignKey(
            'fk_city_region', '{{%city}}', 'regionId', '{{%region}}', 'regionId', 'CASCADE', 'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%city}}');
        $this->dropTable('{{%region}}');
        $this->dropTable('{{%country}}');
    }
}
