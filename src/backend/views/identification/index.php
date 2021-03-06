<?php
use yuncms\helpers\Html;
use yuncms\grid\GridView;
use yii\widgets\Pjax;
use yuncms\admin\widgets\Box;
use yuncms\admin\widgets\Toolbar;
use yuncms\admin\widgets\Alert;
use yuncms\identification\backend\models\IdentificationSearch;

/* @var $this yii\web\View */
/* @var $searchModel yuncms\identification\backend\models\IdentificationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('yuncms/identification', 'Manage Authentication');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12 authentication-index">
            <?= Alert::widget() ?>
            <?php Pjax::begin(); ?>
            <?php Box::begin([
                'header' => Html::encode($this->title),
            ]); ?>
            <div class="row">
                <div class="col-sm-4 m-b-xs">
                    <?= Toolbar::widget(['items' => [
                        [
                            'label' => Yii::t('yuncms/identification', 'Manage Authentication'),
                            'url' => ['index'],
                        ],
                        [
                            'label' => Yii::t('yuncms/identification', 'Settings'),
                            'url' => ['settings'],
                        ],
                    ]]); ?>
                </div>
                <div class="col-sm-8 m-b-xs">
                    <?php echo $this->render('_search', ['model' => $searchModel]); ?>
                </div>
            </div>
            <?= GridView::widget([
                'layout' => "{items}\n{summary}\n{pager}",
                'dataProvider' => $dataProvider,
                'options' => ['id' => 'gridview'],
                //'filterModel' => $searchModel,
                'columns' => [
                    [
                        'class' => 'yii\grid\CheckboxColumn',
                        "name" => "id",
                    ],
                    //['class' => 'yii\grid\SerialColumn'],
                    'user_id',
                    'user.nickname',
                    'real_name',
                    'type',
                    'id_card',
                    [
                        'header' => Yii::t('yuncms/identification', 'Authentication'),
                        'attribute' => 'status',
                        'value' => function ($model) {
                            return IdentificationSearch::dropDown("status", $model->status);
                        },
                        "filter" => IdentificationSearch::dropDown("status"),
                        'format' => 'raw',
                    ],
                    'failed_reason',
                    [
                        'attribute' => 'created_at',
                        'format' => 'datetime',
                        'filter' => \yii\jui\DatePicker::widget([
                            'model' => $searchModel,
                            'options'=>[
                                'class'=>'form-control'
                            ],
                            'attribute' => 'created_at',
                            'name' => 'created_at',
                            'dateFormat' => 'yyyy-MM-dd'
                        ]),
                    ],
                    [
                        'attribute' => 'updated_at',
                        'format' => 'datetime',
                        'filter' => \yii\jui\DatePicker::widget([
                            'model' => $searchModel,
                            'options'=>[
                                'class'=>'form-control'
                            ],
                            'attribute' => 'updated_at',
                            'name' => 'updated_at',
                            'dateFormat' => 'yyyy-MM-dd'
                        ]),
                    ],
                    [
                        'class' => 'yuncms\grid\ActionColumn',
                        'template' => '{view} {update}',
                        //'buttons' => [
                        //    'update' => function ($url, $model, $key) {
                        //        return $model->status === 'editable' ? Html::a('Update', $url) : '';
                        //    },
                        //],
                    ],
                ],
            ]); ?>
            <?php Box::end(); ?>
            <?php Pjax::end(); ?>
        </div>
    </div>
</div>
