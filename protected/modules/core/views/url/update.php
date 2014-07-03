<?php
/* @var $this UrlController */
/* @var $model CoreUrl */

$this->breadcrumbs=array(
	'Core Urls'=>array('index'),
	$model->URL_REWRITE_ID=>array('view','id'=>$model->URL_REWRITE_ID),
	'Update',
);

$this->menu=array(
	array('label'=>'List CoreUrl', 'url'=>array('index')),
	array('label'=>'Create CoreUrl', 'url'=>array('create')),
	array('label'=>'View CoreUrl', 'url'=>array('view', 'id'=>$model->URL_REWRITE_ID)),
	array('label'=>'Manage CoreUrl', 'url'=>array('admin')),
);
?>

<h1>Update CoreUrl <?php echo $model->URL_REWRITE_ID; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>