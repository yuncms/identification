<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\identification\rest\models;

use Yii;
use yuncms\identification\Module;
use yuncms\helpers\ArrayHelper;
use yuncms\rest\models\User;
use yuncms\web\UploadedFile;

/**
 * Class Authentication
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class Identification extends \yuncms\identification\models\Identification
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
     * @var bool 是否同意注册协议
     */
    public $registrationPolicy;

    public $verifyCode;

    /**
     * 屏蔽敏感字段
     * @return array
     */
    public function fields()
    {
        $fields = parent::fields();
        // 删除一些包含敏感信息的字段
        unset($fields['passport_cover'], $fields['passport_person_page'], $fields['passport_self_holding']);
        return $fields;
    }

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
            'idFileRequired' => [
                ['id_file', 'id_file1', 'id_file2'],
                'required',
                'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]
            ],

            'idFileFile' => [
                ['id_file', 'id_file1', 'id_file2'], 'file',
                'extensions' => 'gif,jpg,jpeg,png',
                'maxSize' => 1024 * 1024 * 2,
                'tooBig' => Yii::t('yuncms/identification', 'File has to be smaller than 2MB'),
                'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]
            ],

            'registrationPolicyRequired' => [
                'registrationPolicy',
                'required',
                'skipOnEmpty' => false,
                'requiredValue' => true,
                'message' => Yii::t('yuncms/identification', '{attribute} must be selected.')
            ],

            // verifyCode needs to be entered correctly
            'verifyCodeRequired' => ['verifyCode', 'required'],
            'verifyCodeString' => ['verifyCode', 'string', 'min' => 5, 'max' => 7],
            'verifyCodeValidator' => ['verifyCode',
                'yuncms\sms\captcha\CaptchaValidator',
                'captchaAction' => '/sms/verify-code',
                'skipOnEmpty' => false,
                'message' => Yii::t('yuncms', 'Phone verification code input error.')
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
            'id_file' => Yii::t('yuncms/identification', 'Passport cover'),
            'id_file1' => Yii::t('yuncms/identification', 'Passport person page'),
            'id_file2' => Yii::t('yuncms/identification', 'Passport self holding'),
            'registrationPolicy' => Yii::t('yuncms/identification', 'Agree and accept Service Agreement and Privacy Policy'),
        ]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * 加载上传文件
     * @return bool
     */
    public function beforeValidate()
    {
        $this->id_file = UploadedFile::getInstanceByName('id_file');
        $this->id_file1 = UploadedFile::getInstanceByName('id_file1');
        $this->id_file2 = UploadedFile::getInstanceByName('id_file2');
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
                $this->passport_cover = Module::getVolume()->url($idCardPath);
            }

            if ($this->id_file1 && ($tempFile = $this->id_file1->saveAsTempFile()) != false) {
                $idCardPath = Module::saveImage($this->user_id, $tempFile, '_passport_person_page_image.jpg');
                $this->passport_person_page = Module::getVolume()->url($idCardPath);
            }

            if ($this->id_file2 && ($tempFile = $this->id_file2->saveAsTempFile()) != false) {
                $idCardPath = Module::saveImage($this->user_id, $tempFile, '_passport_self_holding_image.jpg');
                $this->passport_self_holding = Module::getVolume()->url($idCardPath);
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
