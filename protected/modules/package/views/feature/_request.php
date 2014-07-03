<?php
/* @var $this FeatureController */
/* @var $data PackageFeature */
?>

<tr>
    <td><input name="chk_ids[]" <?php echo ($order->checkSelected($data->PACKAGE_FEATURE_ID))?'checked="checked"':''; ?> type="checkbox" class="checkbox" value="<?php echo $data->PACKAGE_FEATURE_ID; ?>" /></td></td>  
    <td>
	<h2> <?php echo CHtml::encode($data->title); ?></h2>		
        <div class="additional_details">
            <?php $featureItems = $data->feature_items; ?>

            <?php if ($featureItems): ?>
                
                <table>
                    <?php foreach ($featureItems as $featureItem): ?>
                        <tr>
                            <td><?php echo $featureItem->item_text; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php endif; ?>

        </div>
	

	
	
</td>

    <?php if (PackageFeature::model()->isShowPrice()): ?>
           <td><?php echo CHtml::encode($data->price_monthly); ?></td>
        <?php endif; ?> 

  <td>
        <?php echo CHtml::textField('qty['.$data->PACKAGE_FEATURE_ID.']',$order->getSelectedQty($data->PACKAGE_FEATURE_ID));  ?>
        
    </td>

</tr>