<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\authentication\frontend\models;

use Yii;
use yii\helpers\ArrayHelper;
use yuncms\authentication\Module;
use yuncms\web\UploadedFile;

/**
 * Class AuthenticationForm
 * @package yuncms\authentication
 */
class Authentication extends \yuncms\authentication\models\Authentication
{
    /**
     * @var UploadedFile 身份证上传字段
     */
    public $id_file;

    /**
     * @var UploadedFile 身份证上传字段
     */
    public $id_file1;

    /**
     * @var UploadedFile 身份证上传字段
     */
    public $id_file2;

    /**
     * @var string 验证码
     */
    public $verifyCode;

    /**
     * @var bool 是否同意注册协议
     */
    public $registrationPolicy;

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        return ArrayHelper::merge($scenarios, [
            self::SCENARIO_CREATE => ['real_name', 'id_type', 'id_card', 'id_file', 'id_file1', 'id_file2'],
            self::SCENARIO_UPDATE => ['real_name', 'id_type', 'id_card', 'id_file', 'id_file1', 'id_file2'],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = parent::rules();
        return array_merge($rules, [
            'idCardString' => [
                ['id_card'],
                'string',
                'when' => function ($model) {//中国大陆18位身份证号码
                    return $model->id_type == static::TYPE_ID;
                },
                'whenClient' => "function (attribute, value) {return jQuery(\"#authentication-id_type\").val() == '" . Authentication::TYPE_ID . "';}",
                'length' => 18,
                'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]
            ],
            'idFile' => [
                ['id_file', 'id_file1', 'id_file2'],
                'file',
                'extensions' => 'gif,jpg,jpeg,png',
                'maxSize' => 1024 * 1024 * 2,
                'tooBig' => Yii::t('yuncms/authentication', 'File has to be smaller than 2MB'),
                'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]
            ],

            // verifyCode needs to be entered correctly
            'verifyCode' => ['verifyCode', 'captcha', 'captchaAction' => '/authentication/authentication/captcha'],

            'registrationPolicyRequired' => ['registrationPolicy', 'required', 'skipOnEmpty' => false, 'requiredValue' => true,
                'message' => Yii::t('yuncms/authentication', '{attribute} must be selected.')
            ],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        $attributeLabels = parent::attributeLabels();
        return array_merge($attributeLabels, [
            'id_file' => Yii::t('yuncms/authentication', 'Passport cover'),
            'id_file1' => Yii::t('yuncms/authentication', 'Passport person page'),
            'id_file2' => Yii::t('yuncms/authentication', 'Passport self holding'),
            'verifyCode' => Yii::t('yuncms', 'VerifyCode'),
            'registrationPolicy' => Yii::t('yuncms/authentication', 'Agree and accept Service Agreement and Privacy Policy'),
        ]);
    }

    /**
     * 加载上传文件
     * @return bool
     */
    public function beforeValidate()
    {
        $this->id_file = UploadedFile::getInstance($this, 'id_file');
        $this->id_file1 = UploadedFile::getInstance($this, 'id_file1');
        $this->id_file2 = UploadedFile::getInstance($this, 'id_file2');
        return parent::beforeValidate();
    }

    /**
     * @param bool $insert
     * @return bool
     * @throws \yii\base\ErrorException
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->id_file && ($tempFile = $this->id_file->saveAsTempFile()) != false) {
                $idCardPath = Module::saveImage($this->user_id, $tempFile, '_passport_cover_image.jpg');
                $this->passport_cover = Module::getVolume()->getUrl($idCardPath);
            }

            if ($this->id_file1 && ($tempFile = $this->id_file1->saveAsTempFile()) != false) {
                $idCardPath = Module::saveImage($this->user_id, $tempFile, '_passport_person_page_image.jpg');
                $this->passport_person_page = Module::getVolume()->getUrl($idCardPath);
            }

            if ($this->id_file2 && ($tempFile = $this->id_file2->saveAsTempFile()) != false) {
                $idCardPath = Module::saveImage($this->user_id, $tempFile, '_passport_self_holding_image.jpg');
                $this->passport_self_holding = Module::getVolume()->getUrl($idCardPath);
            }
            if (!$insert && $this->scenario == 'update') {
                $this->status = self::STATUS_PENDING;
            }
            return true;
        } else {
            return false;
        }
    }
}