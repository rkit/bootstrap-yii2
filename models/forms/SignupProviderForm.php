<?php

namespace app\models\forms;

use Yii;
use yii\base\DynamicModel;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;
use yii\base\Exception;
use yii\base\UserException;
use app\models\entity\User;
use app\models\entity\UserProfile;
use app\services\Tokenizer;

class SignupProviderForm extends \yii\base\Model
{
    /**
     * @var string
     */
    public $email;
    /**
     * @var \app\models\entity\User
     */
    private $user = null;

    /**
     * @param User $user
     */
    public function __construct($user)
    {
        $this->user = $user;
        $this->email = $user->email;
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
                'targetClass' => '\app\models\entity\User',
                'message' => Yii::t('app.msg', 'This email address has already been taken')
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'email' => Yii::t('app', 'Email'),
        ];
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
     * @param \app\models\entity\UserProfile $profile
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
     * Login
     */
    public function login(): void
    {
        $this->user->updateDateLogin();
        Yii::$app->user->login($this->user, 3600 * 24 * 30);
    }

    /**
     * Signs user up
     *
     * @throws Exception
     * @return \app\models\entity\User
     */
    public function signup(): User
    {
        $this->user->email = $this->email;

        $profile = $this->user->profile;
        if ($profile->isNewRecord && !empty($profile->photo)) {
            $this->savePhoto($profile, $profile->photo);
        }

        $this->user->status = User::STATUS_ACTIVE;

        if (!$this->user->save()) {
            throw new Exception(Yii::t('app.msg', 'An error occurred while saving user'));
        }

        $this->login();

        return $this->user;
    }

    /**
     * Sends an email with a link, for confirm the email
     *
     * @throws UserException
     */
    public function sendEmail(): void
    {
        $tokenizer = new Tokenizer();
        if (!$tokenizer->validate($this->user->email_confirm_token)) {
            $this->user->setEmailConfirmToken($tokenizer->generate());
            $this->user->updateAttributes([
                'email_confirm_token' => $this->user->email_confirm_token,
                'date_confirm' => $this->user->date_confirm,
            ]);
        }

        $sent = Yii::$app->notify->sendMessage(
            $this->email,
            Yii::t('app', 'Activate Your Account'),
            'emailConfirmToken',
            ['user' => $this->user]
        );

        if (!$sent) {
            throw new UserException(Yii::t('app.msg', 'An error occurred while sending a message to activate account'));
        }
    }
}
