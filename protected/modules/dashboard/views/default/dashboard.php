<style>
    #usermenu {
        float: right;
        margin: 0 5px;
        width: 25%;
    }
    
    #usercontent {
        margin: 5px;
        padding: 20px;
        width: 70%;
    }
    
    .menucontent {
        background-color: #EEEEEE;
    }
    .portlet-decoration {
        background: none repeat scroll 0 0 #B7D6E7;
        border-left: 5px solid #6FACCF;
        padding: 3px 8px;
    }
    .portlet-title {
        color: #298DCD;
        font-size: 12px;
        font-weight: bold;
        margin: 0;
        padding: 0;
    }
    .menucontent ul {
        cursor: pointer;
        list-style-type: none;
        margin: 5px 0 0 9px;
        padding: 3px;
    }
    .menucontent li {
        list-style-type: none;
        margin: 5px 0 0 9px;
    }
</style>
<?php
/* @var $this DefaultController */

$this->breadcrumbs=array(
	$this->module->id,
);
?>
<div id="usermenu">
<?php
    if(!empty($action_links)){
        foreach($action_links as $menu){
            if(array_key_exists('content', $menu)){
                echo "<div class='portlet-decoration'>";
                echo "<div class='portlet-title'>".$menu['title']."</div>";
                echo "</div>";
                echo "<div class='menucontent'>";
                foreach($menu['content'] as $menu_item){
                    echo "<li>";
                    echo CHtml::link($menu_item['title'],$menu_item['url']);
                    echo "</li>";
                }
                echo "</div>";
            }
        }
    }
?>
</div>
<div id="usercontent">

    <h1><?php echo $this->uniqueId . '/' . $this->action->id; ?></h1>

    <p>
    This is the view content for action "<?php echo $this->action->id; ?>".
    The action belongs to the controller "<?php echo get_class($this); ?>"
    in the "<?php echo $this->module->id; ?>" module.
    </p>
    <p>
    You may customize this page by editing <tt><?php echo __FILE__; ?></tt>
    </p>
</div>