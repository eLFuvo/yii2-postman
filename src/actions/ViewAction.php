<?php
/**
 * Created by PhpStorm
 * User: elfuvo
 * Date: 2020-10-05
 * Time: 12:34
 */

namespace elfuvo\postman\actions;

use elfuvo\postman\models\PostmanLog;
use yii\base\Action;
use yii\web\NotFoundHttpException;

/**
 * Class ViewAction
 * @package elfuvo\postman\actions
 */
class ViewAction extends Action
{
    /**
     * @var string
     */
    public $indexAction = 'index';

    /**
     * @var string
     */
    public $view = '@elfuvo/postman/views/view';

    /**
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function run($id)
    {
        $model = PostmanLog::findOne((int)$id);
        if (!$model) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        return $this->controller->render(
            $this->view,
            [
                'model' => $model,
                'indexAction' => $this->indexAction,
            ]
        );
    }
}
