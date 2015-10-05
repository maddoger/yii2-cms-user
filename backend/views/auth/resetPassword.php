<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model maddoger\user\common\models\ResetPasswordForm */

$this->title = Yii::t('maddoger/user', 'Reset password');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="site-reset-password">

    <p class="minimal-box-msg"><?= Yii::t('maddoger/user', 'Please choose your new password:') ?></p>
    <?php $form = ActiveForm::begin([
        'id' => 'request-password-reset-form',
        'fieldConfig' => [
            'template' => "{input}\n{feedback}\n{hint}\n{error}\n",
            'parts' => ['{feedback}' => '<span class="glyphicon glyphicon-user form-control-feedback"></span>'],
            'options' => ['class' => 'form-group has-feedback']
        ],
    ]) ?>

    <?= $form->field($model, 'password')->passwordInput(['placeholder' => $model->getAttributeLabel('password')]) ?>

    <p><?= Html::submitButton(Yii::t('maddoger/user', 'Send'), ['class' => 'btn btn-primary btn-block btn-flat']) ?></p>
    <p><?= Html::a(Yii::t('maddoger/user', 'I remembered my password!'), ['login']) ?></p>

    <?php ActiveForm::end() ?>

</div>
