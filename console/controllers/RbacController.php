<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\helpers\Console;
use common\rbac\OwnEventRule;
use common\rbac\PendingEventRequestRule;

/**
 * Gestão de RBAC granular via CLI.
 */
class RbacController extends Controller
{
    /**
     * Inicializa roles e permissões granulares CRUD.
     * Comando: php yii rbac/init
     */
    public function actionInit(): int
    {
        $auth = Yii::$app->authManager;

        $this->stdout("A limpar definições RBAC antigas...\n", Console::FG_YELLOW);
        $auth->removeAll();

        // 1. Regras (Rules)
        $this->stdout("A registar regras...\n");
        $ownEventRule = new OwnEventRule();
        $pendingRequestRule = new PendingEventRequestRule();
        $auth->add($ownEventRule);
        $auth->add($pendingRequestRule);

        // 2. Permissões Granulares (CRUD por entidade)
        $this->stdout("A criar permissões granulares...\n");
        $permissions = [
            // Utilizadores
            'createUser'    => 'Criar utilizadores',
            'viewUser'      => 'Visualizar utilizadores',
            'updateUser'    => 'Editar utilizadores',
            'deleteUser'    => 'Eliminar/desativar utilizadores',

            // Locais
            'createLocal'   => 'Criar locais',
            'viewLocal'     => 'Visualizar locais',
            'updateLocal'   => 'Editar locais',
            'deleteLocal'   => 'Eliminar locais',

            // Eventos
            'createEvent'         => 'Criar pedidos de eventos',
            'viewOwnEvents'       => 'Ver os próprios eventos',
            'viewEventRequests'   => 'Ver pedidos de eventos pendentes',
            'acceptEventRequest'  => 'Aceitar pedidos de eventos pendentes',
            'rejectEventRequest'  => 'Recusar pedidos de eventos pendentes',
            'updateEvent'         => 'Editar eventos',
            'deleteEvent'         => 'Eliminar eventos',

            // Produtos / Faturas
            'manageProducts' => 'Gerir produtos e catálogo',
            'viewInvoices'   => 'Visualizar faturas',
            'manageInvoices' => 'Emitir e gerir faturas',

            // Sistema / RBAC
            'accessBackend' => 'Aceder à área de administração (Back-office)',
            'manageRbac'    => 'Gerir roles, regras e permissões do sistema',
        ];

        $p = [];
        foreach ($permissions as $name => $description) {
            $perm = $auth->createPermission($name);
            $perm->description = $description;

            if ($name === 'viewOwnEvents') {
                $perm->ruleName = $ownEventRule->name;
            } elseif (in_array($name, ['acceptEventRequest', 'rejectEventRequest'], true)) {
                $perm->ruleName = $pendingRequestRule->name;
            }

            $auth->add($perm);
            $p[$name] = $perm;
        }

        // Permissões agregadas/agrupadas úteis para regras de controladores
        $manageUsers = $auth->createPermission('manageUsers');
        $manageUsers->description = 'Gestão completa de utilizadores';
        $auth->add($manageUsers);
        $auth->addChild($manageUsers, $p['createUser']);
        $auth->addChild($manageUsers, $p['viewUser']);
        $auth->addChild($manageUsers, $p['updateUser']);
        $auth->addChild($manageUsers, $p['deleteUser']);

        $manageLocations = $auth->createPermission('manageLocations');
        $manageLocations->description = 'Gestão completa de locais';
        $auth->add($manageLocations);
        $auth->addChild($manageLocations, $p['createLocal']);
        $auth->addChild($manageLocations, $p['viewLocal']);
        $auth->addChild($manageLocations, $p['updateLocal']);
        $auth->addChild($manageLocations, $p['deleteLocal']);

        // 3. Roles e Atribuições
        $this->stdout("A configurar roles e heranças...\n");

        // Role: USER (Front-office)
        $user = $auth->createRole('user');
        $user->description = 'Cliente / Utilizador normal do Front-office';
        $auth->add($user);
        $auth->addChild($user, $p['createEvent']);
        $auth->addChild($user, $p['viewOwnEvents']);
        $auth->addChild($user, $p['viewLocal']);

        // Role: GESTOR (Back-office operacional)
        $gestor = $auth->createRole('gestor');
        $gestor->description = 'Gestor de eventos e conteúdos';
        $auth->add($gestor);
        $auth->addChild($gestor, $p['accessBackend']);
        $auth->addChild($gestor, $p['viewEventRequests']);
        $auth->addChild($gestor, $p['acceptEventRequest']);
        $auth->addChild($gestor, $p['rejectEventRequest']);
        $auth->addChild($gestor, $manageLocations);
        $auth->addChild($gestor, $p['manageProducts']);
        $auth->addChild($gestor, $p['viewInvoices']);

        // Role: ADMIN (Back-office total)
        $admin = $auth->createRole('admin');
        $admin->description = 'Administrador do sistema com acesso total';
        $auth->add($admin);
        $auth->addChild($admin, $user);
        $auth->addChild($admin, $gestor);
        $auth->addChild($admin, $manageUsers);
        $auth->addChild($admin, $p['manageInvoices']);
        $auth->addChild($admin, $p['manageRbac']);

        $this->stdout("RBAC com permissões granulares configurado com sucesso!\n", Console::FG_GREEN);
        return ExitCode::OK;
    }

    /**
     * Atribui uma role a um utilizador.
     * Exemplo: php yii rbac/assign gestor 4
     */
    public function actionAssign(string $roleName, int $userId): int
    {
        $auth = Yii::$app->authManager;
        $role = $auth->getRole($roleName);

        if ($role === null) {
            $this->stderr("Erro: Role '{$roleName}' não encontrada.\n", Console::FG_RED);
            return ExitCode::DATAERR;
        }

        if ($auth->getAssignment($roleName, (string) $userId) !== null) {
            $this->stdout("Utilizador #{$userId} já possui a role '{$roleName}'.\n", Console::FG_YELLOW);
            return ExitCode::OK;
        }

        $auth->assign($role, (string) $userId);
        $this->stdout("Role '{$roleName}' atribuída ao utilizador #{$userId} com sucesso!\n", Console::FG_GREEN);
        return ExitCode::OK;
    }

    /**
     * Remove uma role de um utilizador.
     * Exemplo: php yii rbac/revoke gestor 4
     */
    public function actionRevoke(string $roleName, int $userId): int
    {
        $auth = Yii::$app->authManager;
        $role = $auth->getRole($roleName);

        if ($role === null) {
            $this->stderr("Erro: Role '{$roleName}' não encontrada.\n", Console::FG_RED);
            return ExitCode::DATAERR;
        }

        $assignment = $auth->getAssignment($roleName, (string) $userId);
        if ($assignment === null) {
            $this->stdout("Utilizador #{$userId} não possui a role '{$roleName}'.\n", Console::FG_YELLOW);
            return ExitCode::OK;
        }

        $auth->revoke($role, (string) $userId);
        $this->stdout("Role '{$roleName}' removida do utilizador #{$userId} com sucesso!\n", Console::FG_GREEN);
        return ExitCode::OK;
    }
}
