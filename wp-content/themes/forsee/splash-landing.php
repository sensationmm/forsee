<?php
/**
* @package WordPress
* @subpackage Forsee 2015
* Template name: Splash Landing
*/

global $headerInclude, $post, $pageObj, $template;
$pageObj = $post;
$template = 'splash-landing';
$background = get_field('page_banner',$pageObj->ID);
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<title><?php echo $pageTitle; ?>Forsee Career Technology</title>
<base href="/wp-content/themes/forsee/" />
<link rel="shortcut icon" type="image/x-icon" href="assets/images/favicon.ico" />
<link rel="stylesheet" href="assets/css/style.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/3.1.2/css/swiper.min.css">
<link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,200' rel='stylesheet' type='text/css'>
<link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet' type='text/css'>
<link href='https://fonts.googleapis.com/css?family=Lato' rel='stylesheet' type='text/css'>
<?php echo $headerInclude; ?>
<?php wp_head(); ?>
</head>
<body id="<?php echo $template; ?>">
<?php if ( function_exists( 'gtm4wp_the_gtm_tag' ) ) { gtm4wp_the_gtm_tag(); } ?>
    <div class="splash-background" style="background-image:url(<?php echo $background; ?>);">
        <header>
            <div class="body">
                <div class="logo">
                    <a href="/" title="View homepage"><img src="assets/images/forsee-career-technology-reversed.png" /></a>
                </div>
            </div>
        </header>

        <?php
        $display_title = get_field('page_display_title', $pageObj->ID); 
        $strapline = get_field('page_strapline', $pageObj->ID); 
        $content = $pageObj->post_content;
        ?>
        <div class="splash">
            <div class="body outer centred">
                <h1><?php echo ($display_title != '') ? $display_title : $pageObj->post_title; ?></h1>
                <p class="strap"><?php echo get_field('page_strapline', $pageObj->ID); ?></p>

                <article class="col-splash">
                <?php
                    $img = get_the_post_thumbnail($pageObj->ID);
                    if($img != '')
                        echo $img;
                    $video = get_field('welcome_video', $pageObj->ID);
                    $videoMobile = get_field('welcome_video_mobile', $pageObj->ID);
                    if($video != '') {
                        echo '<div class="video-play">Play video</div>';
                    }
                ?>
                </article>

                <section class="col-form">
                <?php 
                    $formHeader = get_field('hubspot_header', $pageObj->ID);
                    $formText = get_field('hubspot_text', $pageObj->ID);

                    if($formHeader != '')
                        echo '<h2>'.$formHeader.'</h2>';

                    if($formText != '')
                        echo '<p>'.$formText.'</p>';

                    if(isset($_GET["message"]) && $_GET["message"] != '')
                        echo '<div class="error">'.$_GET["message"].'</div>';

                    $form = get_field('hubspot_code', $pageObj->ID); 
                    if($form == '[woocommerce_my_account]') {
                        echo apply_filters('the_content', $form);
                    } else {
                        echo $form;
                    }
                ?>
                </section>


            </div>
        </div>
    </div>

    <div class="splash-footer">
        <div class="body">
            <span>100% Satisfaction Guarantee</span>
            <img src="assets/images/satisfaction-guaranteed.png" />
            <span>No credit card details required!</span>
        </div>
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

    <?php if(get_field('splash_testimonials', $pageObj->ID) == '1') { ?>
    <article class="section tarmac">
        <div class="body outer">
            <div class="section-full">
            <?php 
                $testimonialsHeader = get_field('home_testimonials_header', 8);
                if($testimonialsHeader != '')
                    echo '<h2>'.$testimonialsHeader.'</h2>';

                $listings = array('post_type' => 'testimonials');

                remove_all_filters('posts_orderby');//prevent plugin clashing with custom ordering
                $panels = new WP_Query($listings);

                if ($panels->have_posts() ) : 
                    echo '<div id="testimonials-swiper" class="swiper-container">';
                    echo '<div class="swiper-wrapper">';

                    while ( $panels->have_posts() ) : $panels->the_post();

                        echo '<div class="swiper-slide">';
                        echo '<div class="testimonials-quote">"'.get_field('testimonials_quote', get_the_ID()).'"</div>';
                        echo '<div class="testimonials-attr">'.get_field('testimonials_attribution', get_the_ID()).'</div>';
                        echo '</div>';

                    endwhile;
                    echo '</div>';
                    echo '</div>';
                    echo '<div id="testimonials-pagination" class="swiper-pagination"></div>';
                endif;
            ?>
            </div>
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
        </div>
    </article>
    <?php } ?>

    <?php if(get_field('splash_blog', $pageObj->ID) == '1') { ?>
    <article class="section white">
        <div class="body outer">
            <div class="section-full has-sections">
            <?php
                $newsHeader = get_field('home_news_header', 8);
                $newsText = get_field('home_news_text', 8);
                if($newsHeader != '')
                    echo '<h2>'.$newsHeader.'</h2>';
                if($newsText != '')
                    echo '<p>'.$newsText.'</p>';
            ?>
            </div>
            
            <div class="section-sections">
            <?php
                $listings = array('post_type' => 'post',
                                  //'cat' => 8,
                                  'posts_per_page' => 2);

                remove_all_filters('posts_orderby');//prevent plugin clashing with custom ordering
                $news = new WP_Query($listings);

                if ($news->have_posts() ) : 
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
                endif;
            ?>
            </div>
        </div>
    </article>
    <?php } ?>


    <?php
        if($video != '') {
            echo '<div class="video-overlay">';
            echo '<div class="video-holder'.(($videoMobile != '') ? ' mobile-video' : '').'">';
            echo html_entity_decode($video);
            echo '</div>';
            if($videoMobile) {
                echo '<div class="video-holder mobile-video">';
                echo html_entity_decode($videoMobile);
                echo '</div>';
            }
            echo '<div class="overlay-close"></div>';
            echo '</div>';
        }
    ?>
    <div class="mask"></div>

    <script type="text/javascript" src="assets/js/modernizr.min.js"></script>
    <script type="text/javascript" src="assets/js/app.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/3.1.2/js/swiper.jquery.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#reg_billing_first_name').attr("placeholder", "First name");
            $('#reg_billing_last_name').attr("placeholder", "Last name");
            $('#reg_billing_phone').attr("placeholder", "Phone number");
            $('#reg_email').attr("placeholder", "Email");
            if(!Modernizr.svg) {
                $('img[src*="svg"]').attr('src', function() {
                    return $(this).attr('src').replace('.svg', '.png');
                });
            }
            var $videoOpen = $('.video-play');
            var $videoPlayer = $('.video-overlay');
            var $videoClose = $('.overlay-close');
            var $mask = $('.mask');
            var $iframe = $('.video-holder iframe');
            var $iframeSrc = $('.video-holder iframe').attr('src');

 
            $videoOpen.click(function() {
                $mask.fadeIn('slow');
                $videoPlayer.css('visibility','visible');
                $videoPlayer.css('zIndex','1001');
            });
            $videoClose.click(function() {
                $videoPlayer.css('zIndex','-1');
                $videoPlayer.css('visibility','hidden');
                $mask.fadeOut('fast');
                $iframe.attr('src', $iframeSrc);
            });
        });
    </script>
    <?php wp_footer(); ?>
</body>
</html>