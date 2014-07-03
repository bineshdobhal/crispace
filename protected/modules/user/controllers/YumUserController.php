<?php

Yii::import('application.modules.user.controllers.YumController');
class YumUserController extends YumController {
	public $defaultAction = 'login';

	public function accessRules() {
		return array(
				array('allow',
					'actions'=>array('index', 'view', 'login', 'status'),
					'users'=>array('*'),
					),
				array('allow',
					'actions'=>array('profile', 'logout', 'changepassword', 'passwordexpired', 'delete', 'browse', 'approvals', 'approve', 'reject', 'forward'),
					'users'=>array('@'),
					),
				array('allow',
					'actions'=>array('admin','delete','create','update', 'list', 'assign', 'generateData', 'csv'),
					'expression' => 'Yii::app()->user->isStaff()||Yii::app()->user->isAdmin()',
					),
				array('allow',
					'actions'=>array('create'),
					'expression' => 'Yii::app()->user->can("user_create")'
					),
				array('allow',
					'actions'=>array('admin'),
					'expression' => 'Yii::app()->user->can("user_admin")'
					),
				array('deny',  // deny all other users
						'users'=>array('*'),
						),
				);
	}

	public function actionGenerateData() {
		if(Yum::hasModule('role'))
			Yii::import('application.modules.role.models.*');
		if(isset($_POST['user_amount'])) {
			for($i = 0; $i < $_POST['user_amount']; $i++) {
				$user = new YumUser();
				$user->username = sprintf('Demo_%d_%d', rand(1, 50000), $i);
				$user->roles = array($_POST['role']);
				$user->salt = YumEncrypt::generateSalt();
				$user->password = YumEncrypt::encrypt($_POST['password'], $user->salt);
				$user->createtime = time();
				$user->status = $_POST['status'];

				if($user->save()) {
					if(Yum::hasModule('profile')) {
						$profile = new YumProfile();
						$profile->user_id = $user->id;
						$profile->timestamp = time();
						$profile->firstname = $user->username;
						$profile->lastname = $user->username;
						$profile->privacy = 'protected';
						$profile->email = 'e@mail.de';
						$profile->save();
					} 
				}
			}
		}
		$this->render('generate_data');
	}

	public function actionIndex() {
		// If the user is not logged in, so we redirect to the actionLogin,
		// which will render the login Form

		if(Yii::app()->user->isGuest)
			$this->actionLogin();
		else
			$this->actionList();
	}

        
        
	public function actionStats() {
		$this->redirect($this->createUrl('/user/statistics/index'));
	}

	public function actionPasswordExpired()
	{
		$this->actionChangePassword($expired = true);
	}

	public function actionLogin() {
		// Do not show the login form if a session expires but a ajax request
		// is still generated
		if(Yii::app()->user->isGuest && Yii::app()->request->isAjaxRequest)
			return false;
		$this->redirect(array('/user/auth'));
	}

	public function actionLogout() {
		$this->redirect(array('//user/auth/logout'));
	}

	public function beforeAction($event) {
		if(!Yii::app()->user instanceof YumWebUser)
			throw new CException(Yum::t('Please make sure that Yii uses the YumWebUser component instead of CWebUser in your config/main.php components section. Please see the installation instructions.'));
		if (Yii::app()->user->isAdmin())
			$this->layout = Yum::module()->adminLayout;
		else
			$this->layout = Yum::module()->layout;
		return parent::beforeAction($event);
	}

	/**
	 * Change password
	 */
	public function actionChangePassword($expired = false) {
		$id = Yii::app()->user->id;

		$user = YumUser::model()->findByPk($id);
		if(!$user)
			throw new CHttpException(403, Yum::t('User not found'));
		else if($user->status <= 0)
			throw new CHttpException(404, Yum::t('User is not active'));

		$form = new YumUserChangePassword;
		$form->scenario = 'user_request';

		if(isset($_POST['YumUserChangePassword'])) {
			$form->attributes = $_POST['YumUserChangePassword'];
			$form->validate();

			if(!YumEncrypt::validate_password($form->currentPassword,
						YumUser::model()->findByPk($id)->password,
						YumUser::model()->findByPk($id)->salt))
				$form->addError('currentPassword',
						Yum::t('Your current password is not correct'));

			if(!$form->hasErrors()) {
				if(YumUser::model()->findByPk($id)->setPassword($form->password,
							YumUser::model()->findByPk($id)->salt)) {
                                             die('here');
					Yum::setFlash('The new password has been saved');
					Yum::log(Yum::t('User {username} has changed his password', array(
									'{username}' => Yii::app()->user->name)));
				}
				else  {
					Yum::setFlash('There was an error saving the password');
					Yum::log( Yum::t(
								'User {username} tried to change his password, but an error occured', array(
									'{username}' => Yii::app()->user->name)), 'error');
				}

				$this->redirect(Yum::module()->returnUrl);
			}
		}

		if(Yii::app()->request->isAjaxRequest)
			$this->renderPartial('changepassword', array(
						'form'=>$form,
						'expired' => $expired));
		else
			$this->render('changepassword', array(
						'form'=>$form,
						'expired' => $expired));
	}

