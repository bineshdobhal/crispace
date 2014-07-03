<?php
/* @var $this FeatureController */
/* @var $data PackageFeature */
?>

<tr>
   
    <td>
	<h2> <?php echo CHtml::encode($data->feature->title); ?></h2>		
       
</td>

<?php if (PackageFeature::model()->isShowPrice()): ?>
    <td>  
        <?php echo $data->price; ?>
    </td>
    <td>  
        <?php echo $data->row_total; ?>

    </td>
    <?php endif; ?> 

  <td>
        <?php  echo $data->qty; ?>
        
    </td>

</tr>