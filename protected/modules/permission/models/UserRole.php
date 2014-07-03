<?php

/**
 * This is the model class for table "{{permission_user_role}}".
 *
 * The followings are the available columns in table '{{permission_user_role}}':
 * @property string $user_id
 * @property integer $role_id
 */
class UserRole extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{permission_user_role}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('user_id, role_id', 'required'),
            array('role_id', 'numerical', 'integerOnly' => true),
            array('user_id', 'length', 'max' => 10),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('user_id, role_id', 'safe', 'on' => 'search'),
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
            'user_id' => 'User',
            'role_id' => 'Role',
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

        $criteria->compare('user_id', $this->user_id, true);
        $criteria->compare('role_id', $this->role_id);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return UserRole the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    function saveUserRole($user_id = 0, $role_id = 0) {

        $model_data = array(
            'user_id' => $user_id,
            'role_id' => $role_id,
        );
        $model = new UserRole();
        $model->attributes = $model_data;
        if ($model->validate()) {
            //delete old user role value 
            $model->deleteAllByAttributes(array('user_id' => $user_id));
            $model = new UserRole();
            $model->attributes = $model_data;
            $model->save();
        }
    }

    function getUserRole($user) {
        $roleModel = new UserRole;
        if ($user->id) {

            $userRoleArray = $user->role;
            if ($userRoleArray) {
                $roleModel->role_id = $userRoleArray[0]->ROLE_ID;
            }
        }

        return $roleModel;
    }

    function getUserRoleId($user) {
        $roleId = $this->getDefaultRole();
        if ($user->id) {
            $userRoleArray = $user->role;
            if (!empty($userRoleArray)) {
                $roleId = $userRoleArray[0]->ROLE_ID;
            }
        }
        return $roleId;
    }

    function getDefaultRole() {
        return 1;
    }

    public function can($action) {
        $permissions = Yii::app()->user->getState('permissions');
        if (!empty($permissions)) {
            foreach($permissions as $permissionId => $permissionKey) {
                if (is_numeric($action)) {
                    if ($permissionId == $action)
                        return true;
                }else {
                    if ($permissionKey == $action)
                        return true;
                }
            }
        }
        return false;
    }

}
