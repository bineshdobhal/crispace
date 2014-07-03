<?php

/**
 * This is the model class for a User in Yum
 *
 * The followings are the available columns in table '{{users}}':
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $saltf
 * @property string $activationKey
 * @property integer $createtime
 * @property integer $lastvisit
 * @property integer $superuser
 * @property integer $status
 *
 * Relations
 * @property YumProfile $profile
 * @property array $roles array of YumRole
 * @property array $users array of YumUser
 *
 * Scopes:
 * @property YumUser $active
 * @property YumUser $notactive
 * @property YumUser $banned
 * @property YumUser $superuser
 *
 */
class YumUser extends YumActiveRecord
{
	const STATUS_INACTIVE = 0; //user not active
	const STATUS_ACTIVE = 1;//user is active (after staff approval and email verification).
        
        /***/
        const STATUS_APPROVAL_PENDING = 2; //waiting for staff response for approval
        const STATUS_FORWARDED = 3; //forwarded to senior officials for approval
        const STATUS_REJECTED = 4; //rejected by crispace staff.
        const STATUS_VERIFICATION_PENDING = 5; //approved by crispace staff but email verification is pending (user has not clicked on the activation link yet).
        /***/
        
	const STATUS_BANNED = -1;
	const STATUS_REMOVED = -2;

        //constants for User Type
        const USERTYPE_INDIVIDUAL = 0;
        const USERTYPE_STAFF = 1;
        const USERTYPE_BUILDER = 2;
        const USERTYPE_AGENT = 3;
        
	public $username;
	public $password;
	public $salt;
	public $activationKey;
	public $password_changed = false;
        
        
        /**
         * property to hold the form value for approval request flow in the cgridview searching and sorting on user/approval
         */
        public $_flow;
        
        public function behaviors()
	{
		return array(
				'CAdvancedArBehavior' => array(
					'class' => 'application.modules.user.components.CAdvancedArBehavior'));
	}

	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function delete()
	{
		if (Yum::module()->trulyDelete) {
			if($this->profile)
				$this->profile->delete();
			return parent::delete();
		} else {
			$this->status = self::STATUS_REMOVED;
			return $this->save(false, array('status'));
		}
	}

	public function afterDelete()
	{
		if (Yum::hasModule('profiles') && $this->profile !== null)
			$this->profile->delete();

		Yum::log(Yum::t('User {username} (id: {id}) has been deleted', array(
						'{username}' => $this->username,
						'{id}' => $this->id)));
		return parent::afterDelete();
	}

	public function isOnline()
	{
		return $this->lastaction > time() - Yum::module()->offlineIndicationTime;
	}

	// If Online status is enabled, we need to set the timestamp of the
	// last action when a user does something
	public function setLastAction()
	{
		if (!Yii::app()->user->isGuest && !$this->isNewRecord) {
			$this->lastaction = time();
			return $this->save(false, array('lastaction'));
		}
	}

	public function getLogins()
	{
		$sql = "select count(*) from activities where user_id = {$this->id} and action = 'login'";
		$result = Yii::app()->db->createCommand($sql)->queryAll();
		return $result[0]['count(*)'];
	}

	public function logout()
	{
		if (Yum::module()->enableOnlineStatus && !Yii::app()->user->isGuest) {
			$this->lastaction = 0;
			$this->save('lastaction');
		}
	}

	public function isActive()
	{
		return $this->status == YumUser::STATUS_ACTIVE;
	}

	// This function tries to generate a as human-readable password as possible
	public static function generatePassword()
	{
		$consonants = array("b", "c", "d", "f", "g", "h", "j", "k", "l", "m", "n", "p", "r", "s", "t", "v", "w", "x", "y", "z");
		$vocals = array("a", "e", "i", "o", "u");

		$password = '';

		srand((double)microtime() * 1000000);
		for ($i = 1; $i <= 4; $i++) {
			$password .= $consonants[rand(0, 19)];
			$password .= $vocals[rand(0, 4)];
		}
		$password .= rand(0, 9);

		return $password;
	}

	// Which memberships are bought by the user
	public function getActiveMemberships()
	{
		if (!Yum::hasModule('membership'))
			return array();

		Yii::import('application.modules.role.models.*');
		Yii::import('application.modules.membership.models.*');

		$roles = array();

		if ($this->memberships)
			foreach ($this->memberships as $membership) {
				if ($membership->end_date > time())
					$roles[] = $membership->role;
			}

		return $roles;
	}

