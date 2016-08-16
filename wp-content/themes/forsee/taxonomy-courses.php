<?php
/**
* @package WordPress
* @subpackage Forsee 2015
*/
get_header();  
global $isCNS;
$isCNS = true;

global $wp_query;
$term = $wp_query->get_queried_object();

if(is_user_logged_in()) {
  include 'cns-header.php';
} else {  ?>
	<div class="banner" style="background-image:url(<?php echo get_field('page_banner', 413); ?>);">
        <div class="body">
            <?php 
              $display_title = get_field('page_display_title', 413); 
              $strapline = get_field('page_strapline', 413); 
              $content = $pageObj->post_content;
            ?>

            <h1><?php echo ($display_title != '') ? $display_title : $pageObj->post_title; ?></h1>
            <p class="strap"><?php echo $strapline; ?></p>
        </div>
    </div>
<?php } ?>

<div class="body outer">
    <h2><?php echo $term->name; ?></h2>
    <p><?php echo $term->description; ?></p>
  
    <div class="courses">
    <?php
    	if (have_posts() ) : 
            while ( have_posts() ) : the_post();
				echo '<div class="course">';
                    $title = get_the_title();
                    $excerpt = get_the_excerpt();
                    $logo = get_field('course_logo', get_the_ID());

                    echo '<h3 class="course-title">';
                    if(strlen($title) > 35)
                        echo substr($title, 0, 35).'...';
                    else
                        echo $title;
                    echo '</h3>';
	                echo '<p>';
                    $excerptMaxLength = 70;
                    if($logo == '')
                        $excerptMaxLength = 100;
                    if(strlen($excerpt) > $excerptMaxLength)
                        echo substr($excerpt, 0, $excerptMaxLength).'...';
                    else
                        echo $excerpt;
                    echo '</p>';

                    if($logo != '')
	                   echo '<div class="course-logo"><img src="'.$logo.'" /></div>';
	                echo '<div class="course-view"><a href="'.get_permalink(get_the_ID()).'">View Course</a></div>';
	            echo '</div>'; 
        	endwhile;
        endif;
    ?>
    </div>

</div>

<?php get_footer(); ?> 