<?php
/**
* @package WordPress
* @subpackage Forsee 2015
* Template Name: CNS My Reports
*/
get_header();  
global $isCNS;
$isCNS = true;

if(is_user_logged_in()) {
  include 'cns-header.php';
} else { header('Location: /login/'); } 
?>

<div class="body outer">
    <h2><?php echo ($display_title != '') ? $display_title : $pageObj->post_title; ?></h2>
    <p><?php echo $pageObj->post_content; ?></p>
  
    <div class="courses">
    <?php
        if(have_rows('report_career_enjoyment', 'user_'.$user_ID)):
            while(have_rows('report_career_enjoyment', 'user_'.$user_ID)): the_row();

                $careerID = get_sub_field('career_enjoyment_id');
                $file = get_sub_field('career_enjoyment_file');

                $args = array(
                    'posts_per_page'    => 1,
                    'post_type'     => 'career_desc',
                    'meta_key'      => 'id_career',
                    'meta_value'    => $careerID
                );
                $the_query = new WP_Query( $args );

                echo '<div class="course report">';

                    echo '<h3 class="course-title">'; 
                    if( $the_query->have_posts() ):
                        while( $the_query->have_posts() ) : $the_query->the_post();
                            echo get_the_title();
                        endwhile;
                    endif;

                    echo '</h3>';

                    if($file != '')
                        echo '<div class="course-view"><a href="/download.php?report='.$file.'" target="_blank">Download Report</a></div>';
                    else echo '<div class="course-view"><img src="/wp-admin/images/loading.gif" />
                                <script>
                                  setTimeout(function(){
                                     window.location.reload(1);
                                  }, 20000);
                                </script></div>';
                echo '</div>'; 
            endwhile;
        endif;
    ?>
    </div>
    <a class="button" href="/pricing-and-packages/" title="Continue Shopping">Back to Interactive Career Options</a>

</div>

<?php get_footer(); ?> 