<?php
/**
* @package WordPress
* @subpackage Forsee 2015
*/
get_header();  
global $isCNS;
$isCNS = true;

if(is_user_logged_in()) {
  include 'cns-header.php';
} else { ?>
    <div class="banner" style="background-image:url(<?php echo get_field('page_banner', 420); ?>);">
      <div class="body">
      <?php 
        $reportsTitle = get_field('page_display_title', 420); 
      ?>
        <h1><?php echo ($reportsTitle != '') ? $reportsTitle : $pageObj->post_title; ?></h1>
        <p class="strap"><?php echo get_field('page_strapline', 420); ?></p>
      </div>
    </div>
<?php } ?>

<div class="body outer">

    <section class="col-main">
    <?php
      $video = get_field('report_video', $ID);
      if($video != '')
        echo '<div class="video-holder">'.html_entity_decode($video).'</div>';
    ?>
    </section>
    
    <article class="col-main">

    <h2>
    <?php 
        echo ($display_title != '') ? $display_title : $pageObj->post_title; 
        $price = get_field('report_price', $pageObj->ID);
        if($price != '')
            echo ' <span class="price-detail">'.$price.'</span>';
    ?></h2>
    <?php echo apply_filters('the_content', $pageObj->post_content); ?>
        

<?php
    if(is_user_logged_in()) {
        $ha_report_id = get_field('report_id', $ID);
        $check_for_PDF = Check_ha_report_generated($ha_report_id);
        //Co1 - Co2 report
        $ha_data = ha_data_response_check();

        //Do we need to show link to XML HA data
        if( ($ha_report_id=='Co2' || $ha_report_id=='Cc') && $ha_data['count']>10) {
          //Have full data from HA
          echo '<a class="button" href="/careers-in-order-of-likely-level-of-enjoyment/" title="report">Interactive Report</a>';
        } elseif( ($ha_report_id=='Co2' || $ha_report_id=='Cc') && $ha_data['count']==10) {
          //Trial data avail
          echo '<a class="button" href="/careers-in-order-of-likely-level-of-enjoyment/" title="report">View Trial Report</a>';
        }

        if($ha_report_id=='Cc')
            echo '<a class="button" href="/my-reports/" title="View all requested reports">View All Requested Reports</a>';

        //Do we have PDF link to allow them to download                      
        if($check_for_PDF['ha_report'] && !$check_for_PDF['ha_report_file']) {
          echo '<div class="report-waiting">';
          echo '<img src="/wp-admin/images/loading.gif" > Waiting for report to download
                <script>
                  /*setTimeout(function(){
                     window.location.reload(1);
                  }, 20000);*/
                </script>  
          ';
            echo '</div>';
        } elseif($check_for_PDF['ha_report'] && $check_for_PDF['ha_report_file']) {
          //Do not show download button on careers option page
          if($ha_report_id!='Co2') {
            echo '<a class="button" target="_blank" href="/download.php?report='.$check_for_PDF['ha_report_file'].'" title="Download Report">Download Report</a>';
          }
        } elseif($ha_report_id=='Cc') {
          //don't show purchase button if Cc
          //echo '<a class="button" href="/download.php?report='.$check_for_PDF['ha_report_file'].'" title="Download Report">Download Report</a>';
        } else {


          if($pageObj->ID != 1945)
              echo '<a class="button" href="/pricing-and-packages/">Purchase Report</a>'; 

           $sample = get_field('report_sample', $pageObj->ID); 
           if($sample != '')
              echo '<a class="button" target="_blank" href="'.$sample.'">View sample report</a>';  		
	      }
    } else {


          if(in_array($pageObj->ID, array(1945,423))) {
                echo '<a class="button" href="/free-trial/">Start Free Trial</a>';

          } 

          if($pageObj->ID != 1945) {
                echo '<a class="button" href="/pricing-and-packages/">Purchase report</a>'; 
            }



         $sample = get_field('report_sample', $pageObj->ID); 
         if($sample != '')
            echo '<a class="button" target="_blank" href="'.$sample.'">View sample report</a>';       
    }
?>




    </article>
</div>

<?php get_footer(); ?> 