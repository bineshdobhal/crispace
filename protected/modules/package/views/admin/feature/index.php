<?php
/* @var $this FeatureController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Package Features',
);

$this->menu=array(
	array('label'=>'Create PackageFeature', 'url'=>array('create')),
	array('label'=>'Manage PackageFeature', 'url'=>array('admin')),
);
?>

<h1>Package Features</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
