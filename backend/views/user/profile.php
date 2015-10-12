<?php

use maddoger\user\common\models\UserProfile;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model maddoger\core\models\MultiModel */

$this->title = Yii::t('maddoger/user', 'Profile');
$this->params['breadcrumbs'][] = $this->title;

/** @var maddoger\user\common\models\User $userModel */
$userModel = $model->getModel('user');
/** @var maddoger\user\common\models\UserProfile $profileModel */
$profileModel = $model->getModel('profile');

?>
<div class="user-update">

    <?php $form = ActiveForm::begin([
        'options' => [
            'enctype' => 'multipart/form-data',
        ],
    ]); ?>

    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <div class="panel-title"><?= Yii::t('maddoger/user', 'Bio') ?></div>
                </div>
                <div class="panel-body">
                    <?= $form->field($profileModel, 'last_name')->textInput(['maxlength' => 255]) ?>
                    <?= $form->field($profileModel, 'first_name')->textInput(['maxlength' => 255]) ?>
                    <?= $form->field($profileModel, 'patronymic')->textInput(['maxlength' => 255]) ?>
                    <?= $form->field($profileModel, 'gender')->dropDownList(UserProfile::getGenders(), ['prompt' => '']) ?>
                    <?= $form->field($profileModel, 'avatar', [
                        'template' => '{label} <br />' . ($profileModel->avatar ? Html::img($profileModel->avatar,
                                ['width' => 150]) : '') . '{input} {hint} {error}',
                    ])->fileInput() ?>
                    <?= $form->field($profileModel, 'delete_avatar')->checkbox() ?>
                </div>
            </div>

            <div class="form-group">
                <?= Html::submitButton(Yii::t('maddoger/user', 'Save'),
                    ['class' => $userModel->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>
        </div>
        <div class="col-md-6">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <div class="panel-title"><?= Yii::t('maddoger/user', 'Authentication') ?></div>
                </div>
                <div class="panel-body">
                    <?= $form->field($userModel, 'username')->textInput([
                        'maxlength' => 255,
                        'autocomplete' => 'off'
                    ]) ?>
                    <?= $form->field($userModel, 'email')->textInput(['maxlength' => 255, 'autocomplete' => 'off']) ?>
                    <?= $form->field($userModel, 'password')->passwordInput([
                        'maxlength' => 255,
                        'autocomplete' => 'off',
                        'value' => ''
                    ]) ?>
                </div>
            </div>

            <div class="form-group">
                <?= Html::submitButton(Yii::t('maddoger/user', 'Save'),
                    ['class' => $userModel->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
