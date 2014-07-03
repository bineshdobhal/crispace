<?php
/* @var $this RoleController */
/* @var $model Role */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'role-form',
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
		<?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'title'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'key'); ?>
		<?php echo $form->textField($model,'key',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'key'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'parent_id'); ?>
		<?php echo $form->dropDownList($model,'parent_id',$model->getRolesDropDownList(),array('empty'=>array(Role::PARENT_NONE=>Yii::t('role','None')),'encode'=>false)); ?>
		<?php echo $form->error($model,'parent_id'); ?>
	</div>
        <div class="row">
            <h3><?php echo Yii::t('permission', 'Role Permission'); ?></h3>
            
        </div>
        
        <div class="row">
           <?php $permissionsList = PermissionAction::model()->getActionArray();?>
            <?php if(!empty($permissionsList)): ?>
            <ul class="updatepermission_list">
		<li><input type="checkbox" name="check_all" id="check_all" value="1" onclick="checkall(this.form)" /><span>All</span></li>
			<?php 
				$check_box_list='';
                                $selectedPermission=$model->getSelectedPermission();
				PermissionAction::model()->getActionHtmlList($check_box_list,$permissionsList,$selectedPermission); 
				echo $check_box_list;
				?>
			</ul>
            
            <?php endif; ?>
            
        </div>
        
        
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->