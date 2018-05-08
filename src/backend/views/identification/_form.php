<?php
use yuncms\helpers\Html;
use yuncms\identification\models\Identification;
use yuncms\admin\widgets\ActiveForm;

/* @var \yii\web\View $this */
/* @var yuncms\identification\models\Identification $model */
/* @var ActiveForm $form */
?>
<?php $form = ActiveForm::begin(['layout' => 'horizontal']); ?>


<?= $form->field($model, 'real_name')->textInput(['maxlength' => true]) ?>
<div class="hr-line-dashed"></div>
<?= $form->field($model, 'id_type')->dropDownList([
    Identification::TYPE_ID => Yii::t('yuncms/identification', 'ID Card'),
    Identification::TYPE_PASSPORT => Yii::t('yuncms/identification', 'Passport ID'),
    Identification::TYPE_ARMYID => Yii::t('yuncms/identification', 'Army ID'),
    Identification::TYPE_TAIWANID => Yii::t('yuncms/identification', 'Taiwan ID'),
    Identification::TYPE_HKMCID => Yii::t('yuncms/identification', 'HKMC ID'),
]); ?>
<div class="hr-line-dashed"></div>
<?= $form->field($model, 'id_card')->textInput(['maxlength' => true]) ?>

<div class="hr-line-dashed"></div>

<?= $form->field($model, 'status')->inline(true)->radioList([
    0 => Yii::t('yuncms/identification', 'Pending review'),
    1 => Yii::t('yuncms/identification', 'Refuse'),
    2 => Yii::t('yuncms/identification', 'Passed'),
]) ?>
<div class="hr-line-dashed"></div>
<div class="form-group field-authentication-passport_cover">
    <label class="control-label col-sm-3"><?= Yii::t('yuncms/identification', 'Passport cover') ?></label>
    <div class="col-sm-6">
        <?= Html::img($model->passport_cover); ?>
    </div>
</div>
<div class="hr-line-dashed"></div>
<div class="form-group field-authentication-passport_person_page">
    <label class="control-label col-sm-3"><?= Yii::t('yuncms/identification', 'Passport person page') ?></label>
    <div class="col-sm-6">
        <?= Html::img($model->passport_person_page); ?>
    </div>
</div>
<div class="hr-line-dashed"></div>
<div class="form-group field-authentication-passport_self_holding">
    <label class="control-label col-sm-3"><?= Yii::t('yuncms/identification', 'Passport self holding') ?></label>
    <div class="col-sm-6">
        <?= Html::img($model->passport_self_holding); ?>
    </div>
</div>
<div class="hr-line-dashed"></div>
<?= $form->field($model, 'failed_reason')->textInput(['maxlength' => true]) ?>
<div class="hr-line-dashed"></div>

<div class="form-group">
    <div class="col-sm-4 col-sm-offset-2">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('yuncms', 'Create') : Yii::t('yuncms', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>

    </div>
</div>


<?php ActiveForm::end(); ?>

