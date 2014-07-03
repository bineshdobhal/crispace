<?php
/* @var $this LocalityController */
/* @var $model Locality */

$this->breadcrumbs=array(
	'Localities'=>array('index'),
	$model->LOCAL_ID,
);

$this->menu=array(
	array('label'=>'List Locality', 'url'=>array('index')),
	array('label'=>'Create Locality', 'url'=>array('create')),
	array('label'=>'Update Locality', 'url'=>array('update', 'id'=>$model->LOCAL_ID)),
	array('label'=>'Delete Locality', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->LOCAL_ID),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Locality', 'url'=>array('admin')),
);
?>

<h1>View Locality #<?php echo $model->LOCAL_ID; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'LOCAL_ID',
		'city_id',
		'state_id',
		'locality_name',
		'is_active',
	),
)); ?>
