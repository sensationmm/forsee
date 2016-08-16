<?php
// Set up Settings Admin
require_once( 'class-itsec-settings-admin.php' );
$itsec_settings_admin = new ITSEC_Settings_Admin();
$itsec_settings_admin->run( ITSEC_Core::get_instance() );
