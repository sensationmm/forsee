<?php
/**
 * My Account page
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

wc_print_notices(); ?>

<div >
	<p class="myaccount_user">
		<?php
		printf(
			__( 'Hello <strong>%1$s</strong> (not %1$s? <a href="%2$s">Sign out</a>).', 'woocommerce' ) . ' ',
			$current_user->user_firstname.' '.$current_user->user_lastname,
			wc_get_endpoint_url( 'customer-logout', '', wc_get_page_permalink( 'myaccount' ) )
		);
		?>
	</p>

	<?php
		echo '<a class="button button-block" href="'.wc_customer_edit_account_url().'" title="Edit my account details">Edit account details</a>';
		echo '<a class="button button-block" href="'.wc_customer_edit_account_url().'" title="Change my password">Change password</a>';
	?>

	<?php do_action( 'woocommerce_before_my_account' ); ?>

	<?php wc_get_template( 'myaccount/my-downloads.php' ); ?>

	<?php wc_get_template( 'myaccount/my-orders.php', array( 'order_count' => $order_count ) ); ?>

	<?php //wc_get_template( 'myaccount/my-address.php' ); ?>

	<?php do_action( 'woocommerce_after_my_account' ); ?>
</div>
