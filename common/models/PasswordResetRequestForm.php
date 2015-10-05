<?php
/**
 * @copyright Copyright (c) 2014 Vitaliy Syrchikov
 * @link http://syrchikov.name
 */

namespace maddoger\user\common\models;

use Yii;
use yii\base\Model;

/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model
{
    public $email;
    public $code;

    public $captchaAction;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = [
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => User::className(),
                'filter' => ['status' => User::STATUS_ACTIVE],
                'message' => Yii::t('maddoger/user', 'There is no user with such email.')
            ],
        ];
        if ($this->captchaAction) {
            $rules[] = ['code', 'captcha', 'captchaAction' => $this->captchaAction];
        }

        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'email' => Yii::t('maddoger/user', 'Email'),
            'code' => Yii::t('maddoger/user', 'Verification code'),
        ];
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return boolean whether the email was send
     */
    public function sendEmail()
    {
        /* @var $user User */
        $user = User::findOne([
            'status' => User::STATUS_ACTIVE,
            'email' => $this->email,
        ]);

        if ($user) {
            if (!User::isPasswordResetTokenValid($user->password_reset_token)) {
                $user->generatePasswordResetToken();
            }

            if ($user->save()) {
                return Yii::$app->mailer->compose('@maddoger/user/common/mail/passwordResetToken', ['user' => $user])
                    ->setTo($this->email)
                    ->setSubject(Yii::t('maddoger/user', 'Password reset for {app}', ['app' => \Yii::$app->name]))
                    ->send();
            }
        }

        return false;
    }
}
