<?php

use yuncms\helpers\Html;
use yuncms\admin\widgets\Box;
use yuncms\admin\widgets\Toolbar;
use yuncms\admin\widgets\Alert;
use yuncms\admin\widgets\ActiveForm;

/* @var yii\web\View $this  */
/* @var yuncms\identification\backend\models\Settings $model  */

$this->title = Yii::t('yuncms/identification', 'Settings');
$this->params['breadcrumbs'][] = Yii::t('yuncms/identification', 'Manage Authentication');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12 authentication-update">
            <?= Alert::widget() ?>
            <?php Box::begin([
                'header' => Html::encode($this->title),
            ]); ?>
            <div class="row">
                <div class="col-sm-4 m-b-xs">
                    <?= Toolbar::widget([
                        'items' => [
                            [
                                'label' => Yii::t('yuncms/identification', 'Manage Authentication'),
                                'url' => ['index'],
                            ],
                            [
                                'label' => Yii::t('yuncms/identification', 'Settings'),
                                'url' => ['settings'],
                            ],
                        ]
                    ]); ?>
                </div>
                <div class="col-sm-8 m-b-xs">

                </div>
            </div>

            <?php $form = ActiveForm::begin([
                'layout' => 'horizontal'
            ]); ?>

            <?= $form->field($model, 'enableMachineReview')->inline()->checkbox([], false); ?>
            <?= $form->field($model, 'ociAppCode') ?>
            <?= $form->field($model, 'volume') ?>

            <?= Html::submitButton(Yii::t('yuncms', 'Settings'), ['class' => 'btn btn-primary']) ?>

            <?php ActiveForm::end(); ?>
            <?php Box::end(); ?>
        </div>
    </div>
</div>
