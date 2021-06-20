<?php
/**
 * Created by PhpStorm
 * User: elfuvo
 * Date: 2020-10-05
 * Time: 12:33
 */

use elfuvo\postman\models\Message;
use yii\widgets\DetailView;

/** @var $this yii\web\View */
/** @var $batch array */
$model = null;
foreach ($batch as $partialData) {
    if (isset($partialData['class'])) {
        $model = Yii::createObject($partialData);
        if ($model instanceof Message) {
            break;
        } else {
            $model = null;
        }
    }
}
?>
<?php
if ($model): ?>
    <?= DetailView::widget([
        'options' => ['class' => 'table'],
        'model' => $model,
        'attributes' => [
            'subject',
            'body:html',
            'template',
        ],
    ]) ?>
<?php
endif; ?>
