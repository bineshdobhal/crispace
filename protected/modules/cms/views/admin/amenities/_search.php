<?php
/* @var $this AmenitiesController */
/* @var $model Amenities */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'AMENITIES_ID'); ?>
		<?php echo $form->textField($model,'AMENITIES_ID'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'amenities_name'); ?>
		<?php echo $form->textField($model,'amenities_name',array('size'=>60,'maxlength'=>65)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'amenities_icon'); ?>
		<?php echo $form->textField($model,'amenities_icon',array('size'=>60,'maxlength'=>125)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'is_active'); ?>
		<?php echo $form->textField($model,'is_active'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->