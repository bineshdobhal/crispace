<?php
/* @var $this AmenitiesController */
/* @var $model Amenities */

$this->breadcrumbs=array(
	'Amenities'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Amenities', 'url'=>array('index')),
	array('label'=>'Manage Amenities', 'url'=>array('admin')),
);
?>

<h1>Create Amenities</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>