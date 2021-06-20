<?php
/**
 * Created by PhpStorm
 * User: elfuvo
 * Date: 2020-10-04
 * Time: 12:57
 */

namespace elfuvo\postman\result;

/**
 * Class AbstractResult
 * @package elfuvo\postman\result
 */
abstract class AbstractResult implements ResultInterface
{
    public const ERRORS_LIMIT = 100;

    /**
     * @var string
     */
    protected $key = 'postman-result';

    /**
     * @var array
     */
    protected $errors = [];

    /**
     * @var int
     */
    protected $progressDone = 0;

    /**
     * @var int
     */
    protected $progressTotal = 0;

    /**
     * @var array|null
     */
    protected $batch;

    /**
     * @var array
     */
    protected $counters = [
        ResultInterface::SENT_COUNTER => 0,
        ResultInterface::SKIP_COUNTER => 0,
    ];

    /**
     * @param string $key
     */
    public function setKey(string $key)
    {
        $this->key = $key;
    }

    /**
     * @param string $counter
     * @param int $count
     * @return bool
     */
    public function addCount($counter = ResultInterface::SENT_COUNTER, int $count = 1): bool
    {
        if (!in_array($counter, [
            ResultInterface::SENT_COUNTER,
            ResultInterface::SKIP_COUNTER,
        ])) {
            return false;
        }

        $this->counters[$counter] += $count;

        return true;
    }

    /**
     * @param string $counter
     * @return int
     */
    public function getCounter(string $counter): int
    {
        return $this->counters[$counter] ?? 0;
    }

    /**
     * @param string $error
     */
    public function addError(string $error): void
    {
        array_push($this->errors, $error);
        if (count($this->errors) > static::ERRORS_LIMIT) {
            array_shift($this->errors); // there is too many errors
        }
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @return bool
     */
    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }

    /**
     * @param int $total
     */
    public function setProgressTotal(int $total): void
    {
        $this->progressTotal = $total;
    }

    /**
     * @return int
     */
    public function getProgressTotal(): int
    {
        return $this->progressTotal;
    }

    /**
     * @return int
     */
    public function increaseProgressDone(): int
    {
        $this->progressDone++;
        if ($this->progressDone > $this->progressTotal) {
            $this->progressDone = $this->progressTotal;
        }
        return $this->progressDone;
    }

    /**
     * @param int $done
     */
    public function setProgressDone(int $done): void
    {
        $this->progressDone = $done;
    }

    /**
     * @return int
     */
    public function getProgressDone(): int
    {
        return $this->progressDone;
    }

    /**
     * @param array $batch
     */
    public function setBatch($batch): void
    {
        $this->batch = $batch;
    }

    /**
     * @return array|null
     */
    public function getLastBatch()
    {
        return $this->batch;
    }

    /**
     * @return bool
     */
    public function resetBatch(): bool
    {
        // reset all result data
        $this->batch = null;
        $this->counters = [
            ResultInterface::SENT_COUNTER => 0,
            ResultInterface::SKIP_COUNTER => 0,
        ];
        $this->errors = [];
        $this->progressTotal = 0;
        $this->progressDone = 0;

        return true;
    }
}
