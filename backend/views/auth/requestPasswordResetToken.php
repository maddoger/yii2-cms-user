<?php
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \maddoger\user\common\models\PasswordResetRequestForm */

$this->title = Yii::t('maddoger/user', 'Request password reset');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="user-auth-request-password-reset">
    <p class="minimal-box-msg"><?= Yii::t('maddoger/user', 'Please fill out your email. A link to reset password will be sent there.') ?></p>
    <?php $form = ActiveForm::begin([
        'id' => 'request-password-reset-form',
        'fieldConfig' => [
            'template' => "{input}\n{feedback}\n{hint}\n{error}\n",
            'parts' => ['{feedback}' => '<span class="glyphicon glyphicon-user form-control-feedback"></span>'],
            'options' => ['class' => 'form-group has-feedback']
        ],
    ]) ?>

    <?= $form->field($model, 'email')->textInput(['placeholder' => $model->getAttributeLabel('email')]) ?>
    <?php
    //When captcha action is set
    if ($model->isAttributeSafe('code')) {
        echo $form->field($model, 'code')->widget(Captcha::className(), [
            'captchaAction' => $model->captchaAction,
            'template' => '<div class="row"><div class="col-lg-5">{image}</div><div class="col-lg-7">{input}</div></div>',
            'imageOptions' => [
                'class' => 'img-responsive',
            ],
            'options' => [
                'placeholder' => $model->getAttributeLabel('code'),
                'class' => 'form-control',
            ],
        ]);
    }?>

    <p><?= Html::submitButton(Yii::t('maddoger/user', 'Send'), ['class' => 'btn btn-primary btn-block btn-flat']) ?></p>
    <p><?= Html::a(Yii::t('maddoger/user', 'I remembered my password!'), ['login']) ?></p>

    <?php ActiveForm::end() ?>
</div>
