<?php

/**
 * This is the model class for table "{{location_state}}".
 *
 * The followings are the available columns in table '{{location_state}}':
 * @property integer $STATE_ID
 * @property string $state_name
 * @property string $state_code
 * @property integer $is_active
 */
class State extends CActiveRecord
{

	const ACTIVE = 1;
    const INACTIVE = 0;
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{location_state}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('state_name, state_code, is_active', 'required'),
			array('is_active', 'numerical', 'integerOnly'=>true),
			array('state_name', 'length', 'max'=>65),
			array('state_code', 'length', 'max'=>45),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('STATE_ID, state_name, state_code, is_active', 'safe', 'on'=>'search'),
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
			'STATE_ID' => 'State',
			'state_name' => 'State Name',
			'state_code' => 'State Code',
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

		$criteria->compare('STATE_ID',$this->STATE_ID);
		$criteria->compare('state_name',$this->state_name,true);
		$criteria->compare('state_code',$this->state_code,true);
		$criteria->compare('is_active',$this->is_active);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return State the static model class
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
            self::ACTIVE => Yii::t('location', 'Active'),
            self::INACTIVE => Yii::t('location', 'Inactive'),
        );
        return $statusArray;
    }
	
	
	function getStateArray(){
		return CHtml::listData(State::model()->findAll(array('order'=>'state_name ASC','condition'=>' is_active = '.State::ACTIVE)),'STATE_ID','state_name');
	}
	
	/*
     * get the status dropdown
     * @return array of status in key=>value pair 
     */
	function getStateName( $STATE_ID ){ 
		$state = CHtml::listData(State::model()->findAll(array('condition'=>' STATE_ID = '.$STATE_ID)),'STATE_ID','state_name');
		return $state[1];
		
	}
}
