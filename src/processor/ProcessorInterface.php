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
use elfuvo\postman\exceptions\TimeLimitException;
use elfuvo\postman\models\Message;
use elfuvo\postman\result\ResultInterface;

/**
 * Interface ProcessorInterface
 * @package elfuvo\postman\processor
 */
interface ProcessorInterface
{
    /**
     * @param CollectorInterface $collector
     * @return void
     */
    public function addCollector(CollectorInterface $collector);

    /**
     * @return CollectorInterface[]
     */
    public function getCollectors(): array;

    /**
     * @param Message $message
     * @return ProcessorInterface
     * @throws MailTemplateMissingException
     * @throws EmptyMessageException
     */
    public function setMessage(Message $message): ProcessorInterface;

    /**
     * @return ResultInterface
     * @throws TimeLimitException
     * @throws EmptyMessageException
     * @throws MailTemplateMissingException
     */
    public function execute(): ResultInterface;

    /**
     * @return ResultInterface
     */
    public function getCurrentResult(): ResultInterface;

    /**
     * @return int
     */
    public function getTimeLimit(): int;
}
