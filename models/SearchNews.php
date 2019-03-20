<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

class SearchNews extends Model
{


    public function rules()
    {
        return [
        ];
    }

    public function search($params)
    {
        $query = News::find()->where(['<>', 'publicDate', 0]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['date' => SORT_DESC]]
        ]);

        if (!($this->load($params) && $this->validate())) {

            return $dataProvider;
        }

        return $dataProvider;
    }
}