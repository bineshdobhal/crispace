<?php
/* @var $this UrlController */
/* @var $model CoreUrl */

$this->breadcrumbs=array(
	'Core Urls'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List CoreUrl', 'url'=>array('index')),
	array('label'=>'Manage CoreUrl', 'url'=>array('admin')),
);
?>

<h1>Create CoreUrl</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>