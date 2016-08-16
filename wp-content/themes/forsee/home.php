<?php
/**
 * @package WordPress
 * @subpackage Forsee 2015
 * Template Name: Home
*/
get_header();  
$listings = array('post_type' => 'homepage_banners',
		          'orderby' => 'rand',
		          'posts_per_page' => 1);

if($isCorporate) {
	$listings['meta_key'] = 'corporate_show';
	$listings['meta_value'] = '1';
} else {
	$listings['meta_key'] = 'corporate_hide';
	$listings['meta_value'] = '0';
}

remove_all_filters('posts_orderby');//prevent plugin clashing with custom ordering
$banners = new WP_Query($listings);
if ($banners->have_posts() ) : 
	while ( $banners->have_posts() ) : $banners->the_post();
		$bannerID = get_the_ID();
		$bannerImage = get_field('banner_image', $bannerID);
		$bannerPerson = get_field('banner_person', $bannerID);
	endwhile;
endif;
?>
	
	<div class="banner" style="background-image:url(<?php echo $bannerImage; ?>);">
		<div class="body" style="background-image:url(<?php echo $bannerPerson; ?>);">
			<div class="intro">
				<?php 
					$display_title = get_field('page_display_title', $pageObj->ID); 
					$strapline = get_field('page_strapline', $pageObj->ID); 
					$button_label = get_field('page_label', $pageObj->ID); 
					$button_link = get_field('page_link', $pageObj->ID); 

					if($button_label == '')
						$button_label = 'Find out more';
				?>
				<h1><?php echo ($display_title != '') ? $display_title : $pageObj->post_title; ?></h1>
				<p><?php echo $strapline; ?></p>
				<?php if($button_link != '') {
					echo '<div class="button highlight"><a href="'.$button_link.'" title="'.$button_label.'">'.$button_label.'</a></div>';
				} ?>
			</div>
		</div>
	</div>

	<?php
		$videoShown = false;
		$videoString = '';
		$videoEmbed = get_field('home_video_embed', $pageObj->ID);
		$videoMobile = get_field('home_mobile_video', $pageObj->ID);
		if($videoEmbed != '') {
			$videoTitle = get_field('home_video_title', $pageObj->ID);
			$videoText = get_field('home_video_text', $pageObj->ID);
			$videoLink = get_field('home_video_link', $pageObj->ID);
			$videoLabel = get_field('home_video_label', $pageObj->ID);

			$videoString .= '<article class="section white textleft">';
				$videoString .= '<div class="body">';
					$videoString .= '<div class="section-text">';

						$videoString .= '<h2>'.$videoTitle.'</h2>';
						$videoString .= '<p>'.$videoText.'</p>';
						if($videoLabel != '') {
							$videoString .= '<div class="button">';
							$videoString .= '<a href="'.get_permalink($videoLink).'" title="'.$videoLabel.'">';
							$videoString .= $videoLabel.'</a></div>';
						}
					$videoString .= '</div>';
					$videoString .= '<div class="section-video video-holder'.(($videoMobile == '') ? ' mobile-video' : '').'">';
					$videoString .= $videoEmbed;
					$videoString .= '</div>';
					if($videoMobile) {
						$videoString .= '<div class="section-video video-holder mobile-video">';
						$videoString .= $videoMobile;
						$videoString .= '</div>';
					}
				$videoString .= '</div>';
			$videoString .= '</article>';
		}

		echo $videoString;



		$listings = array('post_type' => 'page',
				          'meta_key' => (($isCorporate) ? 'hppanel_corporate' : 'hppanel_show'), 
				          'meta_value' => '1',
				          'orderby' => 'menu_order',
				          'order' => 'asc');

		remove_all_filters('posts_orderby');//prevent plugin clashing with custom ordering
		$panels = new WP_Query($listings);

		// echo '<pre>';print_r($panels);echo '</pre>';

		if ($panels->have_posts() ) : 
			while ( $panels->have_posts() ) : $panels->the_post();
				$ID = get_the_ID();

				$style = get_field('hppanel_style', $ID);
				$align = get_field('hppanel_align', $ID);
				$title = get_field('hppanel_title', $ID);
				$text = get_field('hppanel_text', $ID);
				$label = get_field('hppanel_label', $ID);
				$image = get_field('hppanel_image', $ID);

				if($align != 'full' && $image == '')
					$align = 'full';


				/*/output video panel after homepage panel
				if(!$videoShown && $ID != 8) {
					echo $videoString;
					$videoShown = true;
				}*/


				echo '<article class="section '.$style.' '.$align.'">';
					echo '<div class="body"'.(($image != '') ? ' style="background-image:url('.$image.');"' : '').'>';
					if($align != 'full')
						echo '<div class="section-text">';
					else
						echo '<div class="section-full">';

						echo '<h2>'.(($title != '') ? $title : get_the_title()).'</h2>';
						echo '<p>'.$text.'</p>';
						if($label != '')
							echo '<div class="button"><a href="'.get_permalink($ID).'" title="'.$label.'">'.$label.'</a></div>';
						echo '</div>';
					echo '</div>';
				echo '</article>';

			endwhile;
		endif;

  		$listings = array('post_type' => 'testimonials');

		if($isCorporate) {
			$listings['meta_key'] = 'corporate_show';
			$listings['meta_value'] = '1';
		} else {
			$listings['meta_key'] = 'corporate_hide';
			$listings['meta_value'] = '0';
		}

		remove_all_filters('posts_orderby');//prevent plugin clashing with custom ordering
		$panels = new WP_Query($listings);

		if ($panels->have_posts() ) : 



		  	echo '<article class="section tarmac">';
		  		echo '<div class="body">';
			  		echo '<div class="section-full">';

						$testimonialsHeader = get_field('home_testimonials_header', $pageObj->ID);
						if($testimonialsHeader != '')
							echo '<h2>'.$testimonialsHeader.'</h2>';

						echo '<div id="testimonials-swiper" class="swiper-container">';
						echo '<div class="swiper-wrapper">';

						$count = 0;
						while ( $panels->have_posts() ) : $panels->the_post();

	    					echo '<div class="swiper-slide">';
	    					echo '<div class="testimonials-quote">"'.get_field('testimonials_quote', get_the_ID()).'"</div>';
	    					echo '<div class="testimonials-attr">'.get_field('testimonials_attribution', get_the_ID()).'</div>';
	    					echo '</div>';
	    					$count++;

						endwhile;
						echo '</div>';
						echo '</div>';
						if($count > 1)
							echo '<div id="testimonials-pagination" class="swiper-pagination"></div>';

					echo '</div>';
				echo '</div>';
			echo '</article>';

		endif;
	?>
	<?php if($count > 1) { ?>
	<script type="text/javascript">
	$(document).ready(function () {
	    //initialize swiper when document ready  
	    var mySwiper = new Swiper ('#testimonials-swiper', {
	      // Optional parameters
	      	direction: 'horizontal',
			pagination: '#testimonials-pagination',
			paginationClickable: true,
			autoHeight: true,
	     	loop: true,
	     	autoplay: 5000,
	     	speed: 700
	    })        
	  });
	</script>
	<?php } ?>

	<?php
		$listings = array('post_type' => 'post',
						  //'cat' => 8,
				          'posts_per_page' => 3,
				          'ignore_sticky_posts' => 1);

		if($isCorporate) {
			$listings['meta_key'] = 'corporate_show';
			$listings['meta_value'] = '1';
		} else {
			$listings['meta_key'] = 'corporate_hide';
			$listings['meta_value'] = '0';
		}

		remove_all_filters('posts_orderby');//prevent plugin clashing with custom ordering
		$news = new WP_Query($listings);

		if ($news->have_posts() ) : 








		  	echo '<article class="section white">';
		  		echo '<div class="body">';
			  		echo '<div class="section-full has-sections">';

			  			$newsHeader = get_field('home_news_header', $pageObj->ID);
			  			$newsText = get_field('home_news_text', $pageObj->ID);
			  			if($newsHeader != '')
			  				echo '<h2>'.$newsHeader.'</h2>';
			  			if($newsText != '')
			  				echo '<p>'.$newsText.'</p>';

					echo '</div>';
					
					echo '<div class="section-sections">';


					while ( $news->have_posts() ) : $news->the_post();
						echo '<div class="col3 news">';
						$ID = get_the_ID();
						$image = get_the_post_thumbnail($ID, 'medium');

						echo '<div class="news-image"><a href="'.get_permalink($ID).'" title="Read more">'.$image.'</a></div>';
						echo '<div class="news-title"><a href="'.get_permalink($ID).'" title="Read more">'.get_the_title().'</a></div>';
						echo '<div class="news-date">'.get_the_date('j M Y').'</div>';
						$excerpt = get_the_excerpt();
						echo '<div class="news-excerpt"><p>';
						if(strlen($excerpt) > 100)
							echo substr($excerpt, 0, 100).'...';
						else echo $excerpt;
						echo '</p></div>';
						echo '<div class="news-link"><a href="'.get_permalink($ID).'" title="Read more">Read More</a></div>';
						echo '</div>';
					endwhile;


					echo '</div>';
				echo '</div>';
		  	echo '</article>';
		endif;
	?>

  	<?php
      if( have_rows('content_panels', $pageObj->ID) ):

          while ( have_rows('content_panels', $pageObj->ID) ) : the_row();

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

    <script type="text/javascript">

		var mobileCheck = {
		    Android: function() {
		        return navigator.userAgent.match(/Android/i);
		    },
		    BlackBerry: function() {
		        return navigator.userAgent.match(/BlackBerry/i);
		    },
		    iOS: function() {
		        return navigator.userAgent.match(/iPhone|iPod/i);
		    },
		    Opera: function() {
		        return navigator.userAgent.match(/Opera Mini/i);
		    },
		    Windows: function() {
		        return navigator.userAgent.match(/IEMobile/i) || navigator.userAgent.match(/WPDesktop/i);
		    },
		    any: function() {
		        return (mobileCheck.Android() || mobileCheck.BlackBerry() || mobileCheck.iOS() || mobileCheck.Opera() || mobileCheck.Windows());
		    }
		};

		var isMobile = (mobileCheck.any()) ? true : false;
    	var viewportWidth = Math.max(document.documentElement.clientWidth, window.innerWidth || 0);

    	$(document).ready(function() {
            var $mask = $('.mask');
            var $mobileAlert = $('.mobile-alert');
            var $mobileAlertClose = $('.mobile-alert-close');

            var alertShown = localStorage['forsee-mobile-alerted'];

    		if(isMobile && viewportWidth <= 480 && !alertShown) {
    			$mask.fadeIn('fast');
            	$mobileAlert.css('display','block');
		        localStorage['forsee-mobile-alerted'] = "yes";
    		}

    		$mobileAlertClose.click(function() {
            	$mobileAlert.css('display','none');
    			$mask.fadeOut('fast');
    		});
    	});
    </script>

<?php get_footer(); ?> 