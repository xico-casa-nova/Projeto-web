<?php

use common\models\User;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\models\UserSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Utilizadores';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Criar utilizador', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'username',
            'email:email',
            [
                'label' => 'Role',
                'value' => function (User $model) {
                    $labels = [
                        'admin' => 'Administrador',
                        'gestor' => 'Gestor',
                        'user' => 'Utilizador',
                    ];
                    $badges = [];
                    foreach (Yii::$app->authManager->getRolesByUser($model->id) as $role) {
                        $badgeClass = match ($role->name) {
                            'admin' => 'bg-danger',
                            'gestor' => 'bg-warning text-dark',
                            default => 'bg-secondary',
                        };
                        $label = $labels[$role->name] ?? $role->name;
                        $badges[] = Html::tag('span', Html::encode($label), ['class' => "badge {$badgeClass}"]);
                    }

                    return $badges === [] ? 'Sem role RBAC' : implode(' ', $badges);
                },
                'format' => 'raw',
            ],
            [
                'attribute' => 'status',
                'value' => function (User $model) {
                    return match ($model->status) {
                        User::STATUS_ACTIVE => Html::tag('span', 'Ativo', ['class' => 'badge bg-success']),
                        User::STATUS_INACTIVE => Html::tag('span', 'Inativo', ['class' => 'badge bg-warning text-dark']),
                        User::STATUS_DELETED => Html::tag('span', 'Eliminado', ['class' => 'badge bg-danger']),
                        default => $model->status,
                    };
                },
                'format' => 'raw',
            ],
            'created_at:datetime',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, User $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                }
            ],
        ],
    ]); ?>


</div>
