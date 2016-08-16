<?php
// Set up Recaptcha Admin
require_once( 'class-itsec-recaptcha-admin.php' );
$itsec_recaptcha_admin = new ITSEC_Recaptcha_Admin();
$itsec_recaptcha_admin->run( ITSEC_Core::get_instance() );

// Set up Recaptcha Scheduling
require_once( 'class-itsec-recaptcha.php' );
$itsec_recaptcha = new ITSEC_Recaptcha();
$itsec_recaptcha->run();
