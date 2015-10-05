<?php

/* @var $this yii\web\View */
/* @var $model maddoger\user\common\models\Role */

$this->title = Yii::t('maddoger/user', 'Create user role');
$this->params['breadcrumbs'][] = ['label' => Yii::t('maddoger/user', 'User roles'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="role-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
