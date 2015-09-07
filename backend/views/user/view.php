<?php

use yii\helpers\Html;
// use yii\widgets\DetailView;
use \kartik\detail\DetailView;
use backend\models\User;

/* @var $this yii\web\View */
/* @var $model backend\models\User */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">

<!--     <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p> -->

    <?= DetailView::widget([
        'mode' => is_null($model->id) ? DetailView::MODE_EDIT : DetailView::MODE_VIEW,
        'model' => $model,
        'panel'=>[
            'heading'=>'<i class="glyphicon glyphicon-user"></i> User Details # ' . $model->id,
            'type'=>DetailView::TYPE_PRIMARY,
        ],
        'attributes' => [
            // 'id',
            'username',
            'auth_key',
            'password_hash',
            'password_reset_token',
            'email:email',
            'status',
            // 'created_at',
            [
                'attribute'=>'created_at',
                'format'=>'datetime',
                'type'=>DetailView::INPUT_DATE,
                // 'widgetOptions' => [
                //     'pluginOptions'=>['format'=>'yyyy-mm-dd']
                // ],
                // 'valueColOptions'=>['style'=>'width:30%']
            ],
            // 'updated_at:datetime',
        ]]) ;?>

</div>
