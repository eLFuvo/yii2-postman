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
 * @property string $subject
 * @property string $body
 * @property string $template
 *
 * Class Message
 * @package elfuvo\postman\models
 */
class Message extends Model implements JsonSerializable
{
    /**
     * @var string
     */
    public $subject;

    /**
     * @var string
     */
    public $body;

    /**
     * @var string
     */
    public $template = '@elfuvo/postman/mail/simple';

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['subject'], 'string', 'max' => 255],
            [['body', 'template'], 'string'],
            [['body', 'template', 'subject'], 'required'],
        ];
    }

    /**
     * @return array|string[]
     */
    public function attributeLabels()
    {
        return [
            'subject' => Yii::t('postman', 'Subject'),
            'body' => Yii::t('postman', 'Message text'),
            'template' => Yii::t('postman', 'Template'),
        ];
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return ['class' => self::class, 'attributes' => $this->toArray()];
    }
}
