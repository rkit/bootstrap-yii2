<?php

namespace app\migrations;

class Migration extends \yii\db\Migration
{
    /**
     * @var string
     */
    protected $tableOptions = null;

    public function init()
    {
        parent::init();

        if ($this->db->driverName === 'mysql') {
            $this->tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
    }
}
