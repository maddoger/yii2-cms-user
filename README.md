Yii2 User module

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist maddoger/yii2-user "*"
```

or add

```
"maddoger/yii2-user": "*"
```

to the require section of your `composer.json` file.


##Migration

```
./yii migrate --migrationPath="@maddoger/user/common/migrations"
```

##Configuration

```
...
'modules' => [
    ...
    'user' => [
        'class' => 'maddoger\user\backend\Module',
        'guestLayout' => '@maddoger/admin/views/layouts/minimal.php',
        //'sortNumber' => 11
    ],
    ...
],
...
```

##Components

```
'components' => [
    'urlManager' => [
        'rules' => [
            //User
            '<action:(login|logout|request-password-reset|reset-password)>' => 'user/auth/<action>',
            'profile' => 'user/user/profile',
        ]
    ],
    'user' => [
        'identityClass' => 'maddoger\user\common\models\User',
        'loginUrl' => ['user/auth/login'],
        'enableAutoLogin' => true,
        //'on afterLogin'   => ['maddoger\user\common\models\User', 'updateLastVisit'],
        //'on afterLogout'   => ['maddoger\user\common\models\User', 'updateLastVisit'],
    ],
    'session' => [
        'class' => 'yii\web\DbSession',
        'sessionTable' => '{{%user_session}}',
    ],
...
]
```

