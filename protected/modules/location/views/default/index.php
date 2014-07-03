<?php
/* @var $this DefaultController */

$this->breadcrumbs=array(
	$this->module->id,
);
?>

<div class="top_links">
    <ul>
        <li><?php echo CHtml::link('State',array('/location/admin/state'));?></li>
        <li><?php echo CHtml::link('City',array('/location/admin/city'));?></li>
        <li><?php echo CHtml::link('Locality',array('/location/admin/locality'));?></li>        
    </ul>
    
</div>