	public function search()
	{
		$criteria = new CDbCriteria;

		if (Yum::hasModule('profile') && $this->profile) {
			$criteria->with = array('profile');
			if (isset($this->email))
				$criteria->addSearchCondition('profile.email', $this->email, true);
			else if ($this->profile && $this->profile->email)
				$criteria->compare('profile.email', $this->profile->email, true);
		}

		// Show newest users first by default
		if (!isset($_GET['YumUser_sort']))
			$criteria->order = 't.createtime DESC';

		$criteria->together = false;
		$criteria->compare('t.id', $this->id, true);
		$criteria->compare('t.username', $this->username, true);
                
                $criteria->compare('t.user_type', $this->user_type);
                
		$criteria->compare('t.status', $this->status);
		$criteria->compare('t.superuser', $this->superuser);
		$criteria->compare('t.createtime', $this->createtime, true);
		$criteria->compare('t.lastvisit', $this->lastvisit, true);

		return new CActiveDataProvider(get_class($this), array(
					'criteria' => $criteria,
					'pagination' => array('pageSize' => Yum::module()->pageSize),
					));
	}
        
        
        /**
         * Search function for Manage User Account Approvals.
         * @return CActiveDataProvider All records having status Approval Pending.
         */
        public function searchApprovals()
	{
		$criteria = new CDbCriteria;
                
                
                /**
                 * Example of searchApproval query
                 * 
                 * select * from cs_user as u
                 * LEFT JOIN cs_work_flow_log as wflog
                 * ON u.id = wflog.data_id
                 * WHERE
                 * u.status=2 OR (u.status = 3 AND wflog.to_user_id = 8 AND wflog.data_type = 'YumUser' AND wflog.action = 2 AND wflog.status = 0)
                 */
                Yii::import('application.modules.workflow.models.*');
                
                $criteria->select = 't.*, wflog.*';
                $criteria->join = "LEFT JOIN cs_work_flow_log as wflog ON t.id = wflog.data_id";
                $criteria->condition = '(t.status =:pending OR (t.status = :forwarded AND (wflog.to_user_id = :staff_id OR wflog.user_id = :staff_id) AND wflog.data_type = :user_class AND wflog.action = :action AND wflog.status= :wf_status))';
                
                $criteria->params = array(
                    ':pending'=>  YumUser::STATUS_APPROVAL_PENDING,
                    ':forwarded'=> YumUser::STATUS_FORWARDED,
                    ':staff_id'=>Yii::app()->user->id,
                    ':user_class'=>  get_class($this),
                    ':action'=>  WorkFlow::ACTION_FORWARD,
                    ':wf_status'=>  WorkFlow::STATUS_NEW,
                );
                
                if($this->_flow != null || $this->_flow != ""){
                   var_dump($this->_flow);
                    if($this->_flow == WorkFlow::REQUEST_NEW){
                        $criteria->condition = 't.status =:pending';
                        $criteria->params = array(
                            ':pending'=>  YumUser::STATUS_APPROVAL_PENDING,
                        );
                    }
                    elseif($this->_flow == WorkFlow::REQUEST_FORWARDED){
                        $criteria->condition = '(t.status = :forwarded AND wflog.user_id = :staff_id AND wflog.data_type = :user_class AND wflog.action = :action AND wflog.status= :wf_status)';
                        
                        $criteria->params = array(
                            ':forwarded'=> YumUser::STATUS_FORWARDED,
                            ':staff_id'=>Yii::app()->user->id,
                            ':user_class'=>  get_class($this),
                            ':action'=>  WorkFlow::ACTION_FORWARD,
                            ':wf_status'=>  WorkFlow::STATUS_NEW,
                        );
                    }
                    elseif($this->_flow == WorkFlow::REQUEST_RECEIVED){
                        $criteria->condition = '(t.status = :forwarded AND wflog.to_user_id = :staff_id AND wflog.data_type = :user_class AND wflog.action = :action AND wflog.status= :wf_status)';
                        
                        $criteria->params = array(
                            ':forwarded'=> YumUser::STATUS_FORWARDED,
                            ':staff_id'=>Yii::app()->user->id,
                            ':user_class'=>  get_class($this),
                            ':action'=>  WorkFlow::ACTION_FORWARD,
                            ':wf_status'=>  WorkFlow::STATUS_NEW,
                        );
                    }
                }
               
                //$criteria->compare('t.status', YumUser::STATUS_APPROVAL_PENDING, false, 'AND');
		
                $criteria->together = false;
		$criteria->compare('t.id', $this->id, true);
		$criteria->compare('t.username', $this->username, true);
                
                $criteria->compare('t.user_type', $this->user_type);
                
		$criteria->compare('t.createtime', $this->createtime, true);
		
		return new CActiveDataProvider(get_class($this), array(
                    'criteria' => $criteria,
                    'pagination' => array('pageSize' => Yum::module()->pageSize),                   
                ));
	}

