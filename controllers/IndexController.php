<?php

namespace app\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\components\BaseController;
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
                        'actions' => ['logout'],
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

        $type = UserProvider::getTypeByName($provider->id);
        $profile = $provider->getUserAttributes();
        $token = $provider->getAccessToken()->getParams();
        $data = [
            'type' => $type,
            'profile' => $profile,
            'token' => $token
        ];

        if ($user = User::findByProvider($type, $profile['id'])) {
            if (!$user->isActive()) {
                return $this->alert('error', $user->getStatusDescription());
            }
            $user->updateProvider(UserProvider::parseProvider($type, $data));
            $user->authorize(true);
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
                return $this->alert(
                    'success',
                    Yii::t('app.messages', 'Please activate your account') . '. ' .
                    Yii::t('app.messages', 'A letter for activation was sent to {email}', ['email' => $model->email])
                );
            } else {
                return $this->alert(
                    'error',
                    Yii::t('app.messages', 'An error occurred while sending a message to activate account')
                );
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    public function actionSignupProvider()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new SignupProviderForm(Yii::$app->session['provider']);

        if (!$model->getUser()->isNewRecord && !$model->getUser()->isActive()) {
            return $this->alert('error', $model->getUser()->getStatusDescription());
        }

        if ($model->isVerified()) {
            if ($model->signup(false)) {
                Yii::$app->session['provider'] = null;
                return $this->goHome();
            }
        }

        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            Yii::$app->session['provider'] = null;
            if ($model->sendEmail()) {
                return $this->alert(
                    'success',
                    Yii::t('app.messages', 'Please activate your account') . '. ' .
                    Yii::t('app.messages', 'A letter for activation was sent to {email}', ['email' => $model->email])
                );
            } else {
                return $this->alert(
                    'error',
                    Yii::t('app.messages', 'An error occurred while sending a message to activate account')
                );
            }
        }

        return $this->render('signupProvider', [
            'model' => $model
        ]);
    }

    public function actionConfirmEmail($token)
    {
        $model = new ConfirmEmailForm();

        if (!$model->validateToken($token)) {
            return $this->alert('error', Yii::t('app.messages', 'Invalid link for activate account'));
        }

        if ($model->confirmEmail()) {
            return $this->alert(
                'success',
                Yii::t('app.messages', 'Your account is successfully activated')
            );
        } else {
            return $this->alert(
                'error',
                Yii::t('app.messages', 'An error occurred while activating account')
            );
        }
    }

    public function actionConfirmAgain()
    {
        if (Yii::$app->user->identity->isConfirmed()) {
            Http::exception(403);
        }

        $model = new SignupForm();
        $model->user = Yii::$app->user->identity;
        $model->email = Yii::$app->user->identity->email;

        if ($model->sendEmail()) {
            return $this->alert(
                'success',
                Yii::t('app.messages', 'A letter for activation was sent to {email}', [
                    'email' => Yii::$app->user->identity->email
                ])
            );
        } else {
            return $this->alert(
                'error',
                Yii::t('app.messages', 'An error occurred while sending a message to activate account')
            );
        }
    }

    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                return $this->alert(
                    'success',
                    Yii::t('app.messages', 'We\'ve sent you an email with instructions to reset your password')
                );
            } else {
                return $this->alert(
                    'error',
                    Yii::t('app.messages', 'An error occurred while sending a message to reset your password')
                );
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    public function actionResetPassword($token)
    {
        $model = new ResetPasswordForm();

        if (!$model->validateToken($token)) {
            return $this->alert('error', Yii::t('app.messages', 'Invalid link for reset password'));
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            return $this->alert('success', Yii::t('app', 'New password was saved'));
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
        }

        $this->layout = 'maintenance';
        return $this->render('maintenance');
    }
}
