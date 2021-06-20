<?php
/**
 * Created by PhpStorm
 * User: elfuvo
 * Date: 2020-10-04
 * Time: 12:57
 */

namespace elfuvo\postman\actions;

use elfuvo\postman\processor\ProcessorInterface;
use Yii;
use yii\base\Action;
use yii\web\Controller;

/**
 * Class ProgressAction
 * @package elfuvo\import\actions
 */
class ProgressAction extends Action
{
    /**
     * @var string
     */
    public $view = '@elfuvo/postman/views/progress';

    /**
     * @var ProcessorInterface
     */
    protected $processor;

    /**
     * ProgressAction constructor.
     * @param string $id
     * @param Controller $controller
     * @param ProcessorInterface $processor
     * @param array $config
     */
    public function __construct(
        string $id,
        Controller $controller,
        ProcessorInterface $processor,
        array $config = []
    ) {
        $this->processor = $processor;

        parent::__construct($id, $controller, $config);
    }

    /**
     * @return string
     */
    public function run()
    {
        $result = $this->processor->getCurrentResult();
        if (Yii::$app->request->getIsAjax()) {
            return $this->controller->renderPartial(
                $this->view,
                [
                    'result' => $result,
                ]
            );
        }

        return $this->controller->render(
            $this->view,
            [
                'result' => $result,
            ]
        );
    }
}
