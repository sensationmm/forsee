<?php
    if( !session_id() ){
        session_start();
    }

	add_theme_support('menus');
	add_theme_support( 'post-thumbnails' );

	if ( function_exists('register_sidebar') )
		register_sidebar( array('id'=>'sidebar-1') );

	function textdomain_jquery_enqueue() {
	   wp_deregister_script( 'jquery' );
	   wp_register_script( 'jquery', "http" . ($_SERVER['SERVER_PORT'] == 443 ? "s" : "") . "://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js", false, null );
	   wp_enqueue_script( 'jquery' );
	}
	if ( !is_admin() ) {
	    add_action( 'wp_enqueue_scripts', 'textdomain_jquery_enqueue', 11 );
	}

	add_action( 'init', 'create_post_type');
	function create_post_type() {
		register_post_type( 'news',
			array('labels' => array( 'name' => __( 'News' ), 'singular_name' => __( 'News Article' )),
		  		'public' => true, 
		  		'has_archive' => true, 
		  		'menu_position' => 5, 
		  		'taxonomies' => array('category','post_tag'),
		  		'supports' => array('title','editor','author','thumbnail','excerpt'))
		);
		register_post_type( 'testimonials',
			array('labels' => array( 'name' => __( 'Testimonials' ), 'singular_name' => __( 'Testimonial' )),
		  		'public' => true, 
		  		'has_archive' => true, 
		  		'menu_position' => 5, 
		  		'taxonomies' => array('category','post_tag'),
		  		'supports' => array('title','editor','author','thumbnail','excerpt'))
		);
		register_post_type( 'homepage_banners',
			array('labels' => array( 'name' => __( 'Homepage Banners' ), 'singular_name' => __( 'Homepage Banner' )),
		  		'public' => true, 
		  		'has_archive' => true, 
		  		'menu_position' => 5, 
		  		'supports' => array('title'))
		);
		register_post_type( 'reports',
			array('labels' => array( 'name' => __( 'Reports' ), 'singular_name' => __( 'Report' )),
		  		'public' => true, 
		  		'has_archive' => true, 
		  		'menu_position' => 5, 
		  		'supports' => array('title','editor'))
		);
		register_post_type( 'course',
			array('labels' => array( 'name' => __( 'Courses' ), 'singular_name' => __( 'Course' )),
		  		'public' => true, 
		  		'has_archive' => true, 
		  		'menu_position' => 5, 
		  		'supports' => array('title','editor','excerpt'))
		);
		register_post_type( 'career_desc',
			array('labels' => array( 'name' => __( 'Career Descriptions' ), 'singular_name' => __( 'Career Descriptions' )),
		  		'public' => true, 
		  		'has_archive' => false, 
		  		'menu_position' => 5, 
		  		'taxonomies' => array('post_tag'),
		  		'supports' => array('title','editor'))
		);	
		register_post_type( 'lp',
			array('labels' => array( 'name' => __( 'Landing Pages' ), 'singular_name' => __( 'Landing Page' )),
		  		'public' => true, 
		  		'has_archive' => false, 
		  		'menu_position' => 5, 
		  		'supports' => array('title','editor'))
		);		
		register_post_type( 'broker',
			array('labels' => array( 'name' => __( 'Brokers' ), 'singular_name' => __( 'Broker' )),
		  		'public' => true, 
		  		'has_archive' => false, 
		  		'menu_position' => 5, 
		  		'supports' => array('title','editor'))
		);		

		register_taxonomy( 'courses','course',array('hierarchical'=>true,'label'=>'Categories','query_var'=>true,'rewrite'=>true));
		register_taxonomy( 'career_cats','career_desc',array('hierarchical'=>true,'label'=>'Categories','query_var'=>true,'rewrite'=>true));

	}

	//Show course taxonomy on courses admin screen
	add_filter( 'manage_taxonomies_for_courses_columns', 'course_category_columns' );
	function course_category_columns( $taxonomies ) {
	    $taxonomies[] = 'forsee_course_categories';
	    return $taxonomies;
	}

	add_filter( 'template_include', 'var_template_include', 1000 );
	function var_template_include( $t ){
	    $GLOBALS['current_theme_template'] = basename($t);
	    return $t;
	}

	/*
	* get current page template
	* $echo: (bool) echo or return value
	*/
	function get_current_template( $echo = false ) {
	    if( !isset( $GLOBALS['current_theme_template'] ) )
	        return 'false';
	    if( $echo )
	        echo $GLOBALS['current_theme_template'];
	    else
	        return $GLOBALS['current_theme_template'];
	}


// function get_user_role() {
// 	global $current_user;

// 	$user_roles = $current_user->roles;
// 	$user_role = array_shift($user_roles);

// 	return $user_role;
// }

// add_theme_support( 'post-thumbnails' );
// add_image_size( 'sidebar-thumb', 120, 120, true ); // Hard Crop Mode
	

	//Woocommerce Theme Support 
	add_action('woocommerce_before_main_content', 'my_theme_wrapper_start', 10);
	add_action('woocommerce_after_main_content', 'my_theme_wrapper_end', 10);

	function my_theme_wrapper_start() {
	  echo '<section id="mainwoosection">';
	}

	function my_theme_wrapper_end() {
	  echo '</section>';
	}	

	add_action( 'after_setup_theme', 'woocommerce_support' );
	function woocommerce_support() {
	    add_theme_support( 'woocommerce' );
	}	





/*
 * Do something after WooCommerce sets an order on completed
 */
// add_action('woocommerce_payment_complete', 'custom_process_order', 10, 1);
// function custom_process_order($order_id) {
//    global $wpdb;

//    $user_id = get_current_user_id();

//     $order = new WC_Order( $order_id );
//     $myuser_id = (int)$order->user_id;
//     $user_info = get_userdata($myuser_id);
//     $items = $order->get_items();
//     foreach ($items as $item) {
//         // if ($item['product_id']==24) {
//         //   // Do something clever
//         // }
// 		$wpdb->insert( 
// 			'test', 
// 			array( 
// 				'test' => $item['product_id'],
// 				'user_id' => $user_id
// 			), 
// 			array( 
// 				'%s',
// 				'%s'
// 			) 
// 		);

//     }
//     //var_dump($items);

//     return $order_id;
// }	

/**
 * Add new register fields for WooCommerce registration.
 *
 * @return string Register fields HTML.
 */
function wooc_extra_register_fields() {
	?>

	<p class="form-row form-row-first">
	<label for="reg_billing_first_name"><?php _e( 'First name', 'woocommerce' ); ?> <span class="required">*</span></label>
	<input type="text" class="input-text" name="billing_first_name" id="reg_billing_first_name" value="<?php if ( ! empty( $_POST['billing_first_name'] ) ) esc_attr_e( $_POST['billing_first_name'] ); ?>" placeholder="This name will be used on your reports" />
	</p>

	<p class="form-row form-row-last">
	<label for="reg_billing_last_name"><?php _e( 'Last name', 'woocommerce' ); ?> <span class="required">*</span></label>
	<input type="text" class="input-text" name="billing_last_name" id="reg_billing_last_name" value="<?php if ( ! empty( $_POST['billing_last_name'] ) ) esc_attr_e( $_POST['billing_last_name'] ); ?>" placeholder="This name will be used on your reports" />
	</p>

	<p class="form-row form-row-last">
	<label for="reg_billing_phone"><?php _e( 'Contact number', 'woocommerce' ); ?> <span class="required">*</span></label>
	<input type="text" class="input-text" name="billing_phone" id="reg_billing_phone" value="<?php if ( ! empty( $_POST['billing_phone'] ) ) esc_attr_e( $_POST['billing_phone'] ); ?>" placeholder="This will be used if we need to contact you" />
	</p>

	<div class="clear"></div>

	<?php
}

add_action( 'woocommerce_register_form_start', 'wooc_extra_register_fields' );


/**
 * Validate the extra register fields.
 *
 * @param  string $username          Current username.
 * @param  string $email             Current email.
 * @param  object $validation_errors WP_Error object.
 *
 * @return void
 */
