<?php

/**
 * This is the model class for table "{{package_order_item}}".
 *
 * The followings are the available columns in table '{{package_order_item}}':
 * @property string $POI_ID
 * @property string $featture_id
 * @property integer $qty
 * @property string $order_id
 */
class PackageOrderItem extends CActiveRecord
{
	
        public $itemTotal=0;
    
        /**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{package_order_item}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('qty', 'numerical', 'integerOnly'=>true),
			array('feature_id, order_id', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('POI_ID, feature_id, qty, order_id,price,row_total', 'safe', 'on'=>'search'),
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
                    'feature'=>array(self::BELONGS_TO,'PackageFeature','feature_id',),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'POI_ID' => 'Poi',
			'feature_id' => 'Featture',
			'qty' => 'Qty',
			'order_id' => 'Order',
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

		$criteria->compare('POI_ID',$this->POI_ID,true);
		$criteria->compare('feature_id',$this->featture_id,true);
		$criteria->compare('qty',$this->qty);
		$criteria->compare('order_id',$this->order_id,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PackageOrderItem the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        
        function saveItems($orderId=0){
            //first delete all existing data 
            $this->deleteItems($orderId);
            $featureIds = Yii::app()->request->getPost('chk_ids');
            if (0 <= count($featureIds)) {
                foreach($featureIds as $featureId){
                   $model = new PackageOrderItem();
                   $model->feature_id=$featureId;
                   $model->setItemQty(); 
                   $model->order_id=$orderId;
                   $model->setPrice();
                   $model->setRowTotal();
                   $model->save(false);
                }
            }
        }
        
        
        function deleteItems($orderId=0)
        {
            $this->deleteAllByAttributes(array('order_id' => $orderId));
        }
        
        
        function setItemQty()
        {
            $qtyArray = Yii::app()->request->getPost('qty');
            if(isset($qtyArray[$this->feature_id]) && '' !=  $qtyArray[$this->feature_id] ){
               $this->qty = $qtyArray[$this->feature_id];
            }else{
                $this->qty= $this->getDefaultQty();
            }
           
        }
        
        function getDefaultQty(){
            return 1;
            
        }
        
        
        function setPrice(){
           
            $featureModel = PackageFeature::model()->findByPk($this->feature_id);
            $this->price=$featureModel->price_monthly;
            return $this;
        }
        
        
        function setRowTotal(){
            return $this->row_total =$this->qty*$this->price;
            
        }
        
        
        function getItemsTotal($orderId=0){
            $criteria = new CDbCriteria;
            $criteria->select = '(sum(row_total)) as itemTotal';
            $criteria->compare('order_id', $orderId);
            $sumIt =  $this->find($criteria);
            return $sumIt->itemTotal; 
        }
        
        
}
