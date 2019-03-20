<?php

/* @var $this yii\web\View */

use app\models\News;
use app\models\User;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\grid\GridView;
use kartik\editable\Editable;

$this->title = 'iCryptoNews';
$form = ActiveForm::begin(['id' => 'news-form']);
$model = new News();

if (Yii::$app->user->isGuest || Yii::$app->user->identity->access < 50) {
    $gridColumns = [
        'date',
        'ticker',
        'currency',
        'sourceDate',
        'eventDate',
        'sourceLink' => [
            'attribute' => 'sourceLink',
            'content' => function ($model) {
                return Html::a('Link', $model->sourceLink, ['target' => '_blank']);
            }
        ],
        'comment',
        'chartLink' => [
            'attribute' => 'chartLink',
            'content' => function ($model) {
                return Html::a('Link', $model->chartLink, ['target' => '_blank']);
            }
        ],
    ];
    echo GridView::widget(['dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => $gridColumns,
            'responsive' => true,
            'condensed' => true,
        ]
    );
} else {
    $gridColumns = [
        'expand' => [
            'attribute' => 'expand',
            'class' => 'kartik\grid\ExpandRowColumn',
            'value' => function ($model, $key, $index, $column) {
                return GridView::ROW_COLLAPSED;
            },
            'detail' => function ($model, $key, $index, $column) {
                return Yii::$app->controller->renderPartial('_expand-row-details', ['model' => $model]);
            },
            'headerOptions' => ['class' => 'kartik-sheet-style'],
            'detailAnimationDuration' => 0,
            'hidden' => false,
            'expandOneOnly' => false
        ],
        'id',
        'userId' => [
            'attribute' => 'userId',
            'content' => function ($model) {
                if ($model->userId) {
                    return User::findOne($model->userId)->username;
                }
            }
        ],
        'suId' => [
            'attribute' => 'suId',
            'content' => function ($model) {
                if ($model->suId) {
                    return User::findOne($model->suId)->username;
                }

            }
        ],
        'date' => [
            'attribute' => 'date',
            'filterType' => GridView::FILTER_DATE,
            'filterWidgetOptions' => [
                'pluginOptions' => [
                    'format' => 'yyyy-mm-dd',
                    'autoWidget' => true,
                    'autoclose' => true,
                ],
            ],
        ],
        'ticker',
        'currency',
        'sourceDate' => [
            'attribute' => 'sourceDate',
            'filterType' => GridView::FILTER_DATE,
            'filterWidgetOptions' => [
                'pluginOptions' => [
                    'format' => 'yyyy-mm-dd',
                    'autoWidget' => true,
                    'autoclose' => true,
                ],
            ],
        ],
        'eventDate' => [
            'attribute' => 'eventDate',
            'filterType' => GridView::FILTER_DATE,
            'filterWidgetOptions' => [
                'pluginOptions' => [
                    'format' => 'yyyy-mm-dd',
                    'autoWidget' => true,
                    'autoclose' => true,
                ],
            ],
        ],
        'sourceLink' => [
            'attribute' => 'sourceLink',
            'content' => function ($model) {
                if ($model->sourceLink) {
                    return Html::a('Link', $model->sourceLink, ['target' => '_blank']);
                }
            }
        ],
        'comment' => ['hidden' => true],
        'twitter' => ['class' => 'kartik\grid\BooleanColumn',
            'attribute' => 'twitter',
        ],
        'medium' => ['class' => 'kartik\grid\BooleanColumn',
            'attribute' => 'medium',
        ],
        'cmc' => ['class' => 'kartik\grid\BooleanColumn',
            'attribute' => 'cmc',
        ],
        'rate' => ['attribute' => 'rate'],
        'chartLink' => [
            'attribute' => 'chartLink',
            'content' => function ($model) {
                if ($model->chartLink) {
                    return Html::a('Link', $model->chartLink, ['target' => '_blank']);
                }
            }
        ],
        'publicDate' => [
            'attribute' => 'publicDate',
            'filterType' => GridView::FILTER_DATE,
            'filterWidgetOptions' => [
                'pluginOptions' => [
                    'format' => 'yyyy-mm-dd',
                    'autoWidget' => true,
                    'autoclose' => true,
                ],
            ],
        ],
        'publicRate',
    ];

    if (Yii::$app->user->identity->access >= 50) {
        $gridColumns['comment'] = ['class' => 'kartik\grid\EditableColumn',
            'attribute' => 'comment',
            'readonly' => function ($model, $key, $index) {
                if ($model->userId != Yii::$app->user->identity->id || $model->publicDate != null) {
                    return true;
                } else {
                    return false;
                }
            },
            'editableOptions' => [
                'inputType' => Editable::INPUT_TEXTAREA,
                'size' => 'lg',
                'displayValue' => 'Edit'
            ],
            'content' => function ($model, $key) {
                return Html::a('More...', '#', ['id' => 'comment-expand-button']);
            },
        ];
        $gridColumns['rate'] = ['class' => 'kartik\grid\EditableColumn',
            'attribute' => 'rate',
            'readonly' => function ($model, $key, $index) {
                if ($model->userId != Yii::$app->user->identity->id || $model->publicDate != null) {
                    return true;
                } else {
                    return false;
                }
            },
            'editableOptions' => ['displayValue' => $model->rate],
            'content' => function ($model, $key) {
                return $model->rate;
            },
        ];
    }

    if (Yii::$app->user->identity->access >= 150) {
        $gridColumns['chartLink'] = ['class' => 'kartik\grid\EditableColumn',
            'attribute' => 'chartLink',
            'editableOptions' => [
                'formOptions' => ['action' => ['/news/edit']],
                'displayValue' => 'Edit',
                'inputType' => Editable::INPUT_TEXT,
            ]
        ];
    }

    if (Yii::$app->user->identity->access >= 200) {
        $gridColumns['comment'] = ['class' => 'kartik\grid\EditableColumn',
            'attribute' => 'comment',
            'editableOptions' => [
                'formOptions' => ['action' => ['/news/edit']],
                'displayValue' => 'Edit',
                'size' => 'lg',
                'inputType' => Editable::INPUT_TEXTAREA,
            ]
        ];
        $gridColumns[array_search('publicRate', $gridColumns)] = ['class' => 'kartik\grid\EditableColumn',
            'attribute' => 'publicRate',
            'editableOptions' => [
                'formOptions' => ['action' => ['/news/edit']],
                'inputType' => Editable::INPUT_TEXT,
            ]
        ];
        $gridColumns['actions'] = [
            'class' => '\kartik\grid\ActionColumn',
            'template' => '{delete}',
        ];
        $gridColumns['publicDate'] = [
            'attribute' => 'publicDate',
            'filterType' => GridView::FILTER_DATE,
            'filterWidgetOptions' => [
                'pluginOptions' => [
                    'format' => 'yyyy-mm-dd',
                    'autoWidget' => true,
                    'autoclose' => true,
                ],
            ],
            'content' => function ($model, $key) {
                if ($model->publicDate == null) {
                    return Html::a('Publish', Yii::$app->urlManager->createUrl(['news/publish', 'key' => $key]), ['class' => 'btn btn-success']);
                } else {
                    return $model->publicDate;
                }
            },

        ];
        $gridColumns['rate'] = [
            'class' => 'kartik\grid\EditableColumn',
            'attribute' => 'rate'];
    };

    echo GridView::widget(['dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => $gridColumns,
            'toolbar' => [
                ['content' => Html::a('Create new', ['news/create'], ['class' => 'btn btn-success']) . Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['/news'], ['data-pjax' => 0, 'class' => 'btn btn-default', 'title' => 'Reset Grid'])],
                '{export}',
                '{toggleData}'
            ],
            'responsive' => true,
            'condensed' => true,
            'showPageSummary' => false,
            'persistResize' => true,
            'itemLabelSingle' => 'item',
            'itemLabelPlural' => 'news',
            'toggleDataOptions' => ['minCount' => 10],
            'panel' => [
                'type' => GridView::TYPE_PRIMARY,
            ],
        ]
    );
}
$this->registerJs('$("#w0").find("td.kv-expand-icon-cell").each(function() {
        var $cell = $(this);
        var $obj = $(this).closest("tr").find("#comment-expand-button");        
        $obj.on("click", function () {            
        $cell.trigger("click");
        })
    })
    ;');


