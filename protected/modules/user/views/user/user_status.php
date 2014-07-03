<?php
    if(isset($status_details) && $status_details){
        echo "<h1>".Yum::t($status_details['heading'])."</h1>";
        echo "<p>".Yum::t($status_details['message'])."</p>";
    }
    else{
        echo "<h1>Invalid Status</h1>";
        echo "<p>Status not found</p>";
    } 
        
?>