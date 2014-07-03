<?php

/**
 * This is the model class for table "{{email_template}}".
 *
 * The followings are the available columns in table '{{email_template}}':
 * @property integer $TEMPLATE_ID
 * @property string $template_title
 * @property string $template_code
 * @property string $template_text
 * @property string $template_subject
 * @property string $creation_time
 * @property string $update_time
 * @property integer $is_active
 */
class Email extends CActiveRecord
{
    const ACTIVE = 1;
    const INACTIVE = 0;
	const REDIRECT_NO = 0;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{email_template}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('is_active', 'numerical', 'integerOnly'=>true),
			array('template_title', 'length', 'max'=>45),
			array('template_code', 'length', 'max'=>150),
			array('template_subject', 'length', 'max'=>200),
			array('template_text, creation_time, update_time', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('TEMPLATE_ID, template_title, template_code, template_text, template_subject, creation_time, update_time, is_active', 'safe', 'on'=>'search'),
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
			'TEMPLATE_ID' => 'Template',
			'template_title' => 'Template Title',
			'template_code' => 'Template Code',
			'template_text' => 'Template Text',
			'template_subject' => 'Template Subject',
			'creation_time' => 'Creation Time',
			'update_time' => 'Update Time',
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

		$criteria->compare('TEMPLATE_ID',$this->TEMPLATE_ID);
		$criteria->compare('template_title',$this->template_title,true);
		$criteria->compare('template_code',$this->template_code,true);
		$criteria->compare('template_text',$this->template_text,true);
		$criteria->compare('template_subject',$this->template_subject,true);
		$criteria->compare('creation_time',$this->creation_time,false);
		$criteria->compare('update_time',$this->update_time,false);
		$criteria->compare('is_active',$this->is_active);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Email the static model class
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
    
     function formatIndentifier() {
        if ($this->template_title) {
            $this->template_title  = CoreUrl::model()->formatUrl($this->template_title );
        } else {
            $this->template_title  = CoreUrl::model()->formatUrl($this->template_title );
        }
    }
    
    /*
     * event fire to check the user activity
     * @return array of status in key=>value pair 
     */
    
     protected function beforeSave() {
        if (parent::beforeSave()) {
            if ($this->isNewRecord) {
                $this->creation_time = $this->update_time = Yii::app()->core->getCurrentTime();
            } else {
                $this->update_time = Yii::app()->core->getCurrentTime();
            }
            $this->formatIndentifier();
            return true;
        }
        else
            return false;
    }

    /*
     * event fire to check the user activity
     * @return array of status in key=>value pair 
     */
    
    protected function afterSave() {
        parent::afterSave();
        if ($this->isNewRecord) {
            $activity = Yii::t('cms', '{data_type} is {action} by user {user}', array(
                        '{data_type}' => DataType::DATE_TYPE_EMAIL,
                        '{action}' => 'Created',
                        '{user}' => Yii::app()->user->name,
            ));
        } else {
            $activity = Yii::t('cms', '{data_type} is {action} by user {user}', array(
                        '{data_type}' => DataType::DATE_TYPE_EMAIL,
                        '{action}' => 'Updated',
                        '{user}' => Yii::app()->user->name,
            ));
        }
        //add  the user activity  data 

        UserActivity::model()->addActivity(array(
            'data_type' => DataType::DATE_TYPE_EMAIL,
            'data_id' => $this->TEMPLATE_ID,
            'user_id' => Yii::app()->user->id,
            'activity' => $activity,
        ));

        //add/update  the core url entry 
        Email::model()->addUrl(array(
            'request_path' => $this->template_title,
            'target_path' => 'cms/email/view/id/' . $this->TEMPLATE_ID,
            'data_type' => DataType::DATE_TYPE_EMAIL,
            'data_id' => $this->TEMPLATE_ID,
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
    
    public function formatUrl($title) {
        return Yii::app()->core->formatUrl($title);
    }
}
