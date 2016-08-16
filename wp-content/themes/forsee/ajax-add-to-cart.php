<?php
$path = $_SERVER['DOCUMENT_ROOT'];

include_once $path . '/wp-config.php';
include_once $path . '/wp-load.php';
include_once $path . '/wp-includes/wp-db.php';
include_once $path . '/wp-includes/pluggable.php';

    function price_format($value) {
        $value = number_format($value, 2);
        $value = '&dollar;'.$value;

        return $value;
    }

    global $woocommerce;
    $cart = array();
    while(sizeof($cart) == 0) {
        $cart = $woocommerce->cart->cart_contents;
        // echo '<pre>';print_r($cart);echo '</pre>';
        if(sizeof($cart) > 0) {
            echo '<dl>';
            foreach($cart as $item){
                $name = $item['data']->post->post_title;
                if($item['quantity'] > 1)
                    $name .= ' x '.$item['quantity'];
                $price = ($item['line_subtotal']+$item['line_subtotal_tax']);
                if($price != 0)
                    echo '<dt>'.$name.'</dt><dd>'.price_format($price).'</dd>';
            }
            echo '</dl>';
        } else {
            echo '<div style="text-align:center;">Your cart is currently empty</div>';
        }
    }



    echo '<div class="cart-total">';
        echo '<dl>';
            echo '<dt>Total</dt><dd>'.price_format($woocommerce->cart->subtotal).'</dd>';
        echo '</dl>';
    echo '</div>';


    echo '<div class="cart-loading"></div>';
    echo '<div class="clear"></div>';
?>