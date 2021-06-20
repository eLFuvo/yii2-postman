<?php
/**
 * Created by PhpStorm
 * User: elfuvo
 * Date: 2020-10-04
 * Time: 13:15
 */

namespace elfuvo\postman\controllers\backend;

use elfuvo\postman\actions\IndexAction;
use elfuvo\postman\actions\ProgressAction;
use elfuvo\postman\actions\ViewAction;
use yii\web\Controller;

/**
 * Class DefaultController
 * @package elfuvo\postman\controllers\backend
 */
class DefaultController extends Controller
{
    /**
     * @return array|string[]
     */
    public function actions()
    {
        return [
            'index' => IndexAction::class,
            'progress' => ProgressAction::class,
            'view' => ViewAction::class,
        ];
    }
}
