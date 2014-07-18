<?php

/* This array defines the valid environments for this Wordpress installation */
$wp_envs = array('local', 'dev', 'staging', 'production');

/* Here we look for a valid .env file */
foreach($wp_envs as $env){	

	$has_env 	= true;
	
	$env_file 	= dirname(__FILE__).'/'.$env.'.env';
	$env_config = dirname(__FILE__).'/'.$env.'-config.php';

	if(file_exists($env_file)){
		break;
	}

	$has_env = false;
	continue;
	
}

if(!$has_env)
	die('You need an environment file');

if(!file_exists($env_config))
	die('You need a config file for the "'.$env.'" environment');

/* Get the environment variables */
$env_vars 	= array();
$lines 		= file($env_file);
foreach($lines as $line){
	$line = array_map('trim', explode(':::', $line));
	if(count($line) > 1)
		$env_vars[strtolower($line[0])] = $line[1];
}

/* Merge the envrionement vars with the defaults */
$env_vars = array_merge(array(
	'debug' 		=> 'Off',
	'debug_display' => 'Off',
	'auto_updates' 	=> 'Off',
	'table_prefix' 	=> 'wp_',
	'language' 		=> 'en_US'
), $env_vars);

/* Check if the base_url is set */
if(!isset($env_vars['base_url']))
	die('You need to define the base_url setting in '.$env.'.env');

/* Handle automatic updates */
if(strtolower($env_vars['auto_updates']) == 'off')
	define('AUTOMATIC_UPDATER_DISABLED', true);

/* Error handling */
if(strtolower($env_vars['debug']) == 'on'){
	define('SAVEQUERIES', true);
	error_reporting(E_WARNING | E_ERROR);
}
else{
	define('SAVEQUERIES', false);
	error_reporting(0);
}

if(strtolower($env_vars['debug_display']) == 'on'){
	ini_set('display_errors', 1);
}
else{
	ini_set('display_errors', 0);
}

/* Setup the DB */
define('WP_ENV', $env);
define('WP_BASE_URL', preg_replace('~\/$~', '', $env_vars['base_url']));
include($env_config);


// ========================
// Custom Content Directory
// ========================
define( 'WP_CONTENT_DIR', dirname( __FILE__ ) . '/content' );
define( 'WP_CONTENT_URL', WP_BASE_URL.'/content' );

// ================================================
// You almost certainly do not want to change these
// ================================================
define( 'DB_CHARSET', 'utf8' );
define( 'DB_COLLATE', '' );

// ==============================================================
// Salts, for security
// Grab these from: https://api.wordpress.org/secret-key/1.1/salt
// ==============================================================
define( 'AUTH_KEY',         'put your unique phrase here' );
define( 'SECURE_AUTH_KEY',  'put your unique phrase here' );
define( 'LOGGED_IN_KEY',    'put your unique phrase here' );
define( 'NONCE_KEY',        'put your unique phrase here' );
define( 'AUTH_SALT',        'put your unique phrase here' );
define( 'SECURE_AUTH_SALT', 'put your unique phrase here' );
define( 'LOGGED_IN_SALT',   'put your unique phrase here' );
define( 'NONCE_SALT',       'put your unique phrase here' );

// ==============================================================
// Table prefix
// Change this if you have multiple installs in the same database
// ==============================================================
$table_prefix  = $env_vars['table_prefix'];

// ================================
// Language
// Leave blank for American English
// ================================
define( 'WPLANG', $env_vars['language']);

// ===================
// Bootstrap WordPress
// ===================
if ( !defined( 'ABSPATH' ) )
	define( 'ABSPATH', dirname( __FILE__ ) . '/wp/' );
require_once( ABSPATH . 'wp-settings.php' );
?>