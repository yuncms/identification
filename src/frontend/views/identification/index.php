<?php

use yii\helpers\Url;
/*
 * @var yii\web\View $this
 * @var yuncms\identification\models\Identification $model
 */

$this->title = Yii::t('yuncms/identification', 'Identification');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-md-2">
        <?= $this->render('@yuncms/user/views/_profile_menu') ?>
    </div>
    <div class="col-md-10">
        <h2 class="h3 profile-title"><?= Yii::t('yuncms/identification', 'Authentication') ?></h2>
        <?php if (!$model->isNewRecord): ?>
            <?php if ($model->status == 0): ?>
                <div class="alert alert-info" role="alert">
                    <?= Yii::t('yuncms/identification', 'Your application is submitted successfully! We will be processed within three working days, the results will be processed by mail, station message to inform you, if in doubt please contact the official administrator.') ?>
                </div>
            <?php elseif ($model->status == 1): ?>
                <div class="alert alert-danger" role="alert">
                    <?= Yii::t('yuncms/identification', 'Sorry, after passing your review, the information you submitted has not been approved. Please check the information and submit it again.') ?>
                    <?php if ($model->failed_reason): ?>
                        <?= Yii::t('yuncms/identification', 'Failure reason:') ?><?= $model->failed_reason ?>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-12">
                <div class="box box-solid">
                    <div class="box-body">
                        <dl class="dl-horizontal">
                            <dt><?= Yii::t('yuncms/identification', 'Full Name') ?></dt>
                            <dd><?= $model->real_name ?></dd>
                            <dt><?= Yii::t('yuncms/identification', 'Id Type') ?></dt>
                            <dd><?= $model->type ?></dd>
                            <dt><?= Yii::t('yuncms/identification', 'Id Card') ?></dt>
                            <dd><?= $model->id_card ?></dd>
                            <dd><a href="<?= Url::to(['/identification/identification/update']) ?>"
                                   class="btn btn-warning"><?= Yii::t('yuncms/identification', 'Modify information') ?></a>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
