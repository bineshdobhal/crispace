<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');
// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'My Web Application',
    'language' => 'en',
    // preloading 'log' component
    'preload' => array('log'),
    // autoloading model and component classes
    'import' => array(
        'application.models.*',
        'application.components.*',
        'application.modules.user.models.*',
        'application.modules.user.components.*',
        'application.modules.cms.models.*',
        'application.modules.core.models.*',
        'application.modules.core.models.source.*',
        'application.modules.workflow.models.*',
        'application.modules.package.models.*',
        'application.modules.location.models.*',
        'application.modules.permission.models.*',
    ),
    'modules' => array(
        'registration' => array(),
        'core' => array(),
        'cms' => array(),
        'admin' => array(),
        'location' => array(),
        'permission' => array(),
        'package' => array(),
        'user' => array(
            'debug' => false,
            'userTable' => 'cs_user',
            'translationTable' => 'cs_translation',
        ),
        'profile' => array(
            'privacySettingTable' => 'cs_privacysetting',
            'profileFieldTable' => 'cs_profile_field',
            'profileTable' => 'cs_profile',
            'profileCommentTable' => 'cs_profile_comment',
            'profileVisitTable' => 'cs_profile_visit',
        ),
        'role' => array(
            'roleTable' => 'cs_role',
            'userRoleTable' => 'cs_user_role',
            'actionTable' => 'cs_action',
            'permissionTable' => 'cs_permission',
        ),
        // uncomment the following to enable the Gii tool
        'gii' => array(
            'class' => 'system.gii.GiiModule',
            'password' => 'Bin@123',
            // If removed, Gii defaults to localhost only. Edit carefully to taste.
            'ipFilters' => array('127.0.0.1', '::1'),
        ),
    ),
    // application components
    'components' => array(
        'user' => array(
// enable cookie-based authentication
            'allowAutoLogin' => true,
            'class' => 'application.modules.user.components.YumWebUser',
            'loginUrl' => array('//user/user/login'),
        ),
        'core' => array(
            'class' => 'application.modules.core.components.CoreComponent'
        ),
        // uncomment the following to enable URLs in path-format
        'urlManager' => array(
            'urlFormat' => 'path',
            'showScriptName' => false,
            'rules' => array(
                array(
                    'class' => 'application.modules.core.components.UrlRule',
                    'connectionID' => 'db',
                ),
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ),
        ),
        /*
          'db' => array(
          'connectionString' => 'sqlite:' . dirname(__FILE__) . '/../data/testdrive.db',
          ), */

// uncomment the following to use a MySQL database
        'db' => array(
            'connectionString' => 'mysql:host=localhost;dbname=crispace',
            'emulatePrepare' => true,
            'username' => 'root',
            'tablePrefix' => 'cs_',
            'password' => '',
            'charset' => 'utf8',
        ),
        'errorHandler' => array(
// use 'site/error' action to display errors
            'errorAction' => 'site/error',
        ),
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning',
                ),
            // uncomment the following to show log messages on web pages
            /*
              array(
              'class'=>'CWebLogRoute',
              ),
             */
            ),
        ),
        'cache' => array('class' => 'system.caching.CDummyCache'),
    ),
    // application-level parameters that can be accessed
// using Yii::app()->params['paramName']
    'params' => array(
// this is used in contact page
        'adminEmail' => 'webmaster@example.com',
    ),
);