	// Redirects the user to the specified profile
	// if no profile is specified, redirect to the own profile
	public function actionProfile($id = null) {
		$this->redirect(array('//profile/profile/view',
					'id' => $id ? $id : Yii::app()->user->id));
	}


	/**
	 * Displays a User
	 */
	public function actionView()
	{
		
                if (Yii::app()->request->isAjaxRequest) {
                    $user = YumUser::model()->findByPk($_GET['id']);
                    echo CJSON::encode(array(  
                        'status'=>'success',  
                        'content'=>($user !== null)? $this->renderPartial('_popup_view', array('model'=>$user), true) : "User does not Exists.",
                    )); 

                    Yii::app()->end();
                }
                $model = $this->loadUser();
		
                if (Yii::app()->request->isAjaxRequest) {
                    echo CJSON::encode(array(  
                        'status'=>'success',  
                        'div'=>$this->renderPartial('_popup_view', array('model'=>$model), true)
                    )); 

                    Yii::app()->end();
                }
                
                $this->render('view',array(
                        'model'=>$model,
                    )
                );
	}

	/**
	 * Creates a new User.
	 */
	public function actionCreate() {
		$model = new YumUser;
		if(Yum::hasModule('profile'))
			$profile = new YumProfile;
		
                $model->status = YumUser::STATUS_APPROVAL_PENDING;//set status as Approval Pending
                $roleModel=new UserRole;
		if(isset($_POST['YumUser'])) {
			$model->salt = YumEncrypt::generateSalt();
			$model->attributes=$_POST['YumUser'];
			
			$roleModel->attributes=$_POST['UserRole'];

			if(Yum::hasModule('profile') && isset($_POST['YumProfile']) )
				$profile->attributes = $_POST['YumProfile'];
                        
                        $password = YumUser::generatePassword();
                        $model->setPassword($password, $model->salt);
                        Yum::setFlash(Yum::t('The generated Password is {password}', array('{password}' => $password)));
			
			$model->activationKey = YumEncrypt::encrypt(microtime() . $model->password, $model->salt);

			if($model->username == '' && isset($profile))
				$model->username = $profile->email;

			$model->validate();

			if(isset($profile))
				$profile->validate();

			if(!$model->hasErrors()) {
				$model->save();
                                
                                //TODO: send email containgin the auto generated password to the user.
                                
				if(isset($profile)) {
					$profile->user_id = $model->id;
					$profile->save(array('user_id'), false);
				}
                                //save user role 
                                if(isset($roleModel)) {
                                   $roleModel->saveUserRole( $model->id, $roleModel->role_id); 
                                }
				$this->redirect(array('view', 'id'=>$model->id));
			}
		}

		$this->render('create',array(
					'model' => $model,
					'profile' => isset($profile) ? $profile : null,
                                        'roleModel'=>$roleModel,
					));
	}

	public function actionUpdate() {
		$model = $this->loadUser();
		$passwordform = new YumUserChangePassword();
                $roleModel= UserRole::model()->getUserRole($model);
               
		if(isset($_POST['YumUser'])) {
			if(!isset($model->salt) || empty($model->salt))
				$model->salt = YumEncrypt::generateSalt();
			
			$model->attributes = $_POST['YumUser'];
			$roleModel->attributes=$_POST['UserRole'];
                        
			if(Yum::hasModule('profile')) {
				$profile = $model->profile;

				if(isset($_POST['YumProfile']) )
					$profile->attributes = $_POST['YumProfile'];
			}

			// Password change is requested ?
			if(isset($_POST['YumUserChangePassword'])
					&& $_POST['YumUserChangePassword']['password'] != '') {
				$passwordform->attributes = $_POST['YumUserChangePassword'];
				if($passwordform->validate())
					$model->setPassword($_POST['YumUserChangePassword']['password'], $model->salt);
			}

			if(!$passwordform->hasErrors() && $model->save()) {
				if(isset($profile)) 
					$profile->save();

                                 //save user role 
                                if(isset($roleModel)) {
                                  
                                    
                                    $roleModel->saveUserRole( $model->id, $roleModel->role_id); 
                                }
				
                                
				$this->redirect(array('//user/user/view', 'id' => $model->id));
                                
			}
		}

		$this->render('update', array(
					'model'=>$model,
					'passwordform' =>$passwordform,
					'profile' => isset($profile) ? $profile : false,
                                         'roleModel'=>$roleModel,
					));
	}

