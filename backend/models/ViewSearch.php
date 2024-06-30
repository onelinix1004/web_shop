<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\View;

class ViewsSearch extends View
{
    public function rules()
    {
        return [
            [['id', 'product_id', 'count'], 'integer'],
        ];
    }

    public function scenarios()
{
    return parent::scenarios();
}


    public function search($params)
    {
        $query = View::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'product_id' => $this->product_id,
            'count' => $this->count,
        ]);

        return $dataProvider;
    }
}