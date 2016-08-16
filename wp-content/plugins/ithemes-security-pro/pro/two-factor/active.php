<?php
// Set up Two Factor Admin
require_once( 'class-itsec-two-factor-admin.php' );
$itsec_two_factor_admin = new ITSEC_Two_Factor_Admin();
$itsec_two_factor_admin->run( ITSEC_Core::get_instance() );

// Set up Two Factor Scheduling
require_once( 'class-itsec-two-factor.php' );
$itsec_two_factor = new ITSEC_Two_Factor();
$itsec_two_factor->run( ITSEC_Core::get_instance() );
