<?php

use elfuvo\postman\result\ResultInterface;

/**
 * Class SimpleMailCest
 */
class SimpleMailCest
{
    public function _before(FunctionalTester $I)
    {
        $result = Yii::createObject(ResultInterface::class);
        $result->resetBatch();
    }

    // tests

    /**
     * @param FunctionalTester $I
     */
    public function simpleMailTest(FunctionalTester $I)
    {
        $I->amOnPage('/default/index');
        $I->seeElement('.postman-form');
        $I->submitForm('form.postman-form', []);
        // no subject/message
        $I->seeElement('.has-error');

        // fill required fields
        $I->submitForm('form.postman-form',
            [
                'Message[subject]' => 'Message subject',
                'Message[body]' => 'Simple message',
                'Message[template]' => '@root/src/mail/simple',
                'TextInputCollector[email]' => 'roman.lukhovtsev+rosmintrud1@gmail.com, some-fake@mail',

            ]
        );
        $I->dontSeeElement('.has-error');
        $I->seeEmailIsSent(1);

        // see report
        $I->amOnPage('/default/index');
        $I->seeElement('.postman-progress-done');
        /**
         * only 1 letter is sent
         * @see \elfuvo\postman\collector\TextInputCollector::getRecipients()
         * @see \elfuvo\postman\collector\TextInputCollector::getWrongRecipients()
         */
        $I->assertEquals('1', trim($I->grabTextFrom('.postman-progress-done .letters-sent')));
        $I->assertEquals('1', trim($I->grabTextFrom('.postman-progress-done .letters-fail')));
    }
}
