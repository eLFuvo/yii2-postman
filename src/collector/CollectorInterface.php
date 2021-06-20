<?php
/**
 * Created by PhpStorm
 * User: elfuvo
 * Date: 2020-10-04
 * Time: 12:57
 */

namespace elfuvo\postman\collector;

use elfuvo\postman\models\Recipient;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;

/**
 * Interface CollectorInterface
 * @package elfuvo\postman\collector
 */
interface CollectorInterface
{
    /**
     * @param ActiveForm $form
     * @return string
     */
    public function getActiveInput(ActiveForm $form): string;

    /**
     * @return Recipient[]|null
     */
    public function getRecipients(): ?array;

    /**
     * @param array $data
     * @param null|string $formName
     * @return bool
     */
    public function load($data, $formName = null);

    /**
     * @return array
     * @see DetailView::normalizeAttributes()
     */
    public function getDetailViewAttributes(): array;
}
