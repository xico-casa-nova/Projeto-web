<?php

declare(strict_types=1);

namespace backend\controllers;

use common\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\rbac\Item;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class RbacController extends Controller
{
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['manageRbac'],
                    ],
                ],
                'denyCallback' => static function (): never {
                    throw new ForbiddenHttpException('Apenas administradores podem gerir o RBAC.');
                },
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'create-role' => ['POST'],
                    'create-permission' => ['POST'],
                    'update-role' => ['POST'],
                    'update-permission' => ['POST'],
                    'delete-item' => ['POST'],
                    'add-permission' => ['POST'],
                    'remove-permission' => ['POST'],
                    'assign-role' => ['POST'],
                    'revoke-role' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex(): string
    {
        $auth = Yii::$app->getAuthManager();
        $roles = $auth->getRoles();
        $permissions = $auth->getPermissions();
        $userRows = User::find()
            ->select(['id', 'username'])
            ->orderBy(['username' => SORT_ASC])
            ->asArray()
            ->all();

        $userNames = [];
        foreach ($userRows as $userRow) {
            $userNames[(string) $userRow['id']] = $userRow['username'];
        }

        $assignments = [];
        foreach ($roles as $role) {
            foreach ($auth->getUserIdsByRole($role->name) as $userId) {
                $userId = (string) $userId;
                if (isset($userNames[$userId])) {
                    $assignments[] = [
                        'userId' => $userId,
                        'username' => $userNames[$userId],
                        'role' => $role->name,
                    ];
                }
            }
        }

        return $this->render('index', [
            'roles' => $roles,
            'permissions' => $permissions,
            'userRows' => $userRows,
            'assignments' => $assignments,
        ]);
    }

    public function actionCreateRole(): Response
    {
        $name = $this->postedString('name');
        if (!$this->validItemName($name)) {
            Yii::$app->session->setFlash('error', 'O nome da role é obrigatório e pode ter até 64 caracteres.');
            return $this->redirect(['index']);
        }

        $auth = Yii::$app->getAuthManager();
        if ($auth->getRole($name) !== null || $auth->getPermission($name) !== null) {
            Yii::$app->session->setFlash('error', 'Já existe uma role ou permissão com esse nome.');
            return $this->redirect(['index']);
        }

        $role = $auth->createRole($name);
        $role->description = $this->postedString('description');
        $auth->add($role);
        Yii::$app->session->setFlash('success', 'Role criada com sucesso.');

        return $this->redirect(['index']);
    }

    public function actionCreatePermission(): Response
    {
        $name = $this->postedString('name');
        if (!$this->validItemName($name)) {
            Yii::$app->session->setFlash('error', 'O nome da permissão é obrigatório e pode ter até 64 caracteres.');
            return $this->redirect(['index']);
        }

        $auth = Yii::$app->getAuthManager();
        if ($auth->getRole($name) !== null || $auth->getPermission($name) !== null) {
            Yii::$app->session->setFlash('error', 'Já existe uma role ou permissão com esse nome.');
            return $this->redirect(['index']);
        }

        $permission = $auth->createPermission($name);
        $permission->description = $this->postedString('description');
        $auth->add($permission);
        Yii::$app->session->setFlash('success', 'Permissão criada com sucesso.');

        return $this->redirect(['index']);
    }

    public function actionUpdateRole(): Response
    {
        return $this->updateItem('role');
    }

    public function actionUpdatePermission(): Response
    {
        return $this->updateItem('permission');
    }

    public function actionDeleteItem(): Response
    {
        $name = $this->postedString('name');
        $type = $this->postedString('type');
        $auth = Yii::$app->getAuthManager();
        $item = match ($type) {
            'role' => $auth->getRole($name),
            'permission' => $auth->getPermission($name),
            default => null,
        };

        if ($item === null) {
            throw new NotFoundHttpException('A role ou permissão solicitada não existe.');
        }

        $auth->remove($item);
        Yii::$app->session->setFlash('success', 'Item RBAC removido; as associações relacionadas também foram eliminadas.');

        return $this->redirect(['index']);
    }

    public function actionAddPermission(): Response
    {
        $auth = Yii::$app->getAuthManager();
        $role = $auth->getRole($this->postedString('role'));
        $permission = $auth->getPermission($this->postedString('permission'));
        if ($role === null || $permission === null) {
            throw new NotFoundHttpException('A role ou permissão selecionada não existe.');
        }
        if ($auth->hasChild($role, $permission) || !$auth->canAddChild($role, $permission)) {
            Yii::$app->session->setFlash('error', 'Esta permissão já está associada ou não pode ser associada à role.');
            return $this->redirect(['index']);
        }

        $auth->addChild($role, $permission);
        Yii::$app->session->setFlash('success', 'Permissão associada à role.');

        return $this->redirect(['index']);
    }

    public function actionRemovePermission(): Response
    {
        $auth = Yii::$app->getAuthManager();
        $role = $auth->getRole($this->postedString('role'));
        $permission = $auth->getPermission($this->postedString('permission'));
        if ($role === null || $permission === null) {
            throw new NotFoundHttpException('A role ou permissão selecionada não existe.');
        }
        if (!$auth->hasChild($role, $permission)) {
            throw new NotFoundHttpException('A permissão não está associada a esta role.');
        }

        $auth->removeChild($role, $permission);
        Yii::$app->session->setFlash('success', 'Permissão removida da role.');

        return $this->redirect(['index']);
    }

    public function actionAssignRole(): Response
    {
        $auth = Yii::$app->getAuthManager();
        $role = $auth->getRole($this->postedString('role'));
        $userId = $this->postedString('userId');
        if ($role === null || $userId === '' || User::find()->where(['id' => $userId])->exists() === false) {
            throw new NotFoundHttpException('O utilizador ou role selecionado não existe.');
        }
        if ($auth->getAssignment($role->name, $userId) !== null) {
            Yii::$app->session->setFlash('error', 'Este utilizador já tem esta role.');
            return $this->redirect(['index']);
        }

        $auth->assign($role, $userId);
        Yii::$app->session->setFlash('success', 'Role atribuída ao utilizador.');

        return $this->redirect(['index']);
    }

    public function actionRevokeRole(): Response
    {
        $auth = Yii::$app->getAuthManager();
        $role = $auth->getRole($this->postedString('role'));
        $userId = $this->postedString('userId');
        if ($role === null || $userId === '') {
            throw new NotFoundHttpException('O utilizador ou role selecionado não existe.');
        }
        if ($auth->getAssignment($role->name, $userId) === null) {
            throw new NotFoundHttpException('Esta role não está atribuída ao utilizador.');
        }

        $auth->revoke($role, $userId);
        Yii::$app->session->setFlash('success', 'Role removida do utilizador.');

        return $this->redirect(['index']);
    }

    private function postedString(string $name): string
    {
        $value = $this->request->post($name, '');
        return is_string($value) ? trim($value) : '';
    }

    private function validItemName(string $name): bool
    {
        return $name !== '' && mb_strlen($name) <= 64;
    }

    private function updateItem(string $type): Response
    {
        $auth = Yii::$app->getAuthManager();
        $currentName = $this->postedString('name');
        $newName = $this->postedString('newName');
        if (!$this->validItemName($newName)) {
            Yii::$app->session->setFlash('error', 'O nome é obrigatório e pode ter até 64 caracteres.');
            return $this->redirect(['index']);
        }

        $item = match ($type) {
            'role' => $auth->getRole($currentName),
            'permission' => $auth->getPermission($currentName),
            default => null,
        };
        if (!$item instanceof Item) {
            throw new NotFoundHttpException('A role ou permissão solicitada não existe.');
        }
        if (
            $newName !== $currentName
            && ($auth->getRole($newName) !== null || $auth->getPermission($newName) !== null)
        ) {
            Yii::$app->session->setFlash('error', 'Já existe uma role ou permissão com esse nome.');
            return $this->redirect(['index']);
        }

        $item->name = $newName;
        $item->description = $this->postedString('description');
        $auth->update($currentName, $item);
        Yii::$app->session->setFlash('success', 'Item RBAC atualizado com sucesso.');

        return $this->redirect(['index']);
    }
}
