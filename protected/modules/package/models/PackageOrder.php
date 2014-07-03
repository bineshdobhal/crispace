<?php

/**
 * This is the model class for table "{{package_order}}".
 *
 * The followings are the available columns in table '{{package_order}}':
 * @property string $PO_ID
 * @property string $user_id
 * @property string $payment_id
 * @property integer $status
 * @property string $additional_data
 * @property integer $order_type
 * @property string $creation_time
 * @property string $coupon_code
 * @property string $discount_amount
 * @property string $increment_id
 * @property string $sub_total
 * @property string $grand_total
 * @property string $updation_time
 *
 * The followings are the available model relations:
 * @property User $user
 */
class PackageOrder extends CActiveRecord {
    /**
     * Order type constant 
     */

    CONST ORDER_TYPE_REQUEST = 1;
    CONST ORDER_TYPE_PURCHASE = 0;
    
    /**
     * Order status type constant 
     */
    
    CONST ORDER_STATUS_PENDING = 1;//pending for payment 
    CONST ORDER_STATUS_REQUEST = 2;//requested by user 
    CONST ORDER_STATUS_COMPLETE = 3;//payment completed or approved by crispace 
    CONST ORDER_STATUS_NEW = 4;//submitted by user  but nor a request neither pending for payment .System will delete these requet after some period of time
    
    /**
     * 
     */

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{package_order}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('status, order_type', 'numerical', 'integerOnly' => true),
            array('user_id, payment_id', 'length', 'max' => 10),
            array('coupon_code', 'length', 'max' => 255),
            array('discount_amount, sub_total, grand_total,', 'length', 'max' => 12),
            array('increment_id', 'length', 'max' => 50),
            array('additional_data,creation_time', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('PO_ID, user_id, payment_id, status, additional_data, order_type, creatation_time, coupon_code, discount_amount, increment_id, sub_total, grand_total, updation_time', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'user' => array(self::BELONGS_TO, 'User', 'user_id'),
            'items' => array(self::HAS_MANY, 'PackageOrderItem', 'order_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'PO_ID' => 'Po',
            'user_id' => 'User',
            'start_date' => 'Start Date',
            'end_date' => 'End Date',
            'payment_id' => 'Payment',
            'status' => 'Status',
            'additional_data' => 'Additional Data',
            'order_type' => 'Order Type',
            'created_date' => 'Created Date',
            'coupon_code' => 'Coupon Code',
            'discount_amount' => 'Discount Amount',
            'increment_id' => 'Increment',
            'sub_total' => 'Sub Total',
            'grand_total' => 'Grand Total',
            'updation_time' => 'Updation Time',
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

        $criteria->compare('PO_ID', $this->PO_ID, true);
        $criteria->compare('user_id', $this->user_id, true);
        $criteria->compare('start_date', $this->start_date, true);
        $criteria->compare('end_date', $this->end_date, true);
        $criteria->compare('payment_id', $this->payment_id, true);
        $criteria->compare('status', $this->status);
        $criteria->compare('additional_data', $this->additional_data, true);
        $criteria->compare('order_type', $this->order_type);
        $criteria->compare('created_date', $this->created_date, true);
        $criteria->compare('coupon_code', $this->coupon_code, true);
        $criteria->compare('discount_amount', $this->discount_amount, true);
        $criteria->compare('increment_id', $this->increment_id, true);
        $criteria->compare('sub_total', $this->sub_total, true);
        $criteria->compare('grand_total', $this->grand_total, true);
        $criteria->compare('updation_time', $this->updation_time, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return PackageOrder the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    
    
    
    
    function validateItems() {
        $chk_ids = Yii::app()->request->getPost('chk_ids');

        if (0 >= count($chk_ids)) {
            return false;
        } else {

            return true;
        }
    }

    function saveOrder() {
        
        //save order first 
        if($this->isNewRecord){
            $this->user_id=Yii::app()->user->id;
            $this->creation_time=$this->updation_time= Yii::app()->core->getCurrentTime();
            $this->save(false);
        }
        PackageOrderItem::model()->saveItems($this->PO_ID);
        $this->setOrderStatus();
        $this->setOrderType();
        $this->updation_time= Yii::app()->core->getCurrentTime();
        $this->setIncrementId();
        $this->setSubTotal();
        $this->setGrandTotal();
        $this->save(false);
        return $this;
        
    }
    
    
    
    /*
     * set order status 
     */
    
    function setOrderStatus(){
       $this->status=self::ORDER_STATUS_NEW;
       
        
    }
    
    
    function setOrderType(){
        if(PackageFeature::model()->canPurchasePackage()){
            $this->order_type=self::ORDER_TYPE_PURCHASE;
        }else{
            $this->order_type=self::ORDER_TYPE_REQUEST;
        } 
        
    }
    
    function setIncrementId()
    {
        $this->increment_id = $this->PO_ID;
        
    }
    
    function setSubTotal(){
        $subTotal=  PackageOrderItem::model()->getItemsTotal($this->PO_ID);
        $this->sub_total=$subTotal;
    }
    
    function setGrandTotal(){
        $this->grand_total=$this->sub_total;
        
    }
    
    public function setOrderRequested(){
        $this->status=self::ORDER_STATUS_REQUEST;
        $this->save(false);
        
    }
    
    
    public function setOrderPaymentPending(){
        $this->status=self::ORDER_STATUS_PENDING;
        $this->save(false);
        
    }
   
    
    /**
     * This method is invoked after deleting a record.
    */
    protected function afterDelete() {
        parent::afterDelete();
        //delete items 
        PackageOrderItem::model()->deleteItems($this->PO_ID);
        
    }
    
    function checkSelected($feature_id=0){
       $selectedFeatureItems=$this->items;
        if($selectedFeatureItems){
            foreach($selectedFeatureItems as $selectedFeature){
                if($selectedFeature->feature_id==$feature_id){
                    return true;
                    break;
                }
            }
        }
        return false;
    }
    
    function getSelectedQty($feature_id=0){
        $selectedFeatureItems=$this->items;
        if($selectedFeatureItems){
            foreach($selectedFeatureItems as $selectedFeature){
                if($selectedFeature->feature_id==$feature_id){
                    return $selectedFeature->qty;
                    break;
                }
            }
        }
        return '';
    }
    
    
    
    

}
