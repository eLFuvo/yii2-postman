<?php
/**
 * Created by PhpStorm
 * User: elfuvo
 * Date: 2020-10-05
 * Time: 12:51
 */

use elfuvo\postman\collector\AbstractCollector;
use yii\widgets\DetailView;

/** @var $this yii\web\View */
/** @var $batch array */
$list = [];
foreach ($batch as $partialData) {
    if (is_array($partialData)
        && !empty($partialData)
        && isset($partialData[0]['class'])
    ) {
        foreach ($partialData as $collector) {
            $model = Yii::createObject($collector);
            if (!$model instanceof AbstractCollector) {
                continue;
            }
            /** @var AbstractCollector $model */
            array_push($list, $model);
        }
    }
}

if ($list):
    foreach ($list as $model):?>
        <?= DetailView::widget([
            'options' => ['class' => 'table'],
            'model' => $model,
            'attributes' => $model->getDetailViewAttributes(),
        ]) ?>
    <?php
    endforeach;
endif; ?>
