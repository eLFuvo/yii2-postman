<?php
/**
 * Created by PhpStorm
 * User: elfuvo
 * Date: 2020-08-14
 * Time: 21:33
 */

use elfuvo\postman\actions\IndexAction;
use elfuvo\postman\actions\ProgressAction;

return [
    'id' => 'tests',
    'name' => 'Postman',
    'timeZone' => 'UTC',
    'language' => 'ru',
    'basePath' => dirname(dirname(dirname(__DIR__))),
    'aliases' => [
        '@root' => dirname(dirname(dirname(__DIR__))),
        '@vendor' => '@root/vendor',
        '@bower' => '@vendor/bower-asset',
        '@app' => '@root/tests/app',
        '@runtime' => '@app/runtime',
        '@webroot' => '@app/web',
        '@web' => '/',
    ],
    'container' => [
        'singletons' => [],
        'definitions' => [
            \elfuvo\postman\processor\ProcessorInterface::class => [
                'class' => \elfuvo\postman\processor\MailProcessor::class,
                'collectors' => [
                    \elfuvo\postman\collector\TextInputCollector::class,
                ],
            ],
            \elfuvo\postman\result\ResultInterface::class => \elfuvo\postman\result\FileContinuesResult::class,
            yii\web\Request::class => [
                'class' => yii\web\Request::class,
                'enableCookieValidation' => false,
                'enableCsrfValidation' => false,
            ],
            IndexAction::class => [
                'class' => IndexAction::class,
                'view' => '@root/src/views/index',
                'useQueue' => false,
            ],
            ProgressAction::class => [
                'class' => ProgressAction::class,
                'view' => '@root/src/views/progress'
            ],
        ],
    ],
    'modules' => [],
    'components' => [
        'cache' => [
            'class' => yii\caching\FileCache::class,
            'keyPrefix' => 'postman',
        ],
        'queue' => [
            'class' => \yii\queue\file\Queue::class,
            'path' => '@runtime/queue',
        ],
        'i18n' => [
            'class' => \yii\i18n\I18N::class,
            'translations' => [
                'postman' => [
                    'class' => \yii\i18n\PhpMessageSource::class,
                    'sourceLanguage' => 'en',
                    'basePath' => '@root/src/messages',
                ],
            ],
        ],
        'mailer' => [
            'class' => \yii\swiftmailer\Mailer::class,
            'htmlLayout' => '@app/mail/layouts/html',
            'textLayout' => '@app/mail/layouts/text',
            'useFileTransport' => true,
        ],
    ]
];
