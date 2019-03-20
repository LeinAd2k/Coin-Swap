<?php

namespace app\models;

use Yii;
use yii\helpers\Json;

class News extends \yii\db\ActiveRecord
{
    public $user;
    public $su;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['medium', 'cmc', 'twitter'], 'boolean'],
            [['ticker'], 'string', 'max' => 15],
            [['currency', 'sourceDate', 'eventDate', 'publicDate'], 'string', 'max' => 32],
            [['sourceLink', 'chartLink'], 'string', 'max' => 150],
            [['comment'], 'string', 'max' => 1000],
            [['publicRate', 'rate'], 'integer', 'max' => 100],
        ];
    }

    public static function create($model)
    {
        $news = new News();
        $news->userId = $model->userId;
        $news->date = date('Y-m-d H:i:s');
        $news->sourceLink = $model->sourceLink;
        $news->ticker = $model->ticker;
        $news->currency = $model->currency;
        $news->sourceDate = $model->sourceDate;
        $news->eventDate = $model->eventDate;
        $news->twitter = $model->twitter;
        $news->medium = $model->medium;
        $news->cmc = $model->cmc;
        $news->rate = $model->rate;
        $news->comment = $model->comment;
        $news->save();

        return true;
    }

    public static function publish($news)
    {
        $news->publicDate = date('Y-m-d H:i:s');
        $news->suId = Yii::$app->user->identity->id;
        $news->save();

        return true;
    }

    public static function edit($key, $posted)
    {
        $model = News::findOne($key);
        $out = Json::encode(['output' => '', 'message' => '']);
        $post = ['News' => $posted];
        if ($model->load($post)) {
            $model->save();
            if (isset($posted['chartLink'])) {
                $model->user = User::findOne($model->userId)->username;
                $model->su = User::findOne($model->suId)->username;
                self::mailUpdated($model);
            }
            $output = '';
            $out = Json::encode(['output' => $output, 'message' => '']);
        }
        return $out;
    }

    public static function mailCreated($news)
    {

        $users = User::find()->where(['>=', 'access', '150'])->all();

        foreach ($users as $user) {
            Yii::$app->mailer->compose('created_news_mail', [
                'model' => $news,
            ])
                ->setTo($user->email)
                ->setFrom(['news@icrypto.cash' => 'iCryptoNews'])
                ->setSubject('News have been created!')
                ->send();
        }

    }

    public static function mailPublished($news)
    {

        $users = User::find()->where(['>=', 'access', '50'])->andWhere(['>', 'newsrate', $news->publicRate])->all();

        foreach ($users as $user) {
            Yii::$app->mailer->compose('published_news_mail', [
                'model' => $news,
            ])
                ->setTo($user->email)
                ->setFrom(['news@icrypto.cash' => 'iCryptoNews'])
                ->setSubject('News have been published!')
                ->send();
        }

    }

    public static function mailUpdated($news)
    {

        $users = User::find()->where(['>=', 'access', '200'])->all();

        foreach ($users as $user) {
            Yii::$app->mailer->compose('updated_news_mail', [
                'model' => $news,
            ])
                ->setTo($user->email)
                ->setFrom(['news@icrypto.cash' => 'iCryptoNews'])
                ->setSubject('News have been updated!')
                ->send();
        }

    }
}