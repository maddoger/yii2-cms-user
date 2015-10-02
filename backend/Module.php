<?php

namespace maddoger\user\backend;

use maddoger\core\components\BackendModule;
use Yii;

class Module extends BackendModule
{
    public $controllerNamespace = 'maddoger\\user\\backend\\controllers';

    /**
     * @var string layout for guests
     */
    public $guestLayout;

    /**
     * @var string example: site\captcha
     */
    public $captchaAction;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->registerTranslations();

        if (Yii::$app->getUser()->getIsGuest() && $this->guestLayout !== null) {
            $this->layout = $this->guestLayout;
        }
    }

    public function registerTranslations()
    {
        Yii::$app->i18n->translations['maddoger/user'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en-US',
            'basePath' => '@maddoger/user/messages',
        ];
    }
}