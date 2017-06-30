<?php

namespace app\modules\admin\models\forms;

use Yii;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use app\models\AuthItem;

class AuthItemForm extends \yii\base\Model
{
    /**
     * @var string
     */
    public $name;
    /**
     * @var string
     */
    public $description;
    /**
     * @var array
     */
    public $roles;
    /**
     * @var array
     */
    public $permissions;
    /**
     * @var int
     */
    public $created_at;
    /**
     * @var int
     */
    public $updated_at;
    /**
     * @var \app\models\AuthItem
     */
    private $model;

   /**
    * @return array the validation rules.
    */
    public function rules()
    {
        return [
            [
                ['name', 'description'], 'required'
            ],
            [
                ['roles', 'permissions'], 'safe'
            ],

            ['name', 'unique', 'targetClass' => AuthItem::class, 'filter' => function ($query) {
                if (!$this->model()->isNewRecord) {
                    $query->andWhere(['not', ['name' => $this->model()->name]]);
                }
            }],
            ['name', 'string', 'max' => 64],
            ['name', 'match', 'pattern' => '/^[a-z]\w*$/i'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name'        => Yii::t('app', 'Name'),
            'description' => Yii::t('app', 'Description'),
            'roles'       => Yii::t('app', 'Inherit role'),
            'permissions' => Yii::t('app', 'Permissions'),
        ];
    }

    /**
     * Set model
     *
     * @param AuthItem $model
     */
    public function setModel(AuthItem $model): void
    {
        $this->model = $model;

        $this->name = $model->name;
        $this->description = $model->description;
        $this->created_at = $model->created_at;
        $this->updated_at = $model->updated_at;

        $permissions = Yii::$app->authManager->getPermissionsByRole($this->name);
        $this->permissions = ArrayHelper::index($permissions, 'name', []);
        $this->permissions = array_keys($this->permissions);

        $roles = Yii::$app->authManager->getChildren($this->name);
        $this->roles = ArrayHelper::index($roles, 'name', []);
        $this->roles = array_keys($this->roles);
    }

    /**
     * Get model
     *
     * @return AuthItem
     */
    public function model(): AuthItem
    {
        if ($this->model === null) {
            $this->model = new AuthItem();
        }

        return $this->model;
    }

    /**
     * Save auth item
     *
     * @throws Exception
     * @return AuthItem
     */
    public function save(): AuthItem
    {
        $model = $this->model();

        $model->name = $this->name;
        $model->description = $this->description;
        $model->type = \yii\rbac\Item::TYPE_ROLE;

        if (!$model->save()) {
            throw new Exception(Yii::t('app.msg', 'An error occurred while saving authItem'));
        }

        if (!$model->isSuperUser()) {
            $this->assignRolesAndPermissions();
        }

        return $model;
    }

    public function permissionsList(): array
    {
        $permissions = Yii::$app->authManager->getPermissions();
        return ArrayHelper::map($permissions, 'name', function ($row) {
            return Yii::t('app', $row->description);
        });
    }

    public function rolesList(): array
    {
        $roles = Yii::$app->authManager->getRoles();
        unset($roles[$this->model->name]);

        return ArrayHelper::map($roles, 'name', 'description');
    }

    private function assignRolesAndPermissions(): void
    {
        $auth = Yii::$app->authManager;

        $role = $auth->getRole($this->model->name);
        $roles = $auth->getRoles();
        $permissions = $auth->getPermissions();

        $auth->removeChildren($role);

        if (is_array($this->roles)) {
            foreach ($this->roles as $r) {
                $auth->addChild($role, $roles[$r]);
            }
        }

        if (is_array($this->permissions)) {
            $currPermissions = ArrayHelper::index(
                $auth->getPermissionsByRole($this->model->name),
                'name',
                []
            );
            foreach ($this->permissions as $permission) {
                if (!array_key_exists($permission, $currPermissions)) {
                    $auth->addChild($role, $permissions[$permission]);
                }
            }
        }
    }
}
