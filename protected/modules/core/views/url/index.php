<?php
/* @var $this UrlController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Core Urls',
);

$this->menu=array(
	array('label'=>'Create CoreUrl', 'url'=>array('create')),
	array('label'=>'Manage CoreUrl', 'url'=>array('admin')),
);
?>

<h1>Core Urls</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
