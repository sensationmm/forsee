<?php

/*
 * Plugin Name: iThemes Security Pro
 * Plugin URI: https://ithemes.com/security
 * Description: Protect your WordPress site by hiding vital areas of your site, protecting access to important files, preventing brute-force login attempts, detecting attack attempts and more.
 * Author: iThemes
 * Author URI: https://ithemes.com
 * Version: 2.1.2
 * Text Domain: it-l10n-ithemes-security-pro
 * Domain Path: /lang
 * Network: True
 * License: GPLv2
 * iThemes Package: ithemes-security-pro
 */


$locale = apply_filters( 'plugin_locale', get_locale(), 'it-l10n-ithemes-security-pro' );
load_textdomain( 'it-l10n-ithemes-security-pro', WP_LANG_DIR . "/plugins/ithemes-security-pro/it-l10n-ithemes-security-pro-$locale.mo" );
load_plugin_textdomain( 'it-l10n-ithemes-security-pro', false, basename( dirname( __FILE__ ) ) . '/lang/' );

if ( isset( $itsec_dir ) || class_exists( 'ITSEC_Core' ) ) {
	include( dirname( __FILE__ ) . '/core/show-multiple-version-notice.php' );
	return;
}


$itsec_dir = dirname( __FILE__ );

if ( is_admin() ) {
	require( "$itsec_dir/lib/icon-fonts/load.php" );
}

// Add pro modules at priority 11 so they are added after core modules (thus taking precedence)
add_action( 'itsec-register-modules', 'itsec_pro_register_modules', 11 );
function itsec_pro_register_modules() {
	$itset_modules = ITSEC_Modules::get_instance();
	$itset_modules->register_module( 'core',                'pro/core'                         );
	$itset_modules->register_module( 'dashboard-widget',    'pro/dashboard-widget'             );
	$itset_modules->register_module( 'help',                'pro/help'                         );
	$itset_modules->register_module( 'malware-scheduling',  'pro/malware-scheduling'           );
	$itset_modules->register_module( 'online-files',        'pro/online-files'                 );
	$itset_modules->register_module( 'password',            'pro/password'                     );
	$itset_modules->register_module( 'privilege',           'pro/privilege'                    );
	$itset_modules->register_module( 'recaptcha',           'pro/recaptcha'                    );
	$itset_modules->register_module( 'settings',            'pro/settings'                     );
	$itset_modules->register_module( 'two-factor',          'pro/two-factor'                   );
	$itset_modules->register_module( 'user-logging',        'pro/user-logging'                 );
	$itset_modules->register_module( 'wp-cli',              'pro/wp-cli'                       );
}

add_filter( 'itsec-default-active-modules', 'itsec_pro_default_active_modules' );
function itsec_pro_default_active_modules( $modules ) {
	$modules[] = 'dashboard-widget';
	$modules[] = 'malware-scheduling';
	$modules[] = 'online-files';
	$modules[] = 'password';
	$modules[] = 'privilege';
	$modules[] = 'recaptcha';
	$modules[] = 'settings';
	$modules[] = 'two-factor';
	$modules[] = 'user-logging';
	$modules[] = 'wp-cli';
	return $modules;
}

require( "$itsec_dir/core/class-itsec-core.php" );
$itsec_core = ITSEC_Core::get_instance();
$itsec_core->init( __FILE__, __( 'iThemes Security Pro', 'it-l10n-ithemes-security-pro' ) );


function ithemes_repository_name_updater_register( $updater ) {
	$updater->register( 'ithemes-security-pro', __FILE__ );
}
add_action( 'ithemes_updater_register', 'ithemes_repository_name_updater_register' );

require( "$itsec_dir/lib/updater/load.php" );
