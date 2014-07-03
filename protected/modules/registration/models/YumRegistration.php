<?php
Yii::import('application.modules.user.models.*');
class YumRegistration extends YumActiveRecord {
	const REG_DISABLED = 0;
	const REG_SIMPLE = 1;
	const REG_EMAIL_CONFIRMATION = 2;
	const REG_CONFIRMATION_BY_ADMIN = 3;
	const REG_EMAIL_AND_ADMIN_CONFIRMATION = 4;
	const REG_NO_PASSWORD = 5; 
	const REG_NO_PASSWORD_ADMIN_CONFIRMATION = 6;
        
        //default accoount type
        const DEFAULT_ACCOUNT_TYPE = YumUser::USERTYPE_INDIVIDUAL;
        /**
         * Function returns account types that a user can choose from, before filling the registration form.
         * This function returns only those account types which are valid to be choosen by the user. 
         * For example a user can choose "INDIVIDUAL"/"AGENT"/"BUILDER" as his/her account type but cannot choose "STAFF MEMBER" as the accont type.
         * 
         * @return ARRAY containing key as account type codes and value as titles.
         */
        public static function validAccountType(){
            return array(
                YumUser::USERTYPE_INDIVIDUAL => YumUser::itemAlias('UserType', YumUser::USERTYPE_INDIVIDUAL),
                YumUser::USERTYPE_AGENT => YumUser::itemAlias('UserType', YumUser::USERTYPE_AGENT),
                YumUser::USERTYPE_BUILDER => YumUser::itemAlias('UserType', YumUser::USERTYPE_BUILDER),
            );
        }
}

?>
