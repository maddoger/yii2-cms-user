<?php

namespace maddoger\user\common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\rbac\Item;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "{{%user_user}}".
 *
 * @property integer $id
 * @property string $username
 * @property string $auth_key
 * @property string $oauth_client
 * @property string $oauth_client_user_id
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property integer $role
 * @property integer $status
 * @property integer $last_visit_at
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property string $name @readonly
 * @property string $avatar @readonly
 *
 * @property UserProfile $profile
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;
    const STATUS_BLOCKED = 9;

    /**
     * User role needs for making difference between
     * real users accounts and administrators accounts.
     */
    const ROLE_USER = 10;
    const ROLE_ADMIN = 1;

    /**
     * @var string[]
     */
    private $_rbacRoles;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_user}}';
    }

    /**
     * @return array
     */
    public static function getRoles()
    {
        return [
            self::ROLE_USER => Yii::t('maddoger/user', 'User'),
            self::ROLE_ADMIN => Yii::t('maddoger/user', 'Administrator'),
        ];
    }

    /**
     * @return array
     */
    public static function getStatuses()
    {
        return [
            self::STATUS_ACTIVE => Yii::t('maddoger/user', 'Active'),
            self::STATUS_BLOCKED => Yii::t('maddoger/user', 'Blocked'),
            self::STATUS_DELETED => Yii::t('maddoger/user', 'Deleted'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'email'], 'required'],
            [['password'], 'string'],
            [['username'], 'string', 'min' => 3],
            [['email'], 'email'],
            
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['role', 'default', 'value' => self::ROLE_USER],
            
            [['username'], 'unique', 'message' => Yii::t('maddoger/user', 'This username is already registered.')],
            [['email'], 'unique', 'message' => Yii::t('maddoger/user', 'This email is already registered.')],

            //Create
            [['username', 'email', 'password_hash'], 'required', 'on' => 'create'],

            [['rbacRoles'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('maddoger/user', 'ID'),
            'username' => Yii::t('maddoger/user', 'Username'),
            'auth_key' => Yii::t('maddoger/user', 'Auth key'),
            'oauth_client' => Yii::t('maddoger/user', 'OAuth client'),
            'oauth_client_user_id' => Yii::t('maddoger/user', 'OAuth user id'),
            'password_hash' => Yii::t('maddoger/user', 'Password hash'),
            'password_reset_token' => Yii::t('maddoger/user', 'Password reset token'),
            'email' => Yii::t('maddoger/user', 'Email'),
            'role' => Yii::t('maddoger/user', 'User type'),
            'status' => Yii::t('maddoger/user', 'Status'),
            'last_visit_at' => Yii::t('maddoger/user', 'Last visit at'),
            'created_at' => Yii::t('maddoger/user', 'Created at'),
            'updated_at' => Yii::t('maddoger/user', 'Updated at'),

            'name' => Yii::t('maddoger/user', 'Name'),
            'avatar' => Yii::t('maddoger/user', 'Avatar'),
            'statusDescription' => Yii::t('maddoger/user', 'Status'),
            'roleDescription' => Yii::t('maddoger/user', 'User type'),
            'rbacRoles' => Yii::t('maddoger/user', 'Roles'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if ($insert) {
            $this->generateAuthKey();
        }
        return parent::beforeSave($insert);
    }

    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        //RBAC Roles
        if ($this->isAttributeSafe('rbacRoles')) {

            if (!$insert) {
                Yii::$app->authManager->revokeAll($this->id);
            }
            if ($this->_rbacRoles) {

                foreach ($this->_rbacRoles as $roleName) {
                    $role = Yii::$app->authManager->getRole($roleName);
                    if (!$role) {
                        continue;
                    }
                    Yii::$app->authManager->assign($role, $this->id);
                }
            }
        }
        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * @inheritdoc
     */
    public function afterDelete()
    {
        Yii::$app->authManager->revokeAll($this->id);
        parent::afterDelete();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfile()
    {
        return $this->hasOne(UserProfile::className(), ['user_id' => 'id']);
    }

    //Admin panel

    /**
     * @return string
     */
    public function getName()
    {
        if ($this->profile) {
            return $this->profile->getName() ?: $this->username;
        } else {
            return $this->username;
        }
    }

    /**
     * @return string
     */
    public function getAvatar()
    {
        if ($this->profile) {
            //Avatar resizing?
            return $this->profile->avatar;
        } else {
            return null;
        }
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    public function getPassword()
    {
        return null;
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        if (!empty($password)) {
            $this->password_hash = Yii::$app->security->generatePasswordHash($password);
        }
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString(32);
    }

    /**
     * @return array
     */
    public function getRbacRoles()
    {
        if ($this->_rbacRoles === null) {
            $roles = Yii::$app->authManager->getRolesByUser($this->id);
            if ($roles) {
                foreach ($roles as $child) {
                    if ($child->type == Item::TYPE_ROLE) {
                        $this->_rbacRoles[] = $child->name;
                    }
                }
            }
        }
        return $this->_rbacRoles;
    }

    /**
     * @param $value
     */
    public function setRbacRoles($value)
    {
        $this->_rbacRoles = $value;
    }

    /**
     * Role sting representation
     * @return string
     */
    public function getRoleDescription()
    {
        static $list = null;
        if ($list === null) {
            $list = static::getRoles();
        }
        return (isset($list[$this->role])) ? $list[$this->role] : $this->role;
    }

    /**
     * Status sting representation
     * @return string
     */
    public function getStatusDescription()
    {
        static $list = null;
        if ($list === null) {
            $list = static::getStatuses();
        }
        return (isset($list[$this->status])) ? $list[$this->status] : $this->status;
    }

    /* Identity interface */

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        if ($token === null) {
            return null;
        }
        return static::findOne(['access_token' => $token, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @param bool $checkStatus
     * @return null|static
     */
    public static function findByUsername($username, $checkStatus = true)
    {
        return static::findOne(
            $checkStatus ?
                ['username' => $username, 'status' => self::STATUS_ACTIVE] :
                ['username' => $username]
        );
    }

    /**
     * Finds user by email
     *
     * @param string $email
     * @param bool $checkStatus
     * @return null|static
     */
    public static function findByEmail($email, $checkStatus = true)
    {
        return static::findOne(
            $checkStatus ?
                ['email' => $email, 'status' => self::STATUS_ACTIVE] :
                ['email' => $email]
        );
    }

    /**
     * Finds user by username or email
     *
     * @param string $username
     * @param bool $checkStatus
     * @return null|static
     */
    public static function findByUsernameOrEmail($username, $checkStatus = true)
    {
        return static::findOne(
            $checkStatus ?
            [
                'and',
                ['or', 'username' => $username, 'email' => $username],
                'status' => self::STATUS_ACTIVE
            ] :
            [
                ['or', 'username' => $username, 'email' => $username],
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $authKey !== null && $this->auth_key === $authKey;
    }

    /* Password reset */

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }
        $expire = isset(Yii::$app->params['user.passwordResetTokenExpire']) ?
            Yii::$app->params['user.passwordResetTokenExpire'] : 60 * 60 * 24;
        $parts = explode('_', $token);
        $timestamp = (int)end($parts);
        return $timestamp + $expire >= time();
    }

    /**
     * Update last visit time event handler
     *
     *
     * in user component:
     * 'on afterLogin'   => ['maddoger\user\common\models\User', 'updateLastVisit'],
     * 'on afterLogout'  => ['maddoger\user\common\models\User', 'updateLastVisit'],
     * @param $event
     * @return bool
     */
    public static function updateLastVisit($event)
    {
        if ($event->isValid) {
            /**
             * @var User $user
             */
            $user = $event->identity;
            $user->last_visit_at = time();
            $user->updateAttributes(['last_visit_at']);
            return true;
        }
        return false;
    }
}
