<?php
/* @var $this CityController */
/* @var $data City */ 
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('CITY_ID')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->CITY_ID), array('view', 'id'=>$data->CITY_ID)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('state_id')); ?>:</b>
	<?php echo State::model()->getStateName($data->state_id);?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('city_name')); ?>:</b>
	<?php echo CHtml::encode($data->city_name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('is_active')); ?>:</b>
	<?php echo CHtml::encode($data->is_active); ?>
	<br />


</div>