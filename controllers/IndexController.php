<?php

namespace app\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\components\BaseController;
use app\helpers\Http;
use app\models\User;
use app\models\UserProvider;
use app\models\forms\LoginForm;
use app\models\forms\SignupForm;
use app\models\forms\SignupProviderForm;
use app\models\forms\PasswordResetRequestForm;
use app\models\forms\ResetPasswordForm;
use app\models\forms\ConfirmEmailForm;

class IndexController extends BaseController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup', 'auth'],
                'rules' => [
                    [
                        'actions' => ['signup', 'auth'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout', 'confirm-request'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
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
            'auth' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'successCallback'],
                'successUrl' => 'signup-provider'
            ],
        ];
    }

    public function successCallback($provider)
    {
        Yii::$app->session['provider'] = null;
        Yii::$app->session['blocked'] = false;

        $type = UserProvider::getTypeByName($provider->id);
        $profile = $provider->getUserAttributes();
        $token = $provider->getAccessToken()->getParams();
        $data = [
            'type' => $type,
            'profile' => $profile,
            'token' => $token
        ];

        if ($user = User::findByProvider($type, $profile['id'])) {
            if ($user->isActive()) {
                $user->updateProvider(UserProvider::parseProvider($type, $data));
                $user->authorize(true);
            } else {
                Yii::$app->session['blocked'] = true;
                Yii::$app->session['message'] = $user->getStatusDescription();
            }
        } else {
            Yii::$app->session['provider'] = $data;
        }
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
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash(
                    'success',
                    Yii::t('app.messages', 'Please activate your account') . '. ' .
                    Yii::t('app.messages', 'A letter for activation was sent to {email}', ['email' => $model->email])
                );
            } else {
                Yii::$app->session->setFlash(
                    'error',
                    Yii::t('app.messages', 'An error occurred while sending a message to activate account')
                );
            }
            return $this->goHome();
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    public function actionSignupProvider()
    {
        if (Yii::$app->session['blocked']) {
            Yii::$app->session->setFlash('error', Yii::$app->session['message']);
            return $this->goHome();
        }

        if (!Yii::$app->user->isGuest || Yii::$app->session['provider'] === null) {
            return $this->goHome();
        }

        $model = new SignupProviderForm(Yii::$app->session['provider']);

        if ($model->isVerified() && $model->signup(false)) {
            Yii::$app->session['provider'] = null;
            return $this->goHome();
        }

        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            Yii::$app->session['provider'] = null;
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash(
                    'success',
                    Yii::t('app.messages', 'Please activate your account') . '. ' .
                    Yii::t('app.messages', 'A letter for activation was sent to {email}', ['email' => $model->email])
                );
            } else {
                Yii::$app->session->setFlash(
                    'error',
                    Yii::t('app.messages', 'An error occurred while sending a message to activate account')
                );
            }
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
            Http::exception(403);
        } // @codeCoverageIgnore

        $model = new ConfirmEmailForm();

        if ($model->sendEmail($user)) {
            Yii::$app->session->setFlash(
                'success',
                Yii::t('app.messages', 'A letter for activation was sent to {email}', [
                    'email' => $user->email
                ])
            );
        } else {
            Yii::$app->session->setFlash(
                'error',
                Yii::t('app.messages', 'An error occurred while sending a message to activate account')
            );
        }
        return $this->goHome();
    }

    public function actionConfirmEmail($token)
    {
        $model = new ConfirmEmailForm();

        if (!$model->validateToken($token)) {
            Yii::$app->session->setFlash(
                'error',
                Yii::t('app.messages', 'Invalid link for activate account')
            );
            return $this->goHome();
        }

        if ($model->confirmEmail()) {
            Yii::$app->session->setFlash(
                'success',
                Yii::t('app.messages', 'Your account is successfully activated')
            );
        } else {
            Yii::$app->session->setFlash(
                'error',
                Yii::t('app.messages', 'An error occurred while activating account')
            );
        }
        return $this->goHome();
    }

    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash(
                    'success',
                    Yii::t('app.messages', 'We\'ve sent you an email with instructions to reset your password')
                );
            } else {
                Yii::$app->session->setFlash(
                    'error',
                    Yii::t('app.messages', 'An error occurred while sending a message to reset your password')
                );
            }
            return $this->goHome();
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    public function actionResetPassword($token)
    {
        $model = new ResetPasswordForm();

        if (!$model->validateToken($token)) {
            Yii::$app->session->setFlash(
                'error',
                Yii::t('app.messages', 'Invalid link for reset password')
            );
            return $this->goHome();
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash(
                'success',
                Yii::t('app', 'New password was saved')
            );
            return $this->goHome();
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
            Http::exception(404);
        } // @codeCoverageIgnore

        $this->layout = 'maintenance';
        return $this->render('maintenance');
    }
}
