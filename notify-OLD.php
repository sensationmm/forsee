<?php
/**
 * Receive XML from HA
 *
 */

// $client = new SoapClient("http://path.to/wsdl?WSDL");
// $res = $client->SoapFunction(array('param1'=>'value','param2'=>'value'));
// echo $res->PaymentNotification->payment;

//     $getxml = trim(file_get_contents("php://input"));
//     $doc = new DOMDocument('1.0', 'utf-8');
//     $doc->loadXML( $getxml );
//     $XMLresults = $doc->getElementsByTagName("DocumentID");

// var_dump($doc);
  

//     $DocumentID = $XMLresults->item(0)->nodeValue;

//     $XMLresults = $doc->getElementsByTagName("SubjectID");
//     $SubjectID = $XMLresults->item(0)->nodeValue;   

//     $XMLresults = $doc->getElementsByTagName("AssessmentStatusCode");
//     $AssessmentStatusCode = $XMLresults->item(0)->nodeValue;   


//     @$XMLresults = $doc->getElementsByTagName("ID");
//     @$product_id = $XMLresults->item(0)->nodeValue;  

//     @$XMLresults = $doc->getElementsByTagName("Name");
//     @$product_name = $XMLresults->item(0)->nodeValue;  

//     @$XMLresults = $doc->getElementsByTagName("ID");
//     @$paypal_id = $XMLresults->item(1)->nodeValue;  

//     @$XMLresults = $doc->getElementsByTagName("Amount");
//     @$Amount = $XMLresults->item(0)->nodeValue;          

//     @$XMLresults = $doc->getElementsByTagName("Currency");
//     @$Currency = $XMLresults->item(0)->nodeValue; 


// $path = $_SERVER['DOCUMENT_ROOT'];

// include_once $path . '/wp-config.php';
// include_once $path . '/wp-load.php';
// include_once $path . '/wp-includes/wp-db.php';
// include_once $path . '/wp-includes/pluggable.php';

// $user_id = 9;
// $key = 'last_name';
// $single = true;
// //echo get_user_meta( $user_id, $key, $single ); 




// $your_xml_response = trim(file_get_contents("php://input"));
// $clean_xml = str_ireplace(['SOAP-ENV:', 'SOAP:', 'oa:'], '', $your_xml_response);
// $xml = simplexml_load_string($clean_xml);

// var_dump($xml->DataArea);
// echo $json = json_encode($xml->DataArea);

// $file = fopen("../../xml-saved/test".uniqid().".txt","w");
// fwrite($file,$clean_xml);
// fclose($file);


// $field_key = "assessmentreport";
//  $user_id = "user_14"; // save to user (user id = 123)

// // echo $value = get_field($field_key, $user_id);
// // $value[] = array("documentid" => 25);
// // $value[] = array("documentid" => 30);
// // update_field( $field_key, $value, $user_id );

// $getmyrows = have_rows($field_key, $user_id);

//var_dump($getmyrows);



// $field_key = "assessmentreport";
// $user_id = "17"; // save to user (user id = 123)


//Create default before upload
// add_user_meta( $user_id, 'assessmentreport', 1);
// add_user_meta( $user_id, '_assessmentreport', 'field_6653f32c48d04');


// if ( get_user_meta( $user_id, $field_key ) )
// {
// 	//add_post_meta( $post_id, $field_name, $value );
// 	echo 'found';
// }





// // Add field value
// update_field( "field_5039a99716d1d", "I am a value!", $post_id );


// $total_post = get_post($post_id);

// var_dump($total_post);



// $user_id = "user_19"; // save to user (user id = 123)
// //$value = get_field($field_key, $user_id);

// $subarray[] = array("id" => 111,"description" => "desc","scorenumeric" => 1234);
// $value[] = array("documentid" => 777, "assessmentdetailedresult" => $subarray);

// $result = update_field( $field_key, $value, $user_id );
// var_dump($value);



// function removeElementWithValue($array, $key, $value){
//      foreach($array as $subKey => $subArray){
//           if($subArray[$key] == $value){
//                unset($array[$subKey]);
//           }
//      }
//      return $array;
// }

// $value = removeElementWithValue($value, "documentid", "666");
// update_field( $field_key, $value, $user_id );


// Create post object
// $my_post = array(
//  'post_title' => 'My post',
//  'post_content' => 'This is my post.',
//  'post_status' => 'publish',
//  'post_author' => 8
// );

// // Insert the post into the database
// $post_id = wp_insert_post( $my_post );

// // Add field value
// update_field( "field_5039a99716d1d", "I am a value!", $user_id );


