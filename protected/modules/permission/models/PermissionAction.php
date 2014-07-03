<?php

/**
 * This is the model class for table "{{permission_action}}".
 *
 * The followings are the available columns in table '{{permission_action}}':
 * @property string $ACTION_ID
 * @property string $title
 * @property string $key
 * @property string $parent_id
 */
class PermissionAction extends CActiveRecord {

    CONST PARENT_NONE = 0;

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{permission_action}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('title, key, parent_id', 'required'),
            array('title', 'length', 'max' => 255),
            array('key', 'length', 'max' => 45),
            array('parent_id', 'length', 'max' => 10),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('ACTION_ID, title, key, parent_id', 'safe', 'on' => 'search'),
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
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'ACTION_ID' => 'Action',
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

        $criteria->compare('ACTION_ID', $this->ACTION_ID, true);
        $criteria->compare('title', $this->title, true);
        $criteria->compare('key', $this->key, true);
        $criteria->compare('parent_id', $this->parent_id, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return PermissionAction the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function getChilds($level = 0) {
        $ret = array();
        $level++;

        if ($this->childs) {
            foreach ($this->childs as $child) {
                $ret[] = array('id' => $child->ACTION_ID,
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
    public function getActionArray() {
        $ret = array();
        $level = 0;
        $parentRoles = $this->findAll(array('condition' => 'parent_id=' . self::PARENT_NONE));
        if (!empty($parentRoles)) {
            foreach ($parentRoles as $k) {
                $ret[] = array('id' => $k->ACTION_ID,
                    'title' => $k->title,
                    'key' => $k->key,
                    'childs' => $k->getChilds($level));
            }
        }
        return $ret;
    }

    /**
     * create ready for use in html option action hierarhy
     *
     * @param array $_container
     * @param array $_data
     */
    public function getActionList(&$_container = array(), $_data = array()) {
        if (!empty($_data)) {
            foreach ($_data as $k) {
                $space = '';
                if (isset($k['level'])) {
                    $space = str_repeat('&nbsp;', $k['level']);
                }

                $_container[$k['id']] = $space . $k['title'];
                if (!empty($k['childs'])) {
                    $this->getActionList($_container, $k['childs']);
                }
            }
        }
    }

    public function getActionDropDownList() {
        $rolesArray = $this->getActionArray();
        $roleList = array();
        $this->getActionList($roleList, $rolesArray);
        return $roleList;
    }

    /**
     * create ready for use in html option action hierarhy
     *
     * @param array $_container
     * @param array $_data
     */
    public function getActionHtmlList(&$_container = array(), $_data = array(),$selectedPermission=array() ,$checkbox = true) {
        if (!empty($_data)) {
            foreach ($_data as $k) {
                $_container .= '<li>';
                $class = '';
                $selected = false;
                if (is_array($selectedPermission)) {
                    $selected = (in_array($k['id'], $selectedPermission)) ? true : false;
                }
                $class = 'class="' . (($selected) ? 'enable' : 'disable') . '"';
                if ($checkbox) {
                    $class = '';
                    $_container .= '<input type="checkbox" ' . (($selected) ? 'checked="checked"' : '') . ' name="permission[]" value="' . $k['id'] . '" />';
                }
                $_container .= '<span ' . $class . ' >' . $k['title'] . '</span>';
                if (!empty($k['childs'])) {
                    $_container .= '<ul>';
                    $this->getActionHtmlList($_container, $k['childs'], $selectedPermission, $checkbox);
                    $_container .= '</ul>';
                }
                $_container .= '</li>';
            }
        }
    }

    function getParent() {
        if (self::PARENT_NONE != $this->parent_id) {
            return $this->parent->title;
        } else {
            return Yii::t('permission', 'None');
        }
    }

}
