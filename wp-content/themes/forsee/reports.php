<?php
/**
 * @package WordPress
 * @subpackage Forsee 2015
 * Template Name: Reports
*/
get_header();

$user_id = get_current_user_id();
$survey_url = get_user_meta( $user_id, 'survey_url', true ); 
$survey_completed = get_user_meta( $user_id, 'survey_completed', true );  
$survey_completed_date = get_user_meta( $user_id, 'survey_completed_date', true ); 

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

    <?php if($content != '') { ?>
    <article class="section white">
      <div class="body">
        <div class="section-full">
          <?php echo apply_filters('the_content', $content); ?>
        </div>
      </div>
    </article>
    <?php } ?>

    <div class="body">


<script type="text/javascript">
var ajaxurl = "http://forsee.192.168.1.200.xip.io:8888/wp-admin/admin-ajax.php";

<?php
    if(!$survey_completed || !is_user_logged_in()) {
      echo 'var completed_survey = false;';  
    }else{
      echo 'var completed_survey = true;';
    }
?>

$(document).ready(function() {

  function startpoll() {
    (function poll() {
      tOut = setTimeout(function() {
        $.ajax({
          url: ajaxurl,
          type: "POST",
          data: {
            action: 'check_survey_completion',
            check_survey_compete: 'is_it'
          },            
          success: function(data) {                  
            if(data.survey_complete=='yes') {
              location.reload();
              console.log( 'found yes' );                  
            } else {
              poll();
            }
          },
          dataType: "json",
          //complete: poll,
          //timeout: 2000
        })
      }, 5000);
    })();
  }

  if(completed_survey==false){
    startpoll();
  }

});
</script> 


    <?php
            if( isset($_SESSION['just_completed_survey']) && !$survey_completed ){ //&& $_SESSION['just_completed_survey']===true
                echo '
                        <div class="report-info">
                          <h2>One moment please</h2>
                          <p><img src="/wp-admin/images/loading.gif" /> We are generating your results - Please wait...</p>
                        </div>
                     ';
            }elseif(!$survey_completed && is_user_logged_in()) {
                echo '<p>You still need to complete your questionnaire. Get started by hitting the button below:</p>
                      <div class="button"><a href="'.$survey_url.'" title="Complete Questionnaire" >Complete Questionnaire</a><br/><br/></div>     
                     ';
            }

            if(!is_user_logged_in() || (is_user_logged_in() && $survey_completed)) {
              /*
                Figure out what buttons we are going to show to the user based on reports purchased... trial data
              */
              $Buttons = array();
             
              //Co1 - Co2 report
              $ha_data = ha_data_response_check();

              //echo $survey_completed_date;
              //var_dump($Buttons);

              $listings = array('post_type' => 'reports',
                      'posts_per_page' => $posts_per_page,
                      'paged' => $paged,
                      'orderby' => 'menu_order',
                      'order' => 'ASC');

              remove_all_filters('posts_orderby');//prevent plugin clashing with custom ordering
              $reports = new WP_Query($listings);

              if ($reports->have_posts() ) : 
                  while ( $reports->have_posts() ) : $reports->the_post();
                    $ID = get_the_ID();
                    $title = get_the_title();
                    $desc = get_field('report_summary', $ID);
                    $image = get_field('report_image', $ID);
                    $price = get_field('report_price', $ID);
                    /* replaced by default page link
                    $link = get_field('report_link', $ID);
                    $label = get_field('report_label', $ID); */
                    if( is_user_logged_in() ){
                      $label_fromadmin = get_field('link_label_logged_in', $ID);
                    }else{
                      $label_fromadmin = get_field('report_label', $ID);
                    }
                    
                    $label2 = get_field('report_secondlabel', $ID);
                    $link2 = get_field('report_secondlink', $ID);
                    $ha_report_id = get_field('report_id', $ID);
                    $check_for_PDF = Check_ha_report_generated($ha_report_id);

                    //var_dump( $check_for_PDF );


                    echo '<article class="report">';
                        echo '<div class="report-image">';
                            echo '<a href="'.get_permalink($ID).'" title="Learn more about '.$title.'"><img src="'.$image['url'].'" alt="'.$image['alt'].'" /></a>';

                            echo '<div class="overlay"><a href="'.get_permalink($ID).'" title="Learn more about '.$title.'"></a></div>';
                        echo '</div>';

                        echo '<div class="report-info">';
                            echo '<h2>';
                            echo $title;
                            if(!($ID == 423 && is_user_logged_in())) {
                              if($price != '')
                                  echo ' <span class="price-detail">'.$price.'</span>';
                            }
                            echo '</h2>';
                            echo apply_filters('the_content', $desc);


                            if(is_user_logged_in()) {
                                // //Do we need to show link to XML HA data
                                // if($ha_report_id=='Co2' && $ha_data['count']>10){
                                //   //Have full data from HA
                                //   echo '<a class="button" href="/careers-in-order-of-likely-level-of-enjoyment/" title="report">Interactive Report</a>';
                                // }elseif($ha_report_id=='Co2' && $ha_data['count']==10){
                                //   //Trial data avail
                                //   echo '<a class="button" href="/careers-in-order-of-likely-level-of-enjoyment/" title="report">View Trial Report</a>';
                                // }

                                //Do we have PDF link to allow them to download                      
                                if($check_for_PDF['ha_report'] && !$check_for_PDF['ha_report_file']){
                                  echo '<div class="report-waiting">';
                                  echo '<img src="/wp-admin/images/loading.gif" /> Waiting for report to download
                                        <script>
                                          setTimeout(function(){
                                             window.location.reload(1);
                                          }, 20000);
                                        </script>  
                                  ';
                                  echo '</div>';
                                } elseif($check_for_PDF['ha_report'] && $check_for_PDF['ha_report_file']){
                                  
                                  //echo '<a class="button" href="/download.php?report='.$check_for_PDF['ha_report_file'].'" title="Download Report" target="_blank">Download Report</a>';
                                }    
                            }

                            echo '<a class="button" href="'.get_permalink($ID).'" title="Learn more about '.$title.'">'.$label_fromadmin.'</a>';

                            if($link2 != '') {
                                echo '<a class="button" href="'.$link2.'" title="'.$label2.'">'.$label2.'</a>';
                            }

                        echo '</div>';
                    echo '</article>';
                  endwhile;
              endif;

}


    ?>
  </div>

<?php get_footer(); ?> 