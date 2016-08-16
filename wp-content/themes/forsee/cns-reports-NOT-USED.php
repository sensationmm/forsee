<?php

  if(!is_user_logged_in()) {
    wp_redirect('/login/');
  }
/**
 * @package WordPress
 * @subpackage Forsee 2015
 * Template Name: CNS Reports
*/
get_header(); 
$user_id = get_current_user_id();
$survey_url = get_user_meta( $user_id, 'survey_url', true ); 
$survey_completed = get_user_meta( $user_id, 'survey_completed', true );  
$survey_completed_date = get_user_meta( $user_id, 'survey_completed_date', true ); 

?>
	<?php include 'cns-header.php'; ?>

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
    <?php

      if(!$survey_completed){
          echo '<p>You still need to complete your survey. (If you have just finished it please refresh this page in a minute as we are still generating your results.</p>
                <div class="button"><a href="'.$survey_url.'" title="Complete Survey">Complete Survey</a></div>
                <script>
                  setTimeout(function(){
                     window.location.reload(1);
                  }, 20000);
                </script>        
            ';
      }
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
              $desc = get_the_content();
              $image = get_field('report_image', $ID);
              $price = get_field('report_price', $ID);
              $link = get_field('report_link', $ID);
              $label = get_field('report_label', $ID);
              $label2 = get_field('report_secondlabel', $ID);
              $link2 = get_field('report_secondlink', $ID);
              $ha_report_id = get_field('report_id', $ID);
              $check_for_PDF = Check_ha_report_generated($ha_report_id);

//var_dump( $check_for_PDF );


              echo '<article class="report">';
                  echo '<div class="report-image">';
                      echo '<a href="'.$link.'" title="'.$label.'"><img src="'.$image['url'].'" alt="'.$image['alt'].'" /></a>';

                      echo '<div class="overlay"><a href="'.$link.'" title="'.$label.'"></a></div>';
                  echo '</div>';

                  echo '<div class="report-info">';
                      echo '<h2>';
                      echo $title;
                      if($price != '')
                          echo ' <span class="price-detail">'.$price.'</span>';
                      echo '</h2>';
                      echo apply_filters('the_content', $desc);

                      //Do we need to show link to XML HA data
                      if($ha_report_id=='Co2' && $ha_data['count']>10){
                        //Have full data from HA
                        echo '<a class="button" href="/careers-in-order-of-likely-level-of-enjoyment/" title="report">View Report</a>';
                      }elseif($ha_data['count']==10){
                        //Trial data avail
                        echo '<a class="button" href="/careers-in-order-of-likely-level-of-enjoyment/" title="report">View Trial Report</a>';
                      }

                      //Do we have PDF link to allow them to download                      
                      if($check_for_PDF['ha_report'] && !$check_for_PDF['ha_report_file']){
                        echo '<img src="/wp-admin/images/loading.gif" >Waiting for report to download
                              <script>
                                setTimeout(function(){
                                   window.location.reload(1);
                                }, 20000);
                              </script>  
                        ';
                      }elseif($check_for_PDF['ha_report'] && $check_for_PDF['ha_report_file']){
                        echo '<a class="button" href="/download.php?report='.$check_for_PDF['ha_report_file'].'" title="Download Report">Download Report</a>';
                      }    

                      if($link != '') {
                          echo '<a class="button" href="'.$link.'" title="'.$label.'">'.$label.'</a>';
                      }

                      if($link2 != '') {
                          echo '<a class="button" href="'.$link2.'" title="'.$label2.'">'.$label2.'</a>';
                      }

                  echo '</div>';
              echo '</article>';
            endwhile;
        endif;
    ?>
  </div>

<?php get_footer(); ?> 