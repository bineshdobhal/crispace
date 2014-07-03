<?php
/* @var $this ActionController */
/* @var $data PermissionAction */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('ACTION_ID')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->ACTION_ID), array('view', 'id'=>$data->ACTION_ID)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('title')); ?>:</b>
	<?php echo CHtml::encode($data->title); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('key')); ?>:</b>
	<?php echo CHtml::encode($data->key); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('parent_id')); ?>:</b>
	<?php echo CHtml::encode($data->parent_id); ?>
	<br />


</div>