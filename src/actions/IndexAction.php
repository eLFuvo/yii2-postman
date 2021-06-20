<?php
/**
 * Created by PhpStorm
 * User: elfuvo
 * Date: 2020-10-04
 * Time: 12:57
 */

namespace elfuvo\postman\actions;

use elfuvo\postman\jobs\MailerJob;
use elfuvo\postman\models\Message;
use elfuvo\postman\models\PostmanLogSearch;
use elfuvo\postman\processor\ProcessorInterface;
use elfuvo\postman\result\DatabaseContinuesResult;
use Yii;
use yii\base\Action;
use yii\di\Instance;
use yii\queue\Queue;
use yii\web\Controller;
use yii\web\Response;

/**
 * Class IndexAction
 * @package elfuvo\postman\actions
 */
class IndexAction extends Action
{
    /**
     * @var string
     */
    public $queue = 'queue';

    /**
     * @var string
     */
    public $view = '@elfuvo/postman/views/index';

    /**
     * @var string
     */
    public $progressAction = 'progress';

    /**
     * @var ProcessorInterface
     */
    protected $processor;

    /**
     * use or not job queue
     *
     * @var bool
     */
    public $useQueue = true;

    /**
     * SetupImportAction constructor.
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
     * @return string|Response
     * @throws \yii\base\InvalidConfigException
     */
    public function run()
    {
        $message = new Message();

        if ($message->load(Yii::$app->request->post()) && $message->validate()) {
            $this->processor->getCurrentResult()->resetBatch();
            foreach ($this->processor->getCollectors() as $collector) {
                $collector->load(Yii::$app->request->post());
            }
            $this->processor->setMessage($message);
            if ($this->useQueue !== true) {
                $this->processor->execute();
            } else {
                /** @var Queue $queue */
                $queue = Instance::ensure($this->queue, Queue::class);
                $this->processor->getCurrentResult()->resetBatch(); // unset previous result
                $this->processor->prepareExecute(); // save recipients list
                $queue->push(new MailerJob());
            }
            Yii::$app->session->addFlash('success', Yii::t('postman', 'Task for mailing added to the queue'));

            return $this->controller->redirect([$this->id]);
        }
        $dataProvider = $searchModel = null;
        if ($this->processor->getCurrentResult() instanceof DatabaseContinuesResult) {
            $searchModel = new PostmanLogSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        }
        // hack for test suit
        if (YII_ENV_TEST) {
            $this->controller->layout = false;
        }

        return $this->controller->render(
            $this->view,
            [
                'collectors' => $this->processor->getCollectors(),
                'message' => $message,
                'progressAction' => $this->progressAction,
                'dataProvider' => $dataProvider,
                'searchModel' => $searchModel,
                'result' => $this->processor->getCurrentResult(),
            ]
        );
    }
}
