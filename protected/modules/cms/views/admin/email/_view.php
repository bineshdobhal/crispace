<?php
/* @var $this EmailController */
/* @var $data Email */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('TEMPLATE_ID')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->TEMPLATE_ID), array('view', 'id'=>$data->TEMPLATE_ID)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('template_title')); ?>:</b>
	<?php echo CHtml::encode($data->template_title); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('template_code')); ?>:</b>
	<?php echo CHtml::encode($data->template_code); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('template_text')); ?>:</b>
	<?php echo CHtml::encode($data->template_text); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('template_subject')); ?>:</b>
	<?php echo CHtml::encode($data->template_subject); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('creation_time')); ?>:</b>
	<?php echo CHtml::encode($data->creation_time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('update_time')); ?>:</b>
	<?php echo CHtml::encode($data->update_time); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('is_active')); ?>:</b>
	<?php echo CHtml::encode($data->is_active); ?>
	<br />

	*/ ?>

</div>