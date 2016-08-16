<?php
/**
 * Plugin Name: Cloudflare Developer Mode
 * Plugin URI: https://engagingcomms.com.au
 * Description: Easily set your website into development mode or clear the full sites cache
 * Version: 1.53
 * Author: Ralph Vugts
 * Author URI: https://engagingcomms.com.au
 * License: GPL2
 */

add_action('admin_menu', 'cf_dev_mode_menu');

function cf_dev_mode_menu() {
	add_menu_page('CF Dev Mode', 'Cloudflare Dev', 'administrator', 'cf_dev_mode-settings', 'cf_dev_mode_page', 'dashicons-admin-generic');
}

function cf_dev_mode_page() {
?>
<div class="wrap">
<h2>Cloudflare details</h2>

<form method="post" action="options.php">
    <?php settings_fields( 'my-plugin-settings-group' ); ?>
    <?php do_settings_sections( 'my-plugin-settings-group' ); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row">Cloudflare API Key:</th>
        <td><input type="password" name="api_key_ec_cf_dev_mode" value="<?php echo esc_attr( get_option('api_key_ec_cf_dev_mode') ); ?>" /></td>
        </tr>

        <tr valign="top">
        <th scope="row">Cloudflare Email Address:</th>
        <td><input type="text" name="email_ec_cf_dev_mode" value="<?php echo esc_attr( get_option('email_ec_cf_dev_mode') ); ?>" /></td>
        </tr>        
         
        <tr valign="top">
        <th scope="row">Domain name registered with Cloudflare:</th>
        <td><input type="text" name="domain_ec_cf_dev_mode" value="<?php echo esc_attr( get_option('domain_ec_cf_dev_mode') ); ?>" /></td>
        </tr>
        
    </table>
    
    <?php submit_button(); ?>

</form>
</div>
<hr/>
<h1>Clourflare Developer Mode:</h1>



<?php
if(get_option('email_ec_cf_dev_mode') && get_option('api_key_ec_cf_dev_mode') && get_option('domain_ec_cf_dev_mode')){

	$Just_disabled = false;

	include('class_cloudflare.php');
	$cf = new cloudflare_api(trim(get_option('email_ec_cf_dev_mode')), trim(get_option('api_key_ec_cf_dev_mode')));

	//Turn Dev mode on
	if (isset($_GET['my_nonce']) && wp_verify_nonce($_GET['my_nonce'], 'set_cf_to_dev_mode')) {
        $response = $cf->devmode(trim(get_option('domain_ec_cf_dev_mode')), 1);
        if($response){
            echo 'Developer mode has been enabled<br/><br/>';
        }else{
        	echo 'Something went wrong while trying to set dev mode, check your details above.<br/><br/>';
        }
	}

	//Turn Dev mode off
	if (isset($_GET['my_nonce']) && wp_verify_nonce($_GET['my_nonce'], 'disable_cf_dev_mode')) {
        $response = $cf->devmode(trim(get_option('domain_ec_cf_dev_mode')), 0);
        if($response){
            echo 'Developer mode has been disabled<br/><br/>';
            echo '<a href="'.wp_nonce_url(admin_url('admin.php?page=cf_dev_mode-settings'), 'set_cf_to_dev_mode', 'my_nonce').'">Turn Cloudflare Dev mode on</a>';
            $Just_disabled = true; //API lag form CF to avoid confuison
        }else{
        	echo 'Something went wrong while trying to set dev mode, check your details above.<br/><br/>';
        }
	}

	//Clear all cache 
	if (isset($_GET['my_nonce']) && wp_verify_nonce($_GET['my_nonce'], 'clear_cf_cache')) {
        $response = $cf->fpurge_ts( trim(get_option('domain_ec_cf_dev_mode')) );        
        if($response){
            echo 'The Cloudflare cache has been purged<br/><br/>';
            $Just_disabled = true; //API lag form CF to avoid confuison
        }else{
        	echo 'Something went wrong while trying to set dev mode, check your details above.<br/><br/>';
        }
	}

	if($Just_disabled==false){
	    $response = $cf->stats( trim( get_option('domain_ec_cf_dev_mode') ), $cf::INTERVAL_30_DAYS);    
	    $objs = $response->response->result->objs;
	    if($objs){
	        //echo $objs[0]->dev_mode;
	        if($objs[0]->dev_mode > 0){
	            echo 'Domain currenlty in Dev Mode. (expires automatically after 3 hours) OR ';
	            echo '<a href="'.wp_nonce_url(admin_url('admin.php?page=cf_dev_mode-settings'), 'disable_cf_dev_mode', 'my_nonce').'">Turn Cloudflare dev mode off again manually.</a><br/>';	            
	            echo '<br/><a href="'.wp_nonce_url(admin_url('admin.php?page=cf_dev_mode-settings'), 'clear_cf_cache', 'my_nonce').'">Clear all cache (use sparingly, takes 48 hours to re-cache)</a>';
	        }else{
	            echo '<a href="'.wp_nonce_url(admin_url('admin.php?page=cf_dev_mode-settings'), 'set_cf_to_dev_mode', 'my_nonce').'">Turn Cloudflare Dev mode on</a>';

	        }
	    }else{
	    	echo "Your details do not appear to be correct. Please check them and try again.";
	    }
	}


}



}

add_action( 'admin_init', 'cf_dev_mode_settings' );

function cf_dev_mode_settings() {
	register_setting( 'my-plugin-settings-group', 'api_key_ec_cf_dev_mode' );
	register_setting( 'my-plugin-settings-group', 'email_ec_cf_dev_mode' );
	register_setting( 'my-plugin-settings-group', 'domain_ec_cf_dev_mode' );
}

