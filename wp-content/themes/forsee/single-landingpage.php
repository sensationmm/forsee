<?php
/**
* @package WordPress
* @subpackage Forsee 2015
*/
get_header(); 

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

<div class="body outer">

  <article class="col-half">
  <?php 
    echo apply_filters('the_content', $pageObj->post_content); 

    if($pageObj->ID==3471){
      echo '<br /><div class="button"><a href="'.$survey_url.'" title="Start your questionnaire">START YOUR QUESTIONNAIRE</a><br><br></div>';
    }
  ?>
  </article>

  <section class="col-half">
  <?php 
        $formHeader = get_field('hubspot_header', $pageObj->ID);


        if($formHeader != '')
            echo '<h2>'.$formHeader.'</h2>';

        if(isset($_GET["message"]) && $_GET["message"] != '')
            echo '<div class="error">'.$_GET["message"].'</div>';
          
        echo get_field('hubspot_code', $pageObj->ID); 

        if( have_rows('content_panels') ):

          while ( have_rows('content_panels') ) : the_row();

              $style = get_sub_field('content_panels_style');
              $align = get_sub_field('content_panels_alignment');
              $title = get_sub_field('content_panels_title');
              $text = get_sub_field('content_panels_text');
              $label = get_sub_field('content_panels_label');
              $link = get_sub_field('content_panels_link');
              $image = get_sub_field('content_panels_image');

              if($align != 'full' && $image == '') {
                $align = 'full';
              } else if($align == 'full' && $image != '') {
                $image = '';
              }


              echo '<article class="section '.$style.' '.$align.'">';
                echo '<div class="body"'.(($image != '') ? ' style="background-image:url('.$image.');"' : '').'>';
                if($align != 'full')
                  echo '<div class="section-text">';
                else
                  echo '<div class="section-full">';

                  echo '<h2>'.(($title != '') ? $title : get_the_title()).'</h2>';
                  echo '<p>'.$text.'</p>';
                  if($label != '')
                    echo '<div class="button"><a href="'.$link.'" title="'.$label.'">'.$label.'</a></div>';
                  echo '</div>';
                echo '</div>';
              echo '</article>';

          endwhile;

      endif;
    ?>
  </section>

</div>

<?php get_footer(); ?> 