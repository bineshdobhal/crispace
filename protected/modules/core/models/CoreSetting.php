<?php

/**
 * ContactForm class.
 * ContactForm is the data structure for keeping
 * contact form data. It is used by the 'contact' action of 'SiteController'.
 */
class CoreSetting extends CFormModel
{
	public $core_site_name;
	public $core_site_url;
	public $core_logo_path;
	
	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			array('core_site_name,core_site_url,core_logo_path', 'required'),
			
		);
	}
        
        /**
         * Returns the static model of the specified AR class.
         * Please note that you should have this exact method in all your CActiveRecord descendants!
         * @param string $className active record class name.
         * @return PackageFeatureItem the static model class
        */
        public static function model($className = __CLASS__) {
            $model = new $className(null);
            $model->attachBehaviors($model->behaviors());
            return $model;
        }

	/**
	 * Declares customized attribute labels.
	 * If not declared here, an attribute would have a label that is
	 * the same as its name with the first letter in upper case.
	 */
	public function attributeLabels()
	{
		return array(
                    'core_site_name'=>Yii::t('core','Site Name'),
                    'core_site_url'=>Yii::t('core','Site Url'),
                    'core_logo_path'=>Yii::t('core','Logo'),
		);
	}
        
        function defaultSetting($attribute=''){
         
            $defaultSetting=  array(
                 'core_site_name'=>Yii::app()->name,
                 'core_site_url'=>Yii::app()->request->getBaseUrl(true),
                 'core_logo_path'=>'',
               
            );
          if('' != $attribute)
          {
              if($value=  CoreConfigData::model()->getConfig($attribute)){
                
                  return $value;
              }else{
                  
                  return (isset($defaultSetting[$attribute])) ? $defaultSetting[$attribute] : '';
              }
          }
            return $defaultSetting;
            
        }
        
        
        function loadSetting()
        {
            
            foreach($this->attributes as $attribute=>$value){
                $this->$attribute=self::defaultSetting($attribute);
            }
            return $this;
        }
        
        
}