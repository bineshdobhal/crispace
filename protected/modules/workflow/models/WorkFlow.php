<?php

/**
 * This is the model class for table "{{work_flow_log}}".
 *
 * The followings are the available columns in table '{{work_flow_log}}':
 * @property string $WFLOG_ID
 * @property string $data_id
 * @property string $data_type
 * @property string $user_id
 * @property string $to_user_id
 * @property string $comment
 * @property integer $action
 * @property integer $status
 * @property string $creation_time
 * @property string $parent_id
 */
class WorkFlow extends CActiveRecord
{
    /**
     * Action Constants 
     */

    const ACTION_APPROVE = 1;
    const ACTION_FORWARD = 2;
    const ACTION_REJECT = 3;


    /**
     * Status Constants 
     */
    const STATUS_NEW = 0;
    const STATUS_PROCESSED = 1;

    /**
     * Request flow constants
     */
    const REQUEST_NEW = 0;
    const REQUEST_FORWARDED = -1;//must be negative
    const REQUEST_RECEIVED = -2;//must be negative
    
    
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{work_flow_log}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('creation_time', 'required'),
			array('action, status', 'numerical', 'integerOnly'=>true),
			array('data_id, user_id, to_user_id, parent_id', 'length', 'max'=>11),
			array('data_type', 'length', 'max'=>45),
			array('comment', 'length', 'max'=>300),
                        array('comment', 'conditionalRequired'),//custom validation
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('WFLOG_ID, data_id, data_type, user_id, to_user_id, comment, action, status, creation_time, parent_id', 'safe', 'on'=>'search'),
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
			'WFLOG_ID' => 'Wflog',
			'data_id' => 'Data',
			'data_type' => 'Data Type',
			'user_id' => 'User',
			'to_user_id' => 'To User',
			'comment' => 'Comment',
			'action' => 'Action',
			'status' => 'Status',
			'creation_time' => 'Creation Time',
			'parent_id' => 'Parent',
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

		$criteria->compare('WFLOG_ID',$this->WFLOG_ID,true);
		$criteria->compare('data_id',$this->data_id,true);
		$criteria->compare('data_type',$this->data_type,true);
		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('to_user_id',$this->to_user_id,true);
		$criteria->compare('comment',$this->comment,true);
		$criteria->compare('action',$this->action);
		$criteria->compare('status',$this->status);
		$criteria->compare('creation_time',$this->creation_time,true);
		$criteria->compare('parent_id',$this->parent_id,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return WorkFlow the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        
        function getActionArray() {
        $actionArray = array(
            self::ACTION_APPROVE => Yii::t('workflow', 'Approved'),
            self::ACTION_FORWARD => Yii::t('workflow', 'Forwarded'),
            self::ACTION_REJECT => Yii::t('workflow', 'Rejected'),
        );
        return $actionArray;
    }

    function getAction() 
    {
        $actionArray = self::getActionArray();
        return (isset($actionArray[$this->action])) ? $actionArray[$this->action] : $actionArray[self::ACTION_FORWARD];
    }

    function getStatusArray() {
        $statusArray = array(
            self::STATUS_NEW => Yii::t('workflow', 'New'),
            self::STATUS_PROCESSED => Yii::t('workflow', 'Processed'),
        );
        return $statusArray;
    }

    function getStatus() {
        $statusArray = self::getStatusArray();
        return (isset($statusArray[$this->status])) ? $statusArray[$this->status] : $statusArray[self::STATUS_NEW];
    }
    
    /**
     * function applys conditional required validation rule to comment attribute. 
     * There may be situations when a value must be provided for comment attribute and when it is not required.
     * eg. When a staf member rejects the user account approval, he/she must provide a reason for it which is saved in the comment attribute. 
     */
    public function conditionalRequired($attribute, $params){
           if(!$this->hasErrors('action')){
                   if($this->action == WorkFlow::ACTION_REJECT){
                       if(empty($this[$attribute])){
                                   $err_msg = "Reason For Rejection cannot be empty.";
                                   $this->addError($attribute, $err_msg);
                           }
                   }
           }
    } 
    
    /**
     * Function returns the meaning of the request flow code.
     * Request flow here means whether a request is forwarded to or received by the current staff member.
     * The function is declared static because it will be used in different modules(e.g. As in User module in the User Approval process).
     * 
     * If request flow code in not supplied it returns an array containing all the request flow codes along with meaning. 
     * 
     * @param INTEGER $code the valid request flow code. Default value null.
     * @return MIXED returns string containing the meaning of the code, false if invalid code is supplied and array containing all codes and their meaning if no code is supplied. 
     */
    public static function getRequestFlowAlias($code = null){
        $requests = array(
                        self::REQUEST_NEW => "New Request",
                        self::REQUEST_RECEIVED => "Received",
                        self::REQUEST_FORWARDED => "Forwarded",
                    );
        if($code==null)
            return $requests;
        else
            return isset($requests[$code])?$requests[$code]:false;
        
    }
    
}
