<?php


/* @var $this yii\web\View */
/* @var $model maddoger\user\common\models\User */

$this->title = Yii::t('maddoger/user', 'Create user');
$this->params['breadcrumbs'][] = ['label' => Yii::t('maddoger/user', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-create">

    <?= $this->render('_form', [
        'model' => $model,
        'roles' => $roles,
    ]) ?>

</div>
