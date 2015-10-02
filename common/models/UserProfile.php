<?php

namespace maddoger\user\common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%user_user_profile}}".
 *
 * @property integer $user_id
 * @property string $first_name
 * @property string $last_name
 * @property string $patronymic
 * @property string $avatar
 * @property integer $gender
 *
 * @property User $user
 */
class UserProfile extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_user_profile}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['gender'], 'integer'],
            [['first_name', 'last_name', 'patronymic', 'avatar'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => Yii::t('maddoger/user', 'User ID'),
            'first_name' => Yii::t('maddoger/user', 'First name'),
            'last_name' => Yii::t('maddoger/user', 'Last name'),
            'patronymic' => Yii::t('maddoger/user', 'Patronymic'),
            'avatar' => Yii::t('maddoger/user', 'Avatar'),
            'gender' => Yii::t('maddoger/user', 'Gender'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return string
     */
    public function getName()
    {
        $name = implode(' ', [
            $this->first_name,
            $this->last_name
        ]);
        return $name ?: null;
    }
}
