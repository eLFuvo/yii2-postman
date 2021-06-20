<?php
/**
 * Created by PhpStorm
 * User: elfuvo
 * Date: 2020-10-04
 * Time: 12:57
 */

namespace elfuvo\postman\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%postman_log}}".
 *
 * @property int $id
 * @property int $total
 * @property int $done
 * @property int $status
 * @property array $data
 * @property array $counters
 * @property array $errors
 * @property string $createdAt
 * @property string $updatedAt
 */
class PostmanLog extends ActiveRecord
{
    public const STATUS_IN_PROCESS = 0; // processing now
    public const STATUS_DONE = 1; // all letters has sent
    public const STATUS_PAST = 2; // forgot current result for statistic

    /**
     * @return array
     */
    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%postman_log}}';
    }

    /**
     * @return array|array[]
     */
    public function behaviors()
    {
        return [
            'TimestampBehavior' => [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'createdAt',
                'updatedAtAttribute' => 'updatedAt',
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['total', 'done', 'createdAt', 'updatedAt'], 'integer'],
            [['data', 'counters', 'errors'], 'safe'],
            [['status'], 'boolean'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'total' => 'Всего',
            'done' => 'Обработано',
            'status' => 'Статус выполнения',
            'data' => 'Очередь',
            'counters' => 'Счетчики',
            'errors' => 'Ошибки',
            'createdAt' => 'Создано',
            'updatedAt' => 'Обновлено',
        ];
    }

    /**
     * @inheritdoc
     * @return PostmanLogQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PostmanLogQuery(get_called_class());
    }

    /**
     * @return array
     */
    public static function getStatusList()
    {
        return [
            self::STATUS_IN_PROCESS => Yii::t('postman', 'Mailing in progress'),
            self::STATUS_DONE => Yii::t('postman', 'Last result'),
            self::STATUS_PAST => Yii::t('postman', 'Distribution is over'),
        ];
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return ArrayHelper::getValue(static::getStatusList(), $this->status, '');
    }

    /**
     * @return int
     */
    public function getExecutionTime()
    {
        return $this->updatedAt - $this->createdAt;
    }
}