function wooc_validate_extra_register_fields( $username, $email, $validation_errors ) {
	if ( isset( $_POST['billing_first_name'] ) && empty( $_POST['billing_first_name'] ) ) {
		$validation_errors->add( 'billing_first_name_error', __( '<strong>Error</strong>: First name is required!', 'woocommerce' ) );
	}

	if ( isset( $_POST['billing_last_name'] ) && empty( $_POST['billing_last_name'] ) ) {
		$validation_errors->add( 'billing_last_name_error', __( '<strong>Error</strong>: Last name is required!', 'woocommerce' ) );
	}

	if ( isset( $_POST['billing_phone'] ) && empty( $_POST['billing_phone'] ) ) {
		$validation_errors->add( 'billing_phone_error', __( '<strong>Error</strong>: Contact number is required!', 'woocommerce' ) );
	}
}

add_action( 'woocommerce_register_post', 'wooc_validate_extra_register_fields', 10, 3 );


/**
 * Save the extra register fields.
 * https://support.woothemes.com/hc/en-us/articles/203182373-How-to-add-custom-fields-in-user-registration-on-the-My-Account-page
 * @param  int  $customer_id Current customer ID.
 *
 * @return void
 */
function wooc_save_extra_register_fields( $customer_id ) {
	if ( isset( $_POST['billing_first_name'] ) ) {
		// WordPress default first name field.
		update_user_meta( $customer_id, 'first_name', sanitize_text_field( $_POST['billing_first_name'] ) );

		// WooCommerce billing first name.
		update_user_meta( $customer_id, 'billing_first_name', sanitize_text_field( $_POST['billing_first_name'] ) );
	}

	if ( isset( $_POST['billing_last_name'] ) ) {
		// WordPress default last name field.
		update_user_meta( $customer_id, 'last_name', sanitize_text_field( $_POST['billing_last_name'] ) );

		// WooCommerce billing last name.
		update_user_meta( $customer_id, 'billing_last_name', sanitize_text_field( $_POST['billing_last_name'] ) );
	}

	if ( isset( $_POST['billing_phone'] ) ) {
		// WordPress default last name field.
		update_user_meta( $customer_id, 'billing_phone', sanitize_text_field( $_POST['billing_phone'] ) );
	}

	if ( isset( $_POST['email'] ) ) {
		// WordPress default last name field.
		update_user_meta( $customer_id, 'billing_email', sanitize_text_field( $_POST['email'] ) );
	}

}

add_action( 'woocommerce_created_customer', 'wooc_save_extra_register_fields' );


/**
 * Generate a survey URL after a user account has been created
 *
 * @param  int  $user_id
 * @param  array  $Override_Data - pass billing_first_name , billing_last_name and email to override (used for admin functions)
 *
 * @return void
 */
add_action( 'user_register', 'myplugin_registration_save', 10, 1 );

function myplugin_registration_save( $user_id, $Override_Data=false ) {

    if ( isset( $_POST['billing_first_name'] ) || $Override_Data ) {
        
		//Create users Survey URL
		require_once(ABSPATH.'/3rdparty/nusoap/nusoap.php');
		$DATE_ATOM = date("c");
        //Get survey URL from HA
        //$endpoint = "https://hatstest.harrisonassessments.com/customer/hr-xml/processAssessmentOrder";
        $endpoint = HA_ORDER;

        $client = new nusoap_client($endpoint);
        $client->operation = "ProcessAssessmentOrder";

        //Get data from user profile instead
        if($Override_Data){
			$fname = urlencode( get_user_meta($user_id, 'first_name', true) );
			$lname = urlencode( get_user_meta($user_id, 'last_name', true) );
			$email = get_user_meta($user_id, 'billing_email', true);
        }else{    
        	//Use posted data during account creation process
			$fname = urlencode( $_POST['billing_first_name'] );
			$lname = urlencode( $_POST['billing_last_name'] );

			if($_POST['email']){
				$email = $_POST['email'];
			}else{
				$email = $_POST['billing_email'];
			}
		}
				        

        $msg = 
       '<?xml version="1.0"?>
		<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/">
		       <SOAP-ENV:Header>
		          <wsse:Security SOAP-ENV:mustUnderstand="1" xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
		             <wsse:UsernameToken wsu:Id="UsernameToken-2" xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd">
		                <wsse:Username>'.HA_USER.'</wsse:Username>
		                <wsse:Password Type="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordText">'.HA_PASS.'</wsse:Password>
		                <wsse:Nonce EncodingType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-soap-message-security-1.0#Base64Binary">Dw/vWrB4CuO4wrORCpAXeA==</wsse:Nonce>
		                <wsu:Created>'.$DATE_ATOM.'</wsu:Created>
		             </wsse:UsernameToken>
		          </wsse:Security>
		       </SOAP-ENV:Header>
		       <SOAP-ENV:Body>
		      <!-- XML message here -->
		      <ProcessAssessmentOrder xmlns="http://www.hr-xml.org/3" xmlns:oa="http://www.openapplications.org/oagis/9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" systemEnvironmentCode="Production" releaseID="3.0" languageCode="en-US" xsi:schemaLocation="http://www.hr-xml.org/3 ../Developer/BODs/ProcessAssessmentOrder.xsd">
		        <oa:ApplicationArea>
		          <oa:CreationDateTime>'.$DATE_ATOM.'</oa:CreationDateTime>
		        </oa:ApplicationArea>
		        <DataArea>
		          <oa:Process>
		            <oa:ActionCriteria>
		              <oa:ActionExpression actionCode="Add">/ProcessAssessmentOrder/DataArea/AssessmentOrder</oa:ActionExpression>
		            </oa:ActionCriteria>
		          </oa:Process>
		          <AssessmentOrder>
		            <DocumentID>ForseeSurvey1</DocumentID>
		            <PackageID>Co0</PackageID>
		            <AssessmentSubject>
		              <SubjectID>'.$user_id.'</SubjectID>
		              <PersonName>
		                <oa:GivenName>'.$fname.'</oa:GivenName>
		                <MiddleName />
		                <FamilyName>'.$lname.'</FamilyName>
		              </PersonName>
		                <Communication>
		                  <ChannelCode>Email</ChannelCode>
		                  <oa:URI>'.$email.'</oa:URI>
		                </Communication>              
		            </AssessmentSubject>
		            <AssessmentLanguageCode>en-AU</AssessmentLanguageCode>
		          </AssessmentOrder>
		        </DataArea>
		      </ProcessAssessmentOrder>
		   </SOAP-ENV:Body>
		</SOAP-ENV:Envelope>';

        $result=$client->send($msg, $endpoint);     

		if(isset($result['faultcode'])) {
			user_error_log_custom( 'Error trying to create survey URL: '.$result['faultstring']['!'], $user_id);
		}else{

	        $DocumentID = $result['DataArea']['AssessmentOrder']['DocumentID'];
	        $SubjectID = $result['DataArea']['AssessmentOrder']['AssessmentSubject']['SubjectID'];
	        $URI = $result['DataArea']['AssessmentOrder']['AssessmentAccess']['AssessmentCommunication']['URI'];

	        update_user_meta($user_id, 'survey_url', $URI);
	        //Redirect user to survey: 
	        //wp_redirect( $URI, 302 ); exit;

	        //add user to Hubspot - fake form submit
	        $PropertiesArray['firstname']=$fname;
			$PropertiesArray['lastname']=$lname;
			$PropertiesArray['email']=$email;
			HS_Form_Submit('Create an account (WP internal usage only)','85007dc9-df18-438e-93b6-eba877e171e9',$PropertiesArray);

			//Update user as sync needed
			update_field( 'hubspot_sync_required', true, 'user_'.$user_id);				
			
		}


    }



}


