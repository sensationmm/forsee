<?php

    if(!is_user_logged_in()) {
        wp_redirect('/login/');
    }
/**
* @package WordPress
* @subpackage Forsee 2015
* Template Name: CNS Pricing
*/
get_header();  
global $isCNS;
$isCNS = true;
?>

<?php include 'cns-header.php'; ?>

<div class="body">
    <article class="col-left">

    	<h2><?php echo ($display_title != '') ? $display_title : $pageObj->post_title; ?></h2>
        <?php echo apply_filters('the_content', $content); ?>
    </article>

    <article class="col-left">

    	<table class="highlights" cellspacing="1" cellpadding="0">
    	<tr><td>&nbsp;</td><td><div class="highlight">Most popular</div></td></tr>
    	</table>

    	<table class="pricing" cellspacing="1">
    	<tr><th>Career Pack</th><th>Know Yourself Pack</th></tr>
    	<tr class="price"><td>$69.90</td><td>$109.90</td></tr>
    	<tr><td>Career Options Report</td><td>Career Options Report</td></tr>
    	<tr><td>Career Development Report</td><td>Career Development Report</td></tr>
    	<tr><td>Career Enjoyment Report x 10 jobs</td><td>Career Enjoyment Report x 10 jobs</td></tr>
    	<tr><td><strike>Paradox Graphs Report</strike></td><td>Paradox Graphs Report</td></tr>
    	<tr><td><strike>3 x 1 hour Career Guidance sessions</strike></td><td><strike>3 x 1 hour Career Guidance sessions</strike></td></tr>
    	</table>
    	
    	<table class="pricing-buttons" cellspacing="1">
    	<tr><td><a href="">Add to Cart</a></td><td><a href="">Add to Cart</a></td></tr>
    	</table>

    	<table class="individual" cellspacing="0">
    	<tr><th colspan="3">Individual Reports</th></tr>
    	<tr><td>Your Greatest Strengths Report</td>
    		<td class="price">Free Trial</td>
    		<td class="checkbox"><form><input type="checkbox" /></form></td>
    	</tr>
    	<tr><td>Career Options Report</td>
    		<td class="price">$29.90</td>
    		<td class="checkbox"><form><input type="checkbox" /></form></td>
    	</tr>
    	<tr><td>Career Development Report</td>
    		<td class="price">$29.90</td>
    		<td class="checkbox"><form><input type="checkbox" /></form></td>
    	</tr>
    	<tr><td>Career Enjoyment Report</td>
    		<td class="price">$29.90</td>
    		<td class="checkbox"><form><input type="checkbox" /></form></td>
    	</tr>
    	<tr><td>Paradox Graphs<span>The ultimate tool to develop insight and emotional development</span></td>
    		<td class="price">$29.90</td>
    		<td class="checkbox"><form><input type="checkbox" /></form></td>
    	</tr>
    	</table>
    	
    	<table class="pricing-buttons" cellspacing="1">
    	<tr><td>&nbsp;</td><td><a href="">Add to Cart</a></td></tr>
    	</table>

    </article>

    <section class="col-right">
        <div class="cart">
        	<div class="cart-header">Summary</div>

        	<dl>
    		<dt>Career Options</dt><dd>$29.90</dd>
    		<dt>Paradox Graphs</dt><dd>$74.90</dd>
    		</dl>

        	<div class="cart-total">
        		<dl>
		    		<dt>Total</dt><dd>$115.28</dd>
		    	</dl>
		    </div>

		    <a class="button" href="">Proceed to checkout</a>

		    <div class="cart-payment"><img src="assets/images/icon-payment.gif" /></div>

        	Prices are in AUD and are GST inclusive.
        </div>
    </section>
</div>

<?php get_footer(); ?> 