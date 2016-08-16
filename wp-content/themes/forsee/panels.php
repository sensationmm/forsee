<?php
/**
 * @package WordPress
 * @subpackage Forsee 2015
 * Template Name: Panels
*/
get_header();  
?>
	
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

    <?php if($content != '') { ?>
  	<article class="section white">
  		<div class="body">
	  		<div class="section-full">
		  		<?php echo apply_filters('the_content', $content); ?>
  			</div>
  		</div>
  	</article>
    <?php } ?>

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


      if( have_rows('features') ):
          echo '<section class="section">';
          echo '<div class="body">';
          while ( have_rows('features') ) : the_row();
              echo '<div class="col3 feature '.get_sub_field('features_color').'">';
              echo '<div class="feature-icon '.get_sub_field('features_icon').'"></div>';
              echo '<h3>'.get_sub_field('features_title').'</h3>';
              echo apply_filters('the_content', get_sub_field('features_text'));
              $label = get_sub_field('features_label');
              if($label != '') {
                  echo '<div class="button"><a href="'.get_sub_field('features_link').'" title="'.get_sub_field('features_label').'">';
                  echo get_sub_field('features_label').'</a></div>';
              }
              echo '</div>';
          endwhile;
          echo '</div>';
          echo '</section>';
      endif;
    ?>

<?php get_footer(); ?> 