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
            ['emailMain', 'trim'],
            ['emailMain', 'email'],

            ['emailName', 'trim'],
            ['emailName', 'string', 'max' => 255],

            ['emailPrefix', 'trim'],
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
        ];
    }
}
