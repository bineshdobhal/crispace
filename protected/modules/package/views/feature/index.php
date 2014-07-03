<?php
/* @var $this FeatureController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Package Features',
);


?>
<?php $requestLabel=  PackageFeature::model()->getRequestLabel();?>


<h1>Package Features</h1>

<?php echo CHtml::link($requestLabel,array('request')) ?>
<table class="table table-stripped">
	<tbody>
        <tr>
		<th class="width-125px"></th>
		<th></th>
	</tr>	
</tbody></table>
<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
       	'itemsTagName'=>'table',
	'itemView'=>'_view',
)); ?>

<?php echo CHtml::link($requestLabel,array('request')) ?>

