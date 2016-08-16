<?php
/**
 * Set all contacts to be sync'd
 *
 */

//Access control wget --post-data="apikey=12343423422334" -O - https://forsee.com.au/sync-hubspot.php  >/dev/null 2>&1
if(isset($_POST['apikey']) && $_POST['apikey']=='12343423422334'){	
	echo '<br/>Allowed<br/>';			
}else{
	echo '<br/>Deny<br/>';
	exit();
}

$path = $_SERVER['DOCUMENT_ROOT'];

include_once $path . '/wp-config.php';
include_once $path . '/wp-load.php';
include_once $path . '/wp-includes/wp-db.php';
include_once $path . '/wp-includes/pluggable.php';



//Get accounts where they Hubspot sync is not marked as 1  (1=true, 0=false)
//$user_query = new WP_User_Query( array( 'meta_key' => 'hubspot_sync_required', 'meta_value' => '' ) );
$user_query = new WP_User_Query( array( 'orderby' => 'hubspot_sync_required', 'role' => 'Customer' ) ); //
//$user_query = new WP_User_Query( array( 'meta_key' => 'hubspot_sync_required', 'meta_value' => 0 ) ); // zero for local - new version seems to have blank

if ( ! empty( $user_query->results ) ) {
	foreach ( $user_query->results as $user ) {
		//var_dump($user);
		 //$all_meta_for_user = get_user_meta( $user->ID );
		// var_dump($all_meta_for_user);		
		 
		$user_data = get_userdata( $user->ID );
//var_dump($user_data);
//echo		$user_data->user_email;

		//Set user as to be sync'd	
	update_field( 'hubspot_sync_required', true, 'user_'.$user->ID);


	}


} else {
	echo 'Nothing to do.';
}
