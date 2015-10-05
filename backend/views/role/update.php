<?php

/* @var $this yii\web\View */
/* @var $model maddoger\user\common\models\Role */

$this->title = Yii::t('maddoger/user', 'Update user role:') . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('maddoger/user', 'User roles'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->name]];
$this->params['breadcrumbs'][] = Yii::t('maddoger/user', 'Update');
?>
<div class="role-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
