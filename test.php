<?php

$path = $_SERVER['DOCUMENT_ROOT'];

include_once $path . '/wp-load.php';
include_once $path . '/wp-config.php';
include_once $path . '/wp-includes/wp-db.php';
include_once $path . '/wp-includes/pluggable.php';


$user_query = new WP_User_Query( array( 'orderby' => 'hubspot_sync_required', 'role' => 'Customer' ) ); //


var_dump($user_query);