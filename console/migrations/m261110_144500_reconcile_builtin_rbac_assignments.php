<?php

use yii\base\InvalidConfigException;
use yii\db\Expression;
use yii\db\Migration;
use yii\db\Query;
use yii\rbac\DbManager;

class m261110_144500_reconcile_builtin_rbac_assignments extends Migration
{
    public function safeUp(): void
    {
        $auth = Yii::$app->getAuthManager();
        if (!$auth instanceof DbManager) {
            throw new InvalidConfigException('O componente authManager tem de usar yii\rbac\DbManager.');
        }

        $users = (new Query())
            ->select([
                'user_id' => 'u.id',
                'role' => new Expression("COALESCE(ud.role, 'user')"),
            ])
            ->from(['u' => '{{%user}}'])
            ->leftJoin(['ud' => '{{%user_details}}'], 'ud.user_id = u.id')
            ->all($this->db);

        foreach ($users as $user) {
            $userId = (string) $user['user_id'];
            $roleName = $user['role'];
            if (!in_array($roleName, ['admin', 'gestor', 'user'], true)) {
                continue;
            }

            foreach (['admin', 'gestor', 'user'] as $builtInRole) {
                $assignment = $auth->getAssignment($builtInRole, $userId);
                if ($builtInRole !== $roleName && $assignment !== null) {
                    $auth->revoke($auth->getRole($builtInRole), $userId);
                }
            }

            if ($auth->getAssignment($roleName, $userId) === null) {
                $role = $auth->getRole($roleName);
                if ($role === null) {
                    throw new InvalidConfigException("A role RBAC '{$roleName}' não existe.");
                }
                $auth->assign($role, $userId);
            }
        }
    }

    public function safeDown(): bool
    {
        return false;
    }
}
