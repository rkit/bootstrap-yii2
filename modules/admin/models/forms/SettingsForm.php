<?php

namespace app\modules\admin\models\forms;

use Yii;

class SettingsForm extends \yii\base\Model
{
    /**
     * @var string
     */
    public $emailMain;
    /**
     * @var string
     */
    public $emailRequest;
    /**
     * @var string
     */
    public $emailName;
    /**
     * @var string
     */
    public $emailPrefix;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['emailRequest', 'filter', 'filter' => function ($value) {
                if (!empty($value)) {
                    $emails = explode(',', $value);
                    foreach ($emails as $email) {
                        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                            $this->addError('emailRequest', $email . ' — неверный электронный адрес');
                        }
                    }
                }
                return $value;
            }],
            ['emailRequest', 'trim'],

            [['emailMain', 'emailName', 'emailPrefix'], 'trim'],

            ['emailMain', 'email'],
            ['emailName', 'string', 'max' => 255],
            ['emailPrefix', 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'emailMain' => Yii::t('app', 'Primary email'),
            'emailRequest' => Yii::t('app', 'Email for requests'),
            'emailName' => Yii::t('app', 'Sender name'),
            'emailPrefix' => Yii::t('app', 'Prefix'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeHints()
    {
        return [
            'emailMain' => Yii::t('app', 'All notifications for users go to this address'),
            'emailPrefix' => Yii::t('app', 'Subject in the email: "Prefix: Subject"'),
            'emailRequest' => Yii::t('app', 'All requests from users go to this address.<br>
You can specify multiple addresses separated by a comma'),
        ];
    }
}
