<?php

/* Here we look for a valid env-config.php file */
$has_env 		= false;
$env_file 	= dirname(__FILE__).'/env-config.php';

/* Parsing */
if(!file_exists($env_file))
	die('Could not locate env-config.php');

/* Include the env_config.php file */
require $env_file;

/* Make sure $env_config is set */
if(!isset($env_config))
	die('$env_config is not set');

/* Make sure $env_config is an array */
if(!is_array($env_config))
	die('$env_config should be an array of directives');

if(!isset($env_config['environment']))
	die('Please specify the "environment" setting in $env_config');

/* Check if the base_url is set */
if(!isset($env_config['base_url']))
	die('Please specify the "base_url" setting in $env_config');


/* Merge $env_config with the defaults */
$env_config = array_merge(array(
	'base_path' 	=> dirname(__FILE__),
	'debug' 		=> 'Off',
	'debug_display' => 'Off',
	'auto_updates' 	=> 'Off',
	'table_prefix' 	=> 'wp_',
	'language' 		=> 'en_US',
	'content_dir' 	=> 'content'
	), $env_config);


/* Setup the constants */
define('WP_ENV', $env_config['environment']);
define('WP_BASE_URL', preg_replace('~\/$~', '', $env_config['base_url']));
define('WP_BASE_PATH', preg_replace('~\/$~', '', $env_config['base_path']));


/* Handle automatic updates */
if($env_config['auto_updates'] === false)
	define('AUTOMATIC_UPDATER_DISABLED', true);

/* Error handling */
if($env_config['debug']){
	define('SAVEQUERIES', true);
	error_reporting(E_WARNING | E_ERROR);
}
else{
	define('SAVEQUERIES', false);
	error_reporting(0);
}

if($env_config['debug_display']){
	ini_set('display_errors', 1);
}
else{
	ini_set('display_errors', 0);
}

/* Include the DB config file */
define('DB_NAME', 		$env_config['db_name']);
define('DB_USER', 		$env_config['db_user']);
define('DB_PASSWORD', 	$env_config['db_password']);
define('DB_HOST', 		$env_config['db_host']);

/* Set the custom content directory to content */
define( 'WP_CONTENT_DIR', dirname( __FILE__ ) . '/'.$env_config['content_dir'] );
define( 'WP_CONTENT_URL', WP_BASE_URL.'/'.$env_config['content_dir'] );

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

/* Set table prefix */
$table_prefix  = $env_config['table_prefix'];

/* Set language */
define( 'WPLANG', $env_config['language']);

/* We're ready. Bootstrap WordPress */
if ( !defined( 'ABSPATH' ) )
	define( 'ABSPATH', dirname( __FILE__ ) . '/wp/' );
require_once( ABSPATH . 'wp-settings.php' );

?>
