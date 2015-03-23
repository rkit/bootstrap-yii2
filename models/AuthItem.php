<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for authManager.itemTable
 *
 * @property string $name
 * @property integer $type
 * @property string $description
 * @property string $rule_name
 * @property string $data
 * @property integer $created_at
 * @property integer $updated_at
 */
class AuthItem extends \yii\db\ActiveRecord
{
    /**
     * @var array
     */
    public $roles;
    /**
     * @var array
     */
    public $permissions;
     
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return Yii::$app->authManager->itemTable;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'description'], 'required'],
            
            ['roles', 'safe'],
            
            ['permissions', 'safe'],
            
            ['name', 'unique'],
            ['name', 'string', 'max' => 64],
            ['name', 'match', 'pattern' => '/^[a-z]\w*$/i'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name'        => Yii::t('app', 'Name'),
            'description' => Yii::t('app', 'Description'),         
            'roles'       => Yii::t('app', 'Inherit role'),
            'permissions' => Yii::t('app', 'Permissions'),
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_DELETE => 'beforeDelete',
        ];
    }
    
    /**
     * Is SuperUser?
     *
     * @return bool
     */
    public function isSuperUser()
    {
        return $this->name === \app\models\User::ROLE_SUPERUSER;
    }
    
    /**
     * Deleting a file from the db and from the file system.
     *
     * @return bool
     */
    public function beforeDelete()
    {   
        return !$this->isSuperUser();
    }
}
