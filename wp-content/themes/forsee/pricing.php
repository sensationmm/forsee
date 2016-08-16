<?php
/**
* @package WordPress
* @subpackage Forsee 2015
* Template Name: Pricing
*/
get_header();  
global $isCNS, $woocommerce, $cart;
$cart = $woocommerce->cart->cart_contents;
$isCNS = true;

if(is_user_logged_in()) {
  include 'cns-header.php';
} else { ?>
    <div class="banner" style="background-image:url(<?php echo get_field('page_banner', $pageObj->ID); ?>);">
        <div class="body">
            <?php 
              $display_title = get_field('page_display_title', $pageObj->ID); 
              $strapline = get_field('page_strapline', $pageObj->ID); 
              $content = $pageObj->post_content;
            ?>

            <h1><?php echo ($display_title != '') ? $display_title : $pageObj->post_title; ?></h1>
            <p class="strap"><?php echo $strapline; ?></p>
        </div>
    </div>
<?php } ?>

<div class="body outer">
    <article class="col-left">

        <h2><?php echo ($display_title != '') ? $display_title : $pageObj->post_title; ?></h2>
        <?php echo apply_filters('the_content', $content); ?>
    </article>

    <article class="col-left">

        <table class="pricing" cellspacing="1">
        <tr><th>Career Options Report</th><th>Paradox Graph Report</th></tr>
        <tr class="price">
            <td>
            <?php
                global $woocommerce;
                $_product = wc_get_product( 309 );
                echo '&dollar;'.$_product->get_price();
            ?>
            </td>
            <td>
            <?php
                global $woocommerce;
                $_product = wc_get_product( 312 );
                echo '&dollar;'.$_product->get_price();
            ?>
            </td>
        </tr>
        <tr><td>Data driven Interactive report ranking your most suitable Careers based on your workplace task preferences and interests</td><td>The most accurate and dynamic tool available for behavioural assessment across twelve key workplace skills</td></tr>
        <tr><td>Displays predictive score, description, education requirements and salary data for 683 career options</td><td>Displays behavioural ranges, stress responses and areas to develop with a detailed explanation</td></tr>
        <tr><td>Avoid making bad career choices</td><td>10 minute Report Feedback</td></tr>
        </table>
        
        <table class="pricing-buttons" cellspacing="1">
        <tr valign="top">
            <td><a href="/pricing-and-packages/?add-to-cart=309" rel="nofollow" data-product_id="309" data-product_sku="Co1_Co2" data-quantity="1" class="button add_to_cart_button product_type_bundle product_type_simple ajax_add_to_cart">Add to cart</a></td>
            <td><a href="/pricing-and-packages/?add-to-cart=312" rel="nofollow" data-product_id="312" data-product_sku="Pg1" data-quantity="1" class="button add_to_cart_button product_type_bundle product_type_simple ajax_add_to_cart">Add to cart</a></td>
        </tr>
        </table>

        <table class="highlights" cellspacing="0" cellpadding="0">
        <tr><td>&nbsp;</td><td><div class="highlight">Most popular</div></td></tr>
        </table>

        <table class="pricing" cellspacing="1">
        <tr><th>Career Pack</th><th>Know Yourself Pack</th></tr>
        <tr class="price">
            <td>
            <?php
                global $woocommerce;
                $_product = wc_get_product( 315 );
                echo '&dollar;'.$_product->get_price();
            ?>
            </td>
            <td>
            <?php
                global $woocommerce;
                $_product = wc_get_product( 1927 );
                echo '&dollar;'.$_product->get_price();
            ?>
            </td>
        </tr>
        <tr><td>Career Options Report</td><td>Career Options Report</td></tr>
        <tr><td>Career Development Report</td><td>Career Development Report</td></tr>
        <tr><td>Career Enjoyment Report x 10 jobs</td><td>Career Enjoyment Report x 10 jobs</td></tr>
        <tr><td><strike>Paradox Graphs Report</strike></td><td>Paradox Graphs Report</td></tr>
        <tr><td>20 minute Report Feedback</td><td>30 minute Report Feedback</td></tr>
        </table>
        
        <table class="pricing-buttons" cellspacing="1">
        <tr valign="top">
            <td><a href="/pricing-and-packages/?add-to-cart=315" rel="nofollow" data-product_id="315" data-product_sku="Ccp" data-quantity="1" class="button add_to_cart_button product_type_bundle product_type_simple ajax_add_to_cart">Add to cart</a></td>
            <td><a href="/pricing-and-packages/?add-to-cart=1927" rel="nofollow" data-product_id="1927" data-product_sku="ccp + pg" data-quantity="1" class="button add_to_cart_button product_type_bundle product_type_simple ajax_add_to_cart">Add to cart</a></td>
        </tr>
        </table>

        <table class="individual" cellspacing="0">
        <tr><th colspan="3">Gift Certificates &amp; Individual Reports</th></tr>
        <?php
            $products = array('post_type' => 'product', 'post__not_in' => array(309,312), 'tax_query' => array(array('taxonomy' => 'product_cat',
                                                                            'field' => 'id',
                                                                            'terms' => array(68),
                                                                            'operator' => 'IN',
                                                                            'include_children' => false)));
            $get_products = new WP_Query($products);
            if( $get_products->have_posts() ): 
                $count=0;
                while( $get_products->have_posts() ) : $get_products->the_post(); 
                    $productID = get_the_ID();
                    $product = wc_get_product( $productID );
                    $sku = get_field('_sku', $productID);

                    //check product is not a bundle
                    //$bundle = get_field('_bundle_data', $productID);
                    //if($bundle == '') {
                        echo '<tr>';
                        echo '<td>'.get_the_title().' '.$bundle.'</td>';
                        echo '<td class="price">&dollar;'.$product->get_price().'</td>';
                        echo '<td>';
                        echo '<a href="/pricing-and-packages/?add-to-cart='.$productID.'" rel="nofollow" data-product_id="'.$productID.'" data-product_sku="'.$sku.'" data-quantity="1" class="button add_to_cart_button product_type_bundle product_type_simple ajax_add_to_cart">Add to cart</a>';
                        echo '</td>';
                        echo '<tr>';
                    //}
                endwhile;
            endif;
        ?>
        </table>

    </article>

    <section class="col-right">
        <div class="cart">
            <div class="cart-header">Summary</div>
            <?php
                function price_format($value) {
                    $value = number_format($value, 2);
                    $value = '&dollar;'.$value;

                    return $value;
                }
            ?>
            <div class="cart-contents">
                <dl>
                <?php
                    global $woocommerce;
                    $cart = $woocommerce->cart->cart_contents;
                    //echo '<pre>';print_r($cart);echo '</pre>';
                    if(sizeof($cart) > 0) {
                        foreach($cart as $item){
                            $name = $item['data']->post->post_title;
                            if($item['quantity'] > 1)
                                $name .= ' x '.$item['quantity'];
                            $price = ($item['line_subtotal']+$item['line_subtotal_tax']);
                            if($price != 0)
                                echo '<dt>'.$name.'</dt><dd>'.price_format($price).'</dd>';
                        }
                    } else {
                        echo '<div style="text-align:center;">Your cart is currently empty</div>';
                    }
                ?>
                </dl>

                <div class="cart-total">
                    <dl>
                        <dt>Total</dt><dd><?php echo price_format($woocommerce->cart->subtotal); ?></dd>
                    </dl>
                </div>
            <div class="cart-loading"></div>
            <div class="clear"></div>
            </div>

            <a class="button checkout-btn" 
            <?php if(sizeof($cart) == 0) { ?>
                style="display: none;" 
            <?php } ?>
            href="/cart/" title="Go to cart">Proceed to Cart</a>

            <div class="cart-payment"><img src="assets/images/icon-payment.gif" /></div>

            Prices are in AUD and are GST inclusive.
        </div>
    </section>
</div>

<?php get_footer(); ?> 