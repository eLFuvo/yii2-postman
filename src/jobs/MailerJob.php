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
use yii\di\Instance;
use yii\queue\RetryableJobInterface;

/**
 * Class MailerJob
 * @package elfuvo\postman\jobs
 *
 * @property-read int $ttr
 */
class MailerJob implements RetryableJobInterface
{
    /**
     * @var ProcessorInterface
     */
    protected $processor;

    /**
     * @return array|\elfuvo\postman\processor\ProcessorInterface|object|string
     * @throws \yii\base\InvalidConfigException
     */
    public function getProcessor()
    {
        if (!$this->processor) {
            $this->processor = Instance::ensure(ProcessorInterface::class, ProcessorInterface::class);
        }

        return $this->processor;
    }

    /**
     * @return int
     * @throws \yii\base\InvalidConfigException
     */
    public function getTtr(): int
    {
        return $this->getProcessor()->getTimeLimit();
    }

    /**
     * @param int $attempt
     * @param \Exception|\Throwable $error
     * @return bool
     */
    public function canRetry($attempt, $error): bool
    {
        return $error instanceof TimeLimitException;
    }

    /**
     * @param \yii\queue\Queue $queue
     * @throws \elfuvo\postman\exceptions\EmptyMessageException
     * @throws \elfuvo\postman\exceptions\MailTemplateMissingException
     * @throws \elfuvo\postman\exceptions\TimeLimitException
     * @throws \yii\base\InvalidConfigException
     */
    public function execute($queue)
    {
        return $this->getProcessor()->execute();
    }
}
