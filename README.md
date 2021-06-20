Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist elfuvo/postman "~0.0.1"
```

or add

```
"elfuvo/postman": "~0.0.1"
```

Use:
====
In config define classes

```php
[
    'container' => [
        'definitions' =>[
            \elfuvo\postman\processor\ProcessorInterface::class => [
                'class' => \app\modules\postman\processor\MailAttachmentProcessor::class,
                'collectors' => [
                    \elfuvo\postman\collector\TextInputCollector::class,
                ],
            ],
            \elfuvo\postman\result\ResultInterface::class => \elfuvo\postman\result\CacheContinuesResult::class,
        ],
    ]
];
```

For using `DatabaseContinuesResult` add migration path "" to.

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
                'useQueue' => false, // use or not Yii2 queue for mailing
            ],
            'progress' => ProgressAction::class,
            'view' => ViewAction::class,
        ];
    }
}
```

You can create custom collector of emails, see examples. After creating collector add it in common config.

TODO
==========
 - documentation
 - tests
