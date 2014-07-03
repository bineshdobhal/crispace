<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DataType
 *
 * @author binesh
 */
class DataType extends CFormModel {
   
    const DATE_TYPE_PAGE='page';
    const DATE_TYPE_BLOCK='block';
    const DATE_TYPE_USER='user'; 
    const DATE_TYPE_PROJECT='project';
    const DATE_TYPE_PROPERTY='property';
    const DATE_TYPE_PACKAGE='package';
    const DATE_TYPE_LOCALITY='locality';
    const DATE_TYPE_AMENITIES='amenities';
    
    function getDataTypeArray(){
        $dataTypeArray=array(
         self::DATE_TYPE_PAGE=>self::DATE_TYPE_PAGE,
         self::DATE_TYPE_BLOCK=>self::DATE_TYPE_BLOCK,
         self::DATE_TYPE_USER=>self::DATE_TYPE_USER,
         self::DATE_TYPE_PROJECT=>self::DATE_TYPE_PROJECT,
         self::DATE_TYPE_PROPERTY=>self::DATE_TYPE_PROPERTY,
         self::DATE_TYPE_PACKAGE=>self::DATE_TYPE_PACKAGE,
         self::DATE_TYPE_LOCALITY=>self::DATE_TYPE_LOCALITY,  
	self::DATE_TYPE_AMENITIES=>self::DATE_TYPE_AMENITIES,  
            
        );
        return $dataTypeArray;
        
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
    
}

//end of file 
//path:application.modules.core.source