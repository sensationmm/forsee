<?php
/**
 * @package WordPress
 * @subpackage Forsee 2015
 * Template name: Page Full Width
*/
get_header();  

if($pageObj->ID != 3420 && is_user_logged_in()) {
  include 'cns-header.php';
} else { ?>
<?php 
    $display_title = get_field('page_display_title', $pageObj->ID); 
    $strapline = get_field('page_strapline', $pageObj->ID); 
    $content = $pageObj->post_content;
  ?>
<div class="banner<?php echo ($strapline == '') ? ' banner-slim' : ''; ?>" style="background-image:url(<?php echo get_field('page_banner', $pageObj->ID); ?>);">
  <div class="body">
    <h1><?php echo ($display_title != '') ? $display_title : $pageObj->post_title; ?></h1>
    <p class="strap"><?php echo get_field('page_strapline', $pageObj->ID); ?></p>
  </div>
</div>
<?php } ?>

<div class="body outer">

  <article>
    <?php echo apply_filters('the_content', $pageObj->post_content); ?>
  </article>

</div>

<?php get_footer(); ?> 