<?php

namespace app\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use app\services\SocialAuth;
use app\services\ConfirmEmail;
use app\models\forms\LoginForm;
use app\models\forms\SignupForm;
use app\models\forms\SignupProviderForm;
use app\models\forms\PasswordResetRequestForm;
use app\models\forms\ResetPasswordForm;

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
                'class' => AccessControl::class,
                'only' => [
                    'auth-social',
                    'logout',
                    'signup',
                    'signup-provider',
                    'confirm-request',
                    'request-password-reset',
                ],
                'rules' => [
                    [
                        'actions' => [
                            'auth-social',
                            'signup',
                            'signup-provider',
                            'request-password-reset',
                        ],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => [
                            'logout',
                            'confirm-request'
                        ],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'auth-social' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'successCallback'],
                'successUrl' => 'signup-provider'
            ],
        ];
    }

    public function successCallback($client)
    {
        Yii::$app->session['authClient'] = $client;
    }

    public function goHome()
    {
        Yii::$app->session['authClient'] = null;
        return Yii::$app->getResponse()->redirect(Yii::$app->getHomeUrl());
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionSignup()
    {
        $model = new SignupForm();
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

            return $this->goHome();
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    public function actionSignupProvider()
    {
        try {
            $socialAuth = \Yii::$container->get(SocialAuth::class, [Yii::$app->session['authClient']]);
        } catch (\Throwable $e) {
            Yii::error($e);
            return $this->goHome();
        }

        $user = $socialAuth->prepareUser();
        if ($user === null) {
            return $this->goHome();
        }

        $model = new SignupProviderForm($user);

        if (!$user->isNewRecord && $user->isActive() === false) {
            Yii::$app->session->setFlash('error', $user->getStatusDescription());
            return $this->goHome();
        }

        if (!$user->isNewRecord && $user->isActive()) {
            $model->login();
            return $this->goHome();
        }

        if ($model->validate() || ($model->load(Yii::$app->request->post()) && $model->validate())) {
            $model->signup();
            $model->sendEmail();
            Yii::$app->session->setFlash(
                'success',
                Yii::t(
                    'app.msg',
                    'Please activate your account. A letter for activation was sent to {email}',
                    ['email' => $model->email]
                )
            );
            return $this->goHome();
        }

        return $this->render('signupProvider', [
            'model' => $model
        ]);
    }

    public function actionConfirmRequest()
    {
        $user = Yii::$app->user->identity;
        if ($user->isConfirmed()) {
            throw new ForbiddenHttpException(Yii::t('app.msg', 'Access Denied'));
        } // @codeCoverageIgnore

        $this->confirmEmail->sendEmail($user);

        Yii::$app->session->setFlash(
            'success',
            Yii::t('app.msg', 'A letter for activation was sent to {email}', ['email' => $user->email])
        );

        return $this->goHome();
    }

    public function actionConfirmEmail($token)
    {
        $this->confirmEmail->setConfirmed($token);

        Yii::$app->session->setFlash('success', Yii::t('app.msg', 'Your account is successfully activated'));
        return $this->goHome();
    }

    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->sendEmail();

            Yii::$app->session->setFlash(
                'success',
                Yii::t('app.msg', 'We\'ve sent you an email with instructions to reset your password')
            );

            return $this->goHome();
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    public function actionResetPassword($token)
    {
        $model = new ResetPasswordForm($token);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->resetPassword();
            Yii::$app->session->setFlash('success', Yii::t('app.msg', 'New password was saved'));
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }

    /** @see commands/MaintenanceController **/
    public function actionMaintenance()
    {
        if (!Yii::$app->catchAll) {
            throw new NotFoundHttpException(Yii::t('app.msg', 'Page not found'));
        } // @codeCoverageIgnore

        $this->layout = 'maintenance';
        return $this->render('maintenance');
    }
}
