<?php

/**
 * This is the model class for table "{{user_activity}}".
 *
 * The followings are the available columns in table '{{user_activity}}':
 * @property string $ACTIVITY_ID
 * @property string $data_type
 * @property string $data_id
 * @property string $activity
 * @property string $user_id
 * @property string $creation_time
 */
class UserActivity extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{user_activity}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('user_id', 'required'),
            array('data_type', 'length', 'max' => 15),
            array('data_id', 'length', 'max' => 45),
            array('user_id', 'length', 'max' => 10),
            array('activity, creation_time', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('ACTIVITY_ID, data_type, data_id, activity, user_id, creation_time', 'safe', 'on' => 'search'),
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
            'ACTIVITY_ID' => 'Activity',
            'data_type' => 'Data Type',
            'data_id' => 'Data ID',
            'activity' => 'Activity',
            'user_id' => 'User',
            'creation_time' => 'Creation Time',
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

        $criteria->compare('ACTIVITY_ID', $this->ACTIVITY_ID, true);
        $criteria->compare('data_type', $this->data_type, true);
        $criteria->compare('data_id', $this->data_id, true);
        $criteria->compare('activity', $this->activity, true);
        $criteria->compare('user_id', $this->user_id, true);
        $criteria->compare('creation_time', $this->creation_time, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return UserActivity the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /*
     * handle beforeSave events 
     */
    protected function beforeSave() {
        if (parent::beforeSave()) {
            $this->creation_time = Yii::app()->core->getCurrentTime();
            
            return true;
        }
        else
            return false;
    }
    
     /*
      * log the user activity into the system 
      * @param array $activityData user current  activity data 
      * @return true
     */
    function addActivity($activityData = array()) {
        $model = new UserActivity;
        $model->attributes = $activityData;
        if ($model->validate())
            $model->save();
        return true;
    }

}
