<?php
/* @var $this LocalityController */
/* @var $model Locality */

$this->breadcrumbs=array(
	'Localities'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Locality', 'url'=>array('index')),
	array('label'=>'Manage Locality', 'url'=>array('admin')),
);
?>

<h1>Create Locality</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>