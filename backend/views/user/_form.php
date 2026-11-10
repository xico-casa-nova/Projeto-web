<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\User;

/** @var yii\web\View $this */
/** @var backend\models\UserForm $model */
/** @var yii\widgets\ActiveForm $form */

$isNewUser = $model->userId === null;
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true, 'autocomplete' => 'username']) ?>

    <?= $form->field($model, 'email')->input('email', ['maxlength' => true, 'autocomplete' => 'email']) ?>

    <?= $form->field($model, 'password')->passwordInput([
        'autocomplete' => 'new-password',
        'placeholder' => $isNewUser ? '' : 'Deixe vazio para manter a palavra-passe atual',
    ])->hint($isNewUser ? null : 'Deixe vazio para manter a palavra-passe atual.') ?>

    <?= $form->field($model, 'fullName')->textInput(['maxlength' => true, 'autocomplete' => 'name']) ?>

    <?= $form->field($model, 'role')->dropDownList([
        'admin' => 'Administrador',
        'gestor' => 'Gestor',
        'user' => 'Utilizador',
    ]) ?>

    <?= $form->field($model, 'status')->dropDownList([
        User::STATUS_ACTIVE => 'Ativo',
        User::STATUS_INACTIVE => 'Inativo',
        User::STATUS_DELETED => 'Eliminado',
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton($isNewUser ? 'Criar utilizador' : 'Guardar alterações', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
