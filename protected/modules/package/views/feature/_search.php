<?php
/* @var $this FeatureController */
/* @var $model PackageFeature */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'PACKAGE_FEATURE_ID'); ?>
		<?php echo $form->textField($model,'PACKAGE_FEATURE_ID',array('size'=>11,'maxlength'=>11)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('size'=>45,'maxlength'=>45)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'price_monthly'); ?>
		<?php echo $form->textField($model,'price_monthly'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'price_yearly'); ?>
		<?php echo $form->textField($model,'price_yearly'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->