<?php

	add_theme_support('menus');
	add_theme_support( 'post-thumbnails' );

	if ( function_exists('register_sidebar') )
		register_sidebar();

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
		  		'supports' => array('title'))
		);
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
	<input type="text" class="input-text" name="billing_first_name" id="reg_billing_first_name" value="<?php if ( ! empty( $_POST['billing_first_name'] ) ) esc_attr_e( $_POST['billing_first_name'] ); ?>" />
	</p>

	<p class="form-row form-row-last">
	<label for="reg_billing_last_name"><?php _e( 'Last name', 'woocommerce' ); ?> <span class="required">*</span></label>
	<input type="text" class="input-text" name="billing_last_name" id="reg_billing_last_name" value="<?php if ( ! empty( $_POST['billing_last_name'] ) ) esc_attr_e( $_POST['billing_last_name'] ); ?>" />
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
		$validation_errors->add( 'billing_last_name_error', __( '<strong>Error</strong>: Last name is required!.', 'woocommerce' ) );
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

}

add_action( 'woocommerce_created_customer', 'wooc_save_extra_register_fields' );


/**
 * Generate a survey URL after a user account has been created
 *
 * @param  int  $user_id
 *
 * @return void
 */
add_action( 'user_register', 'myplugin_registration_save', 10, 1 );

function myplugin_registration_save( $user_id ) {

    if ( isset( $_POST['billing_first_name'] ) ) {
        
    	//Create users Survey URL
		require_once(ABSPATH.'/3rdparty/nusoap/nusoap.php');
		$DATE_ATOM = date("c");
        //Get survey URL from HA
        $endpoint = "https://hatstest.harrisonassessments.com/customer/hr-xml/processAssessmentOrder";

        $client = new nusoap_client($endpoint);
        $client->operation = "ProcessAssessmentOrder";     

		$fname = urlencode( $_POST['billing_first_name'] );
		$lname = urlencode( $_POST['billing_last_name'] );
		$email = $_POST['email'];		        

        $msg = 
       '<?xml version="1.0"?>
		<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/">
		       <SOAP-ENV:Header>
		          <wsse:Security SOAP-ENV:mustUnderstand="1" xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
		             <wsse:UsernameToken wsu:Id="UsernameToken-2" xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd">
		                <wsse:Username>coursestarter</wsse:Username>
		                <wsse:Password Type="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordText">2password1</wsse:Password>
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
		            <DocumentID>1</DocumentID>
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

         //var_dump($result);

        $DocumentID = $result['DataArea']['AssessmentOrder']['DocumentID'];
        $SubjectID = $result['DataArea']['AssessmentOrder']['AssessmentSubject']['SubjectID'];
        $URI = $result['DataArea']['AssessmentOrder']['AssessmentAccess']['AssessmentCommunication']['URI'];

        update_user_meta($user_id, 'survey_url', $URI);
        //Redirect user to survey: 
        wp_redirect( $URI, 302 ); exit;


    }







}







?>