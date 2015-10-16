<?php

use yii\db\Migration;

class m151002_122958_user_init extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user_user}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull()->unique(),
            'auth_key' => $this->string(32)->notNull(),
            'oauth_client' => $this->string(),
            'oauth_client_user_id' => $this->string(),
            'password_hash' => $this->string()->notNull(),
            'password_reset_token' => $this->string(),
            'email' => $this->string()->notNull()->unique(),
            'role' => $this->smallInteger()->notNull()->defaultValue(10),
            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'last_visit_at' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $tableOptions);

        $this->createTable('{{%user_user_profile}}', [
            'user_id' => $this->primaryKey(),
            'first_name' => $this->string(),
            'last_name' => $this->string(),
            'patronymic' => $this->string(),
            'avatar' => $this->string(),
            'gender' => $this->smallInteger(1),
        ], $tableOptions);

        $this->addForeignKey($this->db->tablePrefix.'user_user_profile_user_fk',
            '{{%user_user_profile}}', 'user_id',
            '{{%user_user}}', 'id',
            'CASCADE', 'CASCADE');

        $this->createTable('{{%user_session}}', [
            'id' => $this->string(40)->notNull(),
            'expire' => $this->integer(),
            'data' => $this->binary(),
        ], $tableOptions);
        $this->addPrimaryKey($this->db->tablePrefix.'user_session_pk', '{{%user_session}}', 'id');
    }

    public function safeDown()
    {
        $this->dropPrimaryKey($this->db->tablePrefix.'user_session_pk', '{{%user_session}}');
        $this->dropTable('{{%user_session}}');

        $this->dropForeignKey($this->db->tablePrefix.'user_user_profile_user_fk', '{{%user_user_profile}}');
        $this->dropTable('{{%user_user_profile}}');

        $this->dropTable('{{%user_user}}');
    }
}
