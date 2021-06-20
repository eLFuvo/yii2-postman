<?php
/**
 * Created by PhpStorm
 * User: elfuvo
 * Date: 2020-10-04
 * Time: 12:57
 */

namespace elfuvo\postman\models;

use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[MailerLog]].
 *
 * @see PostmanLog
 */
class PostmanLogQuery extends ActiveQuery
{
    /**
     * @inheritdoc
     * @return PostmanLog[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return PostmanLog|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /**
     * @return PostmanLogQuery
     */
    public function processing(): PostmanLogQuery
    {
        return $this->andWhere([
                'OR',
                [PostmanLog::tableName() . '.[[status]]' => PostmanLog::STATUS_IN_PROCESS],
                [PostmanLog::tableName() . '.[[status]]' => PostmanLog::STATUS_DONE],
            ]
        );
    }
}
