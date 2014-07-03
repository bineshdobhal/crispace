<?php
/* @var $this FeatureController */
/* @var $data PackageFeature */
?>

<tr>

    <td>
        
    </td>
    <td>
        <h2> <?php echo CHtml::encode($data->title); ?></h2>
        
        <?php if (PackageFeature::model()->isShowPrice()): ?>
            <b><?php echo CHtml::encode($data->getAttributeLabel('price_monthly')); ?>:</b>
            <?php echo CHtml::encode($data->price_monthly); ?>
        <?php endif; ?>
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

</tr>