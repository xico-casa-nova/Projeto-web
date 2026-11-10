<?php

use common\rbac\OwnEventRule;
use common\rbac\PendingEventRequestRule;
use yii\base\InvalidConfigException;
use yii\db\Expression;
use yii\db\Migration;
use yii\db\Query;
use yii\rbac\DbManager;

class m261110_140000_seed_event_rbac extends Migration
{
    private const PERMISSIONS = [
        'createEvent' => 'Criar pedidos de eventos',
        'viewOwnEvents' => 'Ver os próprios eventos',
        'viewEventRequests' => 'Ver pedidos de eventos pendentes',
        'acceptEventRequest' => 'Aceitar pedidos de eventos pendentes',
        'rejectEventRequest' => 'Recusar pedidos de eventos pendentes',
        'manageRbac' => 'Gerir roles e permissões',
        'manageUsers' => 'Gerir utilizadores',
        'manageLocations' => 'Gerir locais',
    ];

    public function safeUp(): void
    {
        $auth = $this->getAuthManager();
        $ownEventRule = new OwnEventRule();
        $pendingRequestRule = new PendingEventRequestRule();
        $auth->add($ownEventRule);
        $auth->add($pendingRequestRule);

        foreach (['admin', 'gestor', 'user'] as $name) {
            if ($auth->getRole($name) === null) {
                $role = $auth->createRole($name);
                $role->description = match ($name) {
                    'admin' => 'Acesso a todas as funcionalidades.',
                    'gestor' => 'Consulta e decisão sobre pedidos de eventos.',
                    default => 'Criação de pedidos e consulta dos próprios eventos.',
                };
                $auth->add($role);
            }
        }

        foreach (self::PERMISSIONS as $name => $description) {
            if ($auth->getRole($name) !== null) {
                throw new InvalidConfigException("A role '{$name}' já existe e conflita com uma permissão RBAC.");
            }
            if ($auth->getPermission($name) === null) {
                $permission = $auth->createPermission($name);
                $permission->description = $description;
                $auth->add($permission);
            }
        }

        $ownEvents = $auth->getPermission('viewOwnEvents');
        $ownEvents->ruleName = $ownEventRule->name;
        $auth->update('viewOwnEvents', $ownEvents);

        foreach (['acceptEventRequest', 'rejectEventRequest'] as $name) {
            $permission = $auth->getPermission($name);
            $permission->ruleName = $pendingRequestRule->name;
            $auth->update($name, $permission);
        }

        $this->addChildIfMissing($auth, 'user', 'createEvent');
        $this->addChildIfMissing($auth, 'user', 'viewOwnEvents');
        $this->addChildIfMissing($auth, 'gestor', 'viewEventRequests');
        $this->addChildIfMissing($auth, 'gestor', 'acceptEventRequest');
        $this->addChildIfMissing($auth, 'gestor', 'rejectEventRequest');
        $this->addChildIfMissing($auth, 'admin', 'user');
        $this->addChildIfMissing($auth, 'admin', 'gestor');
        $this->addChildIfMissing($auth, 'admin', 'manageRbac');
        $this->addChildIfMissing($auth, 'admin', 'manageUsers');
        $this->addChildIfMissing($auth, 'admin', 'manageLocations');

        $users = (new Query())
            ->select([
                'user_id' => 'u.id',
                'role' => new Expression("COALESCE(ud.role, 'user')"),
            ])
            ->from(['u' => '{{%user}}'])
            ->leftJoin(['ud' => '{{%user_details}}'], 'ud.user_id = u.id')
            ->all($this->db);
        foreach ($users as $user) {
            $role = $user['role'];
            if (!in_array($role, ['admin', 'gestor', 'user'], true)) {
                continue;
            }

            foreach (['admin', 'gestor', 'user'] as $knownRole) {
                if ($knownRole !== $role) {
                    $assignment = $auth->getAssignment($knownRole, (string) $user['user_id']);
                    if ($assignment !== null) {
                        $auth->revoke($auth->getRole($knownRole), (string) $user['user_id']);
                    }
                }
            }
            if ($auth->getAssignment($role, (string) $user['user_id']) === null) {
                $auth->assign($auth->getRole($role), (string) $user['user_id']);
            }
        }
    }

    public function safeDown(): void
    {
        $auth = $this->getAuthManager();
        foreach (['admin', 'gestor', 'user'] as $name) {
            $role = $auth->getRole($name);
            if ($role !== null) {
                $auth->remove($role);
            }
        }

        foreach (array_keys(self::PERMISSIONS) as $name) {
            $permission = $auth->getPermission($name);
            if ($permission !== null) {
                $auth->remove($permission);
            }
        }

        foreach (['ownEvent', 'pendingEventRequest'] as $name) {
            $rule = $auth->getRule($name);
            if ($rule !== null) {
                $auth->remove($rule);
            }
        }
    }

    private function addChildIfMissing(DbManager $auth, string $parentName, string $childName): void
    {
        $parent = $auth->getRole($parentName);
        $child = $auth->getRole($childName) ?? $auth->getPermission($childName);

        if ($parent === null || $child === null) {
            throw new InvalidConfigException('Não foi possível criar a hierarquia de roles RBAC.');
        }
        if (!$auth->hasChild($parent, $child)) {
            $auth->addChild($parent, $child);
        }
    }

    private function getAuthManager(): DbManager
    {
        $auth = Yii::$app->getAuthManager();
        if (!$auth instanceof DbManager) {
            throw new InvalidConfigException('O componente authManager tem de usar yii\rbac\DbManager.');
        }

        return $auth;
    }
}
