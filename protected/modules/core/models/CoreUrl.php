<?php

/**
 * This is the model class for table "{{core_url_rewrite}}".
 *
 * The followings are the available columns in table '{{core_url_rewrite}}':
 * @property string $URL_REWRITE_ID
 * @property string $request_path
 * @property string $target_path
 * @property string $data_type
 * @property string $data_id
 */
class CoreUrl extends CActiveRecord {
    
    // cache key prefix 
    const CACKE_KEY_PREFIX='core_url';
    
    
    /*
     * redirect status 
     */
    const REDIRECT_NO = 0;
    const REDIRECT_PREMANENT = 1;
    const REDIRECT_TEMPORARY = 2;

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{core_url_rewrite}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('request_path, target_path,redirect,data_type', 'required'),
            array('request_path, target_path', 'length', 'max' => 255),
            array('data_type', 'length', 'max' => 45),
            array('data_id', 'length', 'max' => 10),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('URL_REWRITE_ID, request_path, target_path, data_type, data_id', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'URL_REWRITE_ID' => 'Url Rewrite',
            'request_path' => 'Request Path',
            'target_path' => 'Target Path',
            'data_type' => 'Data Type',
            'data_id' => 'Data ID',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('URL_REWRITE_ID', $this->URL_REWRITE_ID, true);
        $criteria->compare('request_path', $this->request_path, true);
        $criteria->compare('target_path', $this->target_path, true);
        $criteria->compare('data_type', $this->data_type, true);
        $criteria->compare('data_id', $this->data_id, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return CoreUrl the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function formatUrl($title) {
        return Yii::app()->core->formatUrl($title);
    }

    /*
     * Add the url redirection 
     * check the url for the duplicate recored 
     * @param array $urlData url data 
     * @return true 
     * 
     */

    public function addUrl($urlData = array()) {


        $model = new CoreUrl;
        $model->attributes = $urlData;
        //validate the data 
        if (!$model->validate()) {
            return false;
        }
        $model = CoreUrl::model()->findByAttributes(array(
            'data_type' => $urlData['data_type'],
            'data_id' => $urlData['data_id'],
            'redirect' => $urlData['redirect'],
        ));
        if (empty($model)) { //if old entry not found 
            $model = new CoreUrl;
        }
        $model->attributes = $urlData;
        $model->save();
        return true;
    }

    /*
     * delete the url 
     * check the url and delete 
     * @param array $urlData url data 
     * @return true 
     * 
     */

    public function deleteUrl($urlData = array()) {
        CoreUrl::model()->deleteAll(array(
            'condition' => "data_type ='" . $urlData['data_type'] . "' AND data_id=" . $urlData['data_id']
        ));
    }
    
    /*
     * get the redirect status values as array
     * @return array of status in key=>value pair 
     */
    function getRedirectStatusArray() {
        $redirectStatusArray = array(
            self::REDIRECT_NO => Yii::t('core', 'No'),
            self::REDIRECT_PREMANENT => Yii::t('core', 'Permanent'),
            self::REDIRECT_TEMPORARY => Yii::t('core', 'Temporary'),
        );
        return $redirectStatusArray;
    }
    
     /*
     * show the string value of redirect status 
     * @return string value of rediect status
     */
    
    function getRedirectValue() {
        $redirectStatusArray = self::getRedirectStatusArray();
        return (isset($redirectStatusArray[$this->redirect])) ? $redirectStatusArray[$this->redirect] : $redirectStatusArray[self::REDIRECT_NO];
    }

}
