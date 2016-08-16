<div class="cns-strap">
  <div class="body">

    <?php 
    $display_title = get_field('page_display_title', $pageObj->ID); 
    $content = $pageObj->post_content;

    //Set a sesh var so we know they have just come from HA survey and are waiting for results
    if (isset($_GET['DocumentID'])) {
       $_SESSION['just_completed_survey'] = true;
    }    

    global $current_user;
    get_currentuserinfo();
    ?>

    <h1><?php echo 'Hi '.$current_user->first_name; ?></h1>
    <?php
        $survey_url = get_user_meta( $current_user->ID, 'survey_url', true );
        $survey_completed = get_user_meta( $current_user->ID, 'survey_completed', true );
        
        if($survey_completed == 1){
            $survey_completed_date = get_user_meta( $current_user->ID, 'survey_completed_date', true );
            //echo 'Questionnaire completed: '.date('d/m/Y', strtotime($survey_completed_date));
            echo 'Questionnaire completed: '.$survey_completed_date;
        }elseif( isset($_SESSION['just_completed_survey']) && !$survey_completed ){
            echo 'We are generating your results - Please wait...';
        }else {
            $survey_url = get_user_meta( $current_user->ID, 'survey_url', true );
            echo 'You haven\'t completed the questionnaire yet! Complete it ';
            echo '<a href="'.$survey_url.'" title="Take the questionnaire">here</a>';
        }

        if($pageObj->ID == 308)
            $activePage = 'welcome';
        else if ($pageObj->ID == 420 || $pageObj->post_type == 'reports')
            $activePage = 'cns-reports';
        else if($pageObj->ID == 431)
            $activePage = 'pricing-and-packages';
    ?>

    <ul class="cns-tabs">
      <li><a<?php echo ($activePage == 'welcome') ? ' class="active"' : ''; ?> href="/welcome/">Welcome</a></li>
      <li><a<?php echo ($activePage == 'cns-reports') ? ' class="active"' : ''; ?> href="/cns-reports/">Reports</a></li>
      <li><a<?php echo ($activePage == 'pricing-and-packages') ? ' class="active"' : ''; ?> href="/pricing-and-packages/">Pricing &amp; Packages</a></li>
    </ul>

    <form name="cnstabs" class="cns-tabs" method="post" action="/wp-content/themes/forsee/mobile-redirect.php">
        <select name="destination" onchange="document.cnstabs.submit()">
            <option<?php echo ($activePage == 'welcome') ? ' selected="selected"' : ''; ?> value="welcome">Welcome</option>
            <option<?php echo ($activePage == 'cns-reports') ? ' selected="selected"' : ''; ?> value="cns-reports">Reports</option>
            <option<?php echo ($activePage == 'pricing-and-packages') ? ' selected="selected"' : ''; ?> value="pricing-and-packages">Pricing &amp; Packages</option>
        </select>
        <input type="hidden" name="action" value="redirect" />
    </form>

    <div class="cns-options">
        <div class="cns-option-exit"><a href="<?php echo wp_logout_url('/login/'); ?>">Exit</a></div>
        <div class="cns-option-settings"><a href="/settings/">Settings</a></div>
        <?php if($pageObj->post_type == 'reports') { ?>
        <div class="cns-option-reports"><a href="/cns-reports/">Back to Reports</a></div>
        <?php } ?>
        <?php if($template == 'taxonomy-courses') { ?>
        <div class="cns-option-reports"><a href="/cns-courses/">Back to Courses</a></div>
        <?php } ?>
        <?php if($template == 'single-course') { ?>
        <div class="cns-option-reports"><a href="javascript:history.go(-1);">Back to Courses</a></div>
        <?php } ?>
    </div>
  </div>
</div>