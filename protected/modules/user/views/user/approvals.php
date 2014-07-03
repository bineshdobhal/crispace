<?php
$this->title = Yum::t('Manage Pending Approvals');

$this->breadcrumbs = array(
	Yum::t('Users') => array('index'),
	Yum::t('Manage'));

echo Yum::renderFlash();
?>

<?php $this->widget('zii.widgets.grid.CGridView',array(
	'id'=>'yumuser-grid',
	'dataProvider'=>$model->searchApprovals(),
	'filter'=>$model,
	'columns'=>array(
                'id',
                array(
                    'name'=>'username',
                    'type'=>'html',
                    'value'=>'CHtml::link($data->username, array("//user/user/view","id"=>$data->id))',
                ),
                array(
                    'name'=>'user_type',
                    'value'=>'YumUser::itemAlias(\'UserType\', $data->user_type)',
                    'filter'=>CHtml::dropDownList('YumUser[user_type]', $model->user_type, YumUser::itemAlias('UserType'), array('empty'=>'--User Type--', 'style'=>'min-width: 100px')),
                ),
                array(
                    'name'=>'createtime',
                    'value'=>'date(\'d M, Y\', $data->createtime)',
                    'filter'=>false,
                ),
            
                array(
                    'name'=>'_flow',
                    'value'=>'$data->getRequestFlow()',
                    'filter'=>CHtml::dropDownList('YumUser[_flow]', $model->_flow, WorkFlow::getRequestFlowAlias(), array('empty'=>'--Request Flow', 'style'=>'min-width: 100px')),
                ),
            
                array(
                    'class'=>'CButtonColumn',
                    'template'=>'{detail}{approve}{reject}{forward}',
                    'buttons'=>array
                    (
                        'detail' => array
                        (
                            'label'=>'[D]',
                            'url'=>'Yii::app()->createUrl("//user/user/view", array("id"=>$data->id))',
                            'click' => "function( e ){
                                e.preventDefault();
                                getDialogContent( $( this ).attr( 'href') );
                                $( '#dialogBox')
                                  .dialog( { title: 'User Details' } )
                                  ; 
                            }",
                            
                        ),
                        'approve' => array
                        (
                            'label'=>'[A]',
                            'url'=>'Yii::app()->createUrl("//user/user/approve", array("id"=>$data->id))',
                            'click' => "function( e ){
                                e.preventDefault();
                                if(confirm('Are you sure you want to approve this User?')){
                                    getDialogContent( $( this ).attr( 'href') );
                                    $( '#dialogBox').dialog( { title: 'User Details' } ); 
                                }
                            }",
                            
                            //button is not visible if user has forwarded the request to some superior member
                            'visible'=>'$data->checkApprovalRights()',
                        ),
                        'reject' => array(
                            'label'=>'[R]',
                            'url'=>'Yii::app()->createUrl("//user/user/reject", array("id"=>$data->id))',
                            'click' => "function( e ){
                                e.preventDefault();
                                $( '#dialogBox' ).children( ':eq(0)' ).empty(); // Stop auto POST
                                getDialogForm( $( this ).attr( 'href' ) );
                                $( '#dialogBox' )
                                  .dialog( { title: 'User Rejection' } )
                                }",
                            
                            //button is not visible if user has forwarded the request to some superior member
                            'visible'=>'$data->checkApprovalRights()',
                        ),
                        'forward' => array
                        (
                            'label'=>'[F]',
                            //'visible'=>'$data->score > 0',
                            'url'=>'Yii::app()->createUrl("//user/user/forward", array("id"=>$data->id))',
                            'click' => "function( e ){
                                e.preventDefault();
                                $( '#dialogBox' ).children( ':eq(0)' ).empty(); // Stop auto POST
                                getDialogForm( $( this ).attr( 'href' ) );
                                $( '#dialogBox' )
                                  .dialog( { title: 'User Rejection' } )
                            }",
                            
                            //button is not visible if user has forwarded the request to some superior member
                            'visible'=>'$data->checkApprovalRights()',
                        ),
                        
                    ),
                ),

	),
)); ?>

<?php
$this->beginWidget( 'zii.widgets.jui.CJuiDialog', array(
    'id' => 'dialogBox',
    'options' => array(
        'title' => 'Dialog',
        'autoOpen' => false,
        'modal' => true,
        'width' => 550,
        'resizable' => false,
    ),
)); ?>
<div id="dialog-content"></div>
<?php $this->endWidget(); ?>

<script>
    function getDialogContent(url){
        <?php echo CHtml::ajax(array(   // code for the javascript  
           'url'=>"js:url",  
           'data'=> "js:$(this).serialize()",  
           'type'=>'post',  
           'dataType'=>'json',  
           'success'=>"function(data)  
           {  
             if (data.status == 'failure')  
             {  
               $('#dialog-content').html(data.content);  
               $('#dialogBox').dialog( 'open' );
                  // Here is the trick: on submit-> once again this function!  
              $('#dialog-content .form form').submit(function(e){
                        //e.preventDefault();
                        getDialogContent(url);
                    }
                );  
             }  
             else  
             {  
               $('#dialog-content').html(data.content);  
               //setTimeout(\"$('#loginDialog').dialog('close') \",300);
               $('#dialogBox').dialog( 'open' );
               $('#yumuser-grid').yiiGridView.update('yumuser-grid');
             }  
           } ",  
    ))?>;  
    return false;  
    }
</script>

<?php
$updateJS = CHtml::ajax( array(
  'url' => "js:url",
  'data' => "js:form.serialize() + action",
  'type' => 'post',
  'dataType' => 'json',
  'success' => "function( data )
  {
    if( data.status == 'failure' )
    {
      $( '#dialogBox div#dialog-content' ).html( data.content );
      $('#dialogBox').dialog( 'open' );
      $( '#dialogBox div#dialog-content form input[type=submit]' )
        .die() // Stop from re-binding event handlers
        .live( 'click', function( e ){ // Send clicked button value
          e.preventDefault();
          getDialogForm( false, $( this ).attr( 'name' ) );
      });
    }
    else
    {
      $( '#dialogBox div#dialog-content' ).html( data.content );
      $('#dialogBox').dialog( 'open' );
      if( data.status == 'success' ) // Update all grid views on success
      {
        $( 'div.grid-view' ).each( function(){ // Change the selector if you use different class or element
          $.fn.yiiGridView.update( $( this ).attr( 'id' ) );
        });
      }
      setTimeout( \"$( '#dialogBox' ).dialog( 'close' ).children( ':eq(0)' ).empty();\", 1000 );
    }
  }"
)); ?>

<?php
Yii::app()->clientScript->registerScript( 'getDialogForm', "
function getDialogForm( url, act )
{
  var action = '';
  var form = $( '#dialogBox div#dialog-content form' );
  if( url == false )
  {
    action = '&action=' + act;
    url = form.attr( 'action' );
  }
  {$updateJS}
}" ); ?>