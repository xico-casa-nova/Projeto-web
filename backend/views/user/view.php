<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\User $model */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'username',
            'email:email',
            [
                'label' => 'Nome Completo',
                'value' => $model->userDetail?->nome_completo ?? '(não definido)',
            ],
            [
                'label' => 'Role',
                'value' => $model->userDetail?->displayRole() ?? 'Sem Perfil',
            ],
            [
                'label' => 'Telefone',
                'value' => $model->userDetail?->telefone,
            ],
            [
                'label' => 'NIF',
                'value' => $model->userDetail?->nif,
            ],
            [
                'attribute' => 'status',
                'value' => match ($model->status) {
                    \common\models\User::STATUS_ACTIVE => 'Ativo',
                    \common\models\User::STATUS_INACTIVE => 'Inativo',
                    \common\models\User::STATUS_DELETED => 'Eliminado',
                    default => $model->status,
                },
            ],
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>

</div>
