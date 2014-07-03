<?php

/**
 * This is the model class for table "{{package_feature}}".
 *
 * The followings are the available columns in table '{{package_feature}}':
 * @property string $PACKAGE_FEATURE_ID
 * @property string $title
 * @property double $price_monthly
 * @property double $is_active
 */
class PackageFeature extends CActiveRecord {

    
     const ACTIVE = 1;
    const INACTIVE = 0;

    
    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{package_feature}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('title,price_monthly', 'required'),
            array('title', 'unique'),
            array('price_monthly, is_active', 'numerical'),
            array('title', 'length', 'max' => 45),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('PACKAGE_FEATURE_ID, title, price_monthly, is_active', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'feature_items'=>array(self::HAS_MANY,'PackageFeatureItem','feature_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'PACKAGE_FEATURE_ID' => 'Package Feature',
            'title' => 'Title',
            'price_monthly' => 'Price',
            'is_active' => 'Status',
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

        $criteria->compare('PACKAGE_FEATURE_ID', $this->PACKAGE_FEATURE_ID, true);
        $criteria->compare('title', $this->title, true);
        $criteria->compare('price_monthly', $this->price_monthly);
        $criteria->compare('is_active', $this->is_active);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return PackageFeature the static model class
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
            self::ACTIVE => Yii::t('package', 'Active'),
            self::INACTIVE => Yii::t('package', 'Inactive'),
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
    
    
    protected function beforeSave() {
        if (parent::beforeSave()) {
            if ($this->isNewRecord) {
              
            } else {
              
            }
            
            return true;
        }
        else
            return false;
    }

    protected function afterSave() {
        parent::afterSave();
        if ($this->isNewRecord) {
            $activity = Yii::t('cms', '{data_type} feature {title} is {action} by user {user}', array(
                        '{data_type}' => DataType::DATE_TYPE_PACKAGE,
                        '{title}'=>$this->title,
                        '{action}' => 'Created',
                        '{user}' => Yii::app()->user->name,
            ));
        } else {
            $activity = Yii::t('cms', '{data_type} feature {title} is {action} by user {user}', array(
                         '{data_type}' => DataType::DATE_TYPE_PACKAGE,
                        '{title}'=>$this->title,
                        '{action}' => 'Updated',
                        '{user}' => Yii::app()->user->name,
            ));
        }
        //add  the user activity  data 

        UserActivity::model()->addActivity(array(
            'data_type' => DataType::DATE_TYPE_PACKAGE,
            'data_id' => $this->PACKAGE_FEATURE_ID,
             'user_id' => Yii::app()->user->id,
            'activity' => $activity,
        ));
        
        //save package Feature items
        PackageFeatureItem::model()->saveItems($this->PACKAGE_FEATURE_ID);
        
       
    }

    /*
     * Delete the related entries on record delete 
     * @return true
     */

    protected function afterDelete() {
        parent::afterDelete();
        
        //delete items 
        PackageFeatureItem::model()->deleteItems($this->PACKAGE_FEATURE_ID);
        $activity = Yii::t('cms', '{data_type} feature {title} is {action} by user {user}', array(
                    '{data_type}' => DataType::DATE_TYPE_PACKAGE,
                    '{title}'=>$this->title,
                    '{action}' => 'Deleted',
                    '{user}' => Yii::app()->user->name,
        ));
        //add user activity
        UserActivity::model()->addActivity(array(
            'data_type' => DataType::DATE_TYPE_PAGE,
            'data_id' => $this->PACKAGE_FEATURE_ID,
            'user_id' => Yii::app()->user->id,
            'activity' => $activity,
        ));
        
       
    }
    
    /*
     * show the feature item values in the feature creation form
     * get the values form existing items or from the post back
     * @param string $key feature item key
     * @param string $field field name
     * @param string $default default value
     * @return string value 
     */
    
    
    function getfeatureItemValue($key,$field,$default=''){
      
        $selectedFeatureItems=$this->feature_items;
        if($selectedFeatureItems)
        {
            foreach ( $selectedFeatureItems as $selectedFeatureItem ):
                if( $key == $selectedFeatureItem['item_key'] )
                    {
                    return $selectedFeatureItem->$field;
                    break;
                }
                
            endforeach;
            
        }
        //show values form post back 
               
        $featureItemsArray=Yii::app()->request->getPost('PackageFeatureItem');
        if($featureItemsArray)
        {
           return $featureItemsArray[$field][$key];
        }    
        
        return $default;
        
    }
    
    /**
     * check if current user can purchase or request a package
     * return true on request and false purchase 
     */
    function canPurchasePackage(){
        if(UserRole::model()->can('package_feature_purchase')){
            return TRUE;
        }
        return FALSE;
        
    }
    
    /**
     * check weather to show package price or not 
     * @return boolean 
     */
    
    function isShowPrice(){
        if(self::canPurchasePackage())
            return true;
        
    }
    
    function getRequestLabel(){
        $requestLabel=Yii::t('package','Request Package'); 
        return $requestLabel;
    }
    
    function getButtonLabel(){
        $requestLabel=Yii::t('package','Request Package'); 
        return $requestLabel;
    }
    
    

}
