<?php

/**
 * This is the model class for table "{{location_locality}}".
 *
 * The followings are the available columns in table '{{location_locality}}':
 * @property integer $LOCAL_ID
 * @property integer $city_id
 * @property integer $state_id
 * @property string $locality_name
 * @property integer $is_active
 */
class Locality extends CActiveRecord {

    const ACTIVE = 1;
    const INACTIVE = 0;
    const REDIRECT_NO = 0;

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{location_locality}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('city_id, state_id, locality_name, is_active', 'required'),
            array('city_id, state_id, is_active', 'numerical', 'integerOnly' => true),
            array('locality_name', 'length', 'max' => 65),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('LOCAL_ID, city_id, state_id, locality_name, is_active', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'city'=>array(self::BELONGS_TO,'City','city_id'),
            'state'=>array(self::BELONGS_TO,'State','state_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'LOCAL_ID' => 'Local',
            'city_id' => 'City',
            'state_id' => 'State',
            'locality_name' => 'Locality Name',
            'is_active' => 'Is Active',
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

        $criteria->compare('LOCAL_ID', $this->LOCAL_ID);
        $criteria->compare('city_id', $this->city_id);
        $criteria->compare('state_id', $this->state_id);
        $criteria->compare('locality_name', $this->locality_name, true);
        $criteria->compare('is_active', $this->is_active);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Locality the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /*
     * get the status dropdown
     * @return array of status in key=>value pair 
     */

    function getStatusArray() {
        $statusArray = array(
            self::ACTIVE => Yii::t('location', 'Active'),
            self::INACTIVE => Yii::t('location', 'In active'),
        );
        return $statusArray;
    }

    /*
     * formate formatIndentifier
     * @return array of status in key=>value pair 
     */

    function formatIndentifier() {
        if ($this->locality_name) {
            $this->locality_name = CoreUrl::model()->formatUrl($this->locality_name);
        } else {
            $this->locality_name = CoreUrl::model()->formatUrl($this->locality_name);
        }
    }

    /*
     * event fire to check the user activity
     * @return array of status in key=>value pair 
     */

    protected function afterSave() {
        parent::afterSave();
        if ($this->isNewRecord) {
            $activity = Yii::t('cms', '{data_type} is {action} by user {user}', array(
                        '{data_type}' => DataType::DATE_TYPE_LOCALITY,
                        '{action}' => 'Created',
                        '{user}' => Yii::app()->user->name,
            ));
        } else {
            $activity = Yii::t('cms', '{data_type} is {action} by user {user}', array(
                        '{data_type}' => DataType::DATE_TYPE_LOCALITY,
                        '{action}' => 'Updated',
                        '{user}' => Yii::app()->user->name,
            ));
        }
        //add  the user activity  data 
        UserActivity::model()->addActivity(array(
            'data_type' => DataType::DATE_TYPE_LOCALITY,
            'data_id' => $this->LOCAL_ID,
            'user_id' => Yii::app()->user->id,
            'activity' => $activity,
        ));

        //add/update  the core url entry 
        locality::model()->addUrl(array(
            'request_path' => $this->locality_name,
            'target_path' => 'location/locality/view/id/' . $this->LOCAL_ID,
            'data_type' => DataType::DATE_TYPE_LOCALITY,
            'data_id' => $this->LOCAL_ID,
            'redirect' => locality::REDIRECT_NO,
        ));
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
     * Add the url Formate 
     * check the url for the duplicate recored 
     * @param array $urlData url data 
     * @return true 
     * 
     */

    public function formatUrl($title) {
        return Yii::app()->core->formatUrl($title);
    }

}
