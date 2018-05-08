<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\identification\models;

use Yii;
use yuncms\identification\Module;
use yuncms\db\ActiveRecord;
use yuncms\helpers\ArrayHelper;
use yuncms\user\models\User;

/**
 * This is the model class for table "identification".
 *
 * @property integer $user_id 用户ID
 * @property string $real_name 真实姓名
 * @property string $id_card 证件号
 * @property string $id_type 证件类型
 * @property string $passport_cover
 * @property string $passport_person_page
 * @property string $passport_self_holding
 * @property int $status 审核状态
 * @property string $failed_reason 拒绝原因
 * @property integer $created_at 创建时间
 * @property integer $updated_at 更新时间
 *
 * @property mixed $type
 * @property User $user
 */
class Identification extends ActiveRecord
{
    //场景定义
    const SCENARIO_CREATE = 'create';//创建
    const SCENARIO_UPDATE = 'update';//更新
    const SCENARIO_VERIFY = 'verify';

    //证件类型
    const TYPE_ID = 'id';
    const TYPE_PASSPORT = 'passport';
    const TYPE_ARMYID = 'armyid';
    const TYPE_TAIWANID = 'taiwan';
    const TYPE_HKMCID = 'hkmcid';

    //认证状态
    const STATUS_PENDING = 0b0;
    const STATUS_REJECTED = 0b1;
    const STATUS_AUTHENTICATED = 0b10;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%authentications}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior'
            ],

            'blameable' => [
                'class' => 'yii\behaviors\BlameableBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'user_id',
                ],
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        return ArrayHelper::merge($scenarios, [
            self::SCENARIO_CREATE => ['real_name', 'id_type', 'id_card'],
            self::SCENARIO_UPDATE => ['real_name', 'id_type', 'id_card'],
            self::SCENARIO_VERIFY => ['real_name', 'id_card', 'status', 'failed_reason'],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //realName rule
            'realNameRequired' => ['real_name', 'required', 'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]],
            'realNameTrim' => ['real_name', 'trim'],

            //idCard rule
            'idCardRequired' => ['id_card', 'required', 'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]],

            'idCardString' => [
                ['id_card'],
                'string',
                'when' => function ($model) {//中国大陆18位身份证号码
                    return $model->id_type == static::TYPE_ID;
                },
                'length' => 18,
                'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]
            ],
            'idCardMatch' => [
                'id_card',
                'yuncms\validators\IdCardValidator',
                'when' => function ($model) {//中国大陆18位身份证号码校验
                    return $model->id_type == static::TYPE_ID;
                },
                'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]
            ],
            'idCardTrim' => ['id_card', 'trim'],

            //idType rule
            'idTypeRange' => [
                'id_type',
                'in',
                'range' => [
                    self::TYPE_ID, self::TYPE_PASSPORT, self::TYPE_ARMYID, self::TYPE_TAIWANID, self::TYPE_HKMCID
                ],
                'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]
            ],
            'idTypeDefault' => [
                'id_type',
                'default',
                'value' => self::TYPE_ID,
            ],

            //status rule
            'statusDefault' => [
                'status',
                'default',
                'value' => self::STATUS_PENDING,
                'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE, self::SCENARIO_VERIFY]
            ],
            'StatusRange' => [
                'status',
                'in',
                'range' => [self::STATUS_PENDING, self::STATUS_REJECTED, self::STATUS_AUTHENTICATED],
                'on' => [self::SCENARIO_VERIFY]
            ],

            //failed_reason rule
            'failedReasonTrim' => [
                'failed_reason',
                'filter',
                'filter' => 'trim',
                'on' => [self::SCENARIO_VERIFY]
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => Yii::t('yuncms/identification', 'User Id'),
            'real_name' => Yii::t('yuncms/identification', 'Full Name'),
            'id_type' => Yii::t('yuncms/identification', 'Id Type'),
            'type' => Yii::t('yuncms/identification', 'Id Type'),
            'id_card' => Yii::t('yuncms/identification', 'Id Card'),
            'passport_cover' => Yii::t('yuncms/identification', 'Passport cover'),
            'passport_person_page' => Yii::t('yuncms/identification', 'Passport person page'),
            'passport_self_holding' => Yii::t('yuncms/identification', 'Passport self holding'),
            'status' => Yii::t('yuncms/identification', 'Status'),
            'failed_reason' => Yii::t('yuncms/identification', 'Failed Reason'),
            'created_at' => Yii::t('yuncms/identification', 'Created At'),
            'updated_at' => Yii::t('yuncms/identification', 'Updated At'),
        ];
    }

    public function getType()
    {
        switch ($this->id_type) {
            case self::TYPE_ID:
                $genderName = Yii::t('yuncms/identification', 'ID Card');
                break;
            case self::TYPE_PASSPORT:
                $genderName = Yii::t('yuncms/identification', 'Passport ID');
                break;
            case self::TYPE_ARMYID:
                $genderName = Yii::t('yuncms/identification', 'Army ID');
                break;
            case self::TYPE_TAIWANID:
                $genderName = Yii::t('yuncms/identification', 'Taiwan ID');
                break;
            case self::TYPE_HKMCID:
                $genderName = Yii::t('yuncms/identification', 'HKMC ID');
                break;
            default:
                throw new \RuntimeException('Not set!');
        }
        return $genderName;
    }

    /**
     * @return \yii\db\ActiveQueryInterface
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * 获取认证实例
     * @param int $userId
     * @return null|ActiveRecord|static
     */
    public static function findByUserId($userId)
    {
        if (($model = self::findOne(['user_id' => $userId])) === null) {
            $model = self::create(['scenario' => self::SCENARIO_CREATE, 'registrationPolicy' => true]);
        }
        return $model;
    }

    /**
     * 是否实名认证
     * @param int $user_id
     * @return bool
     */
    public static function isAuthentication($user_id)
    {
        $user = static::findOne(['user_id' => $user_id]);
        return $user ? $user->status == static::STATUS_AUTHENTICATED : false;
    }

    /**
     * 删除前先删除附件
     * @return bool
     * @throws \yii\base\Exception
     */
    public function beforeDelete()
    {
        if (parent::beforeDelete()) {
            Module::deleteImage($this->user_id, '_passport_cover_image.jpg');
            Module::deleteImage($this->user_id, '_passport_person_page_image.jpg');
            Module::deleteImage($this->user_id, '_passport_self_holding_image.jpg');
            return true;
        } else {
            return false;
        }
    }
}
