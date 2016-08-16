<?php
/** Enable W3 Total Cache */
define('WP_CACHE', true); // Added by W3 Total Cache
define('WP_DEBUG_LOG', true);


/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

//Define which config we are going to use based on domain 
if(strpos($_SERVER['SERVER_NAME'], 'forsee.com.au') !== FALSE) { 
  	//define('ENVIRONMENT', 'production');
  	require_once(ABSPATH . 'wp-config-production.php');
}elseif(strpos($_SERVER['SERVER_NAME'], 'forsee.ec7.co') !== FALSE) { 
  	require_once(ABSPATH . 'wp-config-staging.php');
}elseif(strpos($_SERVER['SERVER_NAME'], 'forsee.192.168.1.200.xip.io') !== FALSE) { 
  	require_once(ABSPATH . 'wp-config-rvdevelopment.php');
}elseif(strpos($_SERVER['SERVER_NAME'], 'forsee') !== FALSE) { 
  	require_once(ABSPATH . 'wp-config-krdevelopment.php');
}else{ 
  	require_once(ABSPATH . 'wp-config-development.php');
}

define( 'AWS_ACCESS_KEY_ID', 'AKIAJEN22RHHX5CURZKQ' );
define( 'AWS_SECRET_ACCESS_KEY', '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' );

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

/* That's all, stop editing! Happy blogging. */