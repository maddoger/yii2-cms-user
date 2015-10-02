<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user maddoger\user\common\models\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['user/auth/reset-password', 'token' => $user->password_reset_token]);

echo Yii::t('maddoger/user', 'Hello {username}', ['username' => Html::encode($user->getName())]), ".\n\n",

Yii::t('maddoger/user', "Follow the link below to reset your password:\n{link}", ['link' => Html::a(Html::encode($resetLink), $resetLink)]);
