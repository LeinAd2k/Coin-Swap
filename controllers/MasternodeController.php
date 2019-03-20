<?php
/**
 * Created by PhpStorm.
 * User: Jeka
 * Date: 09.01.2018
 * Time: 2:20
 */

namespace app\controllers;

class MasternodeController extends \yii\web\Controller
{
    /**
     * Displays node page.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionNode()
    {
        return $this->render('index');
    }


}