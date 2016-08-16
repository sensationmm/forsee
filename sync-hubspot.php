<?php
/**
 * Sync Hubspot data to contact
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

$HAPIKey = '0380e3dd-df30-48ab-b3a4-ea40b7efbbdb';

// $user_id = 9;
// $key = 'last_name';
// $single = true;
//echo get_user_meta( $user_id, $key, $single ); 

date_default_timezone_set('UTC'); //IMPORTANT for Hubspot timestamp format
//echo date('Y-m-d H:i:s');
	



require_once(ABSPATH.'/3rdparty/haPiHP-master/class.contacts.php');

$contacts_to_push_to_hubspot = new HubSpot_Contacts($HAPIKey);	

//Get an expired users accounts
// $User_Array = $this->hs_sync_models->GetSyncableUsers(10);


// $args = array(
// 	'posts_per_page'  => 10,
// 	'post_type'   => 'user',
// 	'meta_key'    => 'hubspot_sync_required',
// 	'meta_value'  => 'true',
// );


//Products they have ordered
//var_dump( WC_What_Products_Have_They_Ordered(false, 29) );

// query
//$the_query = new WP_Query( $args );  
//$the_query = new WP_User_Query( $args );

// $all_meta_for_user = get_user_meta( 29 );
// var_dump($all_meta_for_user);

$user_query = new WP_User_Query( array( 'meta_key' => 'hubspot_sync_required', 'meta_value' => true ) );

// User Loop
$LoopCounter = 0;
if ( ! empty( $user_query->results ) ) {
	foreach ( $user_query->results as $user ) {
		//var_dump($user);
		 //$all_meta_for_user = get_user_meta( $user->ID );
		// var_dump($all_meta_for_user);		
		 
		$user_data = get_userdata( $user->ID );
//var_dump($user_data);
//echo		$user_data->user_email;
		
		$Products_ordered = WC_What_Products_Have_They_Ordered(false, $user->ID);

		//Create Batch array to push to Hubspot
		$DataArray[$LoopCounter] = array(					
			//'email' => get_user_meta($user->ID, 'billing_email', true), 
			'email' => $user_data->user_email, 
			'properties' => array(
				'subjectid' => $user->ID, 
				'firstname' => get_user_meta($user->ID, 'first_name', true), 
				'lastname' => get_user_meta($user->ID, 'last_name', true),
				)
		);	

		//Add more stuff to properties array
		if(get_user_meta($user->ID, 'survey_completed', true)){
			$DataArray[$LoopCounter]['properties']['survey_completed'] = 'Yes';
		}else{
			$DataArray[$LoopCounter]['properties']['survey_completed'] = 'No';
		}

		if($Products_ordered['all']){
			$DataArray[$LoopCounter]['properties']['survey_paid'] = 'Yes';
		}else{
			$DataArray[$LoopCounter]['properties']['survey_paid'] = 'No';
		}		

		if(get_user_meta($user->ID, 'survey_url', true)){
			$DataArray[$LoopCounter]['properties']['survey_url'] = get_user_meta($user->ID, 'survey_url', true);
		}

		//What have they purchased
		/*
			career_development_report = 310
			career_enjoyment_report = 311
			career_options_report = 309
			paradox_graphs = 312
			your_greatest_strengths_report = 455
		*/		
		if($Products_ordered['all']['310']){
			$DataArray[$LoopCounter]['properties']['career_development_report'] = 'Yes';
		}
		if($Products_ordered['all']['311']){
			$DataArray[$LoopCounter]['properties']['career_enjoyment_report'] = 'Yes';
		}			
		if($Products_ordered['all']['309']){
			$DataArray[$LoopCounter]['properties']['career_options_report'] = 'Yes';
		}	
		if($Products_ordered['all']['312']){
			$DataArray[$LoopCounter]['properties']['paradox_graphs'] = 'Yes';
		}	
		if($Products_ordered['all']['455']){
			$DataArray[$LoopCounter]['properties']['your_greatest_strengths_report'] = 'Yes';
		}									


		//Update user as synced
		//Update user as synced
		//Update user as synced		
		update_field( 'hubspot_sync_required', false, 'user_'.$user->ID);

		//Increment loop
		$LoopCounter++;
	}

//var_dump($DataArray);
	//Push new data array to Hubspot - Returns NULL when successful - Stoopid!
	$Pushtest = $contacts_to_push_to_hubspot->batch_create_update_contact($DataArray);

} else {
	echo 'Nothing to do.';
}
