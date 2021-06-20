<?php
/**
 * Created by PhpStorm
 * User: elfuvo
 * Date: 2020-10-04
 * Time: 12:58
 */

use elfuvo\postman\models\PostmanLog;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\grid\SerialColumn;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/** @var $this yii\web\View */
/** @var $message \elfuvo\postman\models\Message */
/** @var $collectors \elfuvo\postman\collector\CollectorInterface[] */
/** @var $progressAction string */
/** @var $searchModel \elfuvo\postman\models\PostmanLogSearch|null */
/** @var $dataProvider \yii\data\ActiveDataProvider|null */
/** @var $result \elfuvo\postman\result\ResultInterface */

$this->title = Yii::t('postman', 'Mailing');
?>
<div class="card">

    <div class="card-header">
        <h4 class="card-title"><?= Html::encode($this->title) ?></h4>
    </div>

    <div class="postman-progress-container" data-url="<?= Url::to([$progressAction]) ?>">
        <?= $this->render('progress', ['result' => $result]); ?>
    </div>

    <?php
    if ($dataProvider): ?>
        <div class="card-body">

            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    [
                        'class' => ActionColumn::class,
                        'template' => '{view}',
                    ],
                    [
                        'class' => SerialColumn::class,
                    ],
                    'id',
                    [
                        'attribute' => 'status',
                        'value' => function (PostmanLog $model) {
                            return $model->getStatus();
                        },
                        'filter' => PostmanLog::getStatusList(),
                    ],
                    'createdAt',
                ],
            ]); ?>
        </div>
    <?php
    endif; ?>

    <div class="card-content">
        <?php
        $form = ActiveForm::begin(['options' => ['class' => 'postman-form']]); ?>

        <?= $form->field($message, 'subject')->textInput(); ?>

        <?= $form->field($message, 'body')->textarea(); ?>

        <?= $form->field($message, 'template')->label(false); ?>

        <?php
        foreach ($collectors as $collector): ?>
            <?= $collector->getActiveInput($form); ?>
        <?php
        endforeach; ?>

        <div class="form-group">
            <?= Html::submitButton(Yii::t('postman', 'Send'),
                ['class' => 'btn btn-primary']) ?>
        </div>
        <?php
        ActiveForm::end(); ?>
    </div>
</div>
