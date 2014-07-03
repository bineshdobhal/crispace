<?php
/* @var $this DefaultController */

$this->breadcrumbs=array(
	$this->module->id,
);
?>

<div class="top_links">
    <ul>
        <li><?php echo CHtml::link('Manage Roles',array('/permission/role/'));?></li>
        <li><?php echo CHtml::link('Manage Actions',array('/permission/action'));?></li>
        
    </ul>
    
</div>