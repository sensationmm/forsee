<?php

/**
* @package WordPress
* @subpackage Forsee 2015
* Template Name: CNS Welcome
*/

if(!is_user_logged_in()) {
	wp_redirect('/login/');
}

$user_id = get_current_user_id();
$survey_url = get_user_meta( $user_id, 'survey_url', true ); 
$survey_completed = get_user_meta( $user_id, 'survey_completed', true );  
$survey_completed_date = get_user_meta( $user_id, 'survey_completed_date', true ); 

//Are they coming from the free trial landing page? if so redirect to survey
if(wp_get_referer()==get_site_url().'/free-trial/' || wp_get_referer()==get_site_url().'/free-trial'){
  wp_redirect( $survey_url, 302 );
  exit;
}

get_header();  
global $isCNS;
$isCNS = true;

?>

<?php include 'cns-header.php'; ?>

<div class="body outer">

    <section class="col-main">
    <?php
      $video = get_field('welcome_video', $pageObj->ID);
      $videoMobile = get_field('welcome_video_mobile', $pageObj->ID);
      if($video != '') {
          echo '<div class="video-holder'.(($videoMobile == '') ? ' mobile-video' : '').'">';
          echo html_entity_decode($video);
          echo '</div>';
          if($videoMobile) {
              echo '<div class="video-holder mobile-video">';
              echo html_entity_decode($videoMobile);
              echo '</div>';
          }
      }
    ?>
    </section>
    
    <article class="col-main">

    <h2><?php echo ($display_title != '') ? $display_title : $pageObj->post_title; ?></h2>


<script type="text/javascript">
var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";

<?php
if(is_user_logged_in()) {
    if(!$survey_completed){
      echo 'var completed_survey = false;';  
    }else{
      echo 'var completed_survey = true;';
    }
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

    <div id="message_container">
    </div>

    <?php
    if(is_user_logged_in()) {



        if( isset($_SESSION['just_completed_survey']) && !$survey_completed ){ //&& $_SESSION['just_completed_survey']===true
            echo '<p><img src="/wp-admin/images/loading.gif" /> We are generating your results - watch the video to learn how to use Career Navigator while you wait.</p>';
        }elseif(!$survey_completed){
            echo '<p>You still need to complete your questionnaire. Get started by hitting the button below:</p>
                  <div class="button"><a href="'.$survey_url.'" title="Complete Questionnaire">Complete Questionnaire</a><br/><br/></div>     
                 ';
        }else{
            echo apply_filters('the_content', $content);
        }
    }
    ?>

    </article>
</div>

<?php get_footer(); ?> 