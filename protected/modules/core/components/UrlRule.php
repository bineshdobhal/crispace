<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CarUrlRule
 *
 * @author binesh
 */
class UrlRule extends CBaseUrlRule {
    public $connectionID = 'db';
 
    public function createUrl($manager,$route,$params,$ampersand)
    {
        if ($route==='car/index')
        {
            if (isset($params['manufacturer'], $params['model']))
                return $params['manufacturer'] . '/' . $params['model'];
            else if (isset($params['manufacturer']))
                return $params['manufacturer'];
        }
        return false;  // this rule does not apply
    }
 
    public function parseUrl($manager,$request,$pathInfo,$rawPathInfo)
    {
        
        if($pathInfo){
            //fetch the url info for the core url data 
            $data= CoreUrl::model()->findByAttributes(array('request_path'=>$pathInfo));

            if ($data)
            {
               //check the rediret status 
                $redirect=$data->redirect;
                switch($redirect)
                {
                    case CoreUrl::REDIRECT_NO:
                        return $data->target_path;
                    break;
                    case CoreUrl::REDIRECT_PREMANENT:
                        return Yii::app()->request->redirect($data->target_path,true, 301);
                      break;
                    default:
                        return Yii::app()->request->redirect($data->target_path,true, 302);
                    break;
                }
                
            }
        }
        return false;  // this rule does not apply
    }
}

?>
