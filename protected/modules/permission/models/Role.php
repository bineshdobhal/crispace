<?php

/**
 * This is the model class for table "{{permission_role}}".
 *
 * The followings are the available columns in table '{{permission_role}}':
 * @property integer $ROLE_ID
 * @property string $title
 * @property string $key
 * @property integer $parent_id
 */
class Role extends CActiveRecord {
    /*
     * define parent constant
     */

    CONST PARENT_NONE = 0;

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{permission_role}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('title, key', 'required'),
            array('key', 'unique'),
            array('parent_id', 'numerical', 'integerOnly' => true),
            array('title', 'length', 'max' => 100),
            array('key', 'length', 'max' => 45),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('ROLE_ID, title, key, parent_id', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'parent' => array(self::BELONGS_TO, __CLASS__, 'parent_id'),
            'childs' => array(self::HAS_MANY, __CLASS__, 'parent_id'),
            'permissions'=> array(self::MANY_MANY, 'PermissionAction',  PermissionMap::model()->tableName().'(principal_id, permission_id)','condition'=>"type='".PermissionMap::TYPE_ROLE."'"),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'ROLE_ID' => 'Role',
            'title' => 'Title',
            'key' => 'Key',
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
    public function search() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('ROLE_ID', $this->ROLE_ID);
        $criteria->compare('title', $this->title, true);
        $criteria->compare('key', $this->key, true);
        $criteria->compare('parent_id', $this->parent_id);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Role the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function getChilds($level = 0) {
        $ret = array();
        $level++;

        if ($this->childs) {
            foreach ($this->childs as $child) {
                $ret[] = array('id' => $child->ROLE_ID,
                    'title' => $child->title,
                    'key' => $child->key,
                    'level' => $level,
                    'childs' => $child->getChilds($level));
            }
        }

        return $ret;
    }

    /**
     * get all parent categories with childs
     *
     * @return array
     */
    public function getRoleArray() {
        $ret = array();
        $level = 0;
        $parentRoles = $this->findAll(array('condition' => 'parent_id=' . self::PARENT_NONE));
        if (!empty($parentRoles)) {
            foreach ($parentRoles as $k) {
                $ret[] = array('id' => $k->ROLE_ID,
                    'title' => $k->title,
                    'key' => $k->key,
                    'childs' => $k->getChilds($level));
            }
        }
        return $ret;
    }

    /**
     * create ready for use in html option role hierarhy
     *
     * @param array $_container
     * @param array $_data
     */
    public function getRoleList(&$_container = array(), $_data = array()) {
        if (!empty($_data)) {
            foreach ($_data as $k) {
                $space = '';
                if (isset($k['level'])) {
                    $space = str_repeat('&nbsp;', $k['level']);
                }

                $_container[$k['id']] = $space . $k['title'];
                if (!empty($k['childs'])) {
                    $this->getRoleList($_container, $k['childs']);
                }
            }
        }
    }
    
    public function getRolesDropDownList(){
        $rolesArray = $this->getRoleArray();
        $roleList=array();
	$this->getRoleList($roleList, $rolesArray);
        return $roleList;
        
    }
    
    
    function getParent(){
        if(self::PARENT_NONE != $this->parent_id){
            return $this->parent->title;
        }else{
            return Yii::t('permission', 'None');
        }
    }
    
    
    function getSelectedPermission(){
        $selectedPermissions=array();
        $rolePermissions=$this->permissions;
        if(!empty($rolePermissions)){
           foreach($rolePermissions as $rolePermission){
               $selectedPermissions[]=$rolePermission->ACTION_ID;
           }
           return $selectedPermissions;
        }else{
            $selectedPermissions=  Yii::app()->request->getPost('permission');
            
        }
        return $selectedPermissions;
    }
    
    /**
     * This method is invoked after saving a record successfully.
    */
    protected function afterSave() {
        parent::afterSave();
        //save role permissions
        PermissionMap::model()->saveRolePermission($this->ROLE_ID);
    }

}
