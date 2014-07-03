<?php
$this->title = Yum::t('Manage users');

echo "<h1>".Yum::t('User Accounts')."</h1>";
if(!empty($accounts)){
    foreach($accounts as $key => $value){
        echo "<h3>".$value."</h3>";
        echo "<p>Description goes here.</p>";
        echo CHtml::link("Register as $value", array($reg_url[0], 'type'=>$key));
    }
}
?>