function ajax_get_career_info_by_id() {
    // Career ID posted
    $CareerID = sanitize_text_field( $_POST["career_id"] );
    $user_id = get_current_user_id();
    $User_Products_Ordered = WC_What_Products_Have_They_Ordered(311); //311 = Career Enjoyment Report - Total QTY returned if matched
	$args = array(
		'posts_per_page'	=> 1,
		'post_type'		=> 'career_desc',
		'meta_key'		=> 'id_career',
		'meta_value'	=> $CareerID
	);
	$the_query = new WP_Query( $args );
	// var_dump($the_query);	
	// //var_dump($the_query->post);	
	// echo $the_query->post->post_title;
	// echo $the_query->post->post_content;



	//check if any requested reports exist
	global $user_ID;
	$requestedLink = '';
	$checkRequestedReports = get_field('report_career_enjoyment', 'user_'.$user_ID);
	if($checkRequestedReports) {
		$requestedLink = '<br /><br /><div class="button"><a href="/my-reports/" title="View all requested reports">View All Requested Reports</a></div>';
	}


	// the_field('id_career', $the_query->post->ID);

      if( $the_query->have_posts() ):

         while( $the_query->have_posts() ) : $the_query->the_post();

              	$ID = get_the_ID();
              	$userObj = get_userdata($user_id);
                echo "<h1>";the_title();echo "</h1>";

                /**********************************************/
                /** Contact Overlay
                /**********************************************/
                echo '<div class="contact-overlay">';

                	echo '<h4>Contact me about Courses</h4>';

                	$broker = get_field('broker', $ID);
                	//echo $broker->post_title; 
					// store variables to populate dynamic fields in hubspot form
					echo '<script type="text/javascript">';
					echo 'var hsCourse = "'.get_the_title().'";';
					echo 'var hsBroker = "'.$broker->post_title.'";';
					echo 'var hsEmail = "'.$userObj->user_email.'";';
					echo 'var checkIn = "<b>Yes</b> I am interested in this career and want to be contacted by '.$broker->post_title.' about available education and training courses.";';
					echo '</script>';
					// echo '<script type="text/javascript" src="assets/js/contact-hubspot.js"></script>';
     				//echo '<div class="contact-overlay-form"></div>';

                	echo '<div class="contact-overlay-form course-contact">';

	                	echo '<form id="contact'.get_the_ID().'" novalidate="" accept-charset="UTF-8" action="" enctype="multipart/form-data" method="POST" class="course-contact hs-form stacked" onsubmit="return fsContactCheck('.get_the_ID().');">';
						echo '<div class="optin">';
						echo '<input id="career_contact_me_opt_in-959e731e-1421-40ed-83eb-e3dc4992ddba" class="hs-input" type="checkbox" name="career_contact_me_opt_in" value="true" checked="">';
						echo '&nbsp;<b>Yes</b> I am interested in this career and want to be contacted by '.$broker->post_title.' about available education and training courses.';
						echo '<span class="required">*</span></div>';
						echo '<input value="'.get_the_title().'" name="career_contact_me_about_course" class="hs-input" type="hidden">';
						echo '<input value="'.$userObj->user_email.'" name="email" class="hs-input" type="hidden">';
						echo '<input value="'.$broker->post_title.'" name="course_broker_name" class="hs-input" type="hidden">';
						echo '<div class="course-input"><label for="">Phone Number <span class="required">*</span></label>';
						echo '<input value="" id="phone-959e731e-1421-40ed-83eb-e3dc4992ddba" class="hs-input" type="tel" name="phone" required="" placeholder=""></div>';
						echo '<div class="form-errors"></div>';
						echo '<div class="course-input"><input value="CONTACT ME" type="submit" class="hs-button primary large course-contact-submit"></div>';
						echo '</form>';

					echo '</div>';

                	echo '<div class="contact-broker-spiel">'.apply_filters('the_content', $broker->post_content).'</div>';

                	echo '<div class="contact-overlay-close" onclick="setModalDisplay(0);"></div>';

					echo '<div class="course-contact-overlay"><img src="/wp-admin/images/spinner-2x.gif" /></div>';

                echo '</div>';
                /**********************************************/
                /** End Contact Overlay
                /**********************************************/

                echo "<h2>Detailed Description</h2>";
                the_content(); 

				//the_field('id_career', $ID);
				echo "<h2>Salary information</h2>";
				$salary_info = get_field('salary_info', $ID);
			
				if($salary_info){
					echo '<p style="margin-bottom: 0px;">'.$salary_info.'</p>
						  <p style="font-size:10px;">* Salary ranges are provided as helpful guides only. Salaries may vary based skills, experience, location, employer and other variables.</p>
					';
				}else{
					echo '<p>There is no information on typical salary</p>';
				}    
				echo "<h2>Possible educational requirements</h2>
				<p>";  
				if(get_field('year_11_or_below', $ID)){
					echo 'Year 11 or Below<br/>';
				}
				if(get_field('year_12_or_equivalent', $ID)){
					echo 'Year 12 or Equivalent<br/>';
				}	
				if(get_field('licenses_vet_certificate_i_to_iii', $ID)){
					echo 'Licenses / VET Certificate I to III<br/>';
				}
				if(get_field('vet_certificate_iv_diploma_advanced_diploma', $ID)){
					echo 'VET Certificate IV / Diploma / Advanced Diploma<br/>';
				}	
				if(get_field('bachelors', $ID)){
					echo 'Bachelor&#39;s<br/>';
				}
				if(get_field('masters', $ID)){
					echo 'Master&#39;s<br/>';
				}	
				if(get_field('doctorate', $ID)){
					echo 'Doctorate<br/>';
				}	

				$link_to_course_category = get_field('link_to_course_category', $ID);
				if($link_to_course_category){					
					echo '<div class="button"><a href="/'.$link_to_course_category->taxonomy.'/'.$link_to_course_category->slug.'" >View Related Courses</a></div>';
				}

				$ceaImage = get_field('report_image', 1947);

				echo '
</p>';

if($broker !== null && $broker !== '')
	echo '<div class="button"><a href="" onclick="setModalDisplay(1);return false;" title="Contact me about courses">Contact me about courses</a></div>';
 
 echo '<div class="cea">
<img style="float:right;width:40%;margin:0 0 5% 5%;border:1px solid grey;" src="'.$ceaImage['url'].'" alt="Career Enjoyment Analysis Report">
<h2>Career Enjoyment Analysis</h2>
<p>Find out what you will and will not enjoy about this career. 
Career Enjoyment Analysis compares your career preferences and interests to the specific tasks related to work satisfaction and success for the above. </p></div>';																										
//Purchase report button - need to detect if they have purchased any before. If so we need to swap button for request report instead so we can pull it from HA API
// They might have mulitple reports they can request - Eg. 10 if they buy the career pack so check to see how many they have requested etc. 

//Show PDF link
//OR show order button - take to order page
//OR show request report if already purchased / have credits to request reports
//Show report credits - redeem process? 


		//Get the HA reports already assigned to this user (if we already have one that matches the ID display the view report button)
		$Career_Enjoyment_Report_Requested_Count = 0;
		$Matched_report_file = false;
		$WaitingForFileFromHA = false;
		if( have_rows('report_career_enjoyment', 'user_'.$user_id) ){
			$Career_Enjoyment_Report_Requested_Count = 0;
		 	// loop through the rows of data
		    while ( have_rows('report_career_enjoyment', 'user_'.$user_id) ) {
		    	the_row();
		    	$Career_Enjoyment_Report_Requested_Count++;

		        // display a sub field value
		        $ha_career_enjoyment_id = get_sub_field('career_enjoyment_id');
		        $ha_career_enjoyment_file = get_sub_field('career_enjoyment_file');
		        $ha_ce_requested = get_sub_field('ce_requested');		        

		        //Retrive file from secure area and allow them to download the PDF
		        if($ha_career_enjoyment_id==$CareerID && $ha_career_enjoyment_file){
		        	$Matched_report_file = $ha_career_enjoyment_file;
		        }		       

		        if($ha_career_enjoyment_id==$CareerID && $ha_career_enjoyment_file==''){
		        	$WaitingForFileFromHA = true;
		        }

		    }
		    

		}

		//Do we have a report for this already? - Also need to accomodate for requests that have not yet been completed (waiting for file from HA)
		$Report_Requests_remaining = $User_Products_Ordered['product_match_qty']-$Career_Enjoyment_Report_Requested_Count;
		
		if($Matched_report_file){			
			echo '<div class="button"><a href="/download.php?report='.$Matched_report_file.'" title="Download CEA Report" target="_blank">Download CEA Report</a></div>';
		}elseif($WaitingForFileFromHA){
			echo '<div class="button" id="report_request_container">Waiting for report to generate... Please check back in a few minutes</div>
				<script type="text/javascript">
					startpoll("'.$CareerID.'");
				</script>
			';
		}elseif($User_Products_Ordered['product_match_qty'] && $Report_Requests_remaining>0){
			echo '<div class="button" id="report_request_container"><a id="request_report" href="#" rel="'.$CareerID.'" title="Request Report">REQUEST CEA FOR THIS CAREER</a>';
			echo '</div>';
			//Show remaining number or reports they can request:
			if($User_Products_Ordered['product_match_qty']){
				if($Career_Enjoyment_Report_Requested_Count<$User_Products_Ordered['product_match_qty']){
					//echo '<br/>You can still request '.$Report_Requests_remaining.' reports';
					echo 'You have '.$Report_Requests_remaining.' Career Enjoyment Analysis reports remaining';
				}
			}


		}else{
			echo '<div class="button"><a href="/pricing-and-packages/" title="Purchase Report">Purchase Report</a></div>';
		}



          endwhile;

       endif;

       echo $requestedLink;

        wp_reset_query();  // Restore global post data stomped by the_post(). 

	die(); //otherwise returns zero
}
add_action( 'wp_ajax_get_career_details', 'ajax_get_career_info_by_id' );    // User must be logged in to use
//add_action( 'wp_ajax_nopriv_get_career_details', 'my_ajax_callback_function' );    // If called from front end

