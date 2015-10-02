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
    }

    public function safeDown()
    {
        $this->delete('{{%user_user_profile}}', ['user_id' => 1]);
        $this->delete('{{%user_user}}', ['id' => 1]);
    }
}
