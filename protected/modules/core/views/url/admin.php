<?php
/* @var $this UrlController */
/* @var $model CoreUrl */

$this->breadcrumbs=array(
	'Core Urls'=>array('index'),
	'Manage',
);

$this->menu=array(
	
	array('label'=>'Create CoreUrl', 'url'=>array('create')),
);
?>

<h1>Manage Core Urls</h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'core-url-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'URL_REWRITE_ID',
		'request_path',
		'target_path',
		array(            
                'name'=>'data_type',
                'filter'=>  DataType::model()->getDataTypeArray()
                 ),
                'data_id',
		array(           
                'name'=>'redirect',
                'filter'=> CoreUrl::model()->getRedirectStatusArray(),
                 'value'=>'$data->redirectValue',
                 ),
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
