<?php
/**
 * Created by PhpStorm
 * User: elfuvo
 * Date: 2020-10-04
 * Time: 12:57
 */

namespace elfuvo\postman\assets;

use yii\web\AssetBundle;
use yii\web\JqueryAsset;

/**
 * Class MailerProgressAsset
 * @package elfuvo\postman\assets
 */
class PostmanProgressAsset extends AssetBundle
{
    /**
     *
     */
    public function init()
    {
        $this->sourcePath = dirname(__FILE__) . '/dist/';
        parent::init();
    }

    /**
     * @var array
     */
    public $js = [
        'postman-progress.js',
    ];

    /**
     * @var string[]
     */
    public $depends = [
        JqueryAsset::class,
    ];
}
