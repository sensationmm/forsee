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
$clean_xml = str_ireplace(['SOAP-ENV:', 'SOAP:', 'oa:', 'wsse:'], '', $your_xml_response); //strip shitty soap stuff so we can process xml
$xml = simplexml_load_string($clean_xml);

//var_dump($xml);
$DataArea = $xml->Body->NotifyAssessmentReport->DataArea;
$SecurityCreds = $xml->Header->Security->UsernameToken;
//var_dump($SecurityCreds);
//echo $json = json_encode($xml->DataArea);

//Check Security details
if($SecurityCreds->Username && $SecurityCreds->Password){
	if($SecurityCreds->Username=='forsee' && $SecurityCreds->Password=='D6ixBABHZnbDgs7r'){
		//echo 'allow';
	}else{
		echo 'deny';
		exit();
	}
}else{
	echo 'deny';
	exit();
}

//Check if there is a subject ID. If not no point doing anything else 
if(!$DataArea->AssessmentReport->AssessmentSubject->SubjectID){
	echo 'no subject';
	exit();
}else{
	$User_subject_id = trim($DataArea->AssessmentReport->AssessmentSubject->SubjectID);
}

//Save original
$file = fopen("../../xml-saved/".uniqid()."original.txt","w");
fwrite($file,$your_xml_response);
fclose($file);
//Save cleaned - more for debug
$file = fopen("../../xml-saved/".uniqid().".txt","w");
fwrite($file,$clean_xml);
fclose($file);

//update_user_meta(21, 'survey_completed', 'no');
// $field_name = "survey_completed";
// $value = "some new string2";
// update_field( $field_name, $value, 'user_21');

// echo $DataArea->AssessmentReport->AssessmentResult->Attachment->AttachmentID;
// echo $DataArea->AssessmentReport->AssessmentResult->Attachment->FileName;
// echo $DataArea->AssessmentReport->AssessmentResult->Attachment->EmbeddedData;



//Process any PDF reports first
if($DataArea->AssessmentReport->AssessmentResult->Attachment->AttachmentID && $DataArea->AssessmentReport->AssessmentResult->Attachment->FileName && $DataArea->AssessmentReport->AssessmentResult->Attachment->EmbeddedData){
	$DataArea->AssessmentReport->AssessmentSubject->SubjectID;
	//Save PDF
	$PDF_Report_FN = $DataArea->AssessmentReport->AssessmentSubject->SubjectID."_".$DataArea->AssessmentReport->AssessmentResult->Attachment->AttachmentID."_".uniqid()."_".$DataArea->AssessmentReport->AssessmentResult->Attachment->FileName;
	$file = fopen("../../pdf-reports/".$PDF_Report_FN,"w");
	fwrite($file,base64_decode($DataArea->AssessmentReport->AssessmentResult->Attachment->EmbeddedData));
	fclose($file);

	//Save filename to WP user profile
	// if($DataArea->AssessmentReport->AssessmentResult->Attachment->AttachmentID=='Cd'){
	// 	update_field( 'report_career_development', $PDF_Report_FN, 'user_'.$User_subject_id);
	// }
	// if($DataArea->AssessmentReport->AssessmentResult->Attachment->AttachmentID=='Ygs'){
	// 	update_field( 'report_greatest_strenghts', $PDF_Report_FN, 'user_'.$User_subject_id);
	// }
	// if($DataArea->AssessmentReport->AssessmentResult->Attachment->AttachmentID=='Co2'){
	// 	update_field( 'report_careers_options', $PDF_Report_FN, 'user_'.$User_subject_id);
	// }	

	//Save / update normal reports to user repeater field Ccp
	$AttachementID = trim($DataArea->AssessmentReport->AssessmentResult->Attachment->AttachmentID);
	if($AttachementID=='Cd' || $AttachementID=='Ygs' || $AttachementID=='Co2' || $AttachementID=='Pg' || $AttachementID=='Pg1' || $AttachementID=='Ccp'){

		$Dupe_check = 0;
		//Check for exisiting so we don't dupe up
		if( have_rows('ha_reports', 'user_'.$User_subject_id) ){

		 	// loop through the rows of data
		    while ( have_rows('ha_reports', 'user_'.$User_subject_id) ) {
		    	the_row();

		        // display a sub field value
		        $subtomatch = get_sub_field('ha_report_id');
		       
		        if($subtomatch==$AttachementID){
		        	$Dupe_check++;
		        	//Found an existing record - Update exisitng record instead.
		        	//echo 'match found update';
		        	update_sub_field('ha_report_file', $PDF_Report_FN);
		        }

		    }

		}

		//Dupe check no match - Insert new record
		if($Dupe_check==0){
			//If it doesn't exisit 
			//Insert the report into database (should only need to update really as when the request it will insert the initial record then)
			$field_key = "field_565bd7de16c68"; //Get this ID from the wordpress Post table assigned to the main repeater record
			$value = get_field($field_key, 'user_'.$User_subject_id);
			// var_dump($value);
			$value[] = array("ha_report_id" => $AttachementID, "ha_report_file" => $PDF_Report_FN, "ha_report_requested" => true);
			update_field( $field_key, $value, 'user_'.$User_subject_id );			

		}

	}	




	//Cc can vary in ID's eg. Cc_HA-1090
	if(substr( $DataArea->AssessmentReport->AssessmentResult->Attachment->AttachmentID, 0, 3 ) === "Cc_"){

		//Remove prefix from report ID
		$prefix = 'Cc_';
		$str = $DataArea->AssessmentReport->AssessmentResult->Attachment->AttachmentID;

		if (substr($str, 0, strlen($prefix)) == $prefix) {
		   $str = substr($str, strlen($prefix));
		} 		

		$Dupe_check = 0;
		//Check for exisiting so we don't dupe up
		if( have_rows('report_career_enjoyment', 'user_'.$User_subject_id) ){

		 	// loop through the rows of data
		    while ( have_rows('report_career_enjoyment', 'user_'.$User_subject_id) ) {
		    	the_row();

		        // display a sub field value
		        $subtomatch = get_sub_field('career_enjoyment_id');
		       
		        if($subtomatch==$str){
		        	$Dupe_check++;
		        	//Found an existing record - Update exisitng record instead.
		        	//echo 'dupe found for Cc';
		        	//exit();
		        	update_sub_field('career_enjoyment_file', $PDF_Report_FN);
		        }

		    }

		}

		//Dupe check no match - Insert new record
		if($Dupe_check==0){
			//If it doesn't exisit 
			//Insert the report into database (should only need to update really as when the request it will insert the initial record then)
			$field_key = "field_5657dffe586cc"; //Get this ID from the wordpress Post table assigned to the main repeater record
			$value = get_field($field_key, 'user_'.$User_subject_id);
			// var_dump($value);
			$value[] = array("career_enjoyment_id" => $str, "career_enjoyment_file" => $PDF_Report_FN, "ce_requested" => true);
			update_field( $field_key, $value, 'user_'.$User_subject_id );			

		}


	}

}