	public function beforeValidate()
	{
		if ($this->isNewRecord) {
			if(!$this->salt)
				$this->salt = YumEncrypt::generateSalt();
			$this->createtime = time();
		}

		return true;
	}

	public function setPassword($password, $salt = null)
	{
		if ($password != '') {
			$this->password = YumEncrypt::encrypt($password, $salt);
			$this->lastpasswordchange = time();
			$this->password_changed = true;
			$this->salt = $salt;
			if (!$this->isNewRecord){
                            return $this->save();
                        }   
                        
			else{
				return $this;
                        }        
		}
	}

	public function afterSave()
	{
		if (Yum::hasModule('profile') && Yum::module('profile')->enablePrivacySetting) {
			// create a new privacy setting, if not already available
			$setting = YumPrivacySetting::model()->findByPk($this->id);
			if (!$setting) {
				$setting = new YumPrivacySetting();
				$setting->user_id = $this->id;
				$setting->save();
			}

			if ($this->isNewRecord) {
				Yum::log(Yum::t('A user has been created: user: {user}', array(
								'{user}' => json_encode($this->attributes))));


			}
		}
		return parent::afterSave();
	}

	/**
	 * Returns resolved table name (incl. table prefix when it is set in db configuration)
	 * Following algorith of searching valid table name is implemented:
	 *  - try to find out table name stored in currently used module
	 *  - if not found try to get table name from UserModule configuration
	 *  - if not found user default {{users}} table name
	 * @return string
	 */
	public function tableName()
	{
		$this->_tableName = Yum::module()->userTable;

		return $this->_tableName;
	}

	public function rules()
	{
		$passwordRequirements = Yum::module()->passwordRequirements;
		$usernameRequirements = Yum::module()->usernameRequirements;

		$passwordrule = array_merge(array('password', 'YumPasswordValidator'), $passwordRequirements);

		$rules[] = $passwordrule;

		$rules[] = array('username', 'length',
				'max' => $usernameRequirements['maxLen'],
				'min' => $usernameRequirements['minLen'],
				'message' => Yum::t(
					'Username length needs to be between {minLen} and {maxlen} characters', array(
						'{minLen}' => $usernameRequirements['minLen'],
						'{maxLen}' => $usernameRequirements['maxLen'])));

		$rules[] = array('username',
				'unique',
				'message' => Yum::t("This user's name already exists."));
		$rules[] = array(
				'username',
				'match',
				'pattern' => $usernameRequirements['match'],
				'message' => Yum::t($usernameRequirements['dontMatchMessage']));
		$rules[] = array('status', 'in', 'range' => array( -1, -2, 0, 1, 2, 3, 4, 5));
                
                //rules for user_type
                $rules[] = array('user_type', 'in', 'range' => array(0, 1, 2, 3));
                
		$rules[] = array('superuser', 'in', 'range' => array(0, 1));
		$rules[] = array('username, createtime, lastvisit, lastpasswordchange, superuser, status', 'required');
		$rules[] = array('notifyType, avatar', 'safe');
		$rules[] = array('password', 'required', 'on' => array('insert', 'registration'));
		$rules[] = array('salt', 'required', 'on' => array('insert', 'registration'));
		$rules[] = array('createtime, lastvisit, lastaction, superuser, status', 'numerical', 'integerOnly' => true);
                
                //$_flow safe on searchApproval
                $rules[] = array('_flow', 'safe', 'on'=>'searchApprovals');
                
                //rules for id on searchApproval
                $rules[] = array('id', 'numerical', 'on'=>array('searchApprovals'));

		if (Yum::hasModule('avatar')) {
			// require an avatar image in the avatar upload screen
			$rules[] = array('avatar', 'required', 'on' => 'avatarUpload');

			// if automatic scaling is deactivated, require the exact size	
			$rules[] = array('avatar', 'EPhotoValidator',
					'allowEmpty' => true,
					'mimeType' => array('image/jpeg', 'image/png', 'image/gif'),
					'maxWidth' => Yum::module('avatar')->avatarMaxWidth,
					'maxHeight' => Yum::module('avatar')->avatarMaxWidth,
					'minWidth' => 50,
					'minHeight' => 50,
					'on' => 'avatarSizeCheck');
		}
		return $rules;
	}

