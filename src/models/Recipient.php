<?php
/**
 * Created by PhpStorm
 * User: elfuvo
 * Date: 2020-10-04
 * Time: 12:57
 */

namespace elfuvo\postman\models;

use JsonSerializable;
use Yii;
use yii\base\Model;

/**
 * @property string $email
 * @property string $name
 *
 * Class Recipient
 * @package elfuvo\postman\models
 */
class Recipient extends Model implements JsonSerializable
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $email;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['email', 'name'], 'string'],
            [['email'], 'required'],
            [['email'], 'email'],
        ];
    }

    /**
     * @return array|string[]
     */
    public function attributeLabels()
    {
        return [
            'email' => Yii::t('postman', 'E-mail'),
            'name' => Yii::t('postman', 'Name'),
        ];
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return ['class' => self::class, 'attributes' => $this->toArray()];
    }
}
