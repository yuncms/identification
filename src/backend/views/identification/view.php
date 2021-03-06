<?php

use yuncms\helpers\Html;
use yuncms\widgets\DetailView;
use yuncms\admin\widgets\Box;
use yuncms\admin\widgets\Toolbar;
use yuncms\admin\widgets\Alert;
use yuncms\identification\models\Identification;

/* @var $this yii\web\View */
/* @var $model yuncms\identification\models\Identification */

$this->title = $model->user_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('yuncms/identification', 'Manage Authentication'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12 authentication-view">
            <?= Alert::widget() ?>
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
                            'label' => Yii::t('yuncms/identification', 'Update Authentication'),
                            'url' => ['update', 'id' => $model->user_id],
                            'options' => ['class' => 'btn btn-primary btn-sm']
                        ],
                    ]]); ?>
                </div>
                <div class="col-sm-8 m-b-xs">

                </div>
            </div>
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'user_id',
                    'user.nickname',
                    'real_name',
                    'id_card',
                    'passport_cover:image',
                    'passport_person_page:image',
                    'passport_self_holding:image',
                    [
                        'header' => Yii::t('yuncms/identification', 'Authentication'),
                        'attribute' => 'status',
                        'value' => function ($model) {
                            if ($model->status == Identification::STATUS_UNSUBMITTED) {
                                return Yii::t('yuncms/identification', 'Unsubmitted');
                            } else if ($model->status == Identification::STATUS_PENDING) {
                                return Yii::t('yuncms/identification', 'Pending review');
                            } elseif ($model->status == Identification::STATUS_REJECTED) {
                                return Yii::t('yuncms/identification', 'Rejected');
                            } elseif ($model->status == Identification::STATUS_IDENTIFIED) {
                                return Yii::t('yuncms/identification', 'Identified');
                            }
                            return null;
                        },
                        'format' => 'raw',
                    ],
                    'failed_reason',
                    'created_at:datetime',
                    'updated_at:datetime',
                ],
            ]) ?>
            <?php Box::end(); ?>
        </div>
    </div>
</div>
