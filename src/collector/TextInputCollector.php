<?php
/**
 * Created by PhpStorm
 * User: elfuvo
 * Date: 2020-10-04
 * Time: 12:57
 */

namespace elfuvo\postman\collector;

use elfuvo\postman\models\Recipient;
use Yii;
use yii\widgets\ActiveForm;

/**
 * Class TextCollector
 * @package elfuvo\postman\collector
 *
 * @property-read null|Recipient[] $recipients
 */
class TextInputCollector extends AbstractCollector
{
    /**
     * @var string
     */
    public $email;

    /**
     * @param ActiveForm $form
     * @return string
     */
    public function getActiveInput(ActiveForm $form): string
    {
        return $form->field($this, 'email')->textInput();
    }

    /**
     * @inheritDoc
     */
    public function getDetailViewAttributes(): array
    {
        return [
            'email',
        ];
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            ['email', 'string'],
        ];
    }

    /**
     * @return Recipient[]|null
     */
    public function getRecipients(): ?array
    {
        if ($this->email && preg_match_all('#([^\s,;]+)#', $this->email, $matches)) {
            $emails = array_map(function ($email) {
                return new Recipient(['email' => $email]);
            }, $matches[1]);

            return array_filter($emails, function (Recipient $recipient) {
                return $recipient->validate();
            });
        }

        return null;
    }


    /**
     * @return array|string[]
     */
    public function attributeLabels()
    {
        return [
            'email' => Yii::t('postman', 'List of E-mail addresses separated by commas'),
        ];
    }
}
