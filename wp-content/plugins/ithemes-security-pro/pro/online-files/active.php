<?php
// Set up Online File Scanning Admin
require_once( 'class-itsec-online-files-admin.php' );
$itsec_online_files_admin = new ITSEC_Online_Files_Admin();
$itsec_online_files_admin->run( ITSEC_Core::get_instance() );

// Set up Online File Scanning Scheduling
require_once( 'class-itsec-online-files.php' );
$itsec_online_files = new ITSEC_Online_Files();
$itsec_online_files->run();
