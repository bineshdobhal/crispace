<?php
/* @var $this AmenitiesController */
/* @var $model Amenities */

$this->breadcrumbs=array(
	'Amenities'=>array('index'),
	$model->AMENITIES_ID=>array('view','id'=>$model->AMENITIES_ID),
	'Update',
);

$this->menu=array(
	array('label'=>'List Amenities', 'url'=>array('index')),
	array('label'=>'Create Amenities', 'url'=>array('create')),
	array('label'=>'View Amenities', 'url'=>array('view', 'id'=>$model->AMENITIES_ID)),
	array('label'=>'Manage Amenities', 'url'=>array('admin')),
);
?>

<h1>Update Amenities <?php echo $model->AMENITIES_ID; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>