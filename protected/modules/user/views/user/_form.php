<div class="form">
<?php 

$form = $this->beginWidget('CActiveForm', array(
			'id'=>'user-form',
			'enableAjaxValidation'=>false));
?>

<div class="note">
<?php echo Yum::requiredFieldNote(); ?>

<?php
$models = array($model);
if(isset($profile) && $profile !== false)
	$models[] = $profile;
if(!$model->isNewRecord){
    $models[]=$passwordform;
}
echo CHtml::errorSummary($models);

?>
</div>

<div style="float: right; margin: 10px;">
<?php if(Yii::app()->user->isAdmin()){ ?>
<div class="row">
<?php echo $form->labelEx($model, 'superuser');
echo $form->dropDownList($model, 'superuser',YumUser::itemAlias('AdminStatus'));
echo $form->error($model, 'superuser'); ?>
</div>
<?php } ?>

<div class="row">
<?php echo $form->labelEx($model, 'user_type');
echo $form->dropDownList($model, 'user_type',YumUser::itemAlias('UserType'));
echo $form->error($model, 'user_type'); ?>
</div>

<div class="row">
<?php echo $form->labelEx($model,'status');
echo $form->dropDownList($model,'status',YumUser::itemAlias('UserStatus'));
echo $form->error($model,'status'); ?>
</div>

    
<div class="row">
<?php echo $form->labelEx($roleModel,'role_id');?>
<?php echo $form->dropDownList($roleModel,'role_id',Role::model()->getRolesDropDownList(),array('empty'=>array(Role::PARENT_NONE=>Yii::t('role','None')),'encode'=>false)); ?>
<?php echo $form->error($roleModel,'role_id'); ?>
</div>
    
    
</div>


<div class="row">
<?php echo $form->labelEx($model, 'username');
echo $form->textField($model, 'username');
echo $form->error($model, 'username'); ?>
</div>

<?php if(!$model->isNewRecord){?>
    <div class="row">
    <p> Leave password <em> empty </em> to keep it <em> unchanged </em></p>
    <?php $this->renderPartial('/user/passwordfields', array(
                            'form'=>$passwordform)); ?>
    </div>
<?php } ?>
<?php if(Yum::hasModule('profile')) 
$this->renderPartial('application.modules.profile.views.profile._form', array(
			'profile' => $profile)); ?>

<div class="row buttons">
<?php echo CHtml::submitButton($model->isNewRecord
			? Yum::t('Create')
			: Yum::t('Save')); ?>
</div>

<?php $this->endWidget(); ?>
</div>
	<div style="clear:both;"></div>
