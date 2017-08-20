<?php

namespace app\controllers;

use app\models\forms\ShopForm;
use Yii;
use app\models\Shop;
use app\models\ShopSearch;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\components\UploadedFile;

/**
 * ShopController implements the CRUD actions for Shop model.
 */
class ShopController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Shop models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel  = new ShopSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Shop model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model     = new Shop();

        $formModel = new ShopForm(['scenario' => ShopForm::SCENARIO_CREATE]);
        $formModel->setModel($model);

        if ($formModel->load(Yii::$app->request->post())) {
            //set file to form
            $formModel->file = UploadedFile::getInstance($formModel, 'file');

            //save from with data and model
            if ($formModel->save()) {
                Yii::$app->getSession()->setFlash('created', Yii::t('app', 'Shop data has been imported from file and will be indexed in 5 minutes'));
                return $this->redirect(['index', 'id' => $formModel->getModel()->id]);
            }
        }

        return $this->render('create', [
            'formModel' => $formModel,
        ]);
    }

    /**
     * Updates an existing Shop model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $formModel = new ShopForm(['scenario' => ShopForm::SCENARIO_UPDATE]);
        $formModel->setModel($model, true);

        if ($formModel->load(Yii::$app->request->post())) {
            //set file to form
            $formModel->file = UploadedFile::getInstance($formModel, 'file');
            //save from with data and model
            if ($formModel->save()) {
                return $this->redirect(['index', 'id' => $formModel->getModel()->id]);
            }
        }

        return $this->render('update', [
            'formModel' => $formModel,
        ]);
    }

    /**
     * Deletes an existing Shop model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Shop model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Shop the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Shop::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
