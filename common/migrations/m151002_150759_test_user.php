<?php

use maddoger\user\common\models\User;
use yii\db\Migration;

class m151002_150759_test_user extends Migration
{
    public function safeUp()
    {
        $this->insert('{{%user_user}}', [
            'id' => 1,
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password_hash' => Yii::$app->security->generatePasswordHash('admin'),
            'auth_key' => Yii::$app->security->generateRandomString(32),
            'status' => User::STATUS_ACTIVE,
            'role' => User::ROLE_ADMIN,
        ]);

        $this->insert('{{%user_user_profile}}', [
            'user_id' => 1,
            'first_name' => 'John',
            'last_name' => 'Doe',
        ]);

        //Superuser role
        $superuserRole = Yii::$app->authManager->createRole('superuser');
        Yii::$app->authManager->add($superuserRole);
        Yii::$app->authManager->assign($superuserRole, 1);
    }

    public function safeDown()
    {
        $superuserRole = Yii::$app->authManager->getRole('superuser');
        if ($superuserRole) {
            Yii::$app->authManager->revoke($superuserRole, 1);
            Yii::$app->authManager->remove($superuserRole);
        }

        $this->delete('{{%user_user_profile}}', ['user_id' => 1]);
        $this->delete('{{%user_user}}', ['id' => 1]);
    }
}
