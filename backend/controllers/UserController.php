<?php

namespace backend\controllers;

use Yii;
use backend\models\User;
use backend\models\UserSearch;
use yii\web\Controller;
use yii\helpers\Url;
use yii\helpers\Json;
use yii\helpers\Html;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use sintret\gii\models\LogUpload;
use sintret\gii\components\Util;

use amnah\yii2\user\models\UserKey;
use amnah\yii2\user\models\UserAuth;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $grid = 'grid-'.self::className();
        $reset = Yii::$app->getRequest()->getQueryParam('p_reset');
        if ($reset) {
            \Yii::$app->session->set($grid, "");
        } else {
            $rememberUrl = Yii::$app->session->get($grid);
            $current = Url::current();
            if ($rememberUrl != $current && $rememberUrl) {
                Yii::$app->session->set($grid, "");
                $this->redirect($rememberUrl);
            }
            if (Yii::$app->getRequest()->getQueryParam('_pjax')) {
                \Yii::$app->session->set($grid, "");
                \Yii::$app->session->set($grid, Url::current());
            }
        }

        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model using DetailView
     * Admin can edit/delete User model here
     * @param integer $id
     * @return mixed
     */
    public function actionView($id, $act = 'add')
    {
        $model = $id > 0 ? $this->findModel($id) : new User();
        $post = Yii::$app->request->post();
        // DELETE: process ajax delete
        if (Yii::$app->request->isAjax && isset($post['kvdelete'])) {
            $profile = $model->profile;
            UserKey::deleteAll(['user_id' => $model->id]);
            UserAuth::deleteAll(['user_id' => $model->id]);
            if ($profile) $profile->delete();
            $model->delete();
            echo Json::encode([
                'success' => true,
                'messages' => [
                    'kv-detail-info' => 'This user was successfully deleted. ' .
                        Html::a('<i class="glyphicon glyphicon-hand-right"></i>  Click here',
                            ['/user/admin'], ['class' => 'btn btn-sm btn-success']) . ' to return the list.'
                ]
            ]);
            return;
        }
        // UPDATE: return messages on update of record
        if (!empty($post)) {
            $profile = $model->profile ? $model->profile :
                Yii::$app->getModule("user")->model("Profile");
            $model->setScenario("admin");
            if ($model->load($post) && $model->validate() && $profile->load($post) && $profile->validate()) {
                $model->save(false);
                $profile->setUser($model->id)->save(false);
                Yii::$app->session->setFlash('kv-detail-success', 'Well done! successfully to save data!');
            } else {
                Yii::$app->session->setFlash('kv-detail-error', 'Something went wrong !');
            }
        }
        // VIEW
        return $this->render('view', [
            'model' => $model,
            'act' => $act
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        // $this->redirect(["view?id=new"]);

        $model = new User();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Well done! successfully to save data!  ');
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        // $this->redirect(["view?id={$id}&act=edit"]);

        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Well done! successfully to update data!  ');
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        // $this->redirect(["view?id={$id}&act=edit"]);

        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', 'Well done! successfully to deleted data!  ');

        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionSample() {

        //$objPHPExcel = new \PHPExcel();
        $template = Util::templateExcel();
        $model = new User;
        $date = date('YmdHis');
        $name = $date.'User';
        //$attributes = $model->attributeLabels();
        $models = User::find()->all();
        $excelChar = Util::excelChar();
        $not = Util::excelNot();

        foreach ($model->attributeLabels() as $k=>$v){
            if(!in_array($k, $not)){
                $attributes[$k]=$v;
            }
        }

        $objReader = \PHPExcel_IOFactory::createReader('Excel5');
        $objPHPExcel = $objReader->load(Yii::getAlias($template));

        return $this->render('sample', ['models' => $models,'attributes'=>$attributes,'excelChar'=>$excelChar,'not'=>$not,'name'=>$name,'objPHPExcel' => $objPHPExcel]);
    }

    public function actionParsing() {
        $num = 0;
        $fields = [];
        $values = [];
        $log = '';
        $route = '';
        $model = new LogUpload;

        $date = date('Ymdhis') . Yii::$app->user->identity->id;

        if (Yii::$app->request->isPost) {
            $model->fileori = UploadedFile::getInstance($model, 'fileori');

            if ($model->validate()) {
                $fileOri = Yii::getAlias(LogUpload::$imagePath) . $model->fileori->baseName . '.' . $model->fileori->extension;
                $filename = Yii::getAlias(LogUpload::$imagePath) . $date . '.' . $model->fileori->extension;
                $model->fileori->saveAs($filename);
            }
            $params = Util::excelParsing(Yii::getAlias($filename));
            $model->params = \yii\helpers\Json::encode($params);
            $model->title = 'parsing User';
            $model->fileori = $fileOri;
            $model->filename = $filename;


            if ($params)
                foreach ($params as $k => $v) {
                    foreach ($v as $key => $val) {
                        if ($num == 0) {
                            $fields[$key] = $val;
                            $max = $key;
                        }

                        if ($num >= 3) {
                            $values[$num][$fields[$key]] = $val;
                        }
                    }
                    $num++;
                }
            if (in_array('id', $fields)) {
                $model->type = LogUpload::TYPE_UPDATE;
            } else {
                $model->type = LogUpload::TYPE_INSERT;
            }
            $model->keys = \yii\helpers\Json::encode($fields);
            $model->values = \yii\helpers\Json::encode($values);
            if ($model->save()) {
                $log = 'log_User'. Yii::$app->user->id;
                Yii::$app->session->setFlash('success', 'Well done! successfully to Parsing data, see log on log upload menu! Please Waiting for processing indicator if available...  ');
                Yii::$app->session->set($log, $model->id);
                $notification = new \sintret\gii\models\Notification;
                $notification->title = 'parsing User';
                $notification->message = Yii::$app->user->identity->username . ' parsing User ';
                $notification->params = \yii\helpers\Json::encode(['model' => 'User', 'id' => $model->id]);
                $notification->save();
            }
        }
        $route = 'user/parsing-log';

        return $this->render('parsing', ['model' => $model,'log'=>$log,'route'=>$route]);
    }

    public function actionParsingLog($id) {
        $mod = LogUpload::findOne($id);
        $type = $mod->type;
        $keys = \yii\helpers\Json::decode($mod->keys);
        $values = \yii\helpers\Json::decode($mod->values);
        $modelAttribute = new User;
        $not = Util::excelNot();

            foreach ($values as $value) {
                if ($type == LogUpload::TYPE_INSERT)
                    $model = new User;
                else
                    $model = User::findOne($value['id']);

                foreach ($keys as $v) {
                        $model->$v = $value[$v];
                }

                $e = 0;
                if ($model->save()) {
                    $model = NULL;
                    $pos = NULL;
                } else {
                    $error[] = \yii\helpers\Json::encode($model->getErrors());
                    $e = 1;
                }
            }

        if ($error) {
            foreach ($error as $err) {
                if ($err) {
                    $er[] = $err;
                    $e+=1;
                }
            }
            if ($e) {
                $mod->warning = \yii\helpers\Json::encode($er);
                $mod->save();
                echo '<pre>';
                print_r($er);
            }
        }
    }

    public function actionExcel() {
        $searchModel = new UserSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $modelAttribute = new User;
        $not = Util::excelNot();
        foreach ($modelAttribute->attributeLabels() as $k=>$v){
            if(!in_array($k, $not)){
                $attributes[$k] = $v;
            }
        }

        $models = $dataProvider->getModels();
        $objReader = \PHPExcel_IOFactory::createReader('Excel5');
        $objPHPExcel = $objReader->load(Yii::getAlias(Util::templateExcel()));
        $excelChar = Util::excelChar();
        return $this->render('_excel', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'attributes' => $attributes,
                    'models' => $models,
                    'objReader' => $objReader,
                    'objPHPExcel' => $objPHPExcel,
                    'excelChar' => $excelChar
        ]);
    }
    public function actionDeleteAll() {
        $pk = Yii::$app->request->post('pk'); // Array or selected records primary keys
        $explode = explode(",", $pk);
        if ($explode)
            foreach ($explode as $v) {
                if($v)
                $this->findModel($v)->delete();
            }
        echo 1;
    }
}
