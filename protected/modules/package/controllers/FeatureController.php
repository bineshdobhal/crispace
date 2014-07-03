<?php

class FeatureController extends Controller {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/main';

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array(
            array('allow', // allow all users to perform 'index' and 'view' actions
                'actions' => array('index', 'request','review','proceed','purchase','cancel'),
                'users' => array('@'),
            ),
           array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {

      
        $dataProvider = new CActiveDataProvider('PackageFeature', array(
            'pagination' => array(
                'pageSize' => 100,
            ),
        ));
        $this->render('index', array(
            'dataProvider' => $dataProvider,
            ''
        ));
    }

    /**
     * Lists all models.
     */
    public function actionRequest($id=0) {
       $model = $this->getOrderModel($id);
       if (Yii::app()->request->isPostRequest) {
          
           if( $model->validateItems() )
           {
               
               $model->saveOrder();
               $this->redirect(array('review','id'=>$model->PO_ID));
               
           }else{
               CoreMessage::model()->addError(Yii::t('package','Please select at least one check box'));
           }
       }
       $dataProvider = new CActiveDataProvider('PackageFeature', array(
            'pagination' => array(
                'pageSize' => 100,
            ),
        ));
        $this->render('request', array(
            'dataProvider' => $dataProvider,
            'order'=>$model,
        ));
    }
    
    function actionReview($id=0){
        if(!$id){
            throw new CHttpException(404,'The requested page does not exist.');
        }
        $model = $this->getOrderModel($id);
        $criteria = new CDbCriteria;
        $criteria->compare('order_id',$id);
        $dataProvider = new CActiveDataProvider('PackageOrderItem', array(
            'criteria'=>$criteria,
            'pagination' => array(
                'pageSize' => 100,
            ),
        ));
        $this->render('review', array(
            'dataProvider' => $dataProvider,
            'order'=>$model,
        ));
        
    }
    
    
    function actionCancel($id=0){
        $model=  PackageOrder::model()->findByPk($id);
        //set package status order as requested
       
        
        if($model===NULL){
             throw new CHttpException(404,'The requested page does not exist.');
        }
        $model->delete();
        CoreMessage::model()->addSuccess(Yii::t('package','Package Component Request has been successfully delete'));
        $this->redirect(array('index'));        
        
    }
    
    
    function actionProceed($id=0){
        $model=  PackageOrder::model()->findByPk($id);
        
        if($model===NULL){
             throw new CHttpException(404,'The requested page does not exist.');
        }
         $model->setOrderRequested();
        CoreMessage::model()->addSuccess(Yii::t('package','Package Component Request has been successfully sent to admin for approval'));
        $this->redirect(array('site/index'));        
    }
    
    
    
    function actionPurchase($id=0){
       $model=  PackageOrder::model()->findByPk($id);
        
        if($model===NULL){
             throw new CHttpException(404,'The requested page does not exist.');
        }
         $model->setOrderPaymentPending();
        
    }
    
    
    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return PackageFeature the loaded model
     * @throws CHttpException
     */
    public function getOrderModel($id) {
        $model = PackageOrder::model()->findByPk($id);
        if ($model === null)
            $model =new PackageOrder();
        return $model;
    }

}
