<?php
/* @var $this FeatureController */
/* @var $model PackageFeature */

$this->breadcrumbs=array(
	'Package Features'=>array('index'),
	$model->title,
);

$this->menu=array(
	array('label'=>'List PackageFeature', 'url'=>array('index')),
	array('label'=>'Create PackageFeature', 'url'=>array('create')),
	array('label'=>'Update PackageFeature', 'url'=>array('update', 'id'=>$model->PACKAGE_FEATURE_ID)),
	array('label'=>'Delete PackageFeature', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->PACKAGE_FEATURE_ID),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage PackageFeature', 'url'=>array('admin')),
);
?>

<h1>View Package Feature #<?php echo $model->PACKAGE_FEATURE_ID; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'PACKAGE_FEATURE_ID',
		'title',
		'price_monthly',
		'status',
	),
)); ?>
<?php $featureItems = $model->feature_items; ?>

<?php if($featureItems):?>
<h2>Component  Includes</h2>
<table>
<?php foreach($featureItems as $featureItem): ?>
    <tr>
        <td><?php echo $featureItem->getFeatureKeyValue(); ?></td>
        <td><?php echo $featureItem->item_value; ?></td>
        <td><?php echo $featureItem->item_text; ?></td>
    </tr>
<?php endforeach; ?>
</table>
<?php endif;?>        




