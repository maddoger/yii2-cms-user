<?php

use yii\db\Migration;

class m151016_100014_user_profile_language extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%user_user_profile}}', 'language', $this->string());
    }

    public function safeDown()
    {
        $this->dropColumn('{{%user_user_profile}}', 'language');
    }
}