//Otherwise we are going to process the XML results from Co0 or Co1
if($DataArea->AssessmentReport->DocumentID && $DataArea->AssessmentReport->AssessmentSubject->SubjectID && $DataArea->AssessmentReport->AssessmentResult->AssessmentStatus->AssessmentStatusCode){	
	//Process Co1 Co0 XML results
	if($DataArea->AssessmentReport->DocumentID=='ForseeSurvey1' && $DataArea->AssessmentReport->AssessmentResult->AssessmentStatus->AssessmentStatusCode=='OrderCompleted'){
		
		//Mark WP user as completed
		$field_name = "survey_completed";
		$value = true;
		update_field( $field_name, $value, 'user_'.$DataArea->AssessmentReport->AssessmentSubject->SubjectID);

		//Add survey date completion
		$field_name = "survey_completed_date";
		$value = date("d/m/Y");
		update_field( $field_name, $value, 'user_'.$DataArea->AssessmentReport->AssessmentSubject->SubjectID);	

		//Remove any old data
		$wpdb->delete( 'ha_data', array( 'user_id' => $DataArea->AssessmentReport->AssessmentSubject->SubjectID, 'doc_id' => $DataArea->AssessmentReport->DocumentID) );		

		//Save free results to database
		if($DataArea->AssessmentReport->AssessmentResult->AssessmentDetailedResult){
			$Count_Career_Options = 0;
			$AssessmentDetailsArray = $DataArea->AssessmentReport->AssessmentResult->AssessmentDetailedResult;
			foreach($DataArea->AssessmentReport->AssessmentResult->AssessmentDetailedResult as $CareerOption){
				$Count_Career_Options++;
				// echo $CareerOption->ID;
				// echo $CareerOption->Description;
				// echo $CareerOption->Score->ScoreNumeric;

				
				//`user_id`, `doc_id`, `meta_id`, `meta_desc`, `meta_score`
				$wpdb->insert( 
					'ha_data', 
					array( 
						'user_id' => $DataArea->AssessmentReport->AssessmentSubject->SubjectID,
						'doc_id' => $DataArea->AssessmentReport->DocumentID,
						'meta_id' => $CareerOption->ID,
						'meta_desc' => $CareerOption->Description,
						'meta_score' => $CareerOption->Score->ScoreNumeric,

					), 
					array( 
						'%s',
						'%s',
						'%s',
						'%s',
						'%s',
					) 
				);


			}

			//Do we need to request reports (Purchsed before completing survey)
			//Check if Co0 (only 10 records get retunred) - if so we need to see if they purchased before they completed the survey - if they have we can now request the reports they purchased from HA	
			if($Count_Career_Options==10){
				//echo 'Co0';

				//Request Ygs report (part of freebies)
				$Pack_Request = SOAP_request_report_from_HA($User_subject_id, 'Ygs' );
				if(!$Pack_Request){
					error_log( "Failed to request pack from HA: Ygs", 0 );
				}else{
					$data_array = array("ha_report_id" => 'Ygs', "ha_report_file" => '', "ha_report_requested" => true);
					Insert_primary_report($User_subject_id,$data_array);									
				}					


				//Check if we have already request reports
				$Have_We_Requested_Any_Reports_Before = Get_primary_reports_requested($User_subject_id);
				if(!$Have_We_Requested_Any_Reports_Before){
					//echo 'no reports requested before';
					
					$items = WC_What_Products_Have_They_Ordered(311, $User_subject_id);
					
					if($items['all']){
						$allow_report_requests = true;
						
						//echo 'they have ordered products so should request reports';
						
						//Process once to check for packs
						$item = $items['all'];		

						//If they are ordering a pack call HA API with this first
						if($item[315] || $item[1927]){
							error_log( "Pack Requested: Ccp", 0 );
							$Pack_Request = SOAP_request_report_from_HA($User_subject_id, 'Ccp' );
							if(!$Pack_Request){
								error_log( "Failed to request pack from HA: Ccp", 0 );
								$allow_report_requests = false;
							}
						}

						//Request reports individually
						if($allow_report_requests){

							//Request Co1 & Co2		
							if($item[309]){									
								error_log( "Pack Requested: Co1", 0 );
								$Pack_Request = SOAP_request_report_from_HA($User_subject_id, 'Co1' );
								if(!$Pack_Request){
									error_log( "Failed to request pack from HA: ".$item['product_id'], 0 );
								}						
								$Pack_Request_Co2 = SOAP_request_report_from_HA($User_subject_id, 'Co2' );
								if(!$Pack_Request_Co2){
									error_log( "Failed to request pack from HA: Co2", 0 );
								}else{
									$data_array = array("ha_report_id" => 'Co2', "ha_report_file" => '', "ha_report_requested" => true);
									Insert_primary_report($User_subject_id,$data_array);										
								}
							}

							//Request Cd	
							if($item[310]){
								error_log( "Pack Requested: Cd", 0 );
								$Pack_Request = SOAP_request_report_from_HA($User_subject_id, 'Cd' );
								if(!$Pack_Request){
									error_log( "Failed to request pack from HA: Cd", 0 );
								}else{
									$data_array = array("ha_report_id" => 'Cd', "ha_report_file" => '', "ha_report_requested" => true);
									Insert_primary_report($User_subject_id,$data_array);										
								}				
							}

							// They get this report for free so no longer have to request after puchase
							// //Request Ygs	
							// if($item[455]){
							// 	error_log( "Pack Requested: Ygs", 0 );
							// 	$Pack_Request = SOAP_request_report_from_HA($User_subject_id, 'Ygs' );
							// 	if(!$Pack_Request){
							// 		error_log( "Failed to request pack from HA: Ygs", 0 );
							// 	}else{
							// 		$data_array = array("ha_report_id" => 'Ygs', "ha_report_file" => '', "ha_report_requested" => true);
							// 		Insert_primary_report($User_subject_id,$data_array);									
							// 	}						
							// }	

							//Request Pg (paradox graphs)	
							if($item[312]){
								error_log( "Pack Requested: Pg1", 0 );
								$Pack_Request = SOAP_request_report_from_HA($User_subject_id, 'Pg1' );
								if(!$Pack_Request){
									error_log( "Failed to request pack from HA: Pg1", 0 );
								}else{
									$data_array = array("ha_report_id" => 'Pg1', "ha_report_file" => '', "ha_report_requested" => true);
									Insert_primary_report($User_subject_id,$data_array);
								}						
							}	

			
						}



					}
				}
			}



		}

	}



}


//Show empty soap for response
echo '<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/">
<SOAP-ENV:Body/>
</SOAP-ENV:Envelope>';  


