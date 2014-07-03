<?php
$this->title = Yum::t("Create user");
$this->breadcrumbs = array(
		Yum::t('Users') => array('index'),
		Yum::t('Create'));

echo $this->renderPartial('_form', array(
			'model'=>$model,
			'profile'=>isset($profile) ? $profile : null,
                        'roleModel'=>$roleModel,
            )); ?>
