<?php
/* @var $this ActionController */
/* @var $model PermissionAction */

$this->breadcrumbs=array(
	'Permission Actions'=>array('index'),
	$model->title=>array('view','id'=>$model->ACTION_ID),
	'Update',
);

$this->menu=array(
	array('label'=>'List PermissionAction', 'url'=>array('index')),
	array('label'=>'Create PermissionAction', 'url'=>array('create')),
	array('label'=>'View PermissionAction', 'url'=>array('view', 'id'=>$model->ACTION_ID)),
	array('label'=>'Manage PermissionAction', 'url'=>array('admin')),
);
?>

<h1>Update PermissionAction <?php echo $model->ACTION_ID; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>