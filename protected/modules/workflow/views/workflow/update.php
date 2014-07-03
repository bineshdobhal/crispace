<?php
/* @var $this WorkflowController */
/* @var $model WorkFlow */

$this->breadcrumbs=array(
	'Work Flows'=>array('index'),
	$model->WFLOG_ID=>array('view','id'=>$model->WFLOG_ID),
	'Update',
);

$this->menu=array(
	array('label'=>'List WorkFlow', 'url'=>array('index')),
	array('label'=>'Create WorkFlow', 'url'=>array('create')),
	array('label'=>'View WorkFlow', 'url'=>array('view', 'id'=>$model->WFLOG_ID)),
	array('label'=>'Manage WorkFlow', 'url'=>array('admin')),
);
?>

<h1>Update WorkFlow <?php echo $model->WFLOG_ID; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>