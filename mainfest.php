<?php
return [
    'id' => 'identification',
    'migrationPath' => '@vendor/yuncms/identification/migrations',
    'translations' => [
        'yuncms/identification' => [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en-US',
            'basePath' => '@vendor/yuncms/identification/messages',
        ],
    ],
    'backend' => [
        'class' => 'yuncms\identification\backend\Module'
    ],
    'frontend' => [
        'class' => 'yuncms\identification\frontend\Module',
    ],
];