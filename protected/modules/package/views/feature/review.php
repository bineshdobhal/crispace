<?php
/* @var $this FeatureController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Package Features',
);

?>

<h1><?php echo Yii::t('package','Review  your request'); ?> </h1>
<table class="table table-stripped">
	<tbody>
        <tr>	
            <th><?php echo Yii::t('package','Adv.Type'); ?></th>
               
		<th><?php echo Yii::t('package','Qty');  ?></th>
                 <?php if (PackageFeature::model()->isShowPrice()): ?>
                    <th> <?php echo Yii::t('package','Unit price'); ?> </th>
                    <th> <?php echo Yii::t('package','Row Total'); ?> </th>
                <?php endif; ?>
	</tr>	
</tbody></table>
<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
       	'itemsTagName'=>'table',
	'itemView'=>'_review',
        'summaryText'=>'',
)); ?>

<div class="row buttons">
    <?php if (PackageFeature::model()->canPurchasePackage()): ?>
    <div><?php echo CHtml::link(Yii::t('package','pruchase'),array('purchase','id'=>$order->PO_ID)) ?> </div>
    <?php else: ?>
    <div><?php echo CHtml::link(Yii::t('package','Submit Request'),array('proceed','id'=>$order->PO_ID)) ?> </div>
    <?php endif; ?>
    
    <div><?php echo CHtml::link(Yii::t('package','Update'),array('request','id'=>$order->PO_ID)) ?> </div>
    
    <div><?php echo CHtml::link(Yii::t('package','Cancel'),array('cancel','id'=>$order->PO_ID)) ?> </div>
    
    
</div>

</form>
