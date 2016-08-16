<?php
header ("Content-Type:text/xml");
/**
 * Receive XML from HA
 *
 */

$path = $_SERVER['DOCUMENT_ROOT'];

include_once $path . '/wp-config.php';
include_once $path . '/wp-load.php';
include_once $path . '/wp-includes/wp-db.php';
include_once $path . '/wp-includes/pluggable.php';

// $user_id = 9;
// $key = 'last_name';
// $single = true;
//echo get_user_meta( $user_id, $key, $single ); 


$your_xml_response = trim(file_get_contents("php://input"));
$clean_xml = str_ireplace(['SOAP-ENV:', 'SOAP:', 'oa:'], '', $your_xml_response); //strip shitty soap stuff so we can process xml
$xml = simplexml_load_string($clean_xml);

var_dump($clean_xml);
//echo $json = json_encode($xml->DataArea);

$file = fopen("../../xml-saved/test".uniqid().".txt","w");
fwrite($file,$clean_xml);
fclose($file);

//update_user_meta(21, 'survey_completed', 'no');
// $field_name = "survey_completed";
// $value = "some new string2";
// update_field( $field_name, $value, 'user_21');

//Survey is completed and should of returned a few free career options
if($xml->DataArea->AssessmentReport->DocumentID && $xml->DataArea->AssessmentReport->AssessmentSubject->SubjectID && $xml->DataArea->AssessmentReport->AssessmentResult->AssessmentStatus->AssessmentStatusCode){	
	//Process Co0 / survey trial links
	if($xml->DataArea->AssessmentReport->DocumentID=='Co0' && $xml->DataArea->AssessmentReport->AssessmentResult->AssessmentStatus->AssessmentStatusCode=='OrderCompleted'){
		
		//Mark WP user as completed
		$field_name = "survey_completed";
		$value = "yes";
		update_field( $field_name, $value, 'user_'.$xml->DataArea->AssessmentReport->AssessmentSubject->SubjectID);

		//Remove any old data
		$wpdb->delete( 'ha_data', array( 'user_id' => $xml->DataArea->AssessmentReport->AssessmentSubject->SubjectID, 'doc_id' => $xml->DataArea->AssessmentReport->DocumentID) );		

		//Save free results to database
		if($xml->DataArea->AssessmentReport->AssessmentResult->AssessmentDetailedResult){
			$AssessmentDetailsArray = $xml->DataArea->AssessmentReport->AssessmentResult->AssessmentDetailedResult;
			foreach($xml->DataArea->AssessmentReport->AssessmentResult->AssessmentDetailedResult as $CareerOption){
				// echo $CareerOption->ID;
				// echo $CareerOption->Description;
				// echo $CareerOption->Score->ScoreNumeric;
				
				//`user_id`, `doc_id`, `meta_id`, `meta_desc`, `meta_score`

				$wpdb->insert( 
					'ha_data', 
					array( 
						'user_id' => $xml->DataArea->AssessmentReport->AssessmentSubject->SubjectID,
						'doc_id' => $xml->DataArea->AssessmentReport->DocumentID,
						'meta_id' => $CareerOption->ID,
						'meta_desc' => $CareerOption->Description,
						'meta_score' => $CareerOption->Score->ScoreNumeric
					), 
					array( 
						'%s',
						'%s',
						'%s',
						'%s',
						'%s'
					) 
				);



			}
		}

		//var_dump($AssessmentDetailsArray);
	}

	//Process Co1 response
	if($xml->DataArea->AssessmentReport->DocumentID=='Co1' && $xml->DataArea->AssessmentReport->AssessmentResult->AssessmentStatus->AssessmentStatusCode=='OrderCompleted'){
		
		//Remove any old data
		$wpdb->delete( 'ha_data', array( 'user_id' => $xml->DataArea->AssessmentReport->AssessmentSubject->SubjectID, 'doc_id' => $xml->DataArea->AssessmentReport->DocumentID) );		

		//Save free results to database
		if($xml->DataArea->AssessmentReport->AssessmentResult->AssessmentDetailedResult){
			$AssessmentDetailsArray = $xml->DataArea->AssessmentReport->AssessmentResult->AssessmentDetailedResult;
			foreach($xml->DataArea->AssessmentReport->AssessmentResult->AssessmentDetailedResult as $CareerOption){
				// echo $CareerOption->ID;
				// echo $CareerOption->Description;
				// echo $CareerOption->Score->ScoreNumeric;
				
				//`user_id`, `doc_id`, `meta_id`, `meta_desc`, `meta_score`

				$wpdb->insert( 
					'ha_data', 
					array( 
						'user_id' => $xml->DataArea->AssessmentReport->AssessmentSubject->SubjectID,
						'doc_id' => $xml->DataArea->AssessmentReport->DocumentID,
						'meta_id' => $CareerOption->ID,
						'meta_desc' => $CareerOption->Description,
						'meta_score' => $CareerOption->Score->ScoreNumeric
					), 
					array( 
						'%s',
						'%s',
						'%s',
						'%s',
						'%s'
					) 
				);



			}
		}

		//var_dump($AssessmentDetailsArray);
	}





}


//Show empty soap for response
echo '<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/">
<SOAP-ENV:Body/>
</SOAP-ENV:Envelope>';  


