<?php
/* @var $this FeatureController */
/* @var $model PackageFeature */

$this->breadcrumbs=array(
	'Package Features'=>array('index'),
	$model->title=>array('view','id'=>$model->PACKAGE_FEATURE_ID),
	'Update',
);

$this->menu=array(
	array('label'=>'List PackageFeature', 'url'=>array('index')),
	array('label'=>'Create PackageFeature', 'url'=>array('create')),
	array('label'=>'View PackageFeature', 'url'=>array('view', 'id'=>$model->PACKAGE_FEATURE_ID)),
	array('label'=>'Manage PackageFeature', 'url'=>array('admin')),
);
?>

<h1>Update PackageFeature <?php echo $model->PACKAGE_FEATURE_ID; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>