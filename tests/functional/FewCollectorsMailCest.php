<?php

use app\collector\CsvFileCollector;
use elfuvo\postman\actions\IndexAction;
use elfuvo\postman\collector\TextInputCollector;
use elfuvo\postman\processor\MailAttachmentProcessor;
use elfuvo\postman\processor\ProcessorInterface;

/**
 * Class SimpleMailCest
 */
class FewCollectorsMailCest
{
    public function _before(FunctionalTester $I)
    {
        $result = Yii::createObject(\elfuvo\postman\result\ResultInterface::class);
        $result->resetBatch();
        Yii::$container->set(ProcessorInterface::class,
            [
                'class' => MailAttachmentProcessor::class,
                'collectors' => [
                    TextInputCollector::class,
                    CsvFileCollector::class,
                ],
            ]
        );
        Yii::$container->set(IndexAction::class, [
            'class' => IndexAction::class,
            'view' => '@root/src/views/index',
            'useQueue' => true,
        ]);
    }

    // tests

    /**
     * @param FunctionalTester $I
     */
    public function fewCollectorsMailTest(FunctionalTester $I)
    {
        // clean up queue before pushing import job
        $I->runShellCommand('/app/tests/app/yii queue/clear --interactive 0');

        $I->amOnPage('/default/index');
        $I->seeElement('.postman-form');
        $I->submitForm('form.postman-form', []);
        // no subject/message
        $I->seeElement('.has-error');

        $message = '<div style="background: url(/img/file.png)">Message <img src="/img/file.png" alt=""></div>';

        // fill required fields
        $I->submitForm('form.postman-form',
            [
                'Message[subject]' => 'Message subject',
                'Message[body]' => $message,
                'Message[template]' => '@root/src/mail/simple',
                'TextInputCollector[email]' => 'roman.lukhovtsev+rosmintrud1@gmail.com, some-fake@mail',
            ]
        );

        $I->dontSeeElement('.has-error');
        // job is in queue but not run
        $I->seeEmailIsSent(0);

        // see progress
        $I->amOnPage('/default/index');
        $I->seeElement('.progress-bar');

        // run queue
        $I->runShellCommand('/app/tests/app/yii queue/run');
        sleep(2);

        // see report
        $I->amOnPage('/default/index');
        $I->seeElement('.postman-progress-done');
        /**
         * @see \elfuvo\postman\collector\TextInputCollector::getRecipients()
         * @see \elfuvo\postman\collector\TextInputCollector::getWrongRecipients()
         */
        $I->assertEquals('3', trim($I->grabTextFrom('.letters-sent')));
        $I->assertEquals('1', trim($I->grabTextFrom('.letters-fail')));
    }
}
