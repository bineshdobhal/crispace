<?php
/* @var $this DefaultController */

$this->breadcrumbs=array(
	$this->module->id,
);
?>

<div class="top_links">
    <ul>
        <li><?php echo CHtml::link('Manage Urls',array('/core/url/admin'));?></li>
        <li><?php echo CHtml::link('Manage Settings',array('/core/setting'));?></li>
        
        
    </ul>
    
</div>
