<?php

class SettingController extends Controller
{
	public function actionIndex()
	{
           
            $model = new CoreSetting;
                
		if(isset($_POST['CoreSetting']))
		{   
			$model->attributes=$_POST['CoreSetting'];
			if($model->validate())
			{
                            foreach($model->attributes as $key=>$value)
                            {
                               
                               CoreConfigData::model()->saveConfig($key,$value);
                               
                            }
                            CoreMessage::model()->addSuccess(Yii::t('cms','Setting has been successfully updated'));
                            $this->refresh(); 
			}
		}
                //load default values of setting fields 
                $model->loadSetting();
		$this->render('index',array('model'=>$model));
	}
	
}