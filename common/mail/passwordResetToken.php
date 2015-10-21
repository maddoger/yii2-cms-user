<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user maddoger\user\common\models\User */
/* @var $resetUrl array */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl($resetUrl);

echo Yii::t('maddoger/user', 'Hello {username}', ['username' => Html::encode($user->getName())]), ".\n\n",

Yii::t('maddoger/user', "Follow the link below to reset your password:\n{link}", ['link' => Html::a(Html::encode($resetLink), $resetLink)]);
