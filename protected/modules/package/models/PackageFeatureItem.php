<?php

/**
 * This is the model class for table "{{package_feature_item}}".
 *
 * The followings are the available columns in table '{{package_feature_item}}':
 * @property string $PFITEM_ID
 * @property string $feature_id
 * @property string $item_key
 * @property string $item_value
 * @property string $item_text
 * @property string $is_active
 */
class PackageFeatureItem extends CActiveRecord {
    /**
     * define package feature item keys 
     */

    const PFI_LOGO = 'feature_logo';
    const PFI_PHOTO = 'feature_photo';
    const PFI_VIDEO = 'feature_video';
    const PFI_DESCRIPTION = 'feature_description';
    const PFI_MICRO_SITE = 'feature_microsite';
    const PFI_MAP = 'feature_map';
    const PFI_FLOOR_PLAN = 'feature_floor_plan';
    const PFI_ROUTE_MAP = 'feature_route_map';
    const PFI_SPECUAL_TAG = 'feature_special_tag';
    const PFI_LOCALITY_PHOTO = 'feature_locality_photo';
    const PFI_CONTACT_DETAILS = 'feature_contact_details';
    const PFI_LIVE_PERIOD = 'feature_live_period';
    const PFI_OTHER_POSTING = 'feature_other_posting';

    /*
     * Define Stats Constant 
     */
    const ACTIVE = 1;
    const INACTIVE = 0;

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{package_feature_item}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('item_key,item_value', 'length', 'max' => 45),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('PFITEM_ID, feature_id, item_key, item_value, item_text, is_active', 'safe', 'on' => 'search'),
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
            'PFITEM_ID' => 'Pfitem',
            'feature_id' => 'Feature',
            'item_key' => 'Item Key',
            'item_value' => 'Item Value',
            'item_text' => 'Item Text',
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
    public function search() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('PFITEM_ID', $this->PFITEM_ID, true);
        $criteria->compare('feature_id', $this->feature_id, true);
        $criteria->compare('item_key', $this->item_key, true);
        $criteria->compare('item_value', $this->item_value, true);
        $criteria->compare('item_text', $this->item_text, true);
        $criteria->compare('is_active', $this->is_active, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return PackageFeatureItem the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /*
     * get the feature items key dropdown 
     * @return array of status in key=>value pair 
     */

    function getFeatureKeyArray() {
        $featureKeyArray = array(
            self::PFI_LOGO => Yii::t('package', 'Logo'),
            self::PFI_PHOTO => Yii::t('package', 'Photos'),
            self::PFI_VIDEO => Yii::t('package', 'Videos'),
            self::PFI_DESCRIPTION => Yii::t('package', 'Description'),
            self::PFI_MICRO_SITE => Yii::t('package', 'Microsite'),
            self::PFI_MAP => Yii::t('package', 'Map'),
            self::PFI_FLOOR_PLAN => Yii::t('package', 'Floor Plan'),
            self::PFI_ROUTE_MAP => Yii::t('package', 'Route Map'),
            self::PFI_SPECUAL_TAG => Yii::t('package', 'Special Tag'),
            self::PFI_LOCALITY_PHOTO => Yii::t('package', 'Locality Photo'),
            self::PFI_CONTACT_DETAILS => Yii::t('package', 'Contact Detauls'),
            self::PFI_LIVE_PERIOD => Yii::t('package', 'Live Period(in days)'),
            self::PFI_OTHER_POSTING => Yii::t('package', 'Other Postings'),
        );
        return $featureKeyArray;
    }
    
    
    function getFeatureKeyValue() 
    {
        $featureKeyArray = self::getFeatureKeyArray();
        return (isset($featureKeyArray[$this->item_key])) ? $featureKeyArray[$this->item_key] : '';
        
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

    /**
     * save the package feature items 
     * @param intiger  $featureID package feature id 
     */
    function saveItems($featureId = 0) {
        //first delete all existing data 
        $this->deleteAllByAttributes(array('feature_id' => $featureId));

        //start inserting the values 
        $featureItemsArray = Yii::app()->request->getPost('PackageFeatureItem');
        if($featureItemsArray){
            foreach ($featureItemsArray['item_key'] as $key):
                if ('' != $featureItemsArray['item_value'][$key]):
                    $model = new PackageFeatureItem;
                    $model->feature_id = $featureId;
                    $model->item_key = $key;
                    $model->item_value = $featureItemsArray['item_value'][$key];
                    $model->item_text = $featureItemsArray['item_text'][$key];
                    $model->is_active = $featureItemsArray['is_active'][$key];
                    $model->save(false);
                endif;

            endforeach;
        }
    }

    /**
     * Delete the package feature items 
     * @param intiger  $featureID package feature id 
     */
    function deleteItems($featureId = 0) {
        $this->deleteAllByAttributes(array('feature_id' => $featureId));
    }

}
