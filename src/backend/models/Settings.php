<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\identification\backend\models;

use Yii;
use yuncms\base\Model;

/**
 * 设置
 * @package yuncms\identification\models
 *
 * @property array $types
 */
class Settings extends Model
{
    /**
     * @var boolean 是否开启机器审查
     */
    public $enableMachineReview;

    /**
     * @var string 阿里云图像识别AppCode
     */
    public $ociAppCode;

    /**
     * @var integer 身份证图片存储卷
     */
    public $volume;

    /**
     * 定义字段类型
     * @return array
     */
    public function getTypes()
    {
        return [
            'enableMachineReview' => 'boolean',
            'ociAppCode' => 'string',
            'volume' => 'string',
        ];
    }

    public function rules()
    {
        return [
            [['enableMachineReview',], 'boolean'],
            [['enableMachineReview'], 'default', 'value' => true],
            [['volume', 'ociAppCode'], 'string'],
            ['volume', 'default', 'value' => 'authentication'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'enableMachineReview' => Yii::t('yuncms/identification', 'Enable Machine Review'),
            'ociAppCode' => Yii::t('yuncms/identification', 'Machine Review Code'),
            'volume' => Yii::t('yuncms/identification', 'Storage Volume'),
        ];
    }

    /**
     * 返回标识
     */
    public function formName()
    {
        return 'identification';
    }
}
