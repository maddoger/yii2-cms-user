<?php

namespace maddoger\user\common\models;

use maddoger\filebehavior\FileBehavior;
use maddoger\user\backend\Module;
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
 * @property string $language
 *
 * @property string $name @readonly
 * @property string $fullName @readonly
 *
 * @property User $user
 */
class UserProfile extends ActiveRecord
{
    public $delete_avatar;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_user_profile}}';
    }

    public function behaviors()
    {
        /** @var \maddoger\user\backend\Module $userModule */
        $userModule = Module::getInstance();
        if (!$userModule) {
            return [];
        }

        return [
            'avatarBehavior' => [
                'class' => FileBehavior::className(),
                'basePath' => $userModule->avatarsUploadPath,
                'baseUrl' => $userModule->avatarsUploadUrl,
                'attribute' => 'avatar',
                'deleteAttribute' => 'delete_avatar',
                'fileName' => function ($model, $file, $index){
                    return $model->user_id.'.'.$file->getExtension();
                }
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['gender'], 'integer'],
            [['first_name', 'last_name', 'patronymic', 'language'], 'string', 'max' => 255],

            ['avatar', 'image'],
            ['delete_avatar', 'boolean'],

            [['first_name', 'last_name', 'patronymic', 'language', 'avatar'], 'default', 'value' => null],
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
            'name' => Yii::t('maddoger/user', 'Name'),
            'fullName' => Yii::t('maddoger/user', 'Full name'),
            'avatar' => Yii::t('maddoger/user', 'Avatar'),
            'gender' => Yii::t('maddoger/user', 'Gender'),
            'language' => Yii::t('maddoger/user', 'Language'),
            'delete_avatar' => Yii::t('maddoger/user', 'Delete avatar'),
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
        $name = implode(' ', array_filter([
            $this->first_name,
            $this->last_name
        ]));
        return $name ?: null;
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        $name = implode(' ', array_filter([
            $this->last_name,
            $this->first_name,
            $this->patronymic,
        ]));
        return $name ?: null;
    }

    /**
     * @return array
     */
    public static function getGenders()
    {
        return [0 => Yii::t('maddoger/user', 'Male'), 1 => Yii::t('maddoger/user', 'Female'),];
    }
}