	/**
	 * Deletes a user by setting the status to 'deleted'
	 */
	public function actionDelete($id = null) {
		if(!$id)
			$id = Yii::app()->user->id;

		$user = YumUser::model()->findByPk($id);

		if(Yii::app()->user->isAdmin()) {
			//This is necesary for handling human stupidity.
			if($user && ($user->id == Yii::app()->user->id)) {
				Yum::setFlash('You can not delete your own admin account');
				$this->redirect(array('//user/user/admin'));
			}

			if($user->delete()) {
				Yum::setFlash('The User has been deleted');
				if(!Yii::app()->request->isAjaxRequest)
					$this->redirect('//user/user/admin');
			}
		} else if(isset($_POST['confirmPassword'])) {
			if(YumEncrypt::validate_password($_POST['confirmPassword'],
						$user->password, $user->salt)) {
				if($user->delete()) {
					Yii::app()->user->logout();
					$this->actionLogout();
				}
				else
					Yum::setFlash('Error while deleting Account. Account was not deleted');
			} else {
				Yum::setFlash('Wrong password confirmation! Account was not deleted');
			}
			$this->redirect(Yum::module()->deleteUrl);
		} 

		$this->render('confirmDeletion', array('model' => $user));
	}

	public function actionBrowse() {
		$search = '';
		if(isset($_POST['search_username']))
			$search = $_POST['search_username'];

		$criteria = new CDbCriteria;

		/*		if(Yum::hasModule('profile')) {
					$criteria->join = 'LEFT JOIN '.Yum::module('profile')->privacysettingTable .' on t.id = privacysetting.user_id';
					$criteria->addCondition('appear_in_search = 1'); 
					} */

		$criteria->addCondition('status = 1 or status = 2 or status = 3');
		if($search) 
			$criteria->addCondition("username = '{$search}'");

		$dataProvider=new CActiveDataProvider('YumUser', array(
					'criteria' => $criteria, 
					'pagination'=>array(
						'pageSize'=>50,
						)));

		$this->render('browse',array(
					'dataProvider'=>$dataProvider,
					'search_username' => $search ? $search : '',
					));
	}

	public function actionList()
	{
		$dataProvider=new CActiveDataProvider('YumUser', array(
					'pagination'=>array(
						/*'criteria'=>array(
							'condition'=>'status > 0', 
							),*/
						'pageSize'=>Yum::module()->pageSize,
						)));

		$this->render('index',array(
					'dataProvider'=>$dataProvider,
					));
	}

	public function actionAdmin()
	{
		if(Yum::hasModule('role'))
			Yii::import('application.modules.role.models.*');

		$this->layout = Yum::module()->adminLayout;

		$model = new YumUser('search');

		if(isset($_GET['YumUser']))
			$model->attributes = $_GET['YumUser'];

		$this->render('admin', array('model'=>$model));
	}

	/**
	 * Loads the User Object instance
	 * @return YumUser
	 */
	public function loadUser($uid = 0)
	{
		if($this->_model === null)
		{
			if($uid != 0)
				$this->_model = YumUser::model()->findByPk($uid);
			elseif(isset($_GET['id']))
				$this->_model = YumUser::model()->findByPk($_GET['id']);
			if($this->_model === null)
				throw new CHttpException(404,'The requested User does not exist.');
		}
		return $this->_model;
	}
        
        /**
         * Action displays appropriate message according to the status of the user.
         * If user is loged in, a message in accordance with his own status code is displayed.
         * If user is guest, a message in accordance with the supplied parameter is displayed (if supplied parameter is a valid status code).
         * 
         * @param INTEGER $code Status Code. Default to null.
         */
        public function actionStatus($code){
            if(!Yii::app()->user->isGuest){
                $code = $this->loadUser(Yii::app()->user->id)->status;
            }
            else if(!isset($code))
                throw new CHttpException(404, 'Status Not Found.');
            
            $status_details = YumUser::getStatusDetail($code);
            $this->render('user_status', array('status_details'=>$status_details));
            
        }
        
