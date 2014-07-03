<?php
/* @var $this ActionController */
/* @var $model PermissionAction */

$this->breadcrumbs=array(
	'Permission Actions'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List PermissionAction', 'url'=>array('index')),
	array('label'=>'Manage PermissionAction', 'url'=>array('admin')),
);
?>

<h1>Create PermissionAction</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>