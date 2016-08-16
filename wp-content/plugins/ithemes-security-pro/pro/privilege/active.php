<?php
// Set up Privilege Admin
require_once( 'class-itsec-privilege-admin.php' );
$itsec_privilege_admin = new ITSEC_Privilege_Admin();
$itsec_privilege_admin->run( ITSEC_Core::get_instance() );

// Set up Privilege Scheduling
require_once( 'class-itsec-privilege.php' );
$itsec_privilege = new ITSEC_Privilege();
$itsec_privilege->run();