function ajax_get_fav_heart_action() {
	global $wpdb;
	$user_id = get_current_user_id();
	$CareerID = sanitize_text_field( $_POST["career_id"] );
	$favstatus = sanitize_text_field( $_POST["favstatus"] );
	
	//Check for stuff
	if(!$user_id && !$CareerID && !$favstatus){
		exit();
	}

	//Need to update to change to 1 in DB
	if($favstatus=="fav favyes"){
		$ChangeFav_to = 0;
	}else{
		$ChangeFav_to = 1;
	}

	$wpdb->update( 
		'ha_data', 
		array( 
			'favourite' => $ChangeFav_to
		), 
		array( 'user_id' => $user_id, 
			   'meta_id'=> $CareerID ), 
		array( 
			'%s'
		), 
		array( '%d', '%s' ) 
	);

	//Update user as sync needed
	update_field( 'hubspot_sync_required', true, 'user_'.$user_id);	
	
	die(); //otherwise returns zero
}
add_action( 'wp_ajax_heart_fav', 'ajax_get_fav_heart_action' );    // User must be logged in to use


//Figure out products ordered
//Figure out products ordered
//Figure out products ordered - You can also pass a product ID to get total qty ordered - Used for
function WC_What_Products_Have_They_Ordered($Product_ID=false, $user_id=false) {
	$total_qty_array = array();	
	$total_qty_array['product_match_qty']=false;
	$total_qty_array['pack_counter']=0; //If they have upgraded we need to subtract 10 reports from Career enjoyment report count

	if(!$user_id){
		$user_id = get_current_user_id();
	}

	$customer_orders = get_posts( apply_filters( 'woocommerce_my_account_my_orders_query', array(
	  //'numberposts' => $order_count,
	  'numberposts' => 500,
	  'meta_key'    => '_customer_user',
	  'meta_value'  => $user_id,
	  'post_type'   => wc_get_order_types( 'view-orders' ),
	  'post_status' => array_keys( wc_get_order_statuses() )
	) ) );

	//var_dump($customer_orders);
	//Process all orders and tally up the qty for each product
	if($customer_orders){
		foreach($customer_orders as $customer_order){						
			$order = new WC_Order( $customer_order->ID );
			$items = $order->get_items();
			if($items){
				foreach($items as $item){
					$total_qty_array['all'][$item['product_id']]['requested_report_ha']=false;
					$total_qty_array['all'][$item['product_id']]['product_id']=$item['product_id'];
					$total_qty_array['all'][$item['product_id']]['name']=$item['name'];
					if( isset($total_qty_array['all'][$item['product_id']]['qty']) ){
						$total_qty_array['all'][$item['product_id']]['qty']+=$item['qty'];
					}else{
						$total_qty_array['all'][$item['product_id']]['qty']=$item['qty'];
					}
					
					//Check for multiple product packs (upgraded user)
					if($item['product_id']==1927 || $item['product_id']==315){
						$total_qty_array['pack_counter']++;
					}					
					
				}
			}
		}
	}
	
	//If upgraded user (multipe packs) subtract 10 reports from Career enjoyment report count
	if($total_qty_array['pack_counter']>1){
		$total_qty_array['all'][311]['qty'] = $total_qty_array['all'][311]['qty'] - 10;
	}

	//Check for match for the requested product ID - needs to happen after loop to get the total qty
	if($Product_ID){
		if($total_qty_array['all'][$Product_ID]){
			$total_qty_array['product_match_qty']=$total_qty_array['all'][$Product_ID]['qty'];
		}
	}

	return $total_qty_array;
}


function ajax_request_report() {
	//Can only request Cc_ reports
	$User_subject_id = get_current_user_id();
	$CareerID = sanitize_text_field( $_POST["career_id"] );
	$Number_of_reports_already_requested = Career_Enjoyment_Report_Request_Count();
	//$report_type = sanitize_text_field( $_POST["report_type"] );

	//Check to make sure they have purchased Cc_ reports
	if( !WC_What_Products_Have_They_Ordered(311) ) {
		echo 'Error: Please purchase report';
		die();
	}

	//Check to see if they have already ordered more than they have purchased
	$CC_ordered_count = WC_What_Products_Have_They_Ordered(311);
	if($Number_of_reports_already_requested>=$CC_ordered_count['product_match_qty']){
		echo 'Error: Requested to many reports - Please purchase more first';
		die();		
	}
//if($Career_Enjoyment_Report_Requested_Count<$User_Products_Ordered['product_match_qty']){

	$Dupe_check = 0;
	//Check for exisiting
	if( have_rows('report_career_enjoyment', 'user_'.$User_subject_id) ){

	 	// loop through the rows of data
	    while ( have_rows('report_career_enjoyment', 'user_'.$User_subject_id) ) {
	    	the_row();

	        // display a sub field value
	        $subtomatch = get_sub_field('career_enjoyment_id');
	       
	        if($subtomatch==$CareerID){
	        	$Dupe_check++;
	        	//Found an existing record - Update exisitng record instead.
	        	//echo 'dupe found for Cc';
	        	//exit();
	        	//update_sub_field('career_enjoyment_file', $PDF_Report_FN);
	        }

	    }

	}

	//Dupe check no match - Insert record
	if($Dupe_check==0){
		
		//Request a report here
		$HA_Request_Report = SOAP_request_report_from_HA($User_subject_id, $CareerID, 'Cc_' );

		if($HA_Request_Report){
			//Insert the report into database (should only need to update really as when the request it will insert the initial record then)
			$field_key = "field_5657dffe586cc"; //Get this ID from the wordpress Post table assigned to the main repeater record
			$value = get_field($field_key, 'user_'.$User_subject_id);
			$value[] = array("career_enjoyment_id" => $CareerID, "career_enjoyment_file" => "", "ce_requested" => true);
			$updateit = update_field( $field_key, $value, 'user_'.$User_subject_id );			
		}		
	}

	//Update user as sync needed
	update_field( 'hubspot_sync_required', true, 'user_'.$User_subject_id);	
	
	echo '<br /><img src="/wp-admin/images/loading.gif" /> Your report has been requested, it can take a few minutes to generate so check back soon. You can request other reports in the mean time.<br /><br />';

	die(); //otherwise returns zero
}
add_action( 'wp_ajax_request_report', 'ajax_request_report' );    // User must be logged in to use




