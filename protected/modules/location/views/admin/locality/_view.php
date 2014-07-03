<?php
/* @var $this LocalityController */
/* @var $data Locality */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('LOCAL_ID')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->LOCAL_ID), array('view', 'id'=>$data->LOCAL_ID)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('city_id')); ?>:</b>
	<?php echo CHtml::encode($data->city_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('state_id')); ?>:</b>
	<?php echo CHtml::encode($data->state_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('locality_name')); ?>:</b>
	<?php echo CHtml::encode($data->locality_name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('is_active')); ?>:</b>
	<?php echo CHtml::encode($data->is_active); ?>
	<br />


</div>