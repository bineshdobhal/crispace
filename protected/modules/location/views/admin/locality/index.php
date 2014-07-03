<?php
/* @var $this LocalityController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Localities',
);

$this->menu=array(
	array('label'=>'Create Locality', 'url'=>array('create')),
	array('label'=>'Manage Locality', 'url'=>array('admin')),
);
?>

<h1>Localities</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
