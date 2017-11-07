<?php

namespace app\modules\auth\controllers;

use Yii;
use yii\web\ForbiddenHttpException;
use app\modules\auth\services\ConfirmEmail;
use app\modules\auth\models\forms\{
    LoginForm,
    SignupForm,
    PasswordResetRequestForm,
    ResetPasswordForm
};

class IndexController extends \yii\web\Controller
{
    private $confirmEmail;

    public function __construct($id, $module, ConfirmEmail $confirmEmail, $config = [])
    {
        $this->confirmEmail = $confirmEmail;

        parent::__construct($id, $module, $config);
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => 'yii\filters\AccessControl',
                'rules' => [
                    [
                        'actions' => [
                            'login',
                            'signup',
                            'request-password-reset',
                        ],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => [
                            'confirm-request',
                        ],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->redirect(['/']);
        }

        $model = new LoginForm();

        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->redirect(['/']);
        }

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionSignup()
    {
        $model = Yii::$container->get(SignupForm::class);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->signup();

            Yii::$app->session->setFlash(
                'success',
                Yii::t(
                    'app.msg',
                    'Please activate your account. A letter for activation was sent to {email}',
                    ['email' => $model->email]
                )
            );
            return $this->redirect(['/']);
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    public function actionConfirmRequest()
    {
        $user = Yii::$app->user->identity;
        if ($user->isConfirmed()) {
            throw new ForbiddenHttpException(Yii::t('app.msg', 'Access Denied'));
        }

        $this->confirmEmail->sendEmail($user);

        Yii::$app->session->setFlash(
            'success',
            Yii::t('app.msg', 'A letter for activation was sent to {email}', ['email' => $user->email])
        );
        return $this->redirect(['/']);
    }

    public function actionConfirmEmail($token)
    {
        $this->confirmEmail->setConfirmed($token);

        Yii::$app->session->setFlash('success', Yii::t('app.msg', 'Your account is successfully activated'));
        return $this->redirect(['/']);
    }

    public function actionRequestPasswordReset()
    {
        $model = Yii::$container->get(PasswordResetRequestForm::class);

        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                $model->sendEmail();

                Yii::$app->session->setFlash(
                    'success',
                    Yii::t('app.msg', 'We\'ve sent you an email with instructions to reset your password')
                );
                return $this->redirect(['/']);
            }
        }

        return $this->render('requestPasswordReset', [
            'model' => $model,
        ]);
    }

    public function actionResetPassword($token)
    {
        $model = Yii::$container->get(ResetPasswordForm::class, [$token]);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->resetPassword();

            Yii::$app->session->setFlash('success', Yii::t('app.msg', 'New password was saved'));
            return $this->redirect(['/']);
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }
}
