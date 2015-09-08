<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use kartik\dynagrid\DynaGrid;
use backend\models\User;

$user = Yii::$app->getModule("user")->model("User");
$role = Yii::$app->getModule("user")->model("Role");

/* @var $this yii\web\View */
/* @var $searchModel backend\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


<?php
     $toolbars = [
        ['content' =>
            Html::a('<i class="glyphicon glyphicon-plus"></i> Add user', ['admin/view?id=new'], ['type' => 'button', 'title' => 'Add ' . $this->title, 'class' => 'btn btn-success']) . ' ' .
            // Html::a('<i class="fa fa-file-excel-o"></i>', ['user/parsing'], ['type' => 'button', 'title' => 'Parsing Excel ' . $this->title, 'class' => 'btn btn-warning']) . ' ' .
            // Html::button('<i class="fa fa-download"></i>', ['type' => 'button', 'title' => 'Excel Backup ' . $this->title, 'class' => 'btn btn-default','id'=>'backupExcel']) . ' ' .
            Html::button('<i class="glyphicon glyphicon-trash"></i> Delete selected users', ['type' => 'button', 'title' => 'Delete Selected ' . $this->title, 'class' => 'btn btn-danger', 'id' => 'deleteSelected']) . ' ' .
            Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset grid', ['user/index','p_reset'=>true], ['data-pjax' => 0, 'class' => 'btn btn-default', 'title' => 'Reset Grid']). ' '


        ],
        // ['content' => '{dynagrid}'],
        // ['content' => '{dynagridFilter}{dynagridSort}{dynagrid}'],
        // '{export}',
    ];
    $panels = [
        'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-book"></i>  ' . $this->title . '</h3>',
        'before' => '<div style="padding-top: 7px;"><em>* The table at the right you can pull reports & personalize</em></div>',
    ];
    $columns = [
        ['class' => 'kartik\grid\SerialColumn', 'order' => DynaGrid::ORDER_FIX_LEFT],
            'username',
            'email:email',
            'api_key',
            'profile.full_name',
        [
            'attribute' => 'role_id',
            'label' => Yii::t('user', 'Role'),
            'filter' => $role::dropdown(),
            'value' => function($model, $index, $dataColumn) use ($role) {
                $roleDropdown = $role::dropdown();
                return $roleDropdown[$model->role_id];
            },
        ],
        [
            'attribute' => 'status',
            'label' => Yii::t('user', 'Status'),
            'filter' => $user::statusDropdown(),
            'value' => function($model, $index, $dataColumn) use ($user) {
                $statusDropdown = $user::statusDropdown();
                return $statusDropdown[$model->status];
            },
        ],
        [
            'attribute'=>'create_time',
            // 'value' => function ($model, $index, $widget) {
            //     return Yii::$app->formatter->asDate($model->created_at);
            // },
            'format' => 'datetime',
            'filterType' => GridView::FILTER_DATE,
            'filterWidgetOptions' => [
                'pickerButton' => false,
                'pluginOptions' => [
                    'format' => 'M dd, yyyy',
                    'autoclose' => true,
                    'todayHighlight' => true,
                ]
            ],
                'width' => '200px',
                'hAlign' => 'center',
        ],
        [
            'class' => 'kartik\grid\ActionColumn',
            'dropdown' => false,
            'vAlign' => 'middle',
            'viewOptions' => ['title' => 'view', 'data-toggle' => 'tooltip'],
            'updateOptions' => ['title' => 'update', 'data-toggle' => 'tooltip'],
            'deleteOptions' => ['title' => 'delete', 'data-toggle' => 'tooltip'],
            'buttons' => [
                'delete' => function ($url , $model) {
                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url,
                        ['data-confirm' => 'Are you sure you want to delete this user?', 'data-method' =>'POST'] );
                }
            ],
            'urlCreator' => function ($action, $model, $key, $index) {
                return Url::to(['admin/view', 'id' => $model->id, 'act' => $action]);
            }
        ],
        [
            'class' => '\kartik\grid\CheckboxColumn',
            'checkboxOptions' => [
                'class' => 'simple'
            ],
            //'pageSummary' => true,
            'rowSelectedClass' => GridView::TYPE_SUCCESS,
        ],
    ];

    $dynagrid = DynaGrid::begin([
                'id' => 'user-grid',
                'columns' => $columns,
                'theme' => 'panel-primary',
                'showPersonalize' => true,
                'storage' => 'db',
                //'maxPageSize' =>500,
                'allowSortSetting' => true,
                'gridOptions' => [
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'showPageSummary' => false,
                    'floatHeader' => true,
                    'pjax' => true,
                    'panel' => $panels,
                    'toolbar' => $toolbars,
                ],
                'options' => ['id' => 'User'.Yii::$app->user->identity->id] // a unique identifier is important
    ]);

    DynaGrid::end();
?> </div>
<?php
$this->registerJs('$(document).on("click", "#backupExcel", function(){
    var myUrl = window.location.href;
    location.href=myUrl.replace(/index/gi, "excel"); ;
});$("#deleteSelected").on("click",function(){
var array = "";
$(".simple").each(function(index){
    if($(this).prop("checked")){
        array += $(this).val()+",";
    }
})
if(array==""){
    alert("No data selected?");
} else {
    if(window.confirm("Are You Sure to delete selected data?")){
        $.ajax({
            type:"POST",
            url:"'.Yii::$app->urlManager->createUrl(['user/delete-all']).'",
            data :{pk:array},
            success:function(){
                location.href="";
            }
        });
    }
}
});');?>
