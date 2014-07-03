<?php
/* @var $this AmenitiesController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Amenities',
);

$this->menu=array(
	array('label'=>'Create Amenities', 'url'=>array('create')),
	array('label'=>'Manage Amenities', 'url'=>array('admin')),
);
?>

<h1>Amenities</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
