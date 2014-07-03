<?php
/* @var $this WorkflowController */
/* @var $model WorkFlow */

$this->breadcrumbs=array(
	'Work Flows'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List WorkFlow', 'url'=>array('index')),
	array('label'=>'Manage WorkFlow', 'url'=>array('admin')),
);
?>

<h1>Create WorkFlow</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>