        /**
         * Action to Manage new user approvals.
         */
        public function actionApprovals()
	{
		$model=new YumUser('searchApprovals');
		$model->unsetAttributes();  // clear any default values
		if (isset($_GET['YumUser'])) {
			$model->attributes=$_GET['YumUser'];
		}

		$this->render('approvals',array(
			'model'=>$model,
		));
	}
        
        /**
         * Action sets the status of user to "Approved" on ajax request.
         */
        public function actionApprove($id=null){
            if(Yii::app()->request->isAjaxRequest){
                Yii::import('application.modules.workflow.models.*');
                
                $approval_right = YumUser::approvalRights($id);
                
                if($approval_right['has_right']){
                    $user = YumUser::model()->findByPk($id);
                    
                    $workflow = new WorkFlow;
                    $workflow->data_type = get_class($user);
                    $workflow->data_id = $user->id;
                    $workflow->user_id = Yii::app()->user->id;
                    $workflow->action = WorkFlow::ACTION_APPROVE;
                    $workflow->creation_time = date('d-m-Y');
                    $workflow->status = WorkFlow::STATUS_NEW;
                    
                    $last_forward = WorkFlow::model()->find(array(
                        'condition'=>'data_id = :id AND data_type = :user_class AND action = :forward AND status = :new',
                        'params'=>array(
                            ':id'=>$user->id,
                            ':user_class'=>  get_class($user),
                            ':forward'=> WorkFlow::ACTION_FORWARD,
                            ':new'=>  WorkFlow::STATUS_NEW,
                        ),
                        'order'=>'WFLOG_ID DESC',
                    ));

                    if($last_forward !== null){
                        $workflow->parent_id = $last_forward->WFLOG_ID;
                        $last_forward->status = WorkFlow::STATUS_PROCESSED;
                    }

                    $user->status = YumUser::STATUS_ACTIVE;
                    if($user->validate() && $workflow->validate()){
                        $user->save();
                        
                        if($last_forward!==null)
                            $last_forward->save();
                        
                        $workflow->save();
                       
                        //todo: Send Activation Confirmation Mail to the user.
                        
                        echo CJSON::encode(array(  
                            'status'=>'success',  
                            'content'=>Yum::t("User Approved."),
                        ));
                    }
                    else{
                        $errors = "<ol>";
                        foreach($user->getErrors() as $err){
                            $errors .= '<li>'.$err[0].'</li>';
                        }
                        foreach($workflow->getErrors() as $err){
                            $errors .='<li>'.$err[0].'</li>';
                        }
                        echo CJSON::encode(array(  
                            'status'=>'success',  
                            'content'=>"<h3>Error Occured</h3><p>$errors</p>",
                        ));
                    }
                    
                    Yii::app()->end();
                    
                }
                else{
                    echo CJSON::encode(array(  
                        'status'=>'failure',  
                        'content'=>$approval_right['error'],
                    ));
                    Yii::app()->end();
                }
            }
            
            throw new CHttpException(403, Yum::t('Request cannot be processed.'));
            
        }
        