	public function hasRole($role_title)
	{
		
	}

	public function getRoles()
	{
		
	}

	// We retrieve the permissions from:
	// 1.) All direct given permissions ($this->permissions)
	// 2.) All direct given permissions to a role the user belongs
	
	public function getPermissions()
	{
		if(!$this->id)
			return array();
                $role=  UserRole::model()->getUserRole($this);
                if(!$role->role_id){
                    return array();
                }
		$permissions = array();
		$sql = "SELECT pa.ACTION_ID AS id, pa.key from ".  PermissionMap::model()->tableName()." pm left join ".  PermissionAction::model()->tableName()." pa on pa.ACTION_ID = pm.permission_id where pm.type = '".PermissionMap::TYPE_ROLE."' and pm.principal_id = {$role->role_id}";
			foreach (Yii::app()->db->cache(500)->createCommand($sql)->query()->readAll() as $permission)
				$permissions[$permission['id']] = $permission['key'];
		


		// Direct user permission assignments
		$sql = "select pa.ACTION_ID as id, pa.key from ".  PermissionMap::model()->tableName()." pm left join ".  PermissionAction::model()->tableName()." pa on pa.ACTION_ID = pm.permission_id where pm.type = '".PermissionMap::TYPE_USER."' and pm.principal_id = {$this->id}";
		foreach (Yii::app()->db->cache(500)->createCommand($sql)->query()->readAll() as $permission)
			$permissions[$permission['id']] = $permission['key'];


		return $permissions;
	}

	public function can($action)
	{
		foreach ($this->getPermissions() as $permission)
			if ($permission == $action)
				return true;
                                
		return false;
	}

	// possible relations are cached because they depend on the active submodules
	// and it takes many expensive milliseconds to evaluate them all the time
	public function relations()
	{
		Yii::import('application.modules.profile.models.*');

		//$relations = Yii::app()->cache->get('yum_user_relations');
                $relations=false;
		if($relations === false) {
			$relations = array();

					

			if (Yum::hasModule('profile')) {
				$relations['visits'] = array(
						self::HAS_MANY, 'YumProfileVisit', 'visited_id');
				$relations['visited'] = array(
						self::HAS_MANY, 'YumProfileVisit', 'visitor_id');
				$relations['profile'] = array(
						self::HAS_ONE, 'YumProfile', 'user_id');
				$relations['privacy'] = array(
						self::HAS_ONE, 'YumPrivacySetting', 'user_id');
			}
                        
                        $relations['role'] = array(
						self::MANY_MANY, 'Role',
						 UserRole::model()->tableName().'(user_id, role_id)');
                      
			Yii::app()->cache->set('yum_user_relations', $relations, 3600);
		}

		return $relations;
	}

	public function isFriendOf($invited_id)
	{
		foreach ($this->getFriendships() as $friendship) {
			if ($friendship->inviter_id == $this->id && $friendship->friend_id == $invited_id)
				return $friendship->status;
		}

		return false;
	}

	public function getFriendships()
	{
		$condition = 'inviter_id = :uid or friend_id = :uid';
		return YumFriendship::model()->findAll($condition, array(':uid' => $this->id));
	}

	// Friends can not be retrieve via the relations() method because a friend
	// can either be in the invited_id or in the friend_id column.
	// set $everything to true to also return pending and rejected friendships
	public function getFriends($everything = false)
	{
		if ($everything)
			$condition = 'inviter_id = :uid';
		else
			$condition = 'inviter_id = :uid and status = 2';

		$friends = array();
		Yii::import('application.modules.friendship.models.YumFriendship');
		$friendships = YumFriendship::model()->findAll($condition, array(
					':uid' => $this->id));
		if ($friendships != NULL && !is_array($friendships))
			$friendships = array($friendships);

		if ($friendships)
			foreach ($friendships as $friendship)
				$friends[] = YumUser::model()->findByPk($friendship->friend_id);

		if ($everything)
			$condition = 'friend_id = :uid';
		else
			$condition = 'friend_id = :uid and status = 2';

		$friendships = YumFriendship::model()->findAll($condition, array(
					':uid' => $this->id));

		if ($friendships != NULL && !is_array($friendships))
			$friendships = array($friendships);


		if ($friendships)
			foreach ($friendships as $friendship)
				$friends[] = YumUser::model()->findByPk($friendship->inviter_id);

		return $friends;
	}

