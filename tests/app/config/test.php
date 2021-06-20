<?php
/**
 * Created by PhpStorm
 * User: elfuvo
 * Date: 2020-08-14
 * Time: 21:33
 */

return \yii\helpers\ArrayHelper::merge(
    require(__DIR__ . '/common.php'),
    [
        'id' => 'web-tests',
        'controllerNamespace' => 'elfuvo\\postman\\controllers\\backend',
        'viewPath' => '@app/views',
        'defaultRoute' => 'default/index',
        'modules' => [],
        'components' => [
            'session' => [
                'class' => \yii\web\CacheSession::class,
                'timeout' => 24 * 60 * 60,
                'cache' => [
                    'class' => yii\caching\FileCache::class,
                    'keyPrefix' => hash('crc32', __LINE__),
                ],
            ],
            'urlManager' => [
                'class' => \yii\web\UrlManager::class,
                'enablePrettyUrl' => true,
                'showScriptName' => false,
                'normalizer' => [
                    'class' => \yii\web\UrlNormalizer::class,
                ],
                'rules' => [
                    '<controller:[\w\-]+>/<action:[\w\-]+>' => '<controller>/<action>',
                ],
                'baseUrl' => '/',
            ],
            'assetManager' => [
                'class' => \yii\web\AssetManager::class,
                'basePath' => '@app/web/assets',
                'baseUrl' => '/',
                'appendTimestamp' => true,
                'dirMode' => 0755,
                'fileMode' => 0644,
            ],
        ]
    ]
);
