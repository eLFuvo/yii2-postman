<?php
/**
 * Created by PhpStorm
 * User: elfuvo
 * Date: 2020-10-04
 * Time: 12:57
 */

namespace elfuvo\postman\processor;

use elfuvo\postman\collector\CollectorInterface;
use elfuvo\postman\exceptions\EmptyMessageException;
use elfuvo\postman\exceptions\MailTemplateMissingException;
use elfuvo\postman\models\Message;
use elfuvo\postman\result\ResultInterface;
use Yii;
use yii\base\BaseObject;
use yii\mail\MailerInterface;

/**
 * Class AbstractProcessor
 * @package elfuvo\postman\processor
 *
 * @property-read \elfuvo\postman\result\ResultInterface $currentResult
 */
abstract class AbstractProcessor extends BaseObject implements ProcessorInterface
{
    /**
     * @var int
     */
    public $timeLimit = 600;// execution time limit in seconds

    /**
     * @var int
     */
    protected $timeStart = 0;

    /**
     * @var Message
     */
    protected $message;

    /**
     * @var CollectorInterface[]
     */
    protected $collectors = [];

    /**
     * @var MailerInterface
     */
    protected $postman;

    /**
     * @var ResultInterface
     */
    protected $result;

    /**
     * MailProcessor constructor.
     * @param ResultInterface $result
     * @param array $config
     */
    public function __construct(ResultInterface $result, $config = [])
    {
        parent::__construct($config);
        $this->postman = Yii::$app->getMailer();
        $this->result = $result;
    }

    /**
     * @param array $collectors
     */
    public function setCollectors(array $collectors)
    {
        foreach ($collectors as $collector) {
            if ($collector instanceof CollectorInterface) {
                $this->addCollector($collector);
            } else {
                $this->addCollector(Yii::createObject($collector));
            }
        }
    }

    /**
     * @param CollectorInterface $collector
     * @return void
     */
    public function addCollector(CollectorInterface $collector)
    {
        array_push($this->collectors, $collector);
    }

    /**
     * @return array|CollectorInterface[]
     */
    public function getCollectors(): array
    {
        return $this->collectors;
    }

    /**
     * @inheritDoc
     */
    public function setMessage(Message $message): ProcessorInterface
    {
        $path = Yii::getAlias($message->template);
        $path .= strstr($path, '.php') ? '' : '.php';
        if (!is_file($path)) {
            throw new MailTemplateMissingException('Mail template not found at "' . $path . '"');
        }
        if (empty($message->subject)) {
            throw new EmptyMessageException('Message subject must be set');
        }
        if (empty($message->subject)) {
            throw new EmptyMessageException('Message body must be set');
        }

        $this->message = $message;

        return $this;
    }

    /**
     * @inheritDoc
     */
    abstract public function execute(): ResultInterface;

    /**
     * @inheritDoc
     */
    public function getCurrentResult(): ResultInterface
    {
        // load last result data
        $this->result->getLastBatch();
        return $this->result;
    }

    /**
     * @return int
     */
    public function getTimeLimit(): int
    {
        return $this->timeLimit;
    }

    /**
     * @throws EmptyMessageException
     */
    public function prepareExecute()
    {
        $this->timeStart = time();
        if (([$message, $recipients] = $this->result->getLastBatch()) === null) {
            $recipients = $this->getRecipients();
            $this->result->setProgressTotal(count($recipients));
            $this->result->setProgressDone(0);
            $this->result->setBatch([$this->message, $recipients, $this->collectors]);
        } else {
            // load message from cache
            $this->setMessage($message);
        }

        if (!$this->message) {
            throw new EmptyMessageException('Message must be set');
        }

        return $recipients;
    }

    /**
     * @return string[]
     */
    protected function getRecipients(): array
    {
        $recipients = [];
        foreach ($this->collectors as $collector) {
            if ($partial = $collector->getRecipients()) {
                foreach ($partial as $recipient) {
                    // skip recipients that already fully defined
                    if (isset($recipients[$recipient->email]) && !empty($recipients[$recipient->email])) {
                        continue;
                    }
                    // check against valid email address
                    if (!filter_var($recipient->email, FILTER_VALIDATE_EMAIL)) {
                        continue;
                    }
                    $recipients[$recipient->email] = $recipient->name;
                }
            }
        }

        return $recipients;
    }
}