	// Registers a user 
	public function register($username = null,
			$password = null,
			$profile = null,
			$salt = null) {
		if (!($profile instanceof YumProfile)) 
			return false;

		if ($username !== null && $password !== null) {
			// Password equality is checked in Registration Form
			$this->username = $username;
			if(!$salt)
				$salt = YumEncrypt::generateSalt();

			$this->setPassword($password, $salt);
		}
		$this->activationKey = $this->generateActivationKey(false/*, $password*/);
		$this->createtime = time();
		$this->superuser = 0;

		// Users stay banned until they confirm their email address.
		$this->status = YumUser::STATUS_INACTIVE;
                
		// If the avatar module and avatar->enableGravatar is activated, we assume
		// the user wants to use his Gravatar automatically after registration
		if(Yum::hasModule('avatar') && Yum::module('avatar')->enableGravatar)
			$this->avatar = 'gravatar';

		if ($this->validate() && $profile->validate()) {
			$this->save();
			$profile->user_id = $this->id;
			$profile->save();
			$this->profile = $profile;

			if(Yum::hasModule('role'))
				foreach(Yum::module('registration')->defaultRoles as $role) 
					Yii::app()->db->createCommand(sprintf(
								'insert into %s (user_id, role_id) values(%s, %s)',
								Yum::module('role')->userRoleTable,
								$this->id,
								$role))->execute(); 

			Yum::log(Yum::t('User {username} registered. Generated activation Url is {activation_url} and has been sent to {email}',
						array(
							'{username}' => $this->username,
							'{email}' => $profile->email,
							'{activation_url}' => $this->getActivationUrl()))
					);

			return $this;
		}

		return false;
	}

	public function getActivationUrl()
	{
		/**
		 * Quick check for a enabled Registration Module
		 */
		if (Yum::module('registration')) {
			$activationUrl = Yum::module('registration')->activationUrl;
			if (is_array($activationUrl) && isset($this->profile)) {
				$activationUrl = $activationUrl[0];
				$params['key'] = $this->activationKey;
				$params['email'] = $this->profile->email;

				return Yii::app()->controller->createAbsoluteUrl($activationUrl, $params);
			}
		}
		return Yum::t('Activation Url cannot be retrieved');
	}

	public function isPasswordExpired()
	{
		$distance = Yum::module('user')->password_expiration_time * 60 * 60;
		return $this->lastpasswordchange - $distance > time();
	}

	/**
	 * Activation of an user account.
	 * If everything is set properly, and the emails exists in the database,
	 * and is associated with a correct user, and this user has the status
	 * NOTACTIVE and the given activationKey is identical to the one in the
	 * database then generate a new Activation key to avoid double activation,
	 * set the status to ACTIVATED and save the data
	 * Error Codes:
	 * -1 : User is not inactive, it can not be activated
	 * -2 : Wrong activation key
	 * -3 : Profile found, but no user - database inconsistency?
	 */
	public static function activate($email, $key)
	{
		Yii::import('application.modules.profile.models.*');

		if ($profile = YumProfile::model()->find("email = :email", array(
						':email' => $email))
			 ) {
			if ($user = $profile->user) {
				if ($user->status != self::STATUS_INACTIVE && !Yum::module('registration')->enableApprovalRequired)
					return -1;
                                if($user->status != self::STATUS_VERIFICATION_PENDING && Yum::module('registration')->enableApprovalRequired)
                                    return -1;
                                
				if ($user->activationKey == $key) {
					$user->activationKey = $user->generateActivationKey(true);
					
                                        //check if "approval required" is active 
                                        if(Yum::module('registration')->enableApprovalRequired){
                                            $user->status = self::STATUS_APPROVAL_PENDING;
                                        }
                                        else{
                                            $user->status = self::STATUS_ACTIVE;
                                        }
                                        
                                        if ($user->save(false, array('activationKey', 'status'))) {
						Yum::log(Yum::t('User {username} has been activated', array(
										'{username}' => $user->username)));
						if (Yum::hasModule('messages')
								&& Yum::module('registration')->enableActivationConfirmation
							 ) {
							Yii::import('application.modules.messages.models.YumMessage');
							YumMessage::write($user, 1,
									Yum::t('Your activation succeeded'),
									strtr(
										'The activation of the account {username} succeeded. Please use <a href="{link_login}">this link</a> to go to the login page', array(
											'{username}' => $user->username,
											'{link_login}' =>
											Yii::app()->controller->createUrl('//user/user/login'))));
						}

						return $user;
					}
				} else return -2;
			} else return -3;
		}
		return false;
	}

