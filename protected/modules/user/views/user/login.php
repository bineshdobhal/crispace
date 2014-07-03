<?php
if(!isset($model)) 
	$model = new YumUserLogin();

$module = Yum::module();

$this->pageTitle = Yum::t('Login');
if(isset($this->title))
$this->title = Yum::t('Login');
$this->breadcrumbs=array(Yum::t('Login'));

Yum::renderFlash();
?>

<div class="form">
<p>
<?php 
echo Yum::t(
		'Please fill out the following form with your login credentials:'); ?>
</p>

<?php echo CHtml::beginForm(array('//user/auth/login'));  ?>

<?php
if(isset($_GET['action']))
	echo CHtml::hiddenField('returnUrl', urldecode($_GET['action']));
?>

<?php echo CHtml::errorSummary($model); ?>
	
	<div class="row">
		<?php 
		if($module->loginType & UserModule::LOGIN_BY_USERNAME 
				|| $module->loginType & UserModule::LOGIN_BY_LDAP)
		echo CHtml::activeLabelEx($model,'username'); 
		if($module->loginType & UserModule::LOGIN_BY_EMAIL)
			printf ('<label for="YumUserLogin_username">%s <span class="required">*</span></label>', Yum::t('E-Mail address')); 
		if($module->loginType & UserModule::LOGIN_BY_OPENID)
			printf ('<label for="YumUserLogin_username">%s <span class="required">*</span></label>', Yum::t('OpenID username'));  ?>

		<?php echo CHtml::activeTextField($model,'username') ?>
	</div>
	
	<div class="row">
		<?php echo CHtml::activeLabelEx($model,'password'); ?>
		<?php echo CHtml::activePasswordField($model,'password');
		if($module->loginType & UserModule::LOGIN_BY_OPENID)
			echo '<br />'. Yum::t('When logging in with OpenID, password can be omitted');
 ?>
		
	</div>


<?php if(Yii::app()->user->getState('login_attempts', 0) > YumUserLogin::ATTEMPTS_BEFORE_CAPTCHA){ ?>
<div class="row">
    <?php echo CHtml::activeLabelEx($model,'verifyCode'); ?>
    <div>
    <?php $this->widget('CCaptcha'); ?>
    <?php echo CHtml::activeTextField($model,'verifyCode'); ?>
    </div>
    <p class="hint">
    <?php echo Yum::t('Please enter the letters as they are shown in the image above.'); ?>
    <br/><?php echo Yum::t('Letters are not case-sensitive.'); ?></p>
</div>
<?php } ?>

	
	<div class="row">
	<p class="hint">
	<?php 
	if(Yum::hasModule('registration') && Yum::module('registration')->enableRegistration){
            $reg_url = Yum::module('registration')->registrationUrl;
            if(Yum::module('registration')->enableChooseAccountType){
                Yii::import('application.modules.registration.models.YumRegistration');
                $valid_type = YumRegistration::validAccountType();
                echo Yum::t("Register As: ");
                foreach($valid_type as $key=>$value){
                    echo " | ";
                    echo CHtml::link($value, array($reg_url[0],'type'=>$key));
                }
                echo ' | '.CHtml::link('Know More', array('//registration/registration/accounts')).'<br/>';
            }
            else echo CHtml::link('Register', array($reg_url[0]));
        }
	if(Yum::hasModule('registration') 
			&& Yum::module('registration')->enableRegistration
			&& Yum::module('registration')->enableRecovery)
	echo ' | ';
	if(Yum::hasModule('registration') 
			&& Yum::module('registration')->enableRecovery) 
	echo CHtml::link(Yum::t("Lost password?"),
			Yum::module('registration')->recoveryUrl);
	?>
</p>
	</div>

<div class="row rememberMe">
<?php echo CHtml::activeCheckBox($model,'rememberMe', array('style' => 'display: inline;')); ?>
<?php echo CHtml::activeLabelEx($model,'rememberMe', array('style' => 'display: inline;')); ?>
</div>

<div class="row submit">
<?php echo CHtml::submitButton(Yum::t('Login')); ?>
</div>

<?php echo CHtml::endForm(); ?>
</div><!-- form -->

<?php
$form = new CForm(array(
			'elements'=>array(
				'username'=>array(
					'type'=>'text',
					'maxlength'=>32,
					),
				'password'=>array(
					'type'=>'password',
					'maxlength'=>32,
					),
				'rememberMe'=>array(
					'type'=>'checkbox',
					)
				),

			'buttons'=>array(
				'login'=>array(
					'type'=>'submit',
					'label'=>'Login',
					),
				),
			), $model);
?>

