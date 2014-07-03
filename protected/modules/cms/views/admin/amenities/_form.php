<?php
/* @var $this AmenitiesController */
/* @var $model Amenities */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'amenities-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'amenities_name'); ?>
		<?php echo $form->textField($model,'amenities_name',array('size'=>60,'maxlength'=>65)); ?>
		<?php echo $form->error($model,'amenities_name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'amenities_icon'); ?>
		<?php echo $form->textField($model,'amenities_icon',array('size'=>60,'maxlength'=>125)); ?>
		<?php echo $form->error($model,'amenities_icon'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'is_active'); ?>
		<?php echo $form->dropDownList($model,'is_active',$model->getStatusArray()); ?>
		<?php echo $form->error($model,'is_active'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->