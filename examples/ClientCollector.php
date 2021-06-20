<?php
/**
 * Created by PhpStorm
 * User: elfuvo
 * Date: 2020-10-05
 * Time: 13:16
 */


use elfuvo\postman\collector\AbstractCollector;
use elfuvo\postman\models\Recipient;
use app\modules\cabinet\models\Client;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

/**
 * Class ClientCollector
 * @package app\modules\postman\collectors
 */
class ClientCollector extends AbstractCollector
{
    /**
     * @var array
     */
    public $clientId = [];

    /**
     * @return array|array[]
     */
    public function rules()
    {
        return [
            [
                ['clientId'],
                'each',
                'rule' => ['integer']
            ],
        ];
    }

    /**
     * @return array|string[]
     */
    public function attributeLabels()
    {
        return [
            'clientId' => 'Пользователи',
        ];
    }

    /**
     * @inheritDoc
     */
    public function getRecipients(): ?array
    {
        if ($this->clientId) {
            $clients = Client::find()
                ->select([
                    'surname',
                    'name',
                    'patronymic',
                    'email',
                ])
                ->where(['id' => $this->clientId])
                ->blocked()
                ->andWhere(['>', 'email', ''])
                ->limit(500)
                ->all();

            return array_map(function (Client $client) {
                return new Recipient([
                    'email' => $client->email,
                    'name' => $client->fullname,
                ]);
            }, $clients);
        }

        return null;
    }

    /**
     * @param ActiveForm $form
     * @return string
     */
    public function getActiveInput(ActiveForm $form): string
    {
        return $form->field($this, 'clientId')->dropDownList(
            $this->getList(),
            [
                'multiple' => true,
            ]
        );
    }

    /**
     * @return array|array[]
     */
    public function getDetailViewAttributes(): array
    {
        return [
            [
                'attribute' => 'clientId',
                'value' => $this->getClientListAsString(),
            ],
        ];
    }

    /**
     * @param array|null $id
     * @return array
     */
    protected function getList(array $id = null): array
    {
        $clients = Client::find()
            ->select([
                'email',
                'surname',
                'name',
                'patronymic',
                'id',
            ])
            ->blocked()
            ->andWhere(['>', 'email', ''])
            ->andFilterWhere(['id' => $id])
            ->orderBy(['surname' => SORT_ASC, 'email' => SORT_ASC])
            ->indexBy('id')
            ->limit(500)
            ->asArray()
            ->all();

        return ArrayHelper::map($clients, 'id', function ($row) {
            return implode(
                    ' ',
                    array_filter([
                        $row['surname'],
                        $row['name'],
                        $row['patronymic'],
                    ])
                ) . ' (' . $row['email'] . ')';
        });
    }

    /**
     * @return string
     */
    public function getClientListAsString(): string
    {
        if (empty($this->clientId)) {
            return '';
        }
        return implode(', ', $this->getList($this->clientId));
    }

    /**
     * @return array|null
     */
    public function getWrongRecipients(): ?array
    {
        return null;
    }
}