function SOAP_request_report_from_HA($Subject_ID, $Report_ID, $Report_Prefix=false ) {
	if($Report_Prefix){
		$Report_ID = $Report_Prefix.$Report_ID;
	}
    
	//Create users Survey URL
	require_once(ABSPATH.'/3rdparty/nusoap/nusoap.php');
	$DATE_ATOM = date("c");
    //Get survey URL from HA
    //$endpoint = "https://hatstest.harrisonassessments.com/customer/hr-xml/processAssessmentReport";
    $endpoint = HA_REPORT;
    $client->operation = "processAssessmentReport"; 
    $client = new nusoap_client($endpoint);        

    $msg = 
   '<?xml version="1.0"?>
	<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/">
	       <SOAP-ENV:Header>
	          <wsse:Security SOAP-ENV:mustUnderstand="1" xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
	             <wsse:UsernameToken wsu:Id="UsernameToken-2" xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd">
	                <wsse:Username>'.HA_USER.'</wsse:Username>
	                <wsse:Password Type="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordText">'.HA_PASS.'</wsse:Password>
	                <wsse:Nonce EncodingType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-soap-message-security-1.0#Base64Binary">Dw/vWrB4CuO4wrORCpAXeA==</wsse:Nonce>
	                <wsu:Created>'.$DATE_ATOM.'</wsu:Created>
	             </wsse:UsernameToken>
	          </wsse:Security>
	       </SOAP-ENV:Header>
	       <SOAP-ENV:Body>
	      <!-- XML message here -->
	        <ProcessAssessmentReport xmlns="http://www.hr-xml.org/3" xmlns:oa="http://www.openapplications.org/oagis/9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" languageCode="en-US" releaseID="3.0" systemEnvironmentCode="Production" xsi:schemaLocation="http://www.hr-xml.org/3 ../Developer/BODs/ProcessAssessmentReport.xsd">
	          <oa:ApplicationArea>
	            <oa:CreationDateTime>'.$DATE_ATOM.'</oa:CreationDateTime>
	          </oa:ApplicationArea>
	          <DataArea>
	            <oa:Process>
	              <oa:ActionCriteria>
	                 <oa:ActionExpression actionCode="Add">/ProcessAssessmentReport/DataArea/AssessmentReport</oa:ActionExpression>
	              </oa:ActionCriteria>
	            </oa:Process>
	            <AssessmentReport>
	              <DocumentID>ForseeSurvey1</DocumentID>
	              <OrderID>'.$Report_ID.'</OrderID>
	              <AssessmentSubject>
	                <SubjectID>'.$Subject_ID.'</SubjectID>
	              </AssessmentSubject>
	            </AssessmentReport>
	          </DataArea>
	        </ProcessAssessmentReport>
	   </SOAP-ENV:Body>
	</SOAP-ENV:Envelope>';

    $result=$client->send($msg, $endpoint);

	$actionCode = $result['DataArea']['Acknowledge']['ResponseCriteria']['ResponseExpression']['!actionCode'];
	$SuccessOrFail = $result['DataArea']['AssessmentReport']['AssessmentResult']['AssessmentStatus']['AssessmentStatusCode'];
    
    if($SuccessOrFail=="Error"){
    	$ErrorMessage = "HA API: ".$result['DataArea']['AssessmentReport']['AssessmentResult']['AssessmentStatus']['Description'];
    	user_error_log_custom($ErrorMessage, $Subject_ID);
    	return false;
    }elseif($actionCode=='Accepted'){
		//Update user as sync needed
		update_field( 'hubspot_sync_required', true, 'user_'.$Subject_ID);    	
    	return true;
    }else{
    	$ErrorMessage = "HA API: Did not get a recognised response from HA.";
    	user_error_log_custom($ErrorMessage, $Subject_ID);    	
    	return false;
    }

}


function user_error_log_custom($Message,$user_id=false) {
	if(!$user_id){
		$user_id = get_current_user_id();
	}	
	//Log a custom error for the user
	if($Message){
		$field_key = "field_56650c31ee4a3"; //Get this ID from the wordpress Post table assigned to the main repeater record
		$value = get_field($field_key, 'user_'.$user_id);
		$value[] = array("ha_error_message" => $Message, "ha_error_timestamp" => date("Y-m-d H:i:s") );
		update_field( $field_key, $value, 'user_'.$user_id );	 
		return true;
	}else{
		return false;
	}

}



function Career_Enjoyment_Report_Request_Count() {
	$user_id = get_current_user_id();
	$Career_Enjoyment_Report_Requested_Count = 0;
	if( have_rows('report_career_enjoyment', 'user_'.$user_id) ){
		$Career_Enjoyment_Report_Requested_Count = 0;
	 	// loop through the rows of data
	    while ( have_rows('report_career_enjoyment', 'user_'.$user_id) ) {
	    	the_row();
	    	$Career_Enjoyment_Report_Requested_Count++;
	    }	    
	}
	return $Career_Enjoyment_Report_Requested_Count;
}



function ajax_check_report_generated() {
    // Career ID posted
    $CareerID = sanitize_text_field( $_POST["career_id"] );
    $user_id = get_current_user_id();
    $Json_Array_to_return = array();
    $Json_Array_to_return['foundreport'] = false;

	//Check for exisiting
	if( have_rows('report_career_enjoyment', 'user_'.$user_id) ){

	 	// loop through the rows of data
	    while ( have_rows('report_career_enjoyment', 'user_'.$user_id) ) {
	    	the_row();

	        // display a sub field value
	        $subtomatch = get_sub_field('career_enjoyment_id');
	        
			//Match the ID's	       
	        if($subtomatch==$CareerID){
	        	//Now look for a file
	        	$Check_for_file = get_sub_field('career_enjoyment_file');
	        	if($Check_for_file){
	        		$Json_Array_to_return['foundreport'] = '<div class="button"><a target="_blank" href="/download.php?report='.$Check_for_file.'" title="Download Report">Download Report</a></div>';
	        	}
	        }

	    }

	}
    
    echo json_encode($Json_Array_to_return);

    die();
}
add_action( 'wp_ajax_check_report_generated', 'ajax_check_report_generated' );    // User must be logged in to use


