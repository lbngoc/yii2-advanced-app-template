<?php

use yii\helpers\Html;
// use yii\widgets\DetailView;
use \kartik\detail\DetailView;
use yii\helpers\Url;
use backend\models\User;

/* @var $this yii\web\View */
/* @var $model backend\models\User */

$this->title = 'User Details #' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$user = Yii::$app->getModule("user")->model("User");
$role = Yii::$app->getModule("user")->model("Role");

// $insert_mode = is_null($model->id);
?>
<div class="user-view">

    <?= DetailView::widget([
        'mode' => ($act === 'view') ? DetailView::MODE_VIEW : DetailView::MODE_EDIT,
        'model' => $model,
        'panel'=>[
            'heading'=>'<i class="glyphicon glyphicon-user"></i> User Details # ' . $model->id,
            'type'=>DetailView::TYPE_PRIMARY,
        ],
        'deleteOptions'=>[ // your ajax delete parameters
            'params' => ['id' => $model->id, 'kvdelete'=>true],
        ],
        'container' => ['id'=>'kv-details'],
        'formOptions' => [
            'action' => Url::current(['#' => 'kv-details']),
        ],
        'attributes' => [
            [
                'attribute' => 'email',
                'format' => 'email',
                'displayOnly' => ($act !== 'add'),
            ],
            'username',
            [
                'attribute' => 'password',
                'displayOnly' => ($act !== 'add'),
            ],
            // 'api_key',
            [
                'attribute' => 'id', // Fake Column
                'label' => 'Full name',
                'type' => DetailView::INPUT_TEXT,
                'value' => $model->profile ? $model->profile->full_name : '',
                'options' => [
                    'id' => 'profile_full_name',
                    'name' => 'Profile[full_name]'
                ]
            ],
            [
                'attribute'=>'status',
                'type' => DetailView::INPUT_DROPDOWN_LIST,
                'items' => $user::statusDropdown(),
            ],
            [
                'attribute'=>'role_id',
                'type' => DetailView::INPUT_DROPDOWN_LIST,
                'items' => $role::dropdown(),
            ],
            [
                'attribute'=>'ban_time',
                'format'=>'raw',
                'type'=>DetailView::INPUT_SWITCH,
                'widgetOptions' => [
                    'pluginOptions' => [
                        'onText' => 'Yes',
                        'offText' => 'No',
                    ]
                ],
                'value'=>$model->ban_time ? $model->ban_time : '<span class="label label-danger">No</span>',
            ],
            'ban_reason'
            //[
                // 'attribute'=>'create_time',
                // 'format'=>'datetime',
                // 'type'=>DetailView::INPUT_DATE,
                // 'widgetOptions' => [
                //     'pluginOptions'=>['format'=>'yyyy-mm-dd']
                // ],
                // 'valueColOptions'=>['style'=>'width:30%']
            //],
        ]]) ;?>

</div>
