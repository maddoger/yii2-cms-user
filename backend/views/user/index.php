<?php

use maddoger\user\common\models\User;
use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel maddoger\user\common\models\search\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('maddoger/user', 'Users');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="user-index">

    <div class="panel panel-default">
        <div class="panel-body">

            <p>
                <?= Html::a('<i class="glyphicon glyphicon-plus"></i> '.Yii::t('maddoger/user', 'Create user'), ['create'], ['class' => 'btn btn-success']) ?>
            </p>

            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    //'id',
                    'username',
                    'email:email',
                    [
                        'attribute' => 'name',
                        'value' => 'profile.name',
                    ],
                    // 'avatar',
                    [
                        'attribute' => 'role',
                        'filter' => User::getRoles(),
                        'value' => 'roleDescription'
                    ],
                    [
                        'attribute' => 'status',
                        'filter' => User::getStatuses(),
                        'value' => 'statusDescription'
                    ],
                    'last_visit_at:datetime',

                    ['class' => 'yii\grid\ActionColumn'],
                ],
            ]); ?>
        </div>
    </div>

</div>
