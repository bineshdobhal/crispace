<?php
/* @var $this AmenitiesController */
/* @var $data Amenities */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('AMENITIES_ID')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->AMENITIES_ID), array('view', 'id'=>$data->AMENITIES_ID)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('amenities_name')); ?>:</b>
	<?php echo CHtml::encode($data->amenities_name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('amenities_icon')); ?>:</b>
	<?php echo CHtml::encode($data->amenities_icon); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('is_active')); ?>:</b>
	<?php echo CHtml::encode($data->is_active); ?>
	<br />


</div>