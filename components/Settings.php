<?php

namespace app\components;

use Yii;
use yii\helpers\ArrayHelper;
use yii\db\Query;

/**
 * Component for storage settings in db
 */
class Settings extends \yii\base\Component
{
    /**
     * @var string $tableName
     */
    public $tableName = 'settings';
    /**
     * @var string $cacheName
     */
    public $cacheName = 'settings';
    /**
     * @var array $data
     */
    private $data = [];

    public function __set($key, $value)
    {
        return $this->set($key, $value);
    }

    public function __get($key)
    {
        return $this->get($key);
    }

    public function init()
    {
        parent::init();

        $data = Yii::$app->cache->get($this->cacheName);

        if ($data) {
            $this->data = unserialize($data);
        } else {
            $data = (new Query())->select('*')->from($this->tableName)->all();
            $data = ArrayHelper::map($data, 'key', 'value');
            $this->updateData($data);
        }
    }

    /**
     * Get all data
     *
     * @return array
     */
    public function all()
    {
        return $this->data;
    }

    /**
     * Get value by key
     *
     * @param string $key
     * @return string
     */
    public function get($key)
    {
        return ArrayHelper::getValue($this->data, $key, null);
    }

    /**
     * Set value
     *
     * @param string $key
     * @param string $value
     */
    public function set($key, $value)
    {
        if (ArrayHelper::getValue($this->data, $key, null)) {
            $this->update($key, $value);
        } else {
            $this->add($key, $value);
        }

        $this->data[$key] = $value;
        $this->updateData($this->data);
    }

    /**
     * Add setting
     *
     * @param string $key
     * @param string $value
     */
    private function add($key, $value)
    {
        Yii::$app->db
            ->createCommand()
            ->insert($this->tableName, ['key' => $key, 'value' => $value])->execute();
    }

    /**
     * Update setting
     *
     * @param string $key
     * @param string $value
     */
    private function update($key, $value)
    {
        Yii::$app->db
            ->createCommand()
            ->update($this->tableName, ['value' => $value], '`key` = :key', [':key' => $key])->execute();
    }

    public function load($data)
    {
        $db = Yii::$app->db->createCommand();
        $db->truncateTable($this->tableName)->execute();
        $db->batchInsert($this->tableName, ['key', 'value'], $this->prepareInsertData($data))->execute();

        $this->updateData($data);
    }

    private function prepareInsertData($data)
    {
        $items = [];
        foreach ($data as $key => $value) {
            $items[] = ['key' => $key, 'value' => $value];
        }

        return $items;
    }

    private function updateData($data)
    {
        $this->data = $data;

        $cache = Yii::$app->cache;
        $cache->delete($this->cacheName);
        $cache->set($this->cacheName, serialize($this->data));
    }
}
