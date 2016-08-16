<?php
/**
* @package WordPress
* @subpackage Forsee 2015
* Template Name: CNS Courses
*/
get_header();  
global $isCNS;
$isCNS = true;

global $wp_query;
$term = $wp_query->get_queried_object();

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
    <?php if(is_user_logged_in()) { ?>
    <h2><?php echo ($display_title != '') ? $display_title : $pageObj->post_title; ?></h2>
    <p><?php echo $pageObj->post_content; ?></p>
    <?php } ?>
  
    <div class="course-categories">
    <?php
        $cats = get_terms('courses', array('hide_empty' => false));

        foreach ( $cats as $cat ) {
            echo '<div class="course-category">';
                echo '<h3 class="course-title">';
                echo '<a href="'.get_term_link($cat->term_id, 'courses').'" title="View '.$cat->name.' courses">';
                echo $cat->name.'</a>';
                echo '</h3>';
                echo '<p>'.$cat->description.'</p>';
                echo '<a class="button" href="'.get_term_link($cat->term_id, 'courses').'" title="View '.$cat->name.' courses">View Courses</a>';
                echo '<div class="course-count">'.$cat->count.' course'.(($cat->count != 1) ? 's' : '').' available</div>';
            echo '</div>'; 
        }
    ?>
    </div>

</div>

<?php get_footer(); ?> 