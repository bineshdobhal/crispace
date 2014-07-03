<?php

/**
 * This is the model class for table "{{location_city}}".
 *
 * The followings are the available columns in table '{{location_city}}':
 * @property integer $CITY_ID
 * @property integer $state_id
 * @property string $city_name
 * @property integer $is_active
 */
class City extends CActiveRecord
{

	const ACTIVE = 1;
    const INACTIVE = 0;
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{location_city}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('state_id, city_name, is_active', 'required'),
			array('state_id, is_active', 'numerical', 'integerOnly'=>true),
			array('city_name', 'length', 'max'=>65),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('CITY_ID, state_id, city_name, is_active', 'safe', 'on'=>'search'),
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
			'CITY_ID' => 'City',
			'state_id' => 'State',
			'city_name' => 'City Name',
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

		$criteria->compare('CITY_ID',$this->CITY_ID);
		$criteria->compare('state_id',$this->state_id);
		$criteria->compare('city_name',$this->city_name,true);
		//$criteria->compare('is_active',$this->is_active);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return City the static model class
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
	
}
