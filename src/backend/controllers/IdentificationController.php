<?php

namespace yuncms\identification\backend\controllers;

use Yii;
use yii\web\Response;
use yii\web\Controller;
use yii\bootstrap\ActiveForm;
use yii\web\NotFoundHttpException;
use yuncms\identification\models\Identification;
use yuncms\identification\backend\models\IdentificationSearch;

/**
 * AuthenticationController implements the CRUD actions for Authentication model.
 */
class IdentificationController extends Controller
{

    public function actions()
    {
        return [
            //....
            'settings' => [
                'class' => 'yuncms\actions\SettingsAction',
                'modelClass' => 'yuncms\identification\backend\models\Settings',
                //'scenario' => 'user',
                //'scenario' => 'site', // Change if you want to re-use the model for multiple setting form.
                'viewName' => 'settings'    // The form we need to render
            ],
            //....
        ];
    }

    /**
     * Lists all Authentication models.
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public function actionIndex()
    {
        $searchModel = Yii::createObject(IdentificationSearch::class);
        $dataProvider = $searchModel->search(Yii::$app->request->get());

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Authentication model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Updates an existing Authentication model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->scenario = 'verify';
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success', Yii::t('yuncms','Update success.'));
            return $this->redirect(['view', 'id' => $model->user_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Finds the Authentication model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Identification the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Identification::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException (Yii::t('yuncms', 'The requested page does not exist.'));
        }
    }
}
