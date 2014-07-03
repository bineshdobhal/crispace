<?php
/* @var $this FeatureController */
/* @var $model PackageFeature */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'package-feature-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'title'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'price_monthly'); ?>
		<?php echo $form->textField($model,'price_monthly'); ?>
		<?php echo $form->error($model,'price_monthly'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'is_active'); ?>
		<?php echo $form->dropDownList($model,'is_active',$model->getStatusArray()); ?>
		<?php echo $form->error($model,'is_active'); ?>
	</div>
                
        <div class="row">
            <table id="feature_item_table">
                <tr>
                    <th><?php echo Yii::t('package','Component'); ?></th>
                    <th><?php echo Yii::t('package','Value'); ?></th>
                    <th><?php echo Yii::t('package','Label'); ?></th>
                    <th><?php echo Yii::t('package','Status'); ?></th>  
                  </tr>
                  <?php $feature_array= PackageFeatureItem::model()->getFeatureKeyArray();?>
                  <?php foreach($feature_array as $key=>$value): ?>
                  <tr>
                      <td><?php echo $value; ?><input type="hidden" name="PackageFeatureItem[item_key][<?php echo $key ?>]" value="<?php echo $key; ?>"></td>  
                     <td><input type="text" name="PackageFeatureItem[item_value][<?php echo $key ?>]" value="<?php echo $model->getfeatureItemValue($key,'item_value',''); ?>"></td>  
                     <td><input type="text" name="PackageFeatureItem[item_text][<?php echo $key ?>]" value="<?php echo $model->getfeatureItemValue($key,'item_text',''); ?>"></td> 
                     <td><?php echo CHtml::dropDownList('PackageFeatureItem[is_active]['.$key.']',$model->getfeatureItemValue($key,'is_active',PackageFeatureItem::ACTIVE),PackageFeatureItem::model()->getStatusArray() ) ?></td> 
                  </tr>
                  <?php endforeach;?>
                  
                  
            </table>
            <div class="note">
                <p>Leave value field blank if component is not applicable<br/>
                Use 0,1 in values for "YES" and "NO" respectively </p>
                
            </div>
        </div>
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->