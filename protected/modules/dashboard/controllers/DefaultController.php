<?php

class DefaultController extends Controller
{
        public $_user_type = null;
        public $defaultAction = 'dashboard';
	
        public function beforeAction($event) {
            $this->_user_type = Yii::app()->user->getUserType();
            if($this->_user_type === null)
                $this->redirect(Yii::app()->createUrl('/user/auth'));
            
            return parent::beforeAction($event);
        }
        
        public function accessRules()
	{
		return array(
			array('allow',
				'actions'=>array('dashboard',),
				'users' => array('@'),
				),

			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
        
	public function actionDashboard()
	{
            $action_links = $this->getActionLinks();
            $panel_html = $this->getPanelHtml($action_links);
            $this->render('dashboard', array(
                'action_links'=>$action_links,
            ));
	}
        
        protected function getActionLinks(){
            $links = array();
            $links['profile']=array(
                'title'=>'Profile Setting',
                'content'=>array(
                    'my_profile'=>array(
                        'title'=>'My Profile',
                        'url'=>Yii::app()->createUrl('//profile/profile/view'),
                    ),
                    'edit_profile'=>array(
                        'title'=>'Edit Profile',
                        'url'=>Yii::app()->createUrl('//profile/profile/update'),
                    ),
                    'privacy_setting'=>array(
                        'title'=>'Privacy Setting',
                        'url'=>Yii::app()->createUrl('//profile/privacy/update'),
                    ),
                    'change_password'=>array(
                        'title'=>'Change Password',
                        'url'=>Yii::app()->createUrl('//user/user/changePassword'),
                    ),
                ),
            );
            
            if($this->_user_type == YumUser::USERTYPE_INDIVIDUAL){
                $links['individual']=array(
                    'title'=>'Links For Individuals',
                    'content'=>array(
                        'link1'=>array(
                            'title'=>'Link#1',
                            'url'=>'#',
                        ),
                        'link2'=>array(
                            'title'=>'Link#2',
                            'url'=>'#',
                        ),
                        'link3'=>array(
                            'title'=>'Link#3',
                            'url'=>'#',
                        ),
                    ),
                );
            }
            if($this->_user_type == YumUser::USERTYPE_AGENT){
                $links['agent']=array(
                    'title'=>'Links for Agents',
                    'content'=>array(
                        'link1'=>array(
                            'title'=>'Link#1',
                            'url'=>'#',
                        ),
                        'link2'=>array(
                            'title'=>'Link#2',
                            'url'=>'#',
                        ),
                        'link3'=>array(
                            'title'=>'Link#3',
                            'url'=>'#',
                        ),
                    ),
                );
            }
            if($this->_user_type == YumUser::USERTYPE_BUILDER){
                $links['builder']=array(
                    'title'=>'Links for Builders',
                    'content'=>array(
                        'link1'=>array(
                            'title'=>'Post Property',
                            'url'=>'#',
                        ),
                        'link2'=>array(
                            'title'=>'My Properties',
                            'url'=>'#',
                        ),
                        'link3'=>array(
                            'title'=>'Customer Requests',
                            'url'=>'#',
                        ),
                    ),
                );
            }
            if($this->_user_type == YumUser::USERTYPE_STAFF || Yii::app()->user->isAdmin()){
                $links['staff']=array(
                    'title'=>'Links for Staff',
                    'content'=>array(
                        'link1'=>array(
                            'title'=>'User Approvals',
                            'url'=>Yii::app()->createUrl('//user/user/approvals'),
                        ),
                        'create_user'=>array(
                            'title'=>'Create User',
                            'url'=>Yii::app()->createUrl('//user/user/create'),
                        ),
                    ),
                );
            }
            if(Yii::app()->user->isAdmin()){
                $links['admin']=array(
                    'title'=>'Links for Administratior',
                    'content'=>array(
                        
                        'manage_users'=>array(
                            'title'=>'Manage Users',
                            'url'=>Yii::app()->createUrl('//user/user/admin'),
                        ),
                        'link2'=>array(
                            'title'=>'Link#2',
                            'url'=>'#',
                        ),
                        'link3'=>array(
                            'title'=>'Link#3',
                            'url'=>'#',
                        ),
                    ),
                );
            }
            
            return $links;
        }
        
        public function getPanelHtml(){
            
        } 
}