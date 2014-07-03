<?php
/* @var $this DefaultController */

$this->breadcrumbs=array(
	$this->module->id,
);
?>
<h1>Welcome <?php echo Yii::app()->user->name ?></h1>

<div class="top_links">
    <ul>
        <li><?php echo CHtml::link('Manage Cms',array('/core/'));?></li>
        <li><?php echo CHtml::link('Manage Users',array('/user/'));?></li>
          <li><?php echo CHtml::link('Manage Email Template',array('/cms/admin/email/'));?></li>
        <li><?php echo CHtml::link('Manage Page',array('/cms/admin/page'));?></li>
        <li><?php echo CHtml::link('Manage Blocks',array('/cms/admin/block'));?></li>
        <li><?php echo CHtml::link('Manage Package Components',array('/package/admin/feature'));?></li>
        
        <li><?php echo CHtml::link('Request for Package',array('/package/feature'));?></li>
        
        <li><?php echo CHtml::link('Manage Location',array('/location/'));?></li>
        
        
        
         <li><?php echo CHtml::link('Manage Permission',array('/permission/'));?></li>
        
        
    </ul>
    
</div>
