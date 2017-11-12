<?php

/* @var $this yii\web\View */

use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\BaseStringHelper;

$this->title = 'Users';

?>

<?php echo GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        ['class' => \yii\grid\SerialColumn::className()],
        'name',
        'email',
        [
            'attribute' => 'realtime_token',
            'content' => function ($user) {
                return $user->realtime_token ? md5($user->realtime_token) : 'not-set';
            }
        ],
        ['class' => ActionColumn::className()],
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{view} {update} {delete} {pushButton}',  // the default buttons + your custom button
            'buttons' => [
                'pushButton' => function ($url, $model, $key) {
                    return $model->realtime_token ? \yii\helpers\Html::a('Push', ['/site/push/' . $model->id]) : '';
                }
            ]
        ]
    ]
]); ?>
