<?php

/**
 * ContactForm class.
 * ContactForm is the data structure for keeping
 * contact form data. It is used by the 'contact' action of 'SiteController'.
 */
class CoreMessage  {

    
    /*
     * define constant 
     */ 
    const MESSAGE_KEY='messages';
    const ERROR_KEY='error';
    const SUCCESS_KEY='success';
    const NOTICE_KEY='notice';
    
    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return PackageFeatureItem the static model class
     */
   
    public static function model($className = __CLASS__) {
        $model = new $className(null);
        return $model;
    }
    
    /**
     * Add message to session 
     * @param string $type message type key 
     * @param string $message message 
     */
    function add($type = self::SUCCESS_KEY, $message) {
        $messages = Yii::app()->user->getState(self::MESSAGE_KEY);
        if ((!isset($messages[$type]) || !in_array($message, $messages[$type])) && is_string($message) && $message) {
           $messages[$type][] = $message;
           
        }

        Yii::app()->user->setState(self::MESSAGE_KEY, $messages);
    }
    
    /**
     * Clear all message form session 
     * @param string $type message type key 
     */
    
    function clear($type = NULL) {
        
        if (!empty($type)) {
            $messages = Yii::app()->user->getState(self::MESSAGE_KEY);
            if (!is_array($messages)) {
                $messages = array();
            }

            if (array_key_exists($type, $messages)) {
                unset($messages[$type]);
            }

            Yii::app()->user->setState(self::MESSAGE_KEY, $messages);
        } else {
            Yii::app()->user->setState(self::MESSAGE_KEY, array());
        }
    }
    
    /**
     * Get all messages from the session 
     * @param string  $type message type key 
     */
    function get($type = null) {
        $messages =  Yii::app()->user->getState(self::MESSAGE_KEY);
        if (!is_array($messages)) {
            $messages = array();
        }
        
        if (!empty($type)) {
            if (array_key_exists($type, $messages)) {
                $messages = $messages[$type];
                $this->clear($type);
                return $messages;
            } else {
                return array();
            }
        }
        
        $this->clear();
        return $messages;
    }
    
    
    function showMessages(){
        $output='';
        if($messages=self::get()):
            foreach($messages as $key=>$values):
                $output .='<div class="'.$key.'">';
                    foreach($values as $value):
                        $output .='<p>'.$value.'</p>';
                    endforeach;
                $output .='</div>';
            
            endforeach;
            
        endif;
        return $output;
        //for
        
    }
    
    
    /**
     * Add success message 
     * @param string  $type message type key 
     */
    
    function addSuccess($message=''){
        $this->add(self::SUCCESS_KEY,$message);
        
    }
    
    
    /**
     * Add error message 
     * @param string  $type message type key 
     */
    function addError($message=''){
        $this->add(self::ERROR_KEY,$message);
        
    }
    
    
    /**
     * Add error message 
     * @param string  $type message type key 
     */
    function addNotice($message=''){
        $this->add(self::NOTICE_KEY,$message);
        
    }
    

}