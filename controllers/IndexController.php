<?php

namespace app\controllers;

use app\components\BaseController;
use app\models\User;
use app\models\forms\LoginForm;
use app\models\forms\SignupForm;
use app\models\forms\SignupProviderForm;
use app\models\forms\PasswordResetRequestForm;
use app\models\forms\ResetPasswordForm;
use app\models\forms\ConfirmEmailForm;
use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

class IndexController extends BaseController
{
    private $providers = [];
    
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
        
        $profile = $provider->getUserAttributes();
        $token   = $provider->getAccessToken()->getParams();
        
        if ($user = User::findByProvider(User::getProviders($provider->id), $profile['id'])) {
            if (!$user->isActive()) {
                return $this->alert('error', $user->getStatusDescription());
            }
            $user->authorize(true);
        } else {
            Yii::$app->session['provider'] = [
                'provider' => $provider->id,
                'profile' => $profile,
                'token' => $token
            ];
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
        if ($model->load(Yii::$app->request->post())) {
            if ($model->signup()) {
                $model->sendEmail();
                return $this->alert(
                    'success', 
                    Yii::t('app', 'Please activate your account') . '.' . 
                    Yii::t('app', 'A letter for activation was sent to {email}', ['email' => $model->email])
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
        
        try {
            $model = new SignupProviderForm(Yii::$app->session['provider']);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        
        if ($model->isVerified()) {
            if ($model->signup(false)) {
                Yii::$app->session['provider'] = null;
                return $this->goHome();
            }
        }
        
        if ($model->load(Yii::$app->request->post())) {
            if ($model->signup()) {
                $model->sendEmail();
                Yii::$app->session['provider'] = null;
                return $this->alert(
                    'success',  
                    Yii::t('app', 'Please activate your account') . '.' . 
                    Yii::t('app', 'A letter for activation was sent to {email}', ['email' => $model->email])
                );
            }
        }
        
        return $this->render('signupProvider', [
            'model' => $model
        ]);
    }
    
    public function actionConfirmEmail($token)
    { 
        try {
            $model = new ConfirmEmailForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        
        if ($model->confirmEmail()) { 
            return $this->alert('success', Yii::t('app', 'Thank! Your account is successfully activated'));
        } else {
            return $this->badRequest(Yii::t('app', 'Error activate your account'));        
        }
    }
    
    public function actionConfirmAgain()
    { 
        if (Yii::$app->user->identity->isConfirmed()) {
            return $this->accessDenied(); 
        }
        
        $model = new SignupForm();
        $model->user = Yii::$app->user->identity;
        $model->email = Yii::$app->user->identity->email;
        
        if ($model->sendEmail()) { 
            return $this->alert(
                'success', 
                Yii::t('app', 'A letter for activation was sent to {email}', ['email' => Yii::$app->user->identity->email])
            );
        } else {
            return $this->badRequest(Yii::t('app', 'Error sending emails'));        
        }
    }
    
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                return $this->alert(
                    'success', 
                    Yii::t('app', 'We\'ve sent you an email with instructions to reset your password')
                );                
            } else {
                return $this->alert(
                    'error', 
                    Yii::t('app', 'Unfortunately, the message to reset your password 
                    was not sent due to a server error, please try later')
                );
            }
        }
        
        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }
    
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
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
            return $this->pageNotFound();
        }
        
        $this->layout = 'maintenance';
        return $this->render('maintenance');
    }
}
