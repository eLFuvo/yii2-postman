<?php

namespace elfuvo\postman\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * MailerLogSearch represents the model behind the search form about `app\modules\event\models\MailerLog`.
 */
class PostmanLogSearch extends PostmanLog
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = PostmanLog::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            PostmanLog::tableName() . '.[[id]]' => $this->id,
            PostmanLog::tableName() . '.[[status]]' => $this->status,
        ]);

        $query->andFilterWhere(['like', PostmanLog::tableName() . '.[[createdAt]]', $this->createdAt]);

        return $dataProvider;
    }
}
