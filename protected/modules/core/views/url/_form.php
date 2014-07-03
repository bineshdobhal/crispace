<?php
/* @var $this UrlController */
/* @var $model CoreUrl */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'core-url-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'request_path'); ?>
		<?php echo $form->textField($model,'request_path',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'request_path'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'target_path'); ?>
		<?php echo $form->textField($model,'target_path',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'target_path'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'data_type'); ?>
		<?php echo $form->dropDownList($model,'data_type',  DataType::model()->getDataTypeArray(),array('empty'=>'Select')); ?>
		<?php echo $form->error($model,'data_type'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'data_id'); ?>
		<?php echo $form->textField($model,'data_id',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'data_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'redirect'); ?>
		<?php echo $form->dropDownList($model,'redirect',  $model->getRedirectStatusArray(),array('empty'=>'Select')); ?>
		<?php echo $form->error($model,'redirect'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->