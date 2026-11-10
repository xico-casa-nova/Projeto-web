<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\rbac\Permission;
use yii\rbac\Role;

/** @var yii\web\View $this */
/** @var Role[] $roles */
/** @var Permission[] $permissions */
/** @var array $userRows */
/** @var array $assignments */

$this->title = 'Gestão de RBAC';
$this->params['breadcrumbs'][] = $this->title;
$roleOptions = ArrayHelper::map($roles, 'name', 'name');
$permissionOptions = ArrayHelper::map($permissions, 'name', 'name');
$userOptions = ArrayHelper::map($userRows, 'id', 'username');
?>

<div class="rbac-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>Crie roles e permissões, associe permissões às roles e atribua roles aos utilizadores.</p>

    <?php foreach (['success', 'error'] as $type): ?>
        <?php if (Yii::$app->session->hasFlash($type)): ?>
            <div class="alert alert-<?= $type === 'success' ? 'success' : 'danger' ?>">
                <?= Html::encode(Yii::$app->session->getFlash($type)) ?>
            </div>
        <?php endif ?>
    <?php endforeach ?>

    <div class="row">
        <div class="col-md-6">
            <h2>Criar role</h2>
            <?php $form = ActiveForm::begin(['action' => ['create-role'], 'method' => 'post']); ?>
            <div class="form-group">
                <?= Html::label('Nome', 'role-name') ?>
                <?= Html::textInput('name', '', ['id' => 'role-name', 'class' => 'form-control', 'maxlength' => 64, 'required' => true]) ?>
            </div>
            <div class="form-group">
                <?= Html::label('Descrição', 'role-description') ?>
                <?= Html::textInput('description', '', ['id' => 'role-description', 'class' => 'form-control', 'maxlength' => 255]) ?>
            </div>
            <?= Html::submitButton('Criar role', ['class' => 'btn btn-success']) ?>
            <?php ActiveForm::end(); ?>
        </div>

        <div class="col-md-6">
            <h2>Criar permissão</h2>
            <?php $form = ActiveForm::begin(['action' => ['create-permission'], 'method' => 'post']); ?>
            <div class="form-group">
                <?= Html::label('Nome', 'permission-name') ?>
                <?= Html::textInput('name', '', ['id' => 'permission-name', 'class' => 'form-control', 'maxlength' => 64, 'required' => true]) ?>
            </div>
            <div class="form-group">
                <?= Html::label('Descrição', 'permission-description') ?>
                <?= Html::textInput('description', '', ['id' => 'permission-description', 'class' => 'form-control', 'maxlength' => 255]) ?>
            </div>
            <?= Html::submitButton('Criar permissão', ['class' => 'btn btn-success']) ?>
            <?php ActiveForm::end(); ?>
        </div>
    </div>

    <hr>
    <h2>Roles e permissões</h2>
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>Role e descrição</th>
                <th>Permissões associadas</th>
                <th>Ações</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($roles as $role): ?>
                <tr>
                    <td>
                        <?php $form = ActiveForm::begin(['action' => ['update-role'], 'method' => 'post']); ?>
                        <?= Html::hiddenInput('name', $role->name) ?>
                        <?= Html::textInput('newName', $role->name, ['class' => 'form-control input-sm', 'maxlength' => 64, 'required' => true, 'aria-label' => 'Nome da role']) ?>
                        <?= Html::textInput('description', $role->description, ['class' => 'form-control input-sm', 'maxlength' => 255, 'placeholder' => 'Descrição']) ?>
                        <?= Html::submitButton('Guardar', ['class' => 'btn btn-default btn-xs']) ?>
                        <?php ActiveForm::end(); ?>
                    </td>
                    <td>
                        <?php $children = Yii::$app->authManager->getChildren($role->name); ?>
                        <?php foreach ($children as $child): ?>
                            <span class="label label-info"><?= Html::encode($child->name) ?></span>
                            <?php if ($child instanceof Permission): ?>
                                <?php $form = ActiveForm::begin(['action' => ['remove-permission'], 'method' => 'post', 'options' => ['style' => 'display:inline']]); ?>
                                <?= Html::hiddenInput('role', $role->name) ?>
                                <?= Html::hiddenInput('permission', $child->name) ?>
                                <?= Html::submitButton('×', ['class' => 'btn btn-xs btn-warning', 'title' => 'Remover permissão da role']) ?>
                                <?php ActiveForm::end(); ?>
                            <?php endif ?>
                        <?php endforeach ?>
                    </td>
                    <td>
                        <?php $form = ActiveForm::begin(['action' => ['delete-item'], 'method' => 'post', 'options' => ['style' => 'display:inline']]); ?>
                        <?= Html::hiddenInput('name', $role->name) ?>
                        <?= Html::hiddenInput('type', 'role') ?>
                        <?= Html::submitButton('Remover role', ['class' => 'btn btn-danger btn-xs', 'data-confirm' => 'Remover esta role e as suas atribuições?']) ?>
                        <?php ActiveForm::end(); ?>
                    </td>
                </tr>
            <?php endforeach ?>
            <?php if ($roles === []): ?>
                <tr><td colspan="3">Ainda não existem roles.</td></tr>
            <?php endif ?>
            </tbody>
        </table>
    </div>

    <h3>Associar permissão a uma role</h3>
    <?php if ($roleOptions !== [] && $permissionOptions !== []): ?>
        <?php $form = ActiveForm::begin(['action' => ['add-permission'], 'method' => 'post']); ?>
        <div class="form-inline">
            <?= Html::dropDownList('role', null, $roleOptions, ['class' => 'form-control', 'prompt' => 'Selecione uma role', 'required' => true]) ?>
            <?= Html::dropDownList('permission', null, $permissionOptions, ['class' => 'form-control', 'prompt' => 'Selecione uma permissão', 'required' => true]) ?>
            <?= Html::submitButton('Associar', ['class' => 'btn btn-primary']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    <?php else: ?>
        <p>Crie pelo menos uma role e uma permissão para associá-las.</p>
    <?php endif ?>

    <hr>
    <h2>Permissões</h2>
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead><tr><th>Permissão e descrição</th><th>Ações</th></tr></thead>
            <tbody>
            <?php foreach ($permissions as $permission): ?>
                <tr>
                    <td>
                        <?php $form = ActiveForm::begin(['action' => ['update-permission'], 'method' => 'post']); ?>
                        <?= Html::hiddenInput('name', $permission->name) ?>
                        <?= Html::textInput('newName', $permission->name, ['class' => 'form-control input-sm', 'maxlength' => 64, 'required' => true, 'aria-label' => 'Nome da permissão']) ?>
                        <?= Html::textInput('description', $permission->description, ['class' => 'form-control input-sm', 'maxlength' => 255, 'placeholder' => 'Descrição']) ?>
                        <?= Html::submitButton('Guardar', ['class' => 'btn btn-default btn-xs']) ?>
                        <?php ActiveForm::end(); ?>
                    </td>
                    <td>
                        <?php $form = ActiveForm::begin(['action' => ['delete-item'], 'method' => 'post']); ?>
                        <?= Html::hiddenInput('name', $permission->name) ?>
                        <?= Html::hiddenInput('type', 'permission') ?>
                        <?= Html::submitButton('Remover', ['class' => 'btn btn-danger btn-xs', 'data-confirm' => 'Remover esta permissão das roles associadas?']) ?>
                        <?php ActiveForm::end(); ?>
                    </td>
                </tr>
            <?php endforeach ?>
            <?php if ($permissions === []): ?>
                <tr><td colspan="2">Ainda não existem permissões.</td></tr>
            <?php endif ?>
            </tbody>
        </table>
    </div>

    <hr>
    <h2>Atribuições a utilizadores</h2>
    <?php if ($roleOptions !== [] && $userOptions !== []): ?>
        <?php $form = ActiveForm::begin(['action' => ['assign-role'], 'method' => 'post']); ?>
        <div class="form-inline">
            <?= Html::dropDownList('userId', null, $userOptions, ['class' => 'form-control', 'prompt' => 'Selecione um utilizador', 'required' => true]) ?>
            <?= Html::dropDownList('role', null, $roleOptions, ['class' => 'form-control', 'prompt' => 'Selecione uma role', 'required' => true]) ?>
            <?= Html::submitButton('Atribuir role', ['class' => 'btn btn-primary']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    <?php else: ?>
        <p>É necessário existir pelo menos um utilizador e uma role para criar atribuições.</p>
    <?php endif ?>

    <div class="table-responsive" style="margin-top: 15px">
        <table class="table table-bordered table-striped">
            <thead><tr><th>Utilizador</th><th>Role</th><th>Ações</th></tr></thead>
            <tbody>
            <?php foreach ($assignments as $assignment): ?>
                <tr>
                    <td><?= Html::encode($assignment['username']) ?></td>
                    <td><?= Html::encode($assignment['role']) ?></td>
                    <td>
                        <?php $form = ActiveForm::begin(['action' => ['revoke-role'], 'method' => 'post']); ?>
                        <?= Html::hiddenInput('userId', $assignment['userId']) ?>
                        <?= Html::hiddenInput('role', $assignment['role']) ?>
                        <?= Html::submitButton('Revogar', ['class' => 'btn btn-warning btn-xs', 'data-confirm' => 'Revogar esta role do utilizador?']) ?>
                        <?php ActiveForm::end(); ?>
                    </td>
                </tr>
            <?php endforeach ?>
            <?php if ($assignments === []): ?>
                <tr><td colspan="3">Ainda não existem atribuições.</td></tr>
            <?php endif ?>
            </tbody>
        </table>
    </div>
</div>
