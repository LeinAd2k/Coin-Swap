<?php
namespace app\assets;

use yii\web\AssetBundle;

class FontAsset extends AssetBundle
{
    public $sourcePath = '@bower/font-awesome';
    public $css = ['css/font-awesome.min.css'];
}
