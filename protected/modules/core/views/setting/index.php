<?php
/* @var $this SettingController */

$this->breadcrumbs=array(
	'Setting',
);
?>
<h1>Core Setting</h1>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'contact-form',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'core_site_name'); ?>
		<?php echo $form->textField($model,'core_site_name'); ?>
		<?php echo $form->error($model,'core_site_name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'core_site_url'); ?>
		<?php echo $form->textField($model,'core_site_url'); ?>
		<?php echo $form->error($model,'core_site_url'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'core_logo_path'); ?>
		<?php echo $form->textField($model,'core_logo_path',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($model,'core_logo_path'); ?>
	</div>

	
	<div class="row buttons">
		<?php echo CHtml::submitButton('Submit'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
