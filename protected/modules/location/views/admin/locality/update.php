<?php
/* @var $this LocalityController */
/* @var $model Locality */

$this->breadcrumbs=array(
	'Localities'=>array('index'),
	$model->LOCAL_ID=>array('view','id'=>$model->LOCAL_ID),
	'Update',
);

$this->menu=array(
	array('label'=>'List Locality', 'url'=>array('index')),
	array('label'=>'Create Locality', 'url'=>array('create')),
	array('label'=>'View Locality', 'url'=>array('view', 'id'=>$model->LOCAL_ID)),
	array('label'=>'Manage Locality', 'url'=>array('admin')),
);
?>

<h1>Update Locality <?php echo $model->LOCAL_ID; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>