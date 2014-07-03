<?php

/**
 * This is the model class for table "{{cms_property_amenities}}".
 *
 * The followings are the available columns in table '{{cms_property_amenities}}':
 * @property integer $AMENITIES_ID
 * @property string $amenities_name
 * @property string $amenities_icon
 * @property integer $is_active
 */
class Amenities extends CActiveRecord
{
	const ACTIVE = 1;
    const INACTIVE = 0;
	const REDIRECT_NO = 0;
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{cms_property_amenities}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('amenities_name, amenities_icon', 'required'),
			array('is_active', 'numerical', 'integerOnly'=>true),
			array('amenities_name', 'length', 'max'=>65),
			array('amenities_icon', 'length', 'max'=>125),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('AMENITIES_ID, amenities_name, amenities_icon, is_active', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'AMENITIES_ID' => 'Amenities',
			'amenities_name' => 'Amenities Name',
			'amenities_icon' => 'Amenities Icon',
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
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('AMENITIES_ID',$this->AMENITIES_ID);
		$criteria->compare('amenities_name',$this->amenities_name,true);
		$criteria->compare('amenities_icon',$this->amenities_icon,true);
		$criteria->compare('is_active',$this->is_active);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Amenities the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/*
     * get the status dropdown
     * @return array of status in key=>value pair 
     */

    function getStatusArray() {
        $statusArray = array(
            self::ACTIVE => Yii::t('cms', 'Active'),
            self::INACTIVE => Yii::t('cms', 'In active'),
        );
        return $statusArray;
    }
    
	/*
     * get the status dropdown
     * @return array of status in key=>value pair 
     */
	function formatIndentifier() {
        if ($this->amenities_name) {
            $this->amenities_name  = CoreUrl::model()->formatUrl($this->amenities_name );
        } else {
            $this->amenities_name  = CoreUrl::model()->formatUrl($this->amenities_name );
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
                        '{data_type}' => DataType::DATE_TYPE_AMENITIES,
                        '{action}' => 'Created',
                        '{user}' => Yii::app()->user->name,
            ));
        } else {
            $activity = Yii::t('cms', '{data_type} is {action} by user {user}', array(
                        '{data_type}' => DataType::DATE_TYPE_AMENITIES,
                        '{action}' => 'Updated',
                        '{user}' => Yii::app()->user->name,
            ));
        }
        //add  the user activity  data 

        UserActivity::model()->addActivity(array(
            'data_type' => DataType::DATE_TYPE_AMENITIES,
            'data_id' => $this->AMENITIES_ID,
            'user_id' => Yii::app()->user->id,
            'activity' => $activity,
        ));

        //add/update  the core url entry 
        Email::model()->addUrl(array(
            'request_path' => $this->amenities_name,
            'target_path' => 'cms/email/view/id/' . $this->AMENITIES_ID,
            'data_type' => DataType::DATE_TYPE_AMENITIES,
            'data_id' => $this->AMENITIES_ID,
            'redirect' => Email::REDIRECT_NO,
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
     * Add the url redirection 
     * check the url for the duplicate recored 
     * @param array $urlData url data 
     * @return true 
     * 
     */
	 
    public function formatUrl($title) {
        return Yii::app()->core->formatUrl($title);
    }
}
