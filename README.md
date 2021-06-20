[![Latest Stable Version](https://img.shields.io/github/v/release/elfuvo/yii2-postman.svg)](https://packagist.org/packages/elfuvo/yii2-postman)
[![Build](https://img.shields.io/github/workflow/status/elfuvo/yii2-postman/Build.svg)](https://github.com/elfuvo/yii2-postman)
[![Total Downloads](https://img.shields.io/github/downloads/elfuvo/yii2-postman/total.svg)](https://packagist.org/packages/elfuvo/yii2-postman)
[![License](https://img.shields.io/github/license/elfuvo/yii2-postman.svg)](https://github.com/elfuvo/yii2-postman/blob/master/LICENSE)
[![Yii2](https://img.shields.io/badge/Powered_by-Yii_Framework-green.svg?style=flat)](http://www.yiiframework.com/)

Requirements
------------

* PHP >=7.1

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist elfuvo/postman "~0.0.2"
```

or add into `composer.json`

```
"elfuvo/postman": "~0.0.2"
```

Use:
====
In common config define classes

```php
[
    'container' => [
        'definitions' =>[
            \elfuvo\postman\processor\ProcessorInterface::class => [
                'class' => \app\modules\postman\processor\MailProcessor::class,
                'collectors' => [
                    \elfuvo\postman\collector\TextInputCollector::class,
                ],
            ],
            \elfuvo\postman\result\ResultInterface::class => \elfuvo\postman\result\CacheContinuesResult::class,
        ],
    ]
];
```

For using `DatabaseContinuesResult` add migration path "@elfuvo/postman/migrations" to console config.

```php
[
    'controllerMap' => [
        'migrate' => [
            'class' => \yii\console\controllers\MigrateController::class,
            'migrationTable' => '{{%migration}}',
            'useTablePrefix' => true,
            'interactive' => false,
            'migrationPath' => [
                '@elfuvo/postman/migrations',
            ],
        ]
    ]
];
```

in backend config define module

```php
[
    'modules' => [
         'postman' => [
            'class' => \yii\base\Module::class,
            'controllerNamespace' => 'elfuvo\postman\controllers\backend',
        ],
    ]
];
```

if you don't want to use queue jobs create your own controller and set `useQueue` property for IndexAction as false

```php
class DefaultController extends Controller
{
    /**
     * @return array|string[]
     */
    public function actions()
    {
        return [
            'index' => [
                'class' => IndexAction::class,
                'view' => '@app/modules/postman/views/backend/default/index', // path to custom template
                'useQueue' => true, // use or not Yii2 queue for mailing
            ],
            'progress' => ProgressAction::class,
            'view' => ViewAction::class,
        ];
    }
}
```

You can create custom collector of emails, see examples. After creating collector add it in common config.
