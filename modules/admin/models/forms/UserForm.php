<?php

namespace app\modules\admin\models\forms;

use Yii;
use app\models\User;

class UserForm extends User
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge((new User())->rules(), [
            ['role', 'string'],

            ['username', 'required', 'when' => function ($model) {
                return empty($model->email);
            }, 'whenClient' => "function (attribute, value) {
                return !$('#userform-email').val().length;
            }", 'message' => Yii::t('app', 'You must fill in username or email')],

            ['passwordNew', 'string', 'min' => 6],
        ]);
    }
}
