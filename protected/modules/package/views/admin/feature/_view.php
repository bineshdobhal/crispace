<?php
/* @var $this FeatureController */
/* @var $data PackageFeature */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('PACKAGE_FEATURE_ID')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->PACKAGE_FEATURE_ID), array('view', 'id'=>$data->PACKAGE_FEATURE_ID)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('title')); ?>:</b>
	<?php echo CHtml::encode($data->title); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('price_monthly')); ?>:</b>
	<?php echo CHtml::encode($data->price_monthly); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('is_active')); ?>:</b>
	<?php echo CHtml::encode($data->is_active); ?>
	<br />


</div>