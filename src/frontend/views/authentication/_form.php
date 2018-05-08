<?php
use yii\bootstrap\Html;
use yii\captcha\Captcha;
use yuncms\widgets\ActiveForm;
use yuncms\authentication\models\Authentication;

/*
 * @var yii\web\View $this
 * @var \yuncms\authentication\frontend\models\Authentication $model
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
    Authentication::TYPE_ID => Yii::t('yuncms/authentication', 'ID Card'),
    Authentication::TYPE_PASSPORT => Yii::t('yuncms/authentication', 'Passport ID'),
    Authentication::TYPE_ARMYID => Yii::t('yuncms/authentication', 'Army ID'),
    Authentication::TYPE_TAIWANID => Yii::t('yuncms/authentication', 'Taiwan ID'),
    Authentication::TYPE_HKMCID => Yii::t('yuncms/authentication', 'HKMC ID'),
]); ?>
<?= $form->field($model, 'id_card') ?>
<?= $form->field($model, 'id_file')->fileInput(); ?>
<?= $form->field($model, 'id_file1')->fileInput(); ?>
<?= $form->field($model, 'id_file2')->fileInput(); ?>

<?= $form->field($model, 'verifyCode')->widget(Captcha::class, [
    'captchaAction' => '/authentication/authentication/captcha',
]); ?>

<?= $form->field($model, 'registrationPolicy')->checkbox()->label(
    Yii::t('yuncms/authentication', 'Agree and accept {serviceAgreement} and {privacyPolicy}', [
        'serviceAgreement' => Html::a(Yii::t('yuncms/authentication', 'Service Agreement'), ['/legal/terms']),
        'privacyPolicy' => Html::a(Yii::t('yuncms/authentication', 'Privacy Policy'), ['/legal/privacy']),
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