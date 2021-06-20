<?php
/**
 * Created by PhpStorm
 * User: elfuvo
 * Date: 2020-10-05
 * Time: 12:14
 */

namespace elfuvo\postman\collector;

use JsonSerializable;
use yii\base\Model;
use yii\widgets\ActiveForm;

/**
 * Class AbstractCollector
 * @package elfuvo\postman\collector
 */
abstract class AbstractCollector extends Model implements CollectorInterface, JsonSerializable
{
    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return ['class' => static::class, 'attributes' => $this->toArray()];
    }

    /**
     * @inheritDoc
     */
    public function getActiveInput(ActiveForm $form): string
    {
        return '';
    }

    /**
     * @inheritDoc
     */
    public function getDetailViewAttributes(): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    abstract public function getRecipients(): ?array;
}
