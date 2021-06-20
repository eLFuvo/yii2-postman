<?php
/**
 * Created by PhpStorm
 * User: elfuvo
 * Date: 2020-10-04
 * Time: 12:57
 */

namespace elfuvo\postman\result;

/**
 * Interface ResultInterface
 * @package elfuvo\postman\result
 */
interface ResultInterface
{
    public const SENT_COUNTER = 'sent';
    public const SKIP_COUNTER = 'skip';

    /**
     * @param string $key
     * @return void
     */
    public function setKey(string $key);

    /**
     * @param string $error
     */
    public function addError(string $error): void;

    /**
     * @return array|null
     */
    public function getErrors(): array;

    /**
     * @return bool
     */
    public function hasErrors(): bool;

    /**
     * @param string $counter
     * @param int $count
     * @return bool
     */
    public function addCount($counter = ResultInterface::SENT_COUNTER, int $count = 1): bool;

    /**
     * @param string $counter
     * @return int
     */
    public function getCounter(string $counter): int;

    /**
     * @param int $total
     */
    public function setProgressTotal(int $total): void;

    /**
     * @return int
     */
    public function getProgressTotal(): int;

    /**
     * @return int
     */
    public function increaseProgressDone(): int;

    /**
     * @param int $done
     */
    public function setProgressDone(int $done): void;

    /**
     * @return int
     */
    public function getProgressDone(): int;

    /**
     * @param array|null $batch
     */
    public function setBatch($batch): void;

    /**
     * @return array|null
     */
    public function getLastBatch();

    /**
     * @return bool
     */
    public function resetBatch(): bool;
}
