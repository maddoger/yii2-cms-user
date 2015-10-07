<?php

/*
 In the configuration view we need:
    - $model with data and validation rules
    - $form ActiveForm object
 */

/** @var $this yii\web\View */
/** @var $model \yii\base\Model */
/** @var $form \yii\widgets\ActiveForm */

echo $form->field($model, 'avatarsUploadPath')
    ->label(Yii::t('maddoger/user', 'Path to upload avatars'))
    ->hint(Yii::t('maddoger/user', 'Use <code>@static</code> for alias to static folder.'))
    ->textInput();

echo $form->field($model, 'avatarsUploadUrl')
    ->label(Yii::t('maddoger/user', 'Url of path to upload avatars'))
    ->hint(Yii::t('maddoger/user', 'Use <code>@staticUrl</code> for alias to static folder url.'))
    ->textInput();

echo $form->field($model, 'sortNumber')
    ->label(Yii::t('maddoger/user', 'Sort number'))
    ->hint(Yii::t('maddoger/user', 'The lower the number, the higher the menu item.'))
    ->textInput();

