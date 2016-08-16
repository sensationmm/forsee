<?php
// Set up Password Admin
require_once( 'class-itsec-password-admin.php' );
$itsec_password_admin = new ITSEC_Password_Admin();
$itsec_password_admin->run( ITSEC_Core::get_instance() );

// Set up Password Scheduling
require_once( 'class-itsec-password.php' );
$itsec_password = new ITSEC_Password();
$itsec_password->run();
