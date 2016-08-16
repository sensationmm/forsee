<?php
/**
 * @package WordPress
 * @subpackage Forsee 2015
 * Template Name: Free Trial
*/
get_header();  
?>

<div class="banner" style="background-image:url(<?php echo get_field('page_banner', $pageObj->ID); ?>);">
  <div class="body">
    <h1><?php echo ($display_title != '') ? $display_title : $pageObj->post_title; ?></h1>
    <p class="strap"><?php echo get_field('page_strapline', $pageObj->ID); ?></p>
  </div>
</div>

<div class="body outer">

  <article class="col-half">
  <?php
      $video = get_field('welcome_video', $pageObj->ID);
      if($video != '') {
          echo '<div class="video-holder">'.html_entity_decode($video).'</div>';
      } else { 
          $img = get_the_post_thumbnail(get_the_ID());
          if($img != '')
              echo $img;
          else
              echo 'Please add a featured image';
      }
  ?>
  </article>

  <section class="col-half">
      <?php echo apply_filters('the_content', $pageObj->post_content); ?>
  </section>

</div>

  <?php
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
            echo '<div class="body outer"'.(($image != '') ? ' style="background-image:url('.$image.');"' : '').'>';
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

<?php get_footer(); ?> 