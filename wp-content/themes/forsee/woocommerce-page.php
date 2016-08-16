<?php
/**
 * @package WordPress
 * @subpackage Forsee 2015
 * Template name: Woocommerce
*/
get_header();  

if(in_array($pageObj->ID, array(307,306, 1953)) && is_user_logged_in()) {
  include 'cns-header.php';
} else { ?>
<div class="banner" style="background-image:url(<?php echo get_field('page_banner', $pageObj->ID); ?>);">
  <div class="body">
  <?php 
    $display_title = get_field('page_display_title', $pageObj->ID); 
    $strapline = get_field('page_strapline', $pageObj->ID); 
  ?>
    <h1><?php echo ($display_title != '') ? $display_title : $pageObj->post_title; ?></h1>
    <p><?php echo get_field('page_strapline', $pageObj->ID); ?></p>
  </div>
</div>
<?php } ?>

<div class="body outer">

  <article>
    <?php if ($pageObj->ID == 306) { ?>
    <a class="button button-float-right" href="/pricing-and-packages/" title="Continue Shopping">Continue Shopping</a>
    <?php } ?>
  	
    <?php if(in_array($pageObj->ID, array(307,306,1953)) && is_user_logged_in()) { ?>
    <h1><?php echo ($display_title != '') ? $display_title : $pageObj->post_title; ?></h1>
    <p><?php echo get_field('page_strapline', $pageObj->ID); ?></p>
    <?php } ?>
    <?php echo apply_filters('the_content', $pageObj->post_content); ?>
  </article>

    </div>

    <?php get_footer(); ?> 