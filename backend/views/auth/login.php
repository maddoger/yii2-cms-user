<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \maddoger\user\common\models\LoginForm */

$this->title = Yii::$app->name;

?>
<div class="user-auth-login">
    <p class="minimal-box-msg"><?= Yii::t('maddoger/user', 'Sign in to start your session') ?></p>
    <?php $form = ActiveForm::begin([
        'fieldConfig' => [
            'template' => "{input}\n{feedback}\n{hint}\n{error}\n",
            'parts' => ['{feedback}' => '<span class="glyphicon glyphicon-user form-control-feedback"></span>'],
            'options' => ['class' => 'form-group has-feedback']
        ],
    ]) ?>
    <?= $form->field($model, 'username',
        [
            'parts' => ['{feedback}' => '<span class="glyphicon glyphicon-user form-control-feedback"></span>'],
        ]
    )->textInput(['placeholder' => $model->getAttributeLabel('username')]); ?>

    <?= $form->field($model, 'password',
        [
            'parts' => ['{feedback}' => '<span class="glyphicon glyphicon-lock form-control-feedback"></span>'],
        ]
    )->passwordInput(['placeholder' => $model->getAttributeLabel('password'), 'value' => '']); ?>

    <div class="row">
        <div class="col-xs-8">
            <div class="checkbox icheck">
                <?= $form->field($model, 'rememberMe')->checkbox(); ?>
            </div>
        </div><!-- /.col -->
        <div class="col-xs-4">
            <?= Html::submitButton(Yii::t('maddoger/user', 'Sign In'), ['class' => 'btn btn-primary btn-block btn-flat']) ?>
        </div><!-- /.col -->
    </div>
    <?php ActiveForm::end() ?>

    <p><?= Html::a(Yii::t('maddoger/user', 'I forgot my password'), ['request-password-reset']) ?></p>
</div>