	/**
	 * @params boolean $activate Whether to generate activation key when user is
	 * registering first time (false)
	 * or when it is activating (true)
	 * @params string $password password entered by user
	 * @param array $params, optional, to allow passing values outside class in inherited classes
	 * By default it uses password and microtime combination to generated activation key
	 * When user is activating, activation key becomes micortime()
	 * @return string
	 */
	public function generateActivationKey($activate = false)
	{
		if($activate) {
			$this->activationKey = $activate;
			$this->save(false, array('activationKey'));
		} else
			$this->activationKey = YumEncrypt::encrypt(microtime() . $this->password, $this->salt);

		if(!$this->isNewRecord)
			$this->save(false, array('activationKey'));

		return $this->activationKey;
	}

	public function attributeLabels()
	{
		return array(
				'id' => Yum::t('#'),
				'username' => Yum::t("Username"),
				'password' => Yum::t("Password"),
				'verifyPassword' => Yum::t("Retype password"),
				'verifyCode' => Yum::t("Verification code"),
				'activationKey' => Yum::t("Activation key"),
				'createtime' => Yum::t("Registration date"),
				'lastvisit' => Yum::t("Last visit"),
				'lastaction' => Yum::t("Online status"),
				'superuser' => Yum::t("Superuser"),
                    
                                'user_type' => Yum::t("User Type"),
                    
				'status' => Yum::t("Status"),
				'avatar' => Yum::t("Avatar image"),
                    
                                '_flow'=>Yum::t('Request Flow'),
                                );
	}
	
	public function withRoles($roles)
	{
		if(!is_array($roles))
			$roles = array($roles);

		$this->with('roles');
		$this->getDbCriteria()->addInCondition('roles.id', $roles);
		return $this;
	}

	public function scopes()
	{
		return array(
				'active' => array('condition' => 'status=' . self::STATUS_ACTIVE,),
				'inactive' => array('condition' => 'status=' . self::STATUS_INACTIVE,),
				'banned' => array('condition' => 'status=' . self::STATUS_BANNED,),
				'superuser' => array('condition' => 'superuser = 1',),
				'public' => array(
					'join' => 'LEFT JOIN privacysetting on t.id = privacysetting.user_id',
					'condition' => 'appear_in_search = 1',),
				);
	}

	public static function itemAlias($type, $code = NULL)
	{
		$_items = array(
				'UserStatus' => array(
					self::STATUS_INACTIVE => Yum::t('Not active'),
					self::STATUS_ACTIVE => Yum::t('Active'),
                                        self::STATUS_APPROVAL_PENDING=> Yum::t('Approval Pending'),
                                        self::STATUS_FORWARDED => Yum::t('Forwarded for Approval'),
                                        self::STATUS_REJECTED => Yum::t('Rejected'),
                                        self::STATUS_VERIFICATION_PENDING => Yum::t('Email Verification Pending'),
					self::STATUS_BANNED => Yum::t('Banned'),
					self::STATUS_REMOVED => Yum::t('Deleted'),
					),
				'AdminStatus' => array(
					'0' => Yum::t('No'),
					'1' => Yum::t('Yes'),
					),
                                
                                'UserType' => array(
                                        self::USERTYPE_INDIVIDUAL=> Yum::t('Individual'),
                                        self::USERTYPE_STAFF => Yum::t('Staff Member'),
                                        self::USERTYPE_BUILDER => Yum::t('Builder'),
                                        self::USERTYPE_AGENT => Yum::t('Agent'),
                                ),
                    
                );
		if (isset($code))
			return isset($_items[$type][$code]) ? $_items[$type][$code] : false;
		else
			return isset($_items[$type]) ? $_items[$type] : false;
	}

	/**
	 * Get all users with a specified role.
	 * @param string $roleTitle title of role to be searched
	 * @return array users with specified role. Null if none are found.
	 */
	public static function getUsersByRole($roleTitle)
	{
		$role = YumRole::model()->findByAttributes(array('title' => $roleTitle));
		return $role ? $role->users : null;
	}

