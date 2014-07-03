<?php
/* @var $this UrlController */
/* @var $model CoreUrl */

$this->breadcrumbs=array(
	'Core Urls'=>array('index'),
	$model->URL_REWRITE_ID,
);

$this->menu=array(
	array('label'=>'List CoreUrl', 'url'=>array('index')),
	array('label'=>'Create CoreUrl', 'url'=>array('create')),
	array('label'=>'Update CoreUrl', 'url'=>array('update', 'id'=>$model->URL_REWRITE_ID)),
	array('label'=>'Delete CoreUrl', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->URL_REWRITE_ID),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage CoreUrl', 'url'=>array('admin')),
);
?>

<h1>View CoreUrl #<?php echo $model->URL_REWRITE_ID; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'URL_REWRITE_ID',
		'request_path',
		'target_path',
		'data_type',
		'data_id',
		'redirect',
	),
)); ?>
