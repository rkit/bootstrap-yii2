<?php

namespace app\models\forms;

use Yii;
use yii\base\InvalidParamException;
use app\models\User;

class ConfirmEmailForm extends \yii\base\Model
{
    /**
     * @var \app\models\User
     */
    private $user;
    
    /**
     * Creates a form model given a token.
     *
     * @param string $token
     * @param array $config Name-value pairs that will be used to initialize the object properties.
     * @throws \yii\base\InvalidParamException If token is empty or not valid.
     */
    public function __construct($token, $config = [])
    {
        if (empty($token) || !is_string($token)) {
            throw new InvalidParamException(Yii::t('app', 'Invalid link'));
        }
        
        $this->user = User::findByEmailConfirmToken($token);
        
        if (!$this->user) {
            throw new InvalidParamException(Yii::t('app', 'Invalid link'));
        }
        
        parent::__construct($config);
    }

    /**
     * Confirm email.
     *
     * @return boolean if email was confirm.
     */
    public function confirmEmail()
    {
        $user = $this->user;
        $user->setConfirmed();
        return $user->save(false);
    }
}
