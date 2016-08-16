<?php
/**
 * @package WordPress
 * @subpackage Forsee 2015
 * Template Name: Hubspot
*/
get_header();  
?>

<div class="banner" style="background-image:url(<?php echo get_field('page_banner', $pageObj->ID); ?>);">
  <div class="body">
    <h1><?php echo ($display_title != '') ? $display_title : $pageObj->post_title; ?></h1>
    <p><?php echo get_field('page_strapline', $pageObj->ID); ?></p>
  </div>
</div>

<div class="body outer">

  <article class="col-half">
    <?php echo apply_filters('the_content', $pageObj->post_content); ?>
  </article>

  <section class="col-half">
      <?php 
          $formHeader = get_field('hubspot_header', $pageObj->ID);


          if($formHeader != '')
              echo '<h2>'.$formHeader.'</h2>';

          if(isset($_GET["message"]) && $_GET["message"] != '')
              echo '<div class="error">'.$_GET["message"].'</div>';
            
          echo get_field('hubspot_code', $pageObj->ID); 
      ?>
  </section>

</div>

<?php get_footer(); ?> 