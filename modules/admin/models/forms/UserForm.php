<?php

namespace app\modules\admin\models\forms;

use Yii;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use app\models\AuthItem;
use app\models\User;

class UserForm extends \yii\base\Model
{
    /**
     * @var int
     */
    public $id;
    /**
     * @var string
     */
    public $username;
    /**
     * @var string
     */
    public $email;
    /**
     * @var string
     */
    public $date_create;
    /**
     * @var string
     */
    public $date_update;
    /**
     * @var string
     */
    public $date_login;
    /**
     * @var int
     */
    public $ip;
    /**
     * @var string
     */
    public $role;
    /**
     * @var int
     */
    public $status;
    /**
     * @var string
     */
    public $passwordNew;
    /**
     * @var \app\models\User
     */
    private $model;

   /**
    * @return array the validation rules.
    */
    public function rules()
    {
        return [
            ['username', 'string', 'min' => 3, 'max' => 40],
            ['username', 'match', 'pattern' => '/^[a-zA-Z0-9_-]+$/'],
            ['username', 'unique', 'targetClass' => User::class, 'filter' => function ($query) {
                if (!$this->model()->isNewRecord) {
                    $query->andWhere(['not', ['id' => $this->model()->id]]);
                }
            }],
            ['username', 'default', 'value' => null],

            ['email', 'email'],
            ['email', 'required'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => User::class, 'filter' => function ($query) {
                if (!$this->model()->isNewRecord) {
                    $query->andWhere(['not', ['id' => $this->model()->id]]);
                }
            }],
            ['email', 'default', 'value' => null],

            ['role', 'string'],
            [
                'role',
                'exist',
                'targetClass' => AuthItem::class,
                'targetAttribute' => ['role' => 'name'],
                'filter' => ['type' => \yii\rbac\Item::TYPE_ROLE]
            ],

            ['status', 'integer'],
            ['status', 'default', 'value' => User::STATUS_ACTIVE],
            ['status', 'in', 'range' => array_keys(User::getStatuses())],

            ['passwordNew', 'string', 'min' => 6],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'username' => Yii::t('app', 'Username'),
            'email' => Yii::t('app', 'Email'),
            'password' => Yii::t('app', 'Password'),
            'date_create' => Yii::t('app', 'Date create'),
            'date_update' => Yii::t('app', 'Date update'),
            'date_login' => Yii::t('app', 'Last login'),
            'ip' => Yii::t('app', 'IP'),
            'role' => Yii::t('app', 'Role'),
            'status' => Yii::t('app', 'Status'),

            'passwordNew' => Yii::t('app', 'New password'),
        ];
    }

    /**
     * @inheritdoc
     * @codeCoverageIgnore
     */
    public function attributeHints()
    {
        return [
            'username' => Yii::t('app.msg', 'Only letters, numbers, symbols _ and -'),
            'passwordNew' => Yii::t('app.msg', 'Set a new password')
        ];
    }

    /**
     * Set model
     *
     * @param User $model
     */
    public function setModel(User $model): void
    {
        $this->model = $model;

        $this->id = $model->id;
        $this->username = $model->username;
        $this->email = $model->email;
        $this->date_create = $model->date_create;
        $this->date_update = $model->date_update;
        $this->date_login = $model->date_login;
        $this->ip = $model->id;
        $this->role = $model->role;
        $this->status = $model->status;
    }

    /**
     * Get model
     *
     * @return User
     */
    public function model(): User
    {
        if ($this->model === null) {
            $this->model = new User();
        }

        return $this->model;
    }

    /**
     * Save user
     *
     * @throws Exception
     * @return User
     */
    public function save(): User
    {
        $model = $this->model();

        $model->id = $this->id;
        $model->username = $this->username;
        $model->email = $this->email;
        $model->date_create = $this->date_create;
        $model->date_update = $this->date_update;
        $model->date_login = $this->date_login;
        $model->ip = $this->id;
        $model->role = $this->role;
        $model->status = $this->status;
        $model->passwordNew = $this->passwordNew;

        if ($model->isNewRecord) {
            $model->setConfirmed();
        }

        if (!$model->save()) {
            throw new Exception(Yii::t('app.msg', 'An error occurred while saving user'));
        }

        $this->id = $model->id;

        $this->assignUserToRole($model->id, $model->role);

        return $model;
    }

    public function statusesList(): array
    {
        return $this->model()->getStatuses();
    }

    public function rolesList(): array
    {
        $roles = Yii::$app->authManager->getRoles();
        return ArrayHelper::map($roles, 'name', 'description');
    }

    private function assignUserToRole(int $userId, string $roleName = ''): void
    {
        $auth = Yii::$app->authManager;
        $auth->revokeAll($userId);

        if (!empty($roleName)) {
            $role = $auth->getRole($roleName);
            if ($role) {
                $auth->assign($role, $userId);
            }
        }
    }
}
