<?php

namespace app\modules\admin\models\forms;

use Yii;
use yii\base\Exception;
use app\models\UserProfile;

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
     * @var string
     */
    public $birth_day;
    /**
     * @var \app\models\UserProfile
     */
    private $model;

   /**
    * @return array the validation rules.
    */
    public function rules()
    {
        return [
            [
                ['birth_day', 'photo'], 'safe',
            ],

            ['birth_day', 'date', 'format' => 'php:Y-m-d'],

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
            'full_name' => Yii::t('app', 'Full Name'),
            'photo' => Yii::t('app', 'Photo'),
            'birth_day' => Yii::t('app', 'Birth Day'),
        ];
    }

    /**
     * Set model
     *
     * @param UserProfile $model
     */
    public function setModel(UserProfile $model): void
    {
        $this->model = $model;

        $this->user_id = $model->user_id;
        $this->full_name = $model->full_name;
        $this->photo = $model->photo;
        $this->birth_day = $model->birth_day;
    }

    /**
     * Get model
     *
     * @return UserProfile
     */
    public function model(): UserProfile
    {
        if ($this->model === null) {
            $this->model = new UserProfile();
        }

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
        $model->birth_day = $this->birth_day;

        if (!$model->save()) {
            throw new Exception(Yii::t('app.msg', 'An error occurred while saving profile'));
        }

        return $model;
    }
}
