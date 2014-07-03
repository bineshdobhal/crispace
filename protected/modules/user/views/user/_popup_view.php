<?php
$profiles = Yum::hasModule('profile');

$attributes = array(
			'id',
	);

	if(!Yum::module()->loginType & UserModule::LOGIN_BY_EMAIL)
		$attributes[] = 'username';

	if($profiles) {
		$profileFields = YumProfileField::model()->forOwner()->findAll();
		if ($profileFields && $model->profile) {
			foreach($profileFields as $field) {
				array_push($attributes, array(
							'label' => Yum::t($field->title),
							'type' => 'raw',
							'value' => is_array($model->profile)
							? $model->profile->getAttribute($field->varname)
							: $model->profile->getAttribute($field->varname) ,
							));
			}
		}
	}

	array_push($attributes,
		/*
		There is no added value to showing the password/salt/activationKey because 
		these are all encrypted 'password', 'salt', 'activationKey',*/
		array(
			'name' => 'createtime',
			'value' => date(UserModule::$dateFormat,$model->createtime),
			),
		array(
			'name' => 'lastvisit',
			'value' => date(UserModule::$dateFormat,$model->lastvisit),
			),
		array(
			'name' => 'lastpasswordchange',
			'value' => date(UserModule::$dateFormat,$model->lastpasswordchange),
			),
		array(
			'name' => 'superuser',
			'value' => YumUser::itemAlias("AdminStatus",$model->superuser),
			),
		array(
			'name' => Yum::t('Activation link'),
			'value' =>$model->getActivationUrl()),
		array(
				'name' => 'status',
			'value' => YumUser::itemAlias("UserStatus",$model->status),
			)
		);

	$this->widget('zii.widgets.CDetailView', array(
				'data'=>$model,
				'attributes'=>$attributes,
				));
