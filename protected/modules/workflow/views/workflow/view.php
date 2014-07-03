<?php
/* @var $this WorkflowController */
/* @var $model WorkFlow */

$this->breadcrumbs=array(
	'Work Flows'=>array('index'),
	$model->WFLOG_ID,
);

$this->menu=array(
	array('label'=>'List WorkFlow', 'url'=>array('index')),
	array('label'=>'Create WorkFlow', 'url'=>array('create')),
	array('label'=>'Update WorkFlow', 'url'=>array('update', 'id'=>$model->WFLOG_ID)),
	array('label'=>'Delete WorkFlow', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->WFLOG_ID),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage WorkFlow', 'url'=>array('admin')),
);
?>

<h1>View WorkFlow #<?php echo $model->WFLOG_ID; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'WFLOG_ID',
		'data_id',
		'data_type',
		'user_id',
		'to_user_id',
		'comment',
		'action',
		'status',
		'creation_time',
		'parent_id',
	),
)); ?>
