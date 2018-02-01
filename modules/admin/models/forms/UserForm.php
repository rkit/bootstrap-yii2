<?php

namespace app\modules\admin\models\forms;

use Yii;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use app\models\entity\{AuthItem, User};

class UserForm extends \yii\base\Model
{
    /**
     * @var int
     */
    public $id;
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
    public $role_name;
    /**
     * @var int
     */
    public $status;
    /**
     * @var string
     */
    public $passwordNew;
    /**
     * @var \app\models\entity\User
     */
    private $model;

   /**
    * @return array The validation rules
    */
    public function rules()
    {
        return [
            ['email', 'email'],
            ['email', 'required'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => User::class, 'filter' => function ($query) {
                if (!$this->model()->isNewRecord) {
                    $query->andWhere(['not', ['id' => $this->model()->id]]);
                }
            }],

            ['role_name', 'string'],
            [
                'role_name',
                'exist',
                'targetClass' => AuthItem::class,
                'targetAttribute' => ['role_name' => 'name'],
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
            'email' => Yii::t('app', 'Email'),
            'password' => Yii::t('app', 'Password'),
            'date_create' => Yii::t('app', 'Date create'),
            'date_update' => Yii::t('app', 'Date update'),
            'date_login' => Yii::t('app', 'Last login'),
            'ip' => Yii::t('app', 'IP'),
            'role_name' => Yii::t('app', 'Role'),
            'status' => Yii::t('app', 'Status'),

            'passwordNew' => Yii::t('app', 'New password'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeHints()
    {
        return [
            'passwordNew' => Yii::t('app', 'Set a new password')
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
        $this->email = $model->email;
        $this->date_create = $model->date_create;
        $this->date_update = $model->date_update;
        $this->date_login = $model->date_login;
        $this->ip = $model->id;
        $this->role_name = $model->role_name;
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
        $model->email = $this->email;
        $model->date_create = $this->date_create;
        $model->date_update = $this->date_update;
        $model->date_login = $this->date_login;
        $model->ip = $this->id;
        $model->role_name = $this->role_name;
        $model->status = $this->status;
        $model->passwordNew = $this->passwordNew;

        if ($model->isNewRecord) {
            $model->setConfirmed();
        }

        if (!$model->save()) {
            throw new Exception(Yii::t('app.msg', 'An error occurred while saving user'));
        }

        $this->id = $model->id;

        $this->assignUserToRole($model->id, $model->role_name);

        return $model;
    }

    public function statusesList(): array
    {
        return $this->model()->getStatuses();
    }

    public function rolesList(): array
    {
        $list = Yii::$app->authManager->getRoles();
        return ArrayHelper::map($list, 'name', 'description');
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
