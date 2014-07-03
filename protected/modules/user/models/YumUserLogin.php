<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'YumUserController'.
 */
class YumUserLogin extends YumFormModel {
	public $username;
	public $password;
	public $rememberMe;
        
        const ATTEMPTS_BEFORE_CAPTCHA = 3;
        
        public $verifyCode; //captcha

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		if(!isset($this->scenario))
			$this->scenario = 'login';

		$rules = array(
			array('username, password', 'required', 'on' => 'login'),
			array('username', 'required', 'on' => 'openid'),
			array('rememberMe', 'boolean'),
		);
                
                $login_attempts = Yii::app()->user->getState('login_attempts', 0);
                if($login_attempts > YumUserLogin::ATTEMPTS_BEFORE_CAPTCHA){
                    array_push($rules, array('verifyCode', 'captcha', 'allowEmpty'=>!CCaptcha::checkRequirements()));
                }

		return $rules;
	}

	public function attributeLabels() {
		return array(
			'username'=>Yum::t('Name'),
			'password'=>Yum::t("Password"),
			'rememberMe'=>Yum::t("Remember me next time"),
		);
	}

}
