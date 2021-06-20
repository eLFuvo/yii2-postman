<?php
/**
 * Created by PhpStorm
 * User: elfuvo
 * Date: 2020-10-04
 * Time: 12:57
 */

namespace elfuvo\postman\jobs;

use elfuvo\postman\exceptions\TimeLimitException;
use elfuvo\postman\processor\ProcessorInterface;
use yii\base\BaseObject;
use yii\queue\RetryableJobInterface;

/**
 * Class MailerJob
 * @package elfuvo\postman\jobs
 *
 * @property-read int $ttr
 */
class MailerJob extends BaseObject implements RetryableJobInterface
{
    /**
     * @var ProcessorInterface
     */
    protected $processor;

    /**
     * MailerJob constructor.
     * @param ProcessorInterface $processor
     * @param array $config
     */
    public function __construct(ProcessorInterface $processor, $config = [])
    {
        $this->processor = $processor;
        parent::__construct($config);
    }

    /**
     * @return int
     */
    public function getTtr()
    {
        return $this->processor->getTimeLimit();
    }

    /**
     * @param int $attempt
     * @param \Exception|\Throwable $error
     * @return bool|void
     */
    public function canRetry($attempt, $error)
    {
        return $error instanceof TimeLimitException;
    }

    /**
     * @param \yii\queue\Queue $queue
     */
    public function execute($queue)
    {
        $this->processor->execute();
    }
}
