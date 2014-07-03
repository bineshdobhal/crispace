<?php
/* @var $this WorkflowController */
/* @var $data WorkFlow */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('WFLOG_ID')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->WFLOG_ID), array('view', 'id'=>$data->WFLOG_ID)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('data_id')); ?>:</b>
	<?php echo CHtml::encode($data->data_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('data_type')); ?>:</b>
	<?php echo CHtml::encode($data->data_type); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('user_id')); ?>:</b>
	<?php echo CHtml::encode($data->user_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('to_user_id')); ?>:</b>
	<?php echo CHtml::encode($data->to_user_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('comment')); ?>:</b>
	<?php echo CHtml::encode($data->comment); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('action')); ?>:</b>
	<?php echo CHtml::encode($data->action); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b>
	<?php echo CHtml::encode($data->status); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('creation_time')); ?>:</b>
	<?php echo CHtml::encode($data->creation_time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('parent_id')); ?>:</b>
	<?php echo CHtml::encode($data->parent_id); ?>
	<br />

	*/ ?>

</div>