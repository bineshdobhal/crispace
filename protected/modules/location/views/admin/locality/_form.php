<?php
/* @var $this LocalityController */
/* @var $model Locality */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'locality-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>
	
	<div class="row">
		<?php echo $form->labelEx($model,'state_id'); ?>
		 <?php echo $form->dropDownList($model,'state_id',State::model()->getStateArray(),array('empty'=>'Select')); ?>
		<?php echo $form->error($model,'state_id'); ?>
	</div>
	
	<div class="row none">
		<?php echo $form->labelEx($model,'city_id'); ?>
		<?php $this->widget('zii.widgets.jui.CJuiAutoComplete',
				 array(
					'id'=>'city_name',
					'name'=>'city_name',
					'source'=>$this->createUrl('/location/admin/locality/Ajax'),      
					'options'=>
					array(
					   'showAnim'=>'fold',
					   'select'=>"js:function(locality, ui) { 
					   $('#Locality_city_id').val(ui.item.id);
					   }"
					),	
				  'cssFile'=>true,
				)); ?>
		<?php echo $form->error($model,'city_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'locality_name'); ?>
		<?php echo $form->textField($model,'locality_name',array('size'=>60,'maxlength'=>65)); ?>
		<?php echo $form->error($model,'locality_name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'is_active'); ?>
		<?php echo $form->dropDownList($model,'is_active',$model->getStatusArray()); ?>
		<?php echo $form->error($model,'is_active'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>
	
	<?php echo $form->hiddenField($model,'city_id',array()); ?>
	
<?php $this->endWidget(); ?>

</div><!-- form -->
 
