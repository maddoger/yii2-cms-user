<?php

namespace maddoger\user\backend;

use maddoger\core\components\BackendModule;
use Yii;
use yii\rbac\Item;

class Module extends BackendModule
{
    /**
     * @var string
     */
    public $controllerNamespace = 'maddoger\\user\\backend\\controllers';

    /**
     * @var string layout for guests (using in sign in page)
     */
    public $guestLayout;

    /**
     * @var string example: site\captcha
     */
    public $captchaAction;

    /**
     * @var string path for saving avatars
     */
    public $avatarsUploadPath = '@static/users/avatars';

    /**
     * @var string url to avatar's path
     */
    public $avatarsUploadUrl = '@staticUrl/users/avatars';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if (Yii::$app->getUser()->getIsGuest() && $this->guestLayout !== null) {
            $this->layout = $this->guestLayout;
        }
    }

    /**
     * @inheritdoc
     */
    public function registerTranslations()
    {
        if (!isset(Yii::$app->i18n->translations['maddoger/user'])) {
            Yii::$app->i18n->translations['maddoger/user'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'sourceLanguage' => 'en-US',
                'basePath' => '@maddoger/user/common/messages',
            ];
        }
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return Yii::t('maddoger/user', 'Users module');
    }

    /**
     * @inheritdoc
     */
    public function getVersion()
    {
        return '1.0.0';
    }

    /**
     * @inheritdoc
     */
    public function getRbacItems()
    {
        return [
            //Users
            'user.user.dashboard' =>
                [
                    'type' => Item::TYPE_PERMISSION,
                    'description' => Yii::t('maddoger/user', 'User. Access to dashboard'),
                ],
            'user.user.view' =>
                [
                    'type' => Item::TYPE_PERMISSION,
                    'description' => Yii::t('maddoger/user', 'User. View users'),
                ],
            'user.user.create' =>
                [
                    'type' => Item::TYPE_PERMISSION,
                    'description' => Yii::t('maddoger/user', 'User. Create users'),
                ],
            'user.user.update' =>
                [
                    'type' => Item::TYPE_PERMISSION,
                    'description' => Yii::t('maddoger/user', 'User. Update users'),
                ],
            'user.user.delete' =>
                [
                    'type' => Item::TYPE_PERMISSION,
                    'description' => Yii::t('maddoger/user', 'User. Delete users'),
                ],
            'user.user.manager' =>
                [
                    'type' => Item::TYPE_ROLE,
                    'description' => Yii::t('maddoger/user', 'User. Manage users'),
                    'children' => [
                        'user.user.view',
                        'user.user.create',
                        'user.user.update',
                        'user.user.delete',
                    ],
                ],
            //RBAC
            'user.rbac.updateFromModules' =>
                [
                    'type' => Item::TYPE_PERMISSION,
                    'description' => Yii::t('maddoger/user', 'User. Update user roles from modules'),
                ],
            'user.rbac.manageRoles' =>
                [
                    'type' => Item::TYPE_PERMISSION,
                    'description' => Yii::t('maddoger/user', 'User. Create, update and delete user roles'),
                ],
            'user.rbac.manager' =>
                [
                    'type' => Item::TYPE_ROLE,
                    'description' => Yii::t('maddoger/user', 'User. Manage user roles'),
                    'children' => [
                        'user.rbac.manageRoles',
                        'user.rbac.updateFromModules',
                    ]
                ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function getNavigation()
    {
        return [
            [
                'label' => Yii::t('maddoger/user', 'Users'),
                'icon' => 'fa fa-users',
                'items' => [
                    [
                        'label' => Yii::t('maddoger/user', 'Users'),
                        'url' => ['/' . $this->id . '/user/index'],
                        'activeUrl' => '/' . $this->id . '/user/*',
                        'icon' => 'fa fa-user',
                        'roles' => ['user.user.view'],
                    ],
                    [
                        'label' => Yii::t('maddoger/user', 'User roles'),
                        'url' => ['/' . $this->id . '/role/index'],
                        'activeUrl' => '/' . $this->id . '/role/*',
                        'icon' => 'fa fa-users',
                        'roles' => ['user.rbac.manageRoles'],
                    ],
                ]
            ]
        ];
    }

    /**
     * @return array
     */
    public function getSearchSources()
    {
        return [
            [
                'class' => '\maddoger\core\search\ArraySearchSource',
                'data' => [
                    [
                        'label' => Yii::t('maddoger/user', 'Users'),
                        'url' => ['/' . $this->id . '/user/index'],
                    ],
                    [
                        'label' => Yii::t('maddoger/user', 'User roles'),
                        'url' => ['/' . $this->id . '/role/index'],
                    ],
                ],
                'roles' => ['user.user.view', 'user.rbac.manageRoles'],
            ],
            [
                'class' => '\maddoger\core\search\ActiveSearchSource',
                'modelClass' => '\maddoger\user\models\User',
                'searchAttributes' => ['username', 'email'],
                'url' => ['/' . $this->id . '/user/view', 'id' => null],
                'label' => 'username',
                'labelPrefix' => Yii::t('maddoger/user', 'User - '),
                'roles' => ['user.user.view'],
            ],
        ];
    }
}