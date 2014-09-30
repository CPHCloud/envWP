<?php

/* This array defines the valid environments for this Wordpress installation */
$wp_envs = array('local', 'test', 'dev', 'staging', 'production');

/* Here we look for a valid .env file */
$has_env 	= false;
foreach(glob("*.env") as $env){	
	
	$env        = trim(str_ireplace('.env', '', $env));
	$env_file 	= dirname(__FILE__).'/'.$env.'.env';
	$env_config = dirname(__FILE__).'/'.$env.'-config.php';
	$has_env 	= true;
	break;
	
}


/* Parsing */
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
	'base_path' 	=> dirname(__FILE__),
	'debug' 		=> 'Off',
	'debug_display' => 'Off',
	'auto_updates' 	=> 'Off',
	'table_prefix' 	=> $env.'_',
	'language' 		=> 'en_US',
	'content_dir' 	=> 'content'
), $env_vars);


/* Check if the base_url is set */
if(!isset($env_vars['base_url']))
	die('You need to define the base_url setting in '.$env.'.env');


/* Setup the constants */
define('WP_ENV', $env);
define('WP_BASE_URL', preg_replace('~\/$~', '', $env_vars['base_url']));
define('WP_BASE_PATH', preg_replace('~\/$~', '', $env_vars['base_path']));


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

/* Include the DB config file */
include($env_config);

/* Set the custom content directory to content */
define( 'WP_CONTENT_DIR', dirname( __FILE__ ) . '/'.$env_vars['content_dir'] );
define( 'WP_CONTENT_URL', WP_BASE_URL.'/'.$env_vars['content_dir'] );

/* Don't change these */
define( 'DB_CHARSET', 'utf8' );
define( 'DB_COLLATE', '' );

/*
This will create the salts.php file and fill it with the neccesary
random strings used to encrypt sessions, nonces and more.
*/
$salts_file = WP_BASE_PATH.'/salts.php';
if(!file_exists($salts_file)){
	touch($salts_file);
	$salts = file_get_contents('https://api.wordpress.org/secret-key/1.1/salt');
	if(!$salts)
		die('Unable to create salts.php file. Please create it manually in your base path.');

	file_put_contents($salts_file, "<?php\n\n".$salts."\n?>");
}
require $salts_file;
unset($salts_file);

/* Set the table prefix ot that of the env var 'table_prefix' */
$table_prefix  = $env_vars['table_prefix'];

/* Set language to that of env var 'language' */
define( 'WPLANG', $env_vars['language']);

/* We're ready. Bootstrap WordPress */
if ( !defined( 'ABSPATH' ) )
	define( 'ABSPATH', dirname( __FILE__ ) . '/wp/' );
require_once( ABSPATH . 'wp-settings.php' );

?>