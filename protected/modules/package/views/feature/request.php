<?php
/* @var $this FeatureController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Package Features',
);

?>
<script>
jQuery(document).ready(function(){
    jQuery( "#package_request_form" ).submit(function( event ) {
     // return validateCheckGroup('chk_ids[]');
     
    });
});    
</script>

<h1>Package Request </h1>
<form action='' method="post"  id="package_request_form">
<table class="table table-stripped">
	<tbody>
        <tr>	
            <th><?php echo Yii::t('package','Adv.Type'); ?></th>
                <?php if (PackageFeature::model()->isShowPrice()): ?>
                <th> <?php echo Yii::t('package','Price'); ?> </th>
                <?php endif; ?>
		<th><?php echo Yii::t('package','Qty');  ?></th>
	</tr>	
</tbody></table>
<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
       	'itemsTagName'=>'table',
	'itemView'=>'_request',
        'viewData'=>array('order'=>$order),
        'summaryText'=>'',
)); ?>

<div class="row buttons">
    <?php $submitButtonLabel=  PackageFeature::model()->getButtonLabel(); ?>
    <?php echo CHtml::submitButton($submitButtonLabel); ?>
</div>

</form>
