<?php
/**
 * Created by PhpStorm
 * User: elfuvo
 * Date: 2020-10-05
 * Time: 12:30
 */

use elfuvo\postman\result\ResultInterface;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/** @var $this yii\web\View */
/** @var $model \elfuvo\postman\models\PostmanLog */
/** @var $indexAction string */

$this->title = Yii::t('postman', 'Mailing') . ': ' . Yii::$app->formatter->asDatetime($model->createdAt);
?>
<div class="card">

    <div class="card-header">
        <h4 class="card-title"><?= Html::encode($this->title) ?></h4>
    </div>

    <div class="card-content">

        <?= DetailView::widget([
            'options' => ['class' => 'table'],
            'model' => $model,
            'attributes' => [
                'id',
                'total',
                'done',
                [
                    'attribute' => 'counters',
                    'value' => Yii::t('postman', 'Letters sent') . ': ' .
                        ArrayHelper::getValue($model->counters, ResultInterface::SENT_COUNTER, 0) . '; <br />' .
                        Yii::t('postman', 'Letters not sent') . ': ' .
                        ArrayHelper::getValue($model->counters, ResultInterface::SKIP_COUNTER, 0) . ';',
                    'format' => 'html',
                ],
                [
                    'label' => Yii::t('postman', 'Message text'),
                    'value' => $this->render('_message', ['batch' => $model->data]),
                    'format' => 'raw',
                ],
                [
                    'label' => Yii::t('postman', 'Collectors'),
                    'value' => $this->render('_collectors', ['batch' => $model->data]),
                    'format' => 'raw',
                ],
                'createdAt:datetime',
                'updatedAt:datetime',
            ],
        ]) ?>

    </div>
    <div class="card-footer">
        <a href="<?= Url::to([$indexAction]) ?>" class="btn btn-default"><?= Yii::t('yii', 'Home') ?></a>
    </div>
</div>