        /**
         * Action processes the rejection of the Users.
         * Staff members are required to specify the reason for rejection which will be send to the user in mail.
         */
        public function actionReject($id=null){
                if(Yii::app()->request->isAjaxRequest){
                    
                    Yii::import('application.modules.workflow.models.*');
                    
                    $approval_right = YumUser::approvalRights($id);
                    
                    if($approval_right['has_right']){
                        $user = YumUser::model()->findByPk($id);
                        
                        $workflow=new WorkFlow;
                    
                        if(isset($_POST['WorkFlow'])){
                                $user->status = YumUser::STATUS_REJECTED;

                                $workflow->attributes=$_POST['WorkFlow'];
                                $workflow->data_type = get_class($user);
                                $workflow->data_id = $user->id;
                                $workflow->user_id = Yii::app()->user->id;
                                $workflow->action = WorkFlow::ACTION_REJECT;
                                $workflow->creation_time = date('d-m-Y');
                                $workflow->status = WorkFlow::STATUS_NEW;
                                
                                $last_forward = WorkFlow::model()->find(array(
                                    'condition'=>'data_id = :id AND data_type = :user_class AND action = :forward AND status = :new',
                                    'params'=>array(
                                        ':id'=>$user->id,
                                        ':user_class'=>  get_class($user),
                                        ':forward'=> WorkFlow::ACTION_FORWARD,
                                        ':new'=>  WorkFlow::STATUS_NEW,
                                    ),
                                    'order'=>'WFLOG_ID DESC',
                                ));

                                if($last_forward !== null){
                                    $workflow->parent_id = $last_forward->WFLOG_ID;
                                    $last_forward->status = WorkFlow::STATUS_PROCESSED;
                                }
                                
                                if($workflow->validate() && $user->validate()){
                                    
                                    $user->save();
                                    
                                    if($last_forward!==null)
                                        $last_forward->save();
                                    
                                    $workflow->save();
                                    
                                    // Stop jQuery from re-initialization
                                    Yii::app()->clientScript->scriptMap['jquery.js'] = false;

                                    echo CJSON::encode( array(
                                      'status' => 'success',
                                      'content' => '<h3>User Rejected.</h3><p><h5>Reason for Rejection:</h5>'.$workflow->comment.'</p>',
                                    ));
                                    exit;
                                }
                        }
                        // Stop jQuery from re-initialization
                        Yii::app()->clientScript->scriptMap['jquery.js'] = false;

                        echo CJSON::encode( array(
                            'status' => 'failure',
                            'content' => $this->renderPartial( '_reject_form', array(
                            'model' => $workflow ), true, true ),
                        ));
                        exit;

                    }
                    else{
                        echo CJSON::encode( array(
                            'status' => 'failure',
                            'content' => $approval_right['error'],
                        ));
                        exit;
                    }
            }    
            else 
                throw new CHttpException(403, Yum::t('Request cannot be processed.'));
        }
        
        /**
         * Action to forward User Approval Request of a User to senior staff member.
         */
        public function actionForward($id=null){
            if(Yii::app()->request->isAjaxRequest){
                Yii::import('application.modules.workflow.models.*');
                
                $approval_right = YumUser::approvalRights($id);
                    
                if($approval_right['has_right']){
                    $user = YumUser::model()->findByPk($id);
                    $workflow=new WorkFlow;
                    if(isset($_POST['WorkFlow'])){
                        $user->status = YumUser::STATUS_FORWARDED;
                        
                        $workflow->attributes=$_POST['WorkFlow'];
                        $workflow->data_type = get_class($user);
                        $workflow->data_id = $user->id;
                        $workflow->user_id = Yii::app()->user->id;
                        $workflow->action = WorkFlow::ACTION_FORWARD;
                        $workflow->creation_time = date('d-m-Y');
                        $workflow->status = WorkFlow::STATUS_NEW;
                        
                        $last_forward = WorkFlow::model()->find(array(
                            'condition'=>'data_id = :id AND data_type = :user_class AND action = :forward AND status = :new',
                            'params'=>array(
                                ':id'=>$user->id,
                                ':user_class'=>  get_class($user),
                                ':forward'=> WorkFlow::ACTION_FORWARD,
                                ':new'=>  WorkFlow::STATUS_NEW,
                            ),
                            'order'=>'WFLOG_ID DESC',
                        ));
                            
                        if($last_forward !== null){
                            $workflow->parent_id = $last_forward->WFLOG_ID;
                            $last_forward->status = WorkFlow::STATUS_PROCESSED;
                        }
                                
                        if($workflow->validate() && $user->validate()){
                            
                            $user->save();
                            if($last_forward!==null)
                                $last_forward->save();
                            
                            $workflow->save();
                            // Stop jQuery from re-initialization
                            Yii::app()->clientScript->scriptMap['jquery.js'] = false;

                            echo CJSON::encode( array(
                                'status' => 'success',
                                'content' => '<p>Request Forwarded Successfully.</p>',
                            ));
                            exit;
                        }
                    }

                    // Stop jQuery from re-initialization
                    Yii::app()->clientScript->scriptMap['jquery.js'] = false;
 
                    echo CJSON::encode(array(
                      'status' => 'failure',
                      'content' => $this->renderPartial( '_forward_form', array(
                        'model' => $workflow ), true, true ),
                    ));
                    exit;
                }
                else{
                    echo CJSON::encode(array(
                        'status' => 'failure',
                        'content' => $approval_right['error'],
                    ));
                    exit;
                }
            }    
            else 
                throw new CHttpException(403, Yum::t('Request cannot be processed.'));
        }
        
}
