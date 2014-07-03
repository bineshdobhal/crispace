<?php
/* @var $this ActionController */
/* @var $model PermissionAction */

$this->breadcrumbs=array(
	'Permission Actions'=>array('index'),
	$model->title,
);

$this->menu=array(
	array('label'=>'List PermissionAction', 'url'=>array('index')),
	array('label'=>'Create PermissionAction', 'url'=>array('create')),
	array('label'=>'Update PermissionAction', 'url'=>array('update', 'id'=>$model->ACTION_ID)),
	array('label'=>'Delete PermissionAction', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->ACTION_ID),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage PermissionAction', 'url'=>array('admin')),
);
?>

<h1>View PermissionAction #<?php echo $model->ACTION_ID; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'ACTION_ID',
		'title',
		'key',
		'parent_id',
	),
)); ?>
