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
     * @var array $keys
     */
    private $keys = [];

    public function init()
    {
        parent::init();

        $this->load();
    }

    public function __set($key, $value)
    {
        return $this->set($key, $value);
    }

    public function __get($key)
    {
        return $this->get($key);
    }

    /**
     * Get value by key
     *
     * @param string $key
     * @return mixed
     */
    public function get($key)
    {
        return ArrayHelper::getValue($this->keys, [$key, 'value'], null);
    }

    /**
     * Set value
     *
     * @param string $key
     * @param mixed $value
     */
    public function set($key, $value)
    {
        if (ArrayHelper::getValue($this->keys, $key, null)) {
            $this->update($key, $value);
        } else {
            $this->add($key, $value);
        }

        $this->keys[$key]['value'] = $value;
        Yii::$app->cache->set($this->cacheName, serialize($this->keys));
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

    /**
     * Load all settings
     *
     * @param bool $reload Get from storage
     * @return array
     */
    public function load($reload = false)
    {
        $keys = Yii::$app->cache->get($this->cacheName);

        if (!$keys || $reload) {
            $settings = (new Query())->select('*')->from($this->tableName)->all();
            $keys = ArrayHelper::index($settings, 'key');
            $keys = serialize($keys);

            Yii::$app->cache->set($this->cacheName, $keys);
        }

        $this->keys = unserialize($keys);

        return $this->keys;
    }
}
