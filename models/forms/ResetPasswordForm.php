<?php

namespace app\models\forms;

use Yii;
use yii\base\InvalidParamException;
use app\models\User;

class ResetPasswordForm extends \yii\base\Model
{
    public $password;
    /**
     * @var \app\models\User
     */
    private $user;
    
    /**
     * Creates a form model given a token.
     *
     * @param  string $token
     * @param  array $config Name-value pairs that will be used to initialize the object properties.
     * @throws \yii\base\InvalidParamException If token is empty or not valid.
     */
    public function __construct($token, $config = [])
    {
        if (empty($token) || !is_string($token)) {
            throw new InvalidParamException(Yii::t('app', 'Invalid link'));
        }
        
        $this->user = User::findByPasswordResetToken($token);
        
        if (!$this->user) {
            throw new InvalidParamException(Yii::t('app', 'Invalid link'));
        }
        
        parent::__construct($config);
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return (new User())->attributeLabels();
    }
    
    /**
     * Resets password.
     *
     * @return boolean If password was reset.
     */
    public function resetPassword()
    {
        $user = $this->user;
        $user->setPassword($this->password);
        $user->removePasswordResetToken();
        $user->authorize(true);
        
        return $user->save(false);
    }
}
