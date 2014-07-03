<?php
/* @var $this ActionController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Permission Actions',
);

$this->menu=array(
	array('label'=>'Create PermissionAction', 'url'=>array('create')),
	array('label'=>'Manage PermissionAction', 'url'=>array('admin')),
);
?>

<h1>Permission Actions</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