// After payment received
function forsee_woocommerce_payment_complete( $order_id ) {
	$user_id = get_current_user_id();
	$survey_completed = get_user_meta( $user_id, 'survey_completed', true ); 

	//Need check to see if they have completed survey before requesting reports (eg. If they purcahse before creating an account)

	//Check for packs first and make those calls - then reprocess and call reports
	if($order_id && $user_id && $survey_completed){					
		$order = new WC_Order( $order_id );
		$items = $order->get_items();
		$allow_report_requests = true;

		if($items){
			//Process once to check for packs
			foreach($items as $item){				
				//If they are ordering a pack call HA API with this first
				if($item['product_id']==315 || $item['product_id']==1927){
					error_log( "Pack Requested: ".$item['product_id'], 0 );
					$Pack_Request = SOAP_request_report_from_HA($user_id, 'Ccp' );
					if(!$Pack_Request){
						error_log( "Failed to request pack from HA: ".$item['product_id'], 0 );
						$allow_report_requests = false;
					}
				}
			}

			//Request reports individually
			if($allow_report_requests){
				foreach($items as $item){	
					$AttachementID=false;	

					//Request Co1 & Co2		
					if($item['product_id']==309){
						error_log( "Pack Requested: ".$item['product_id'], 0 );
						$Pack_Request = SOAP_request_report_from_HA($user_id, 'Co1' );
						if(!$Pack_Request){
							error_log( "Failed to request pack from HA: ".$item['product_id'], 0 );
						}						
						$Pack_Request_Co2 = SOAP_request_report_from_HA($user_id, 'Co2' );
						if(!$Pack_Request_Co2){
							error_log( "Failed to request pack from HA: ".$item['product_id'], 0 );
						}else{
							$AttachementID='Co2';
						}
					}

					//Request Cd	
					if($item['product_id']==310){
						error_log( "Pack Requested: ".$item['product_id'], 0 );
						$Pack_Request = SOAP_request_report_from_HA($user_id, 'Cd' );
						if(!$Pack_Request){
							error_log( "Failed to request pack from HA: ".$item['product_id'], 0 );
						}else{
							$AttachementID='Cd';
						}				
					}

					//Request Ygs	
					if($item['product_id']==455){
						error_log( "Pack Requested: ".$item['product_id'], 0 );
						$Pack_Request = SOAP_request_report_from_HA($user_id, 'Ygs' );
						if(!$Pack_Request){
							error_log( "Failed to request pack from HA: ".$item['product_id'], 0 );
						}else{
							$AttachementID='Ygs';
						}						
					}	

					//Request Pg (paradox graphs)	
					if($item['product_id']==312){
						error_log( "Pack Requested: ".$item['product_id'], 0 );
						$Pack_Request = SOAP_request_report_from_HA($user_id, 'Pg1' );
						if(!$Pack_Request){
							error_log( "Failed to request pack from HA: ".$item['product_id'], 0 );
						}else{
							$AttachementID='Pg1';
						}						
					}		

					//Save report to user profile
					if($AttachementID=='Cd' || $AttachementID=='Ygs' || $AttachementID=='Co2' || $AttachementID=='Pg1'){

						$Dupe_check = 0;
						//Check for exisiting so we don't dupe up
						if( have_rows('ha_reports', 'user_'.$user_id) ){

						 	// loop through the rows of data
						    while ( have_rows('ha_reports', 'user_'.$user_id) ) {
						    	the_row();

						        // display a sub field value
						        $subtomatch = get_sub_field('ha_report_id');
						       
						        if($subtomatch==$AttachementID){
						        	$Dupe_check++;
						        	//Found an existing record - Update exisitng record instead.
						        	//update_sub_field('ha_report_file', $PDF_Report_FN);
						        	//error_log( "User $user_id already had report: ".$AttachementID, 0 );
						        	user_error_log_custom("User $user_id already had report: ".$AttachementID);
						        }

						    }

						}

						//Dupe check no match - Insert new record
						if($Dupe_check==0){
							//If it doesn't exisit 
							//Insert the report into database (should only need to update really as when the request it will insert the initial record then)
							$field_key = "field_565bd7de16c68"; //Get this ID from the wordpress Post table assigned to the main repeater record
							$value = get_field($field_key, 'user_'.$user_id);
							// var_dump($value);
							//$value[] = array("ha_report_id" => $AttachementID, "ha_report_file" => '', "ha_report_requested" => true);
							$value[] = array("ha_report_id" => $AttachementID, "ha_report_file" => '', "ha_report_requested" => true);
							update_field( $field_key, $value, 'user_'.$user_id );			

						}

					}						





				}				
			}
		}

	}



	//If they have not ordered a pack give them a coupon credit towards it which equals the total of the normal reports they have ordered (coupon can only use for packs)

	$order = new WC_Order( $order_id );	
	$user_id = $order->user_id;
	$email = $order->billing_email;
	$total = $order->get_total();
	$items = $order->get_items();
	$Generate_coupon = true;
	$Generate_coupon_for_Pack_UPGRADE = false;

	if($items && $total > 0 && $total <= 139){
		//Process once to check for packs
		foreach($items as $item){	
			//If they are ordering a pack call HA API with this first
			//if($item['product_id']==315 || $item['product_id']==1927){
			if($item['product_id']==315){
				//echo 'we found a pack';
				$Generate_coupon_for_Pack_UPGRADE = true;
				$Amount_Generate_coupon_for_Pack_UPGRADE = $item['line_total'] + $item['line_tax'];
			}
			//Did they order anything else that isn't one of the single reports - if they do we can not use total for coupon credit
			if($item['product_id']!=='312' && $item['product_id']!=='311' && $item['product_id']!=='310' && $item['product_id']!=='309'){
				$Generate_coupon = false;
				//user_error_log_custom( 'coupon '.$item['product_id'], $user_id);
			}

		}

		//Create a coupon they can use to upgrade to the Paradox pack
		if($Generate_coupon_for_Pack_UPGRADE){

			$coupon_code = 'Pack_Discount_'.$user_id.'_'.uniqid(); // Code - UserID + a unique code
			$amount = $Amount_Generate_coupon_for_Pack_UPGRADE; // Amount
			$discount_type = 'fixed_cart'; // Type: fixed_cart, percent, fixed_product, percent_product
								
			$coupon = array(
				'post_title' => $coupon_code,
				'post_content' => 'Discount credit if you decide to upgarde to a pack.',
				'post_excerpt' => 'Discount credit if you decide to upgarde to a pack.',
				'post_status' => 'publish',
				'post_author' => 1,
				'post_type'		=> 'shop_coupon'
			);
								
			$new_coupon_id = wp_insert_post( $coupon );
								
			// Add meta
			update_post_meta( $new_coupon_id, 'discount_type', $discount_type );
			update_post_meta( $new_coupon_id, 'coupon_amount', $amount );
			update_post_meta( $new_coupon_id, 'individual_use', 'yes' );
			update_post_meta( $new_coupon_id, 'minimum_amount', '' );
			update_post_meta( $new_coupon_id, 'maximum_amount', '' );
			update_post_meta( $new_coupon_id, 'product_ids', '1927' );
			update_post_meta( $new_coupon_id, 'exclude_product_ids', '' );
			update_post_meta( $new_coupon_id, 'usage_limit', '1' );
			update_post_meta( $new_coupon_id, 'usage_limit_per_user', '1' );
			update_post_meta( $new_coupon_id, 'limit_usage_to_x_items', '' );
			update_post_meta( $new_coupon_id, 'expiry_date', '' );
			update_post_meta( $new_coupon_id, 'customer_email', $email );
			update_post_meta( $new_coupon_id, 'apply_before_tax', '' );
			update_post_meta( $new_coupon_id, 'free_shipping', 'no' );
			update_post_meta( $new_coupon_id, 'product_categories', '' );
			update_post_meta( $new_coupon_id, 'exclude_product_categories', '' );			
			update_post_meta( $new_coupon_id, 'exclude_sale_items', 'no' );
			update_post_meta( $new_coupon_id, 'auto_generate_coupon', 'no' );
			update_post_meta( $new_coupon_id, 'coupon_title_prefix', '' );
			update_post_meta( $new_coupon_id, 'coupon_title_suffix', '' );
			update_post_meta( $new_coupon_id, 'sc_coupon_validity', '' );
			update_post_meta( $new_coupon_id, 'validity_suffix', 'days' );
			update_post_meta( $new_coupon_id, 'sc_is_visible_storewide', 'no' );
			update_post_meta( $new_coupon_id, 'sc_disable_email_restriction', 'no' );
			update_post_meta( $new_coupon_id, 'is_pick_price_of_product', 'no' );


		}


		//Generate coupon for only standard reports
		if($Generate_coupon){

			$coupon_code = 'Pack_Discount_'.$user_id.'_'.uniqid(); // Code - UserID + a unique code
			$amount = $total; // Amount
			$discount_type = 'fixed_cart'; // Type: fixed_cart, percent, fixed_product, percent_product
								
			$coupon = array(
				'post_title' => $coupon_code,
				'post_content' => 'Discount credit if you decide to upgarde to a pack.',
				'post_excerpt' => 'Discount credit if you decide to upgarde to a pack.',
				'post_status' => 'publish',
				'post_author' => 1,
				'post_type'		=> 'shop_coupon'
			);
								
			$new_coupon_id = wp_insert_post( $coupon );

			//Serialise email address
			//$email = 'ralph@test.com';
			//$email = maybe_serialize( $email );
								
			// Add meta
			update_post_meta( $new_coupon_id, 'discount_type', $discount_type );
			update_post_meta( $new_coupon_id, 'coupon_amount', $amount );
			update_post_meta( $new_coupon_id, 'individual_use', 'yes' );
			update_post_meta( $new_coupon_id, 'minimum_amount', '' );
			update_post_meta( $new_coupon_id, 'maximum_amount', '' );
			update_post_meta( $new_coupon_id, 'product_ids', '315,1927' );
			update_post_meta( $new_coupon_id, 'exclude_product_ids', '' );
			update_post_meta( $new_coupon_id, 'usage_limit', '1' );
			update_post_meta( $new_coupon_id, 'usage_limit_per_user', '1' );
			update_post_meta( $new_coupon_id, 'limit_usage_to_x_items', '' );
			update_post_meta( $new_coupon_id, 'expiry_date', '' );
			update_post_meta( $new_coupon_id, 'customer_email', $email );
			update_post_meta( $new_coupon_id, 'apply_before_tax', '' );
			update_post_meta( $new_coupon_id, 'free_shipping', 'no' );
			// update_post_meta( $new_coupon_id, 'product_categories', '69' );
			// update_post_meta( $new_coupon_id, 'exclude_product_categories', '68' );
			update_post_meta( $new_coupon_id, 'product_categories', '' );
			update_post_meta( $new_coupon_id, 'exclude_product_categories', '' );			
			update_post_meta( $new_coupon_id, 'exclude_sale_items', 'no' );
			update_post_meta( $new_coupon_id, 'auto_generate_coupon', 'no' );
			update_post_meta( $new_coupon_id, 'coupon_title_prefix', '' );
			update_post_meta( $new_coupon_id, 'coupon_title_suffix', '' );
			update_post_meta( $new_coupon_id, 'sc_coupon_validity', '' );
			update_post_meta( $new_coupon_id, 'validity_suffix', 'days' );
			update_post_meta( $new_coupon_id, 'sc_is_visible_storewide', 'no' );
			update_post_meta( $new_coupon_id, 'sc_disable_email_restriction', 'no' );
			update_post_meta( $new_coupon_id, 'is_pick_price_of_product', 'no' );
		}

	}



	//Update user as sync needed
	update_field( 'hubspot_sync_required', true, 'user_'.$user_id);

}
add_action( 'woocommerce_payment_complete', 'forsee_woocommerce_payment_complete');




