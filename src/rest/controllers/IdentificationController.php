<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\identification\rest\controllers;

use Yii;
use yii\web\MethodNotAllowedHttpException;
use yii\web\ServerErrorHttpException;
use yuncms\identification\rest\models\Identification;
use yuncms\rest\Controller;

/**
 * 实名认证接口
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class IdentificationController extends Controller
{
    /**
     * Declares the allowed HTTP verbs.
     * Please refer to [[VerbFilter::actions]] on how to declare the allowed verbs.
     * @return array the allowed HTTP verbs.
     */
    protected function verbs()
    {
        return array_merge(parent::verbs(), [
            'get' => ['GET'],
            'put' => ['POST'],
            'identification' => ['GET', 'POST'],
        ]);
    }

    /**
     * 获取实名认证
     * @return Identification
     */
    public function actionGet()
    {
        return Identification::findByUserId(Yii::$app->user->getId());
    }

    /**
     * 提交实名认证
     * @return Identification
     * @throws ServerErrorHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionPut()
    {
        $model = Identification::findByUserId(Yii::$app->user->getId());
        $model->scenario = Identification::SCENARIO_UPDATE;
        $model->load(Yii::$app->getRequest()->getBodyParams(), '');
        if (($model->save()) != false) {
            $response = Yii::$app->getResponse();
            $response->setStatusCode(201);
            return $model;
        } elseif (!$model->hasErrors()) {
            throw new ServerErrorHttpException('Failed to create the object for unknown reason.');
        }
        return $model;
    }

    /**
     * 实名认证
     * @return Identification
     * @throws MethodNotAllowedHttpException
     * @throws ServerErrorHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionIdentification()
    {
        if (Yii::$app->request->isPost) {
            return $this->actionPut();
        } else if (Yii::$app->request->isGet) {
            return $this->actionGet();
        }
        throw new MethodNotAllowedHttpException();
    }
}
