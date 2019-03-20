<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

class AdminNews extends Model
{
    public $id;
    public $userId;
    public $suId;
    public $date;
    public $ticker;
    public $currency;
    public $sourceDate;
    public $eventDate;
    public $sourceLink;
    public $twitter;
    public $medium;
    public $cmc;
    public $comment;
    public $rate;
    public $chartLink;
    public $publicDate;
    public $publicRate;

    public function rules()
    {
        return [
            [['id', 'userId', 'suId', 'date', 'ticker', 'currency', 'sourceDate', 'eventDate', 'sourceLink', 'twitter', 'medium', 'cmc', 'comment', 'rate', 'chartLink', 'publicDate', 'publicRate'], 'safe'],
        ];
    }

    public function search($params)
    {
        $query = News::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['date' => SORT_DESC]],
            'pagination' => ['pageSize' => 10],
        ]);

        if (!($this->load($params) && $this->validate())) {

            return $dataProvider;
        }

        if ($this->userId != null && $user = User::find()->where(['LIKE', 'username', $this->userId])->all()) {
            foreach ($user as $i) $query->orWhere(['userId' => $i->id]);
        }

        if ($this->suId != null && $su = User::find()->where(['LIKE', 'username', $this->suId])->all()) {
            foreach ($su as $i) $query->orWhere(['suId' => $i->id]);
        }

        if ($this->publicDate) {
            $query->andWhere(['LIKE', 'publicDate', $this->publicDate]);
        }

        $query->andWhere(['LIKE', 'id', $this->id]);
        $query->andWhere(['LIKE', 'date', $this->date]);
        $query->andWhere(['LIKE', 'ticker', $this->ticker]);
        $query->andWhere(['LIKE', 'currency', $this->currency]);
        $query->andWhere(['LIKE', 'sourceDate', $this->sourceDate]);
        $query->andWhere(['LIKE', 'eventDate', $this->eventDate]);
        $query->andWhere(['LIKE', 'sourceLink', $this->sourceLink]);
        $query->andWhere(['LIKE', 'comment', $this->comment]);
        $query->andWhere(['LIKE', 'rate', $this->rate]);
        $query->andWhere(['LIKE', 'chartLink', $this->chartLink]);
        $query->andWhere(['LIKE', 'publicRate', $this->publicRate]);

        if ($this->twitter != null) $query->andWhere(['twitter' => $this->twitter]);
        if ($this->medium != null) $query->andWhere(['medium' => $this->medium]);
        if ($this->cmc != null) $query->andWhere(['cmc' => $this->cmc]);

        return $dataProvider;
    }
}