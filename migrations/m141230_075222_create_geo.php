<?php // @codingStandardsIgnoreFile

use yii\db\Schema;
use app\migrations\Migration;

class m141230_075222_create_geo extends Migration
{
    public function safeUp()
    {
        //
        // Country
        //
        $this->createTable('{{%country}}', [
            'country_id' => Schema::TYPE_PK,
            'title' => Schema::TYPE_STRING . "(60) NOT NULL",
        ], $this->tableOptions);

        //
        // Region
        //
        $this->createTable('{{%region}}', [
            'region_id' => Schema::TYPE_PK,
            'country_id' => Schema::TYPE_INTEGER . " NOT NULL DEFAULT 0",
            'title' => Schema::TYPE_STRING . "(150) NOT NULL",
        ], $this->tableOptions);

        $this->createIndex('country_id', '{{%region}}', 'country_id');

        $this->addForeignKey(
            'fk_region_country',
            '{{%region}}',
            'country_id',
            '{{%country}}',
            'country_id',
            'CASCADE',
            'CASCADE'
        );

        //
        // City
        //
        $this->createTable('{{%city}}', [
            'city_id' => Schema::TYPE_PK,
            'country_id' => Schema::TYPE_INTEGER . " NOT NULL DEFAULT 0",
            'important' => "tinyint(1) NOT NULL DEFAULT 0",
            'region_id' => Schema::TYPE_INTEGER . " DEFAULT NULL",
            'title' => Schema::TYPE_STRING . "(150) NOT NULL",
            'area' => Schema::TYPE_STRING . "(150) NOT NULL DEFAULT ''",
        ], $this->tableOptions);

        $this->createIndex('country_id', '{{%city}}', 'country_id');
        $this->createIndex('title', '{{%city}}', 'title');
        $this->createIndex('country_important', '{{%city}}', 'country_id, important');

        $this->addForeignKey(
            'fk_city_country',
            '{{%city}}',
            'country_id',
            '{{%country}}',
            'country_id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_city_region',
            '{{%city}}',
            'region_id',
            '{{%region}}',
            'region_id',
            'CASCADE',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%city}}');
        $this->dropTable('{{%region}}');
        $this->dropTable('{{%country}}');
    }
}
