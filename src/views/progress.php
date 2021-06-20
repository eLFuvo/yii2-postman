<?php
/**
 * Created by PhpStorm
 * User: elfuvo
 * Date: 2020-10-04
 * Time: 12:57
 */

/** @var yii\web\View $this */

/** @var \elfuvo\postman\result\ResultInterface $result */

?>
<?php
if ($result->getProgressTotal() && $result->getProgressDone() < $result->getProgressTotal()):
    $percentDone = $result->getProgressDone() > 0 ?
        round($result->getProgressDone() / $result->getProgressTotal() * 100) : 1;
    ?>
    <div class="postman-progress-stat card-content">
        <div class="well">
            <p><?= Yii::t('postman', 'Mailing in progress'); ?>...</p>
            <p><?= Yii::t('postman', 'Processed'); ?>:
                <?= $result->getProgressDone(); ?> / <?= $result->getProgressTotal(); ?></p>
            <div class="progress">
                <div class="progress-bar" role="progressbar" aria-valuenow="<?= $percentDone; ?>"
                     aria-valuemin="0"
                     aria-valuemax="100"
                     style="width: <?= $percentDone; ?>%">
                    <span class="sr-only"></span>
                </div>
            </div>
        </div>
    </div>
<?php
elseif ($result->getProgressTotal() && $result->getProgressDone() >= $result->getProgressTotal()): ?>
    <div class="card-content postman-progress-stat postman-progress-done">
        <div class="well">
            <p><?= Yii::t('postman', 'Distribution is over'); ?></p>
            <p><?= Yii::t('postman', 'Mailing statistic'); ?>: <br/>
                <span><?= Yii::t('postman', 'Letters sent'); ?>:
                    <?= $result->getCounter($result::SENT_COUNTER); ?></span><br/>
                <span><?= Yii::t('postman', 'Letters not sent'); ?>:
                    <?= $result->getCounter($result::SKIP_COUNTER); ?></span><br/>
            </p>
        </div>
        <?php
        if ($result->hasErrors()): ?>
            <div class="alert alert-danger">
                <p>
                    <?= Yii::t('postman', 'Errors'); ?>:
                </p>
                <p>
                    <?= implode('<br />', $result->getErrors()); ?>
                </p>
            </div>
        <?php
        endif; ?>
    </div>
<?php
endif; ?>
