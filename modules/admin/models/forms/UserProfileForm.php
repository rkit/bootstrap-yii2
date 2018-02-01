<?php

namespace app\modules\admin\models\forms;

use Yii;
use yii\base\Exception;
use app\models\entity\UserProfile;

class UserProfileForm extends \yii\base\Model
{
    /**
     * @var int
     */
    public $user_id;
    /**
     * @var string
     */
    public $full_name;
    /**
     * @var string
     */
    public $photo;
    /**
     * @var \app\models\entity\UserProfile
     */
    private $model;

    /**
     * Creates a form model given a user profile
     *
     * @param UserProfile $model
     * @param array $config name-value pairs that will be used to initialize the object properties
     */
    public function __construct(UserProfile $model, $config = [])
    {
        $this->model = $model;

        $this->user_id = $model->user_id;
        $this->full_name = $model->full_name;
        $this->photo = $model->photo;

        parent::__construct($config);
    }


   /**
    * @return array The validation rules
    */
    public function rules()
    {
        return [
            [['photo'], 'safe'],

            ['full_name', 'string', 'max' => 40],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => Yii::t('app', 'User'),
            'full_name' => Yii::t('app', 'Name'),
            'photo' => Yii::t('app', 'Photo'),
        ];
    }

    /**
     * Get model
     *
     * @return UserProfile
     */
    public function model(): UserProfile
    {
        return $this->model;
    }

    /**
     * Save profile
     *
     * @throws Exception
     * @return UserProfile
     */
    public function save(): UserProfile
    {
        $model = $this->model();

        $model->user_id = $this->user_id;
        $model->full_name = $this->full_name;
        $model->photo = $this->photo;

        if (!$model->save()) {
            throw new Exception(Yii::t('app.msg', 'An error occurred while saving profile'));
        }

        return $model;
    }
}
