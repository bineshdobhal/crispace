<?php
/* @var $this EmailController */
/* @var $model Email */
/* @var $form CActiveForm */
?>
<?php 
Yii::app()->clientScript->registerScriptFile(Yii::app()->request->getBaseUrl(true).'/js/tinymce/tinymce.min.js');?>
<script>
    tinymce.init({
		selector: "textarea#Email_template_text",
		height:175,
		plugins: [
         "advlist autolink link image lists charmap  preview hr anchor pagebreak ",
         "searchreplace wordcount visualblocks visualchars code   media nonbreaking",
         "jbimages code"
		],
		relative_urls : false,
		remove_script_host : true,
		convert_urls : true,
		theme: "modern",
		menubar: false,
		paste_auto_cleanup_on_paste : true,
		skin : "lightgray",
		toolbar: "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link unlink image jbimages advlist |  preview media fullpage | code ", 
  
	}); 
</script>
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'email-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'template_title'); ?>
		<?php echo $form->textField($model,'template_title',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'template_title'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'template_code'); ?>
		<?php echo $form->textField($model,'template_code',array('size'=>60,'maxlength'=>150)); ?>
		<?php echo $form->error($model,'template_code'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'template_text'); ?>
		<?php echo $form->textArea($model,'template_text',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'template_text'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'template_subject'); ?>
		<?php echo $form->textField($model,'template_subject',array('size'=>60,'maxlength'=>200)); ?>
		<?php echo $form->error($model,'template_subject'); ?>
	</div>
<?php /*
	<div class="row">
		<?php echo $form->labelEx($model,'creation_time'); ?>
		<?php echo $form->textField($model,'creation_time'); ?>
		<?php echo $form->error($model,'creation_time'); ?>
	</div>

	
	<div class="row">
		<?php echo $form->labelEx($model,'update_time'); ?>
		<?php echo $form->textField($model,'update_time'); ?>
		<?php echo $form->error($model,'update_time'); ?>
	</div>
*/ ?>
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