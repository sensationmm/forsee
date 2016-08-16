<?php
/**
 * Customer new account email
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates/Emails
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


// load customer data and user email into email template
$survey_url='';
$user_email = $user_login;
$user       = get_user_by('login', $user_login);
if ( $user ) {
    $user_email = $user->user_email;
	$user_id = $user->ID;
	$survey_url = get_user_meta( $user_id, 'survey_url', true ); 
}

?>

<?php do_action( 'woocommerce_email_header', $email_heading ); ?>

<p><?php printf( __( "Hi %s.", 'woocommerce' ), $user->first_name.' '.$user->last_name ); ?></p>

<p><?php printf( __( "Thanks for registering with %s. <br/><br/>Your username is: <strong>%s</strong>.", 'woocommerce' ), esc_html( $blogname ), esc_html( $user_email ) ); ?></p>

<?php if ( get_option( 'woocommerce_registration_generate_password' ) == 'yes' && $password_generated ) : ?>

	<p><?php printf( __( "Your password is: <strong>%s</strong>", 'woocommerce' ), esc_html( $user_pass ) ); ?></p>

<?php endif; ?>

<p><?php printf( __( 'If you have not completed your questionnaire yet, you can access it here: %s.', 'woocommerce' ), $survey_url ); ?></p>

<p><?php printf( __( 'You can access your account settings to view your orders and change your password here: %s.', 'woocommerce' ), wc_get_page_permalink( 'myaccount' ) ); ?></p>

<?php do_action( 'woocommerce_email_footer' ); ?>