	/**
	 * Return admins.
	 * @return array syperusers names
	 */
	public static function getAdmins()
	{
		$admins = YumUser::model()->active()->superuser()->findAll();
		$returnarray = array();
		foreach ($admins as $admin)
			array_push($returnarray, $admin->username);
		return $returnarray;
	}

	public function getGravatarHash() {
		return md5(strtolower(trim($this->profile->email)));		
	}

	public function getAvatar($thumb = false)
	{
		if (Yum::hasModule('avatar') && $this->profile) {
			$options = array();
			if ($thumb)
				$options = array('class' => 'avatar', 'style' => 'width: ' . Yum::module('avatar')->avatarThumbnailWidth . ' px;');
			else
				$options = array('class' => 'avatar', 'style' => 'width: ' . Yum::module('avatar')->avatarDisplayWidth . ' px;');

			$return = '<div class="avatar">';

			if(Yum::module('avatar')->enableGravatar && $this->avatar == 'gravatar') 
				return CHtml::image(
						'http://www.gravatar.com/avatar/'. $this->getGravatarHash(),
						Yum::t('Avatar image'),
						$options);

			if (isset($this->avatar) && $this->avatar)
				$return .= CHtml::image(Yii::app()->baseUrl . '/'
						. $this->avatar, 'Avatar', $options);
			else
				$return .= CHtml::image(Yii::app()->getAssetManager()->publish(
							Yii::getPathOfAlias('YumAssets.images') . ($thumb
								? '/no_avatar_available_thumb.jpg' : '/no_avatar_available.jpg'),
							Yum::t('No image available'),
							$options));
			$return .= '</div><!-- avatar -->';
			return $return;
		}
	}
        
        /**
         * function returns appropriate heading and message in accordance with the status code.
         * If status code is not supplied, an array containg respective heading and message for all status code is returned.
         * If invalid status code is supplied, false is returned.
         * 
         * @param INTEGER $status_code status code.
         * @return MIXED array containing heading and message. if invalid status code is supplied, false is returned. 
         */
        public static function getStatusDetail($status_code = null){
            $_items = array(
                self::STATUS_INACTIVE => array('heading'=>Yum::t("Account Inactive"), 'message'=>Yum::t("This account is Inactive.")),
                self::STATUS_ACTIVE => array('heading'=>Yum::t("Account Active"), 'message'=>Yum::t("This account is Active.")),
                self::STATUS_APPROVAL_PENDING => array('heading'=>Yum::t("Account is waiting for Approval"), 'message'=>Yum::t("This account is listed for approval and will be Active after Approval only. An Activation Confirmation mail will be sent at the registered email address as soon as account is approved.")),
                self::STATUS_FORWARDED=> array('heading'=>Yum::t("Forwarded For Approval"), 'message'=>Yum::t("This account is forwarded for Approval.")),
                self::STATUS_REJECTED => array('heading'=>Yum::t("Account Rejected"), 'message'=>Yum::t("This account is Disapproved.")),
                self::STATUS_VERIFICATION_PENDING => array('heading'=>Yum::t("Email Verification Pending"), 'message'=>Yum::t("An Email ID Varification mail is sent at the registered email address. User is requested to check his/her email account and click on the activation link. Account will be considered for further Activation Process after Email ID Verification only.")),
                self::STATUS_BANNED => array('heading'=>Yum::t("Account Banned"), 'message'=>Yum::t("This account is Banned.")),
                self::STATUS_REMOVED => array('heading'=>Yum::t("Account Deleted"), 'message'=>Yum::t("This account is Deleted.")),
            );
            
            if (isset($status_code))
                return isset($_items[$status_code]) ? $_items[$status_code] : false;
            else
                return isset($_items) ? $_items : false;
            
        }
        
        /**
         * function to return senior staff member of the current staff member.
         * 
         * NOTE: This function is temporary and must be defined in the class for the Staff Hierarchy
         */
        public static function getSeniorStaff(){
            $senior = YumUser::model()->findAll('id IN (1,8,11)');
            return CHtml::listData($senior, 'id', 'username');
        }
        
