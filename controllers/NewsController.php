<?php

namespace app\controllers;

use app\models\NewsForm;
use app\models\News;
use app\models\SearchNews;
use app\models\AdminNews;
use app\models\User;
use Yii;
use yii\web\Controller;
use kartik\grid\EditableColumnAction;
use yii\helpers\ArrayHelper;

class NewsController extends Controller
{
    public function actions()
    {
        return ArrayHelper::merge(parent::actions(), [
            'edit' => [
                'class' => EditableColumnAction::className(),
                'modelClass' => News::className(),
            ]
        ]);
    }

    public function actionIndex()
    {
        if (Yii::$app->user->isGuest || Yii::$app->user->identity->access < 50) {
            $searchModel = new SearchNews();
        } else {
            $searchModel = new AdminNews();
        }

        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        if (!Yii::$app->user->isGuest && Yii::$app->user->identity->access >= 150) {

            if (Yii::$app->request->post('hasEditable')) {
                $id = Yii::$app->request->post('editableKey');
                $posted = current($_POST['News']);
                return News::edit($id, $posted);
            }
        }

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionCreate()
    {
        if (!Yii::$app->user->isGuest && Yii::$app->user->identity->access >= 100) {
            $model = new NewsForm();

            if ($model->load(Yii::$app->request->post())) {
                Yii::$app->session->setFlash('success', 'News successfully created.');
                $model->user = Yii::$app->user->identity->username;
                $model->userId = Yii::$app->user->identity->id;
                News::create($model);
                News::mailCreated($model);
                return $this->redirect(['index']);
            }

            return $this->render('create', [
                'model' => $model,
            ]);
        }

        $this->redirect(['index']);
    }

    public function actionPublish()
    {
        if (!Yii::$app->user->isGuest && Yii::$app->user->identity->access >= 200 && Yii::$app->request->get('key') != null) {
            Yii::$app->session->setFlash('success', 'News successfully published.');
            $key = Yii::$app->request->get('key');
            $obj = News::findOne($key);
            $obj->user = User::findOne($obj->userId)->username;
            $obj->su = Yii::$app->user->identity->username;
            News::publish($obj);
            News::mailPublished($obj);
        }

        return $this->redirect(['index']);
    }

    public function actionDelete()
    {
        if (!Yii::$app->user->isGuest && Yii::$app->user->identity->access >= 200 && Yii::$app->request->get('id') != null) {
            Yii::$app->session->setFlash('success', 'News successfully deleted.');
            $key = Yii::$app->request->get('id');
            $obj = News::findOne($key);
            $obj->delete();
        }

        return $this->redirect(['index']);
    }
}
