<?php

return [
    'sourcePath' => __DIR__ . DIRECTORY_SEPARATOR .'..' . DIRECTORY_SEPARATOR .'..',
    'languages' => ['ru-RU'],
    'translator' => 'Yii::t',
    'sort' => false,
    'removeUnused' => false,
    'only' => ['*.php'],
    'except' => [
        '.svn',
        '.git',
        '.gitignore',
        '.gitkeep',
        '.hgignore',
        '.hgkeep',
        //'/messages',
    ],

    // 'php' output format is for saving messages to php files.
    'format' => 'php',
    // Root directory containing message translations.
    'messagePath' => __DIR__,
    // boolean, whether the message file should be overwritten with the merged messages
    'overwrite' => true,
];