        /**
         * Function returns the flow of request with respect to the current staff member.
         * If a request in work flow table is forwarded by current staff member to another staff member then "Forwarded to {staff member}" will be returned if $alias is true, else WorkFlow::REQUEST_FORWARDED will be returned.
         * If a request in work flow table is forwarded to the current staff member by another staff member then "Received from {staff member} will be returned" if $alias is true, else WorkFlow::REQUEST_RECEIVED will be returned.
         * If no record if found in the work flow log table then "New Request" will be returned if $alias is true, else WorkFlow::REQUEST_NEW will be returned..
         * 
         * @param BOOLEAN $alias default to true. If set to true, function returns alias for the request flow, else numeric code is returned.
         * 
         * @return STRING explanation of the request flow. 
         */
        public function getRequestFlow($alias = true){
            Yii::import('application.modules.workflow.models.*');
            $flow = ($alias)?Yum::t('New Request'):WorkFlow::REQUEST_NEW;
            
            $workFlow = WorkFlow::model()->find(array(
                'condition'=>'data_type = :user_class AND data_id = :uid AND (user_id = :staff_id OR to_user_id = :staff_id)',
                'params'=>array(':user_class'=>  get_class($this), ':uid'=>$this->id, ':staff_id'=>Yii::app()->user->id),
            ));
            
            if($workFlow !== null){
                if($workFlow->user_id == Yii::app()->user->id)
                    $flow = ($alias)?WorkFlow::getRequestFlowAlias(WorkFlow::REQUEST_FORWARDED)." to ". YumUser::model()->findByPk($workFlow->to_user_id)->username:WorkFlow::REQUEST_FORWARDED;
                else if($workFlow->to_user_id = Yii::app()->user->id)
                    $flow = ($alias)?WorkFlow::getRequestFlowAlias (WorkFlow::REQUEST_RECEIVED)." from ". YumUser::model()->findByPk($workFlow->user_id)->username:WorkFlow::REQUEST_RECEIVED;
            }
            
            return $flow;
        }
        
        
        /**
         * Function determines whether the currently logged in staff member can process an approval request or not.
         * Current staff member can only process those requests which are new or forwarded to him/her by some other staff member.
         * While those forwarded by him/her to other staff members cannot be processed by him/her.
         * 
         * Function also returns appropriate error messages if current staff member has no right to process the request. 
         *    
         * @param INTEGER $user_id ID of the user waiting for approval.
         * @return ARRAY containing keys has_right and error. if value at has_right is true then the current staff member can process the request. If it is false then the key "error" contains the appropriate error message.    
         */
        public static function approvalRights($user_id){
            
            $return['has_right'] = false;
            $return['error'] = Yum::t("You can not process this request.");
            
            if(Yii::app()->user->isStaff()||Yii::app()->user->isAdmin()){
                if($user_id == null){
                    $return['error'] = Yum::t("User Not Defined");
                    return $return;
                }
                
                $user = YumUser::model()->find(array('condition'=>'id = :id', 'params'=>array(':id'=>$user_id,)));
                
                if($user === null){
                    $return['error'] = Yum::t("User Not Found.");
                    return $return;
                }
                
                if($user->status == YumUser::STATUS_APPROVAL_PENDING){
                    $return['has_right'] = true;
                    $return['error'] = false;
                    return $return;
                }
                
                if($user->status == YumUser::STATUS_FORWARDED){
                    Yii::import('application.modules.workflow.models.*');
                    $workflow = WorkFlow::model()->find(array(
                        'condition'=>'data_id = :id AND data_type = :user_class AND to_user_id = :staff_id AND status = :new',
                        'params'=>array(
                            ':id'=>$user->id,
                            ':user_class'=>  get_class($user),
                            ':staff_id' => Yii::app()->user->id,
                            ':new' => WorkFlow::STATUS_NEW,
                        ),
                    ));
                    
                    if($workflow !== null){
                        $return['has_right']=true;
                        return $return;
                    }
                    else{
                        $return['has_right'] = false;
                        $return['error']=Yum::t('This request is forwarded to some other staff member. You can not process this request.');
                        return $return;
                    }
                }
                
                $return['has_right'] = false;
                $return['error'] = Yum::t('This request is already processed by some other staff member.');
                    
            }
            return $return;
            
        }
        
        /**
         * Function just returns whether a staff member can process approval request or not.
         * this function calls approvalRight() and returns true or false.
         * 
         * @return BOOLEAN returns true if current staff member can process approval request else false.
         */
        public function checkApprovalRights(){
            $check = YumUser::approvalRights($this->id);
            return $check['has_right'];
        }
        
}