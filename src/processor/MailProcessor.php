<?php
/**
 * Created by PhpStorm
 * User: elfuvo
 * Date: 2020-10-04
 * Time: 12:57
 */

namespace elfuvo\postman\processor;

use elfuvo\postman\exceptions\TimeLimitException;
use elfuvo\postman\result\ResultInterface;
use Exception;

/**
 * Class MailProcessor
 * @package elfuvo\postman\processor
 */
class MailProcessor extends AbstractProcessor
{
    /**
     * @inheritDoc
     */
    public function execute(): ResultInterface
    {
        $recipients = $this->prepareExecute();
        if ($recipients) {
            $emailMessage = $this->postman->compose(
                $this->message->template,
                ['content' => $this->message->body]
            )->setSubject($this->message->subject);
            /**
             * @see AbstractProcessor::getRecipients()
             */
            foreach ($recipients as $email => $name) {
                try {
                    $sent = $emailMessage->setTo($name > '' ? [$email => $name] : $email)
                        ->send($this->postman);
                    $this->result->addCount($sent ?
                        ResultInterface::SENT_COUNTER : ResultInterface::SKIP_COUNTER);
                } catch (Exception $e) {
                    $this->result->addCount(ResultInterface::SKIP_COUNTER);
                    $this->result->addError($e->getMessage());
                }
                $this->result->increaseProgressDone();
                unset($recipients[$email]);
                // abort execution by time limit
                if ((time() - $this->timeStart) >= $this->timeLimit) {
                    $this->result->setBatch([$this->message, $recipients, $this->getCollectors()]);
                    throw new TimeLimitException('Runtime exceeded');
                }
            }
            // save results
            $this->result->setBatch([$this->message, $recipients, $this->getCollectors()]);
        }

        return $this->result;
    }
}
