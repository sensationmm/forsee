<?php
/**
 * @package WordPress
 * @subpackage Forsee 2015
*/
get_header(); 
$user_id = get_current_user_id();
$survey_url = get_user_meta( $user_id, 'survey_url', true );

if(is_user_logged_in() && !in_array($pageObj->ID, array(141,138,152,3471))) {
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

  <article class="col-left">
    <?php echo apply_filters('the_content', $pageObj->post_content); 
		
		if($pageObj->ID == 3471) {
?>
<script>
fbq('track', 'CompleteRegistration');
</script>
<?php      
			echo '<br /><div class="button"><a href="'.$survey_url.'" title="Start your questionnaire">START YOUR QUESTIONNAIRE</a><br><br></div>';
		} else if($pageObj->ID == 141) {
        if( have_rows('faq') ):

            while ( have_rows('faq') ) : the_row();
                $question = get_sub_field('faq_question');
                $answer = get_sub_field('faq_answer');

                echo '<div class="faq">';
                echo '<div class="faq-question">'.$question.'</div>';
                echo '<div class="faq-answer">'.$answer.'</div>';
                echo '</div>';

            endwhile;
        endif;
    }
	?>
  </article>

  <section class="col-right">
    <?php

        if( have_rows('boxes') ):

            while ( have_rows('boxes') ) : the_row();

                // display a sub field value
                $boxType = get_sub_field('boxes_type');
                echo '<div class="box box-'.$boxType.' '.get_sub_field('boxes_style').'">';
                $boxImage = get_sub_field('boxes_image');
                if($boxType == 'image' && $boxImage != '')
                    echo '<img src="'.$boxImage.'" alt="'.get_sub_field('boxes_text').'" />';
                else {
                    echo '<h2>'.get_sub_field('boxes_title').'</h2>';
                    if($boxImage != '')
                        echo '<img src="'.$boxImage.'" alt="'.get_sub_field('boxes_text').'" />';
                    echo apply_filters('the_content', get_sub_field('boxes_text'));
                }
                echo '</div>';

            endwhile;

        endif;

        if(get_field('boxes_testimonials') == 1) {
            $listings = array('post_type' => 'testimonials', 'posts_per_page' => 1, 'orderby' => 'rand');

            remove_all_filters('posts_orderby');//prevent plugin clashing with custom ordering
            $testimonials = new WP_Query($listings);

            if ($testimonials->have_posts() ) : 

              echo '<div class="testimonials">';
              echo '<div class="testimonials-header">Testimonials</div>';

              while ( $testimonials->have_posts() ) : $testimonials->the_post();

              echo '<div class="testimonials-quote">"'.get_field('testimonials_quote', get_the_ID()).'"</div>';
              echo '<div class="testimonials-attr">'.get_field('testimonials_attribution', get_the_ID()).'</div>';

              endwhile;
              echo '</div>';
            endif;
        }

        if ( !function_exists('dynamic_sidebar')
          || !dynamic_sidebar() ) :
        endif;
      ?>
      </section>

    </div>

    <?php get_footer(); ?> 