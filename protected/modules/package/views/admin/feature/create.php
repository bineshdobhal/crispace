<?php
/* @var $this FeatureController */
/* @var $model PackageFeature */

$this->breadcrumbs=array(
	'Package Features'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List PackageFeature', 'url'=>array('index')),
	array('label'=>'Manage PackageFeature', 'url'=>array('admin')),
);
?>

<h1>Create PackageFeature</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>