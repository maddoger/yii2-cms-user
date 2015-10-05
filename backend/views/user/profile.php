<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model maddoger\user\common\models\User */

$this->title = Yii::t('maddoger/user', 'Profile');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-update">

        <?php $form = ActiveForm::begin([
            'options' => [
                'enctype' => 'multipart/form-data',
            ],
        ]); ?>

        <div class="row">
            <div class="col-md-6">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <div class="panel-title"><?= Yii::t('maddoger/user', 'Authentication') ?></div>
                    </div>
                    <div class="panel-body">
                        <?= $form->field($model, 'username')->textInput(['maxlength' => 255]) ?>
                        <?= $form->field($model, 'email')->textInput(['maxlength' => 255]) ?>
                        <?= $form->field($model, 'password')->passwordInput(['maxlength' => 255]) ?>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <div class="panel-title"><?= Yii::t('maddoger/user', 'Bio') ?></div>
                    </div>
                    <div class="panel-body">
                        <?= $form->field($model, 'real_name')->textInput(['maxlength' => 255]) ?>
                        <?= $form->field($model, 'avatar', [
                            'template' => '{label} <br />'.($model->avatar ? Html::img($model->avatar) : '').'{input} {hint} {error}',
                        ])->fileInput() ?>
                        <?= $form->field($model, 'delete_avatar')->checkbox() ?>
                    </div>
                </div>

                <div class="form-group">
                    <?= Html::submitButton(Yii::t('maddoger/user', 'Save'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                </div>

            </div>
        </div>

        <?php ActiveForm::end(); ?>

</div>
