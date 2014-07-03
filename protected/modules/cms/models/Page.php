<?php

/**
 * This is the model class for table "{{cms_page}}".
 *
 * The followings are the available columns in table '{{cms_page}}':
 * @property integer $PAGE_ID
 * @property string $page_title
 * @property string $content
 * @property string $meta_title
 * @property string $meta_description
 * @property string $identifier
 * @property string $creation_time
 * @property string $update_time
 * @property integer $is_active
 * @property integer $sort_order
 */
class Page extends CActiveRecord {

    const ACTIVE = 1;
    const INACTIVE = 0;

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{cms_page}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('page_title, content,identifier', 'required'),
            array('identifier', 'unique'),
            array('is_active, sort_order', 'numerical', 'integerOnly' => true),
            array('page_title, meta_title', 'length', 'max' => 255),
            array('identifier', 'length', 'max' => 100),
            array('content, meta_description, creation_time, update_time', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('PAGE_ID, page_title, content, meta_title, meta_description, identifier, creation_time, update_time, is_active, sort_order', 'safe', 'on' => 'search'),
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
            'PAGE_ID' => 'Page',
            'page_title' => 'Page Title',
            'content' => 'Content',
            'meta_title' => 'Meta Title',
            'meta_description' => 'Meta Description',
            'identifier' => 'Identifier',
            'creation_time' => 'Creation Time',
            'update_time' => 'Update Time',
            'is_active' => 'Is Active',
            'sort_order' => 'Sort Order',
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

        $criteria->compare('PAGE_ID', $this->PAGE_ID);
        $criteria->compare('page_title', $this->page_title, true);
        $criteria->compare('identifier', $this->identifier, true);
        $criteria->compare('creation_time', $this->creation_time, true);
        $criteria->compare('update_time', $this->update_time, true);
        $criteria->compare('is_active', $this->is_active);
        $criteria->compare('sort_order', $this->sort_order);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Page the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /*
     * get the status dropdown
     * @return array of status in key=>value pair 
     */

    function getStatusArray() {
        $statusArray = array(
            self::ACTIVE => Yii::t('cms', 'Active'),
            self::INACTIVE => Yii::t('cms', 'Inactive'),
        );
        return $statusArray;
    }
    /*
     * show the status text 
     * 
     */
    function getStatus() {
        $statusArray = self::getStatusArray();
        return (isset($statusArray[$this->is_active])) ? $statusArray[$this->is_active] : $statusArray[self::INACTIVE];
    }

    function formatIndentifier() {
        if ($this->identifier) {
            $this->identifier = CoreUrl::model()->formatUrl($this->identifier);
        } else {
            $this->identifier = CoreUrl::model()->formatUrl($this->page_title);
        }
    }

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

    protected function afterSave() {
        parent::afterSave();
        if ($this->isNewRecord) {
            $activity = Yii::t('cms', '{data_type} is {action} by user {user}', array(
                        '{data_type}' => DataType::DATE_TYPE_PAGE,
                        '{action}' => 'Created',
                        '{user}' => Yii::app()->user->name,
            ));
        } else {
            $activity = Yii::t('cms', '{data_type} is {action} by user {user}', array(
                        '{data_type}' => DataType::DATE_TYPE_PAGE,
                        '{action}' => 'Updated',
                        '{user}' => Yii::app()->user->name,
            ));
        }
        //add  the user activity  data 

        UserActivity::model()->addActivity(array(
            'data_type' => DataType::DATE_TYPE_PAGE,
            'data_id' => $this->PAGE_ID,
            'user_id' => Yii::app()->user->id,
            'activity' => $activity,
        ));

        //add/update  the core url entry 
        CoreUrl::model()->addUrl(array(
            'request_path' => $this->identifier,
            'target_path' => 'cms/page/view/id/' . $this->PAGE_ID,
            'data_type' => DataType::DATE_TYPE_PAGE,
            'data_id' => $this->PAGE_ID,
            'redirect' => CoreUrl::REDIRECT_NO,
        ));
    }

    /*
     * Delet the related entries on record delete 
     * @return true
     */

    protected function afterDelete() {
        parent::afterDelete();

        $activity = Yii::t('cms', '{data_type} is {action} by user {user}', array(
                    '{data_type}' => DataType::DATE_TYPE_PAGE,
                    '{action}' => 'Deleted',
                    '{user}' => Yii::app()->user->name,
        ));
        //add user activity
        UserActivity::model()->addActivity(array(
            'data_type' => DataType::DATE_TYPE_PAGE,
            'data_id' => $this->PAGE_ID,
            'user_id' => Yii::app()->user->id,
            'activity' => $activity,
        ));


        //delete the core url 
        CoreUrl::model()->deleteUrl(array(
            'data_type' => DataType::DATE_TYPE_PAGE,
            'data_id' => $this->PAGE_ID,
        ));
    }

}
