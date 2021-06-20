<?php
/**
 * Created by PhpStorm
 * User: elfuvo
 * Date: 2020-10-04
 * Time: 14:00
 */

namespace elfuvo\postman\processor;

use DOMDocument;
use DOMXPath;
use elfuvo\postman\exceptions\TimeLimitException;
use elfuvo\postman\result\ResultInterface;
use Exception;
use Yii;
use yii\mail\MessageInterface;
use yii\swiftmailer\Message;

/**
 * Class MailAttachmentProcessor
 * @package app\modules\postman\processor
 */
class MailAttachmentProcessor extends AbstractProcessor
{
    /**
     * @var array
     */
    protected $images = [];

    /**
     * @inheritDoc
     * @throws \yii\base\InvalidConfigException
     */
    public function execute(): ResultInterface
    {
        $recipients = $this->prepareExecute();
        if ($recipients) {
            $content = Yii::$app->getMailer()->render(
                $this->message->template,
                [
                    'content' => $this->message->body,
                ]
            );
            $content = $this->collectImages($content);

            $emailMessage = Yii::createObject(Message::class);
            $emailMessage->setSubject($this->message->subject);
            $emailMessage->setHtmlBody($this->attach($emailMessage, $content));

            /**
             * @see AbstractProcessor::getRecipients()
             */
            foreach ($recipients as $email => $name) {
                try {
                    $sent = $emailMessage->setTo($name > '' ? [$email => $name] : $email)
                        ->send(Yii::$app->getMailer());
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

    /**
     * @param string $content
     * @return string|string[]|null
     */
    protected function collectImages(string $content)
    {
        if (strip_tags($content) == $content) { // no html content - nothing to parse
            return $content;
        }
        $doc = new DOMDocument();
        $doc->loadHTML($content); //load the string into DOMDocument
        $selector = new DOMXPath($doc); //create a new domxpath instance
        // simple images
        $images = $selector->query('//img/@src'); //Query the image tag and get the src
        foreach ($images as $index => $item) {
            $this->images['image' . $index] = $item->value;
            // replace src url to it placeholder
            $content = preg_replace(
                '#' . preg_quote($item->value, '#') . '#i',
                '{image' . $index . '}',
                $content
            );
        }
        // backgrounds
        // get all elements with style attribute
        $nodes = $selector->query('//*/@style');
        foreach ($nodes as $index => $item) {
            // if style contains url('/some.jpg')
            if (preg_match('#url\(\'?\"?([^\'\"\)]+)#i', $item->value, $matches)) {
                $this->images['bg' . $index] = $matches[1];
                $content = preg_replace(
                    '#' . preg_quote($matches[1], '#') . '#i',
                    '{bg' . $index . '}',
                    $content
                );
            }
        }
        unset($doc, $selector, $images, $nodes);

        return $content;
    }

    /**
     * @param MessageInterface $message
     * @param string $messageBody
     * @return string
     */
    protected function attach(MessageInterface $message, string $messageBody): string
    {
        if ($this->images) {
            foreach ($this->images as $placeholder => $image) {
                if (preg_match('#(.+)#i', $image, $matches)) {
                    $path = Yii::getAlias('@webroot/' . $matches[1]);
                    if (is_file($path)) {
                        $cid = $message->embed($path);
                        $messageBody = preg_replace('#\{' . $placeholder . '\}#', $cid, $messageBody);
                    } else {
                        $messageBody = preg_replace('#\{' . $placeholder . '\}#', '', $messageBody);
                    }
                }
            }
        }
        return $messageBody;
    }
}
