<?php
/* @var $this WorkflowController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Work Flows',
);

$this->menu=array(
	array('label'=>'Create WorkFlow', 'url'=>array('create')),
	array('label'=>'Manage WorkFlow', 'url'=>array('admin')),
);
?>

<h1>Work Flows</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
