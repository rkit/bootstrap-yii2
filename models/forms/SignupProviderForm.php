<?php

namespace app\models\forms;

use Yii;
use yii\base\DynamicModel;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;
use app\models\User;
use app\models\UserProfile;
use app\services\Tokenizer;

class SignupProviderForm extends \yii\base\Model
{
    /**
     * @var string
     */
    public $email;
    /**
     * @var \app\models\User
     */
    private $user = null;

    /**
     * @param User $user
     */
    public function __construct($user, $email)
    {
        $this->user = $user;
        $this->email = $email;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'string', 'max' => 255],
            ['email', 'email'],
            ['email', 'unique',
                'targetClass' => '\app\models\User',
                'message' => Yii::t('app.msg', 'This email address has already been taken')
            ],
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
     * Create manually UploadedFile instance by file path
     *
     * @param string $path file path
     * @return UploadedFile
     */
    private function makeUploadedFile(string $path): UploadedFile
    {
        $tmpFile = tempnam(sys_get_temp_dir(), 'app');
        file_put_contents($tmpFile, file_get_contents($path));

        $uploadedFile = new UploadedFile();
        $uploadedFile->name = strtok(pathinfo($path, PATHINFO_BASENAME), '?');
        $uploadedFile->tempName = $tmpFile;
        $uploadedFile->type = FileHelper::getMimeType($tmpFile);
        $uploadedFile->size = filesize($tmpFile);
        $uploadedFile->error = 0;

        return $uploadedFile;
    }

    /**
     * Save photo
     *
     * @param \app\models\UserProfile $profile
     * @param string $photo
     * @return void
     */
    private function savePhoto(UserProfile $profile, string $photo): void
    {
        $file = $this->makeUploadedFile($photo);
        $model = new DynamicModel(compact('file'));
        $model->addRule('file', 'image', $profile->fileRules('photo', true))->validate();
        if (!$model->hasErrors()) {
            $profile->createFile('photo', $file->tempName, $model->file->name);
        } else {
            $profile->photo = '';
        }
    }

    /**
     * Save user
     *
     * @return bool
     */
    public function saveUser(): bool
    {
        $this->user->email = $this->email;

        $profile = $this->user->profile;
        if ($profile->isNewRecord && !empty($profile->photo)) {
            $this->savePhoto($profile, $profile->photo);
        }

        if ($this->user->save()) {
            return true;
        }

        $this->addErrors($this->user->getErrors());
        return false;
    }

    /**
     * Login
     *
     * @return bool
     */
    public function login(): bool
    {
        $this->user->updateDateLogin();
        return Yii::$app->user->login($this->user, 3600 * 24 * 30);
    }

    /**
     * Signs user up
     *
     * @return bool
     */
    public function signup(): bool
    {
        if ($this->validate()) {
            return $this->saveUser();
        } // @codeCoverageIgnore

        return false;
    }

    /**
     * Sends an email with a link, for confirm the email
     *
     * @return boolean
     */
    public function sendEmail(): bool
    {
        $tokenizer = new Tokenizer();
        if (!$tokenizer->validate($this->user->email_confirm_token)) {
            $this->user->setEmailConfirmToken($tokenizer->generate());
            $this->user->updateAttributes([
                'email_confirm_token' => $this->user->email_confirm_token,
                'date_confirm' => $this->user->date_confirm,
            ]);
        }

        return Yii::$app->notify->sendMessage(
            $this->email,
            Yii::t('app', 'Activate Your Account'),
            'emailConfirmToken',
            ['user' => $this->user]
        );
    }
}
