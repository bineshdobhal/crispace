<?php
$profiles = Yum::hasModule('profile');

if(Yum::module()->loginType & UserModule::LOGIN_BY_EMAIL & $profiles)
$this->title = Yum::t('View user "{email}"',array(
			'{email}'=>$model->profile->email));
else
$this->title = Yum::t('View user "{username}"',array(
			'{username}'=>$model->username));

$this->breadcrumbs = array(Yum::t('Users') => array('index'), $model->username);

echo Yum::renderFlash();

if(Yii::app()->user->isAdmin() || Yii::app()->user->isStaff()) {
	$attributes = array(
			'id',
	);

	if(!Yum::module()->loginType & UserModule::LOGIN_BY_EMAIL)
		$attributes[] = 'username';

	if($profiles) {
		$profileFields = YumProfileField::model()->forOwner()->findAll();
		if ($profileFields && $model->profile) {
			foreach($profileFields as $field) {
				array_push($attributes, array(
							'label' => Yum::t($field->title),
							'type' => 'raw',
							'value' => is_array($model->profile)
							? $model->profile->getAttribute($field->varname)
							: $model->profile->getAttribute($field->varname) ,
							));
			}
		}
	}

	array_push($attributes,
		/*
		There is no added value to showing the password/salt/activationKey because 
		these are all encrypted 'password', 'salt', 'activationKey',*/
		array(
			'name' => 'createtime',
			'value' => date(UserModule::$dateFormat,$model->createtime),
			),
		array(
			'name' => 'lastvisit',
			'value' => date(UserModule::$dateFormat,$model->lastvisit),
			),
		array(
			'name' => 'lastpasswordchange',
			'value' => date(UserModule::$dateFormat,$model->lastpasswordchange),
			),
		array(
			'name' => 'superuser',
			'value' => YumUser::itemAlias("AdminStatus",$model->superuser),
			),
		array(
			'name' => Yum::t('Activation link'),
			'value' =>$model->getActivationUrl()),
		array(
				'name' => 'status',
			'value' => YumUser::itemAlias("UserStatus",$model->status),
			)
		);

	$this->widget('zii.widgets.CDetailView', array(
				'data'=>$model,
				'attributes'=>$attributes,
				));

} else {
	// For all users
	$attributes = array(
			'username',
			);

	if($profiles) {
		$profileFields = YumProfileField::model()->forAll()->findAll();
		if ($profileFields) {
			foreach($profileFields as $field) {
				array_push($attributes,array(
							'label' => Yii::t('UserModule.user', $field->title),
							'name' => $field->varname,
							'value' => $model->profile->getAttribute($field->varname),
							));
			}
		}
	}

	array_push($attributes,
			array(
				'name' => 'createtime',
				'value' => date(UserModule::$dateFormat,$model->createtime),
				),
			array(
				'name' => 'lastvisit',
				'value' => date(UserModule::$dateFormat,$model->lastvisit),
				)
			);

	$this->widget('zii.widgets.CDetailView', array(
				'data'=>$model,
				'attributes'=>$attributes,
				));
}


if(Yum::hasModule('role') && Yii::app()->user->isAdmin()) {
	Yii::import('application.modules.role.models.*');
	echo '<h2>'.Yum::t('This user belongs to these roles:') .'</h2>';

	if($model->roles) {
		echo "<ul>";
		foreach($model->roles as $role) {
			echo CHtml::tag('li',array(),CHtml::link(
						$role->title,array('//role/role/view','id'=>$role->id)),true);
		}
		echo "</ul>";
	} else {
		printf('<p>%s</p>', Yum::t('None'));
	}
}

if(Yii::app()->user->isAdmin() || Yii::app()->user->isStaff())
    echo CHtml::Button(Yum::t('Update User'), array('submit' => array('user/update', 'id' => $model->id)));

if(Yum::hasModule('profile'))
    echo CHtml::Button(Yum::t('Visit profile'), array('submit' => array('//profile/profile/view', 'id' => $model->id)));

$approvalRights = YumUser::approvalRights($model->id);
if($approvalRights['has_right']){
    echo CHtml::link('Approve', Yii::app()->createUrl("//user/user/approve", array("id"=>$model->id)), // the link for open the dialog  
        array(  
            'style'=>'cursor: pointer; text-decoration: none;',
            'class'=>'rqst-mgmt btn',
            )
    );  

    $this->beginWidget( 'zii.widgets.jui.CJuiDialog', array(
        'id' => 'dialogBox',
        'options' => array(
            'title' => 'Dialog',
            'autoOpen' => false,
            'modal' => true,
            'width' => 550,
            'resizable' => false,
        ),
    )); 
    echo '<div id="dialog-content"></div>';
    $this->endWidget();
}
?>
<script>
    $('.rqst-mgmt').click(
            function( e ){
                                e.preventDefault();
                                if(confirm('Are you sure you want to approve this User?')){
                                    getDialogContent( $( this ).attr( 'href') );
                                    $( '#dialogBox').dialog( { title: 'User Details' } ); 
                                }
                            }
            );
    
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