//Perform a fake hubspot submit so we can track the user.
function HS_Form_Submit($pageName,$formGuid,$PropertiesArray)
{
	$Hubspot_Portal_ID = '493485';

    if(isset($_COOKIE['hubspotutk'])){
        $hubspotutk      = $_COOKIE['hubspotutk']; //grab the cookie from the visitors browser.
    }else{
        $hubspotutk      = '';
    }
    
    $ip_addr         = $_SERVER['REMOTE_ADDR']; //IP address too.
    $hs_context      = array(
        'hutk' => $hubspotutk,
        'ipAddress' => $ip_addr,
        'pageUrl' => (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]",
        'pageName' => $pageName
    );
    $hs_context_json = json_encode($hs_context);

    //Need to populate these varilables with values from the form.
    // $str_post = "firstname=" . urlencode($firstname) 
    //     . "&lastname=" . urlencode($lastname) 
    //     . "&email=" . urlencode($email) 
    //     . "&phone=" . urlencode($phonenumber) 
    //     . "&company=" . urlencode($company) 
    //     . "&hs_context=" . urlencode($hs_context_json); //Leave this one be

    //Add the Hubspot data to array 
    $PropertiesArray['hs_context'] = $hs_context_json;
    $str_post = http_build_query($PropertiesArray);

    //replace the values in this URL with your portal ID and your form GUID
    $endpoint = 'https://forms.hubspot.com/uploads/form/v2/'.$Hubspot_Portal_ID.'/'.$formGuid;

    $ch = @curl_init();
    @curl_setopt($ch, CURLOPT_POST, true);
    @curl_setopt($ch, CURLOPT_POSTFIELDS, $str_post);
    @curl_setopt($ch, CURLOPT_URL, $endpoint);
    @curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/x-www-form-urlencoded'
    ));
    @curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response    = @curl_exec($ch); //Log the response from HubSpot as needed.
    $status_code = @curl_getinfo($ch, CURLINFO_HTTP_CODE); //Log the response status code
    @curl_close($ch);
    //echo $status_code . " " . $response;
    //echo $status_code;
    if (substr($status_code, 0, 1) === '2') {
        //checks for 200 http code
        return true;
    }else{
        return false;
    }
}


function Login_do_extra_stuff()
{
	//Are we getting by username or email? 
    if(filter_var($_POST['username'], FILTER_VALIDATE_EMAIL)) {
        // valid address
        $get_by = 'email';
    }
    else {
        // invalid address
        $get_by = 'login';
    }	

	$user = get_user_by($get_by,$_POST['username']);

	//Submit to Hubspot 
	$PropertiesArray['email']=$user->user_email;
	HS_Form_Submit('User login (WP internal usage only)','b11c24d9-ba5d-47c9-98bb-bf00531e71b5',$PropertiesArray);

	//Update user as sync needed
	update_field( 'hubspot_sync_required', true, 'user_'.$user->ID);


}
add_action('wp_login', 'Login_do_extra_stuff');


function Check_ha_report_generated($report_id=false)
{
	$user_id = get_current_user_id();
	$return_array['ha_report'] = false;
	$return_array['ha_report_id'] = false;
	$return_array['ha_report_file'] = false;

	if( have_rows('ha_reports', 'user_'.$user_id) ){

	 	// loop through the rows of data
	    while ( have_rows('ha_reports', 'user_'.$user_id) ) {
	    	the_row();

	        // display a sub field value
	        $ha_report_id = get_sub_field('ha_report_id');
	        //$ha_report_file = get_sub_field('ha_report_file');
	       
	        if($ha_report_id==$report_id){

	        	//Found an existing record
	        	//error_log( "User $user_id already had report: ".$AttachementID, 0 );
	        	//user_error_log_custom("User $user_id already had report: ".$AttachementID);
	        	$return_array['ha_report'] = true;
	        	$return_array['ha_report_id'] = $ha_report_id;

	        	if(get_sub_field('ha_report_file')){
	        		$return_array['ha_report_file'] = get_sub_field('ha_report_file');
	        	}

	        }

	    }

	}

	return $return_array;
}


function ha_data_response_check()
{
	global $wpdb;
	$user_id = get_current_user_id();
	$array_to_return['count']= 0;
	$array_to_return['all_results']= false;

    $courses = $wpdb->get_results(
      $wpdb->prepare( "SELECT * FROM `ha_data` WHERE `user_id` = %s ORDER BY `meta_score` DESC", $user_id )
    );

    if($courses){
    	$array_to_return['count'] = count ( $courses );
    	$array_to_return['all_results'] = $courses;
    }
    return $array_to_return;
}


function Get_primary_reports_requested($user_id=false,$exclude_report=false)
{
	//Returns an array of ha_reports reports requested (No report_career_enjoyment reports)
	if(!$user_id){
		$user_id = get_current_user_id();
	}	

	$report_array = array();

	if( have_rows('ha_reports', 'user_'.$user_id) ){

	 	// loop through the rows of data
	    while ( have_rows('ha_reports', 'user_'.$user_id) ) {
	    	the_row();
	        // display a sub field value
	        $ha_report_id = get_sub_field('ha_report_id');
	        $ha_report_file = get_sub_field('ha_report_file');
	        $ha_report_requested = get_sub_field('ha_report_requested');
	        if($exclude_report){
	        	//Skip YGS
	        }else{
	        	$report_array[$ha_report_id] = array('ha_report_requested'=>$ha_report_requested, 'ha_report_file'=>$ha_report_file);
	        }
	    }

	}	
	return $report_array;
}

function Insert_primary_report($user_id=false,$data_array)
{
	$field_key = "field_565bd7de16c68"; //Get this ID from the wordpress Post table assigned to the main repeater record
	$value = get_field($field_key, 'user_'.$user_id);
	$value[] = $data_array;
	update_field( $field_key, $value, 'user_'.$user_id );		
}





add_filter( 'gettext', 'forsee_register_form', 10, 2 );
function forsee_register_form( $custom_translation, $login_texts ) {

    if ( 'Register' == $login_texts ) { return 'Start free trial'; } // Login Button
    return $login_texts;

}

//Add extra action buttons to admin area
//add_action( 'show_user_profile', 'my_show_extra_profile_fields' );
add_action( 'edit_user_profile', 'user_admin_extra_buttons' );

