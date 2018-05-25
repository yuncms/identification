<?php
use yii\bootstrap\Html;
use yii\captcha\Captcha;
use yuncms\widgets\ActiveForm;
use yuncms\identification\models\Identification;

/*
 * @var yii\web\View $this
 * @var \yuncms\identification\frontend\models\Identification $model
 */
?>
<?php
$form = ActiveForm::begin([
    'layout' => 'horizontal',
    'options' => [
        'enctype' => 'multipart/form-data',
    ],
]); ?>

<?= $form->field($model, 'real_name') ?>

<?= $form->field($model, 'id_type')->dropDownList([
    Identification::TYPE_ID => Yii::t('yuncms/identification', 'ID Card'),
    Identification::TYPE_PASSPORT => Yii::t('yuncms/identification', 'Passport ID'),
    Identification::TYPE_ARMYID => Yii::t('yuncms/identification', 'Army ID'),
    Identification::TYPE_TAIWANID => Yii::t('yuncms/identification', 'Taiwan ID'),
    Identification::TYPE_HKMCID => Yii::t('yuncms/identification', 'HKMC ID'),
]); ?>
<?= $form->field($model, 'id_card') ?>
<?= $form->field($model, 'id_file')->fileInput(); ?>
<?= $form->field($model, 'id_file1')->fileInput(); ?>
<?= $form->field($model, 'id_file2')->fileInput(); ?>

<?= $form->field($model, 'verifyCode')->widget(Captcha::class, [
    'captchaAction' => '/identification/identification/captcha',
]); ?>

<?= $form->field($model, 'registrationPolicy')->checkbox()->label(
    Yii::t('yuncms/identification', 'Agree and accept {serviceAgreement} and {privacyPolicy}', [
        'serviceAgreement' => Html::a(Yii::t('yuncms/identification', 'Service Agreement'), ['/legal/terms']),
        'privacyPolicy' => Html::a(Yii::t('yuncms/identification', 'Privacy Policy'), ['/legal/privacy']),
    ]), [
        'encode' => false
    ]
) ?>

    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-9">
            <?= Html::submitButton(Yii::t('yuncms', 'Submit'), ['class' => 'btn btn-success']) ?>
        </div>
    </div>
<?php ActiveForm::end(); ?>
