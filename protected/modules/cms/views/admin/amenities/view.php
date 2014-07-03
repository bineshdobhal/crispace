<?php
/* @var $this AmenitiesController */
/* @var $model Amenities */

$this->breadcrumbs=array(
	'Amenities'=>array('index'),
	$model->AMENITIES_ID,
);

$this->menu=array(
	array('label'=>'List Amenities', 'url'=>array('index')),
	array('label'=>'Create Amenities', 'url'=>array('create')),
	array('label'=>'Update Amenities', 'url'=>array('update', 'id'=>$model->AMENITIES_ID)),
	array('label'=>'Delete Amenities', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->AMENITIES_ID),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Amenities', 'url'=>array('admin')),
);
?>

<h1>View Amenities #<?php echo $model->AMENITIES_ID; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'AMENITIES_ID',
		'amenities_name',
		'amenities_icon',
		'is_active',
	),
)); ?>
