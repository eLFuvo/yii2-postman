<?php
/**
 * Created by PhpStorm
 * User: elfuvo
 * Date: 2020-10-04
 * Time: 12:57
 */

namespace elfuvo\postman\result;

use elfuvo\postman\models\PostmanLog;
use Yii;

/**
 * Class DatabaseContinuesResult
 * @package elfuvo\postman\result
 */
class DatabaseContinuesResult extends AbstractResult
{
    /**
     * @var PostmanLog
     */
    protected $model;

    /**
     * @param array $list
     */
    public function setBatch($list): void
    {
        if (!$this->model) {
            $this->model = new PostmanLog([
                'total' => $this->getProgressTotal(),

            ]);
        }
        $isDone = $this->getProgressDone() >= $this->getProgressTotal();
        $this->model->setAttributes([
            'data' => $list,
            'status' => $isDone ? PostmanLog::STATUS_DONE : PostmanLog::STATUS_IN_PROCESS,
            'done' => $this->getProgressDone(),
            'counters' => $this->counters,
            'errors' => $this->getErrors(),
        ]);

        $this->model->save();
        $this->batch = $list;
    }

    /**
     * @return array|null
     */
    public function getLastBatch()
    {
        if (!$this->model) {
            $this->model = PostmanLog::find()->processing()->one();
        }
        if ($this->model && $this->model->data) {
            $batch = [];
            foreach ($this->model->data as $dataPartial) {
                if (isset($dataPartial['class'])) {
                    array_push($batch, Yii::createObject($dataPartial));
                } else {
                    array_push($batch, $dataPartial);
                }
            }
            $this->batch = $batch ?: null;
            $this->counters = $this->model->counters;
            $this->errors = $this->model->errors;
            $this->progressTotal = (int)$this->model->total;
            $this->progressDone = (int)$this->model->done;
        }

        return $this->batch;
    }

    /**
     * @return bool
     */
    public function resetBatch(): bool
    {
        PostmanLog::updateAll(['status' => PostmanLog::STATUS_PAST]);
        $this->model = null;
        parent::resetBatch();

        return true;
    }
}
