<?php

/* @var $this yii\web\View */
/* @var $model maddoger\core\models\MultiModel */

/** @var maddoger\user\common\models\User $user */
$user = $model->getModel('user');
$this->title = Yii::t('maddoger/user', 'Update user:') . ' ' . $user->getName();
$this->params['breadcrumbs'][] = ['label' => Yii::t('maddoger/user', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $user->getName(), 'url' => ['view', 'id' => $user->id]];
$this->params['breadcrumbs'][] = Yii::t('maddoger/user', 'Update');
?>
<div class="user-update">

    <?= $this->render('_form', [
        'model' => $model,
        'roles' => $roles,
    ]) ?>

</div>
