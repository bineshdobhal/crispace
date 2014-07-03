<?php
/* @var $this UrlController */
/* @var $data CoreUrl */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('URL_REWRITE_ID')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->URL_REWRITE_ID), array('view', 'id'=>$data->URL_REWRITE_ID)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('request_path')); ?>:</b>
	<?php echo CHtml::encode($data->request_path); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('target_path')); ?>:</b>
	<?php echo CHtml::encode($data->target_path); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('data_type')); ?>:</b>
	<?php echo CHtml::encode($data->data_type); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('data_id')); ?>:</b>
	<?php echo CHtml::encode($data->data_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('redirect')); ?>:</b>
	<?php echo CHtml::encode($data->redirect); ?>
	<br />


</div>