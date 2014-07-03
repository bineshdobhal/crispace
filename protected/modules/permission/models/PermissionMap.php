<?php

/**
 * This is the model class for table "{{permission_map}}".
 *
 * The followings are the available columns in table '{{permission_map}}':
 * @property string $principal_id
 * @property string $permission_id
 * @property string $type
 */
class PermissionMap extends CActiveRecord
{
	/*define type constant 
         * 
         */
         CONST TYPE_ROLE='role';
         CONST TYPE_USER='user';
        /**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{permission_map}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('principal_id, permission_id', 'required'),
			array('principal_id, permission_id', 'length', 'max'=>10),
			array('type', 'length', 'max'=>4),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('principal_id, permission_id, type', 'safe', 'on'=>'search'),
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
			'principal_id' => 'Principal',
			'permission_id' => 'Permission',
			'type' => 'Type',
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

		$criteria->compare('principal_id',$this->principal_id,true);
		$criteria->compare('permission_id',$this->permission_id,true);
		$criteria->compare('type',$this->type,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PermissionMap the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        
        public function saveRolePermission($roleId=0){
            
            //delete the all permissions 
            $this->deleteRolePermission($roleId);
            $selectedPermissions =  Yii::app()->request->getPost('permission');
            if(!empty($selectedPermissions )){
                foreach($selectedPermissions as $permissionId){
                    $model=new PermissionMap;
                    $model->principal_id=$roleId;
                    $model->permission_id=$permissionId;
                    $model->type=self::TYPE_ROLE;
                    $model->save();
                }
                
            }
            
        }
        
        /**
		 * delete all permissions assigned to the role
		 * @param numeric $roleId role id
		 * @return boolean true
		 */
        public function deleteRolePermission($roleId=0){
            $this->deleteAllByAttributes(array(
                'principal_id'=>$roleId,
                'type'=>self::TYPE_ROLE,
            ));
            return true;
        }
        
        
        
        
        
        
        
        
        
}