function user_admin_extra_buttons( $user ) { 

	//Build career enjoyment drop down selections
	$CE_Options = '';
	$args = array(
		'posts_per_page'  => -1,
		'post_type'   => 'career_desc',
		//'meta_key'    => $filtercareers,
		//'meta_value'  => 'true',
		);

	$the_query = new WP_Query( $args );       

	if( $the_query->have_posts() ): 
		while( $the_query->have_posts() ) : $the_query->the_post(); 
			// the_title(); 
			$Get_id_career = trim(get_field('id_career', $the_query->post->ID));
			$CE_Options.= '<option value="'.$Get_id_career.'">'.get_the_title().'</option>';
		endwhile;
	endif;
	wp_reset_query();  // Restore global post data stomped by the_post().		

	?>

	<h3>Force HA API (Overrides)</h3>

	<table class="form-table">
		<tr>
			<th><label for="surveyURLforce">Generate Survey URL</label></th>

			<td>
				<a id="force_survey_url" href="#" class="acf-button blue force_survey_url" data-event="force-survey_url">Force Survey URL</a><br />
				<span id="message_force_survey_url" class="description">Min info: Email, first name, last name. User ID: <?php echo esc_attr(  $user->ID ); ?></span>
			</td>
		</tr>

		<tr>
			<th><label for="surveyURLforce">Request a report</label></th>

			<td>

				<?php
					$survey_completed = get_user_meta( $user->ID, 'survey_completed', true ); 
					if($survey_completed){
						?>
						<select name="force_report_request" id="select_a_report">
						  <option value="" disabled selected>Please select</option>
						  <option value="Ccp">Career Pack (triggers bulk discount only)</option>
						  <option value="Co">Career Option (Co1 &amp; Co2) </option>
						  <option value="Cd">Career Development (Cd)</option>
						  <option value="Ygs">Your Greatest Strenghts</option>
						  <option value="Pg1">Paradox Graphs (PG1)</option>
						  <option value="Cc_">Career enjoyment (must provide additional ID)</option>
						</select>

						<select name="CE_force_report_request" id="select_a_CE_force_report_request" hidden>
						  <option value="" disabled selected>Please select</option>
						  <?php echo $CE_Options;?>
						</select>						
						<a id="force_report_request" href="#" class="acf-button blue force_report_request">Force Report Request</a>
						<?php
					}
				?>				

				<br />
				<span id="message_report_request" class="description">User must of completed survey before you can request reports</span>
			</td>
		</tr>		

	</table>
	<script type="text/javascript">
	   jQuery('#select_a_report').on('change',function(){
	        if( jQuery(this).val()==="Cc_"){
	        	jQuery("#select_a_CE_force_report_request").show()
	        }
	        else{
	        	jQuery("#select_a_CE_force_report_request").hide()
	        }
	    });

	   	//Force Report AJAX call
		jQuery('a.force_report_request').click(function(e) { 			

			jQuery('#message_report_request').html('<br /><img src="/wp-admin/images/loading.gif" /> Please wait while we request your report...<br /><br />');

			//var carID = $(this).attr('rel');
			var force_report_request_ID = jQuery( "#select_a_report" ).val();
			var force_report_request_CE_ID = jQuery( "#select_a_CE_force_report_request" ).val();

			//Validate the input before proceding
			if(!force_report_request_ID){
				alert('Please select a report');
				return false;
			}

			if(force_report_request_ID=='Cc_' && !force_report_request_CE_ID){
				alert('Please select a Cc_ ID');
				return false;
			}			

			jQuery.ajax({
				url: ajaxurl,
				data: {
					action: 'force_report_request',
					user_id: '<?php echo esc_attr( $user->ID ); ?>',
					force_report_request_ID: force_report_request_ID,
					force_report_request_CE_ID: force_report_request_CE_ID,
				},
				success: function(data) {
					jQuery('#message_report_request').html(data);
				},        
				type: 'POST'
			});
			e.preventDefault();
		});






	   	//Force Survey URL AJAX call
		jQuery('a.force_survey_url').click(function(e) { 			

			jQuery('#message_force_survey_url').html('<br /><img src="/wp-admin/images/loading.gif" /> Please wait while we request your report...<br /><br />');

			//var carID = $(this).attr('rel');
			jQuery.ajax({
				url: ajaxurl,
				data: {
					action: 'force_survey_url',
					user_id: '<?php echo esc_attr( $user->ID ); ?>',
					//report_type: 'cc'
				},
				success: function(data) {
					jQuery('#message_force_survey_url').html(data);
				},        
				type: 'POST'
			});

			//return false; // prevent default
			e.preventDefault();
		});

	</script>

<?php }

function ajax_force_survey_url() { 
	if( isset($_POST["user_id"]) && is_admin() ){
    	$user_id = $_POST["user_id"];
    	//Regenerate survey URL
    	myplugin_registration_save( $user_id, true );
    	echo 'Updated - Please reload the page and check the survey url field';
	}else{
		return false;
	}
	die();
}

add_action( 'wp_ajax_force_survey_url', 'ajax_force_survey_url' );    // User must be logged in to use



function ajax_force_report_request() { 
	if( isset($_POST["user_id"]) && is_admin() ){
    	$user_id = $_POST["user_id"];

    	$force_report_request_ID = $_POST["force_report_request_ID"];
    	$force_report_request_CE_ID = $_POST["force_report_request_CE_ID"];
    	$Report_Prefix=false;
    	if($force_report_request_ID=='Cc_'){
    		$Report_Prefix='Cc_';
    		$force_report_request_ID=$force_report_request_CE_ID;
    	}

    	//if Co - need to request 2 reports
    	if($force_report_request_ID=='Co'){
	    	//Request report
	    	$request = SOAP_request_report_from_HA($user_id, 'Co1', false );   
	    	$request = SOAP_request_report_from_HA($user_id, 'Co2', false );  		
    	}else{
	    	//Request report
	    	$request = SOAP_request_report_from_HA($user_id, $force_report_request_ID, $Report_Prefix );
	    }

	    if($request){
    		echo 'Report '.$Report_Prefix.$force_report_request_ID.' has been requested. Reload this page in a few minutes. ';
    	}else{
    		echo 'Something went wrong and we could not complete the request. Reload page and see error log.';
    	}
	}else{
		echo 'Something went wrong and we could not complete the request. Reload page and see error log.';
	}
	die();
}

add_action( 'wp_ajax_force_report_request', 'ajax_force_report_request' );    // User must be logged in to use


//Redirect user to Welcome page on login
function wc_custom_user_redirect( $redirect, $user ) {

	// if(wp_get_referer()==get_site_url().'/free-trial/' || wp_get_referer()==get_site_url().'/free-trial'){
	// 	return get_site_url().'/welcome/';	
	// }else{
	// 	return get_site_url().'/welcome/';	
	// }
	return get_site_url().'/welcome/';	

}
add_filter( 'woocommerce_login_redirect', 'wc_custom_user_redirect', 10, 2 );


//Redirect on initial rego
add_filter('woocommerce_registration_redirect', 'ps_wc_registration_redirect', 100, 2);
function ps_wc_registration_redirect( $redirect_to ) {
     //return '/welcome/';
	return '/thank-you/';
}

//Check survey has been completed
function ajax_check_survey_completed() { 
	$user_id = get_current_user_id();
	if($user_id){
		$survey_completed = get_user_meta( $user_id, 'survey_completed', true );  
		if($survey_completed){
			$array['survey_complete']='yes';
		}else{
			$array['survey_complete']='no';
		}
	}else{
		$array['survey_complete']='no';
	}
	echo json_encode($array);
	die();
}
add_action( 'wp_ajax_check_survey_completion', 'ajax_check_survey_completed' );    // User must be logged in to use

function logout_do_stuff() {
    // remove Hubspot cookies so user can register new leads / contacts
    if (isset($_SESSION['just_completed_survey']))
    {
        unset($_SESSION['just_completed_survey']);
    }
    // unset($_COOKIE['hubspotutk']);
    if(isset($_COOKIE['hubspotutk'])):
		//unset( $_COOKIE['hubspotutk'] );
    	setcookie( 'hubspotutk', '', time() - ( 15 * 60 ), '/', ".forsee.com.au");
	    setcookie( '__hssc', '', time() - ( 15 * 60 ), '/', ".forsee.com.au");
	    setcookie( '__hssrc', '', time() - ( 15 * 60 ), '/', ".forsee.com.au");
	    setcookie( '__hstc', '', time() - ( 15 * 60 ), '/', ".forsee.com.au");
	    setcookie( 'hsfirstvisit', '', time() - ( 15 * 60 ), '/', ".forsee.com.au");
    endif;    
}
add_action('wp_logout', 'logout_do_stuff', 1000, 2);

// Change the stripe icon
add_filter('woocommerce_gateway_icon', 'remove_payment_icons');

function remove_payment_icons( $url ) {
	$url = '';
	return $url;
}


?>