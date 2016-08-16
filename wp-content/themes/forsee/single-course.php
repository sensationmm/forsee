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
    <div class="banner" style="background-image:url(<?php echo get_field('page_banner', 413); ?>);">
        <div class="body">
            <?php 
              $display_title = get_field('page_display_title', 413); 
            ?>

            <h1>Course Information</h1>
            <p>&nbsp;</p>
        </div>
    </div>
<?php } ?>

<div class="body outer">
    <article class="course-details">
    <?php
        $courseID = get_field('course_id', $pageObj->ID);
        $online = get_field('course_glance_online', $pageObj->ID);
        $campus = get_field('course_glance_campus', $pageObj->ID);
        $blended = get_field('course_glance_blended', $pageObj->ID);
        $tutor = get_field('course_glance_tutor', $pageObj->ID);
        $government_assisted = get_field('course_glance_government_assistance', $pageObj->ID);
        $conference_events = get_field('course_glance_conference_events', $pageObj->ID);
        $payment_options = get_field('course_glance_payment_options', $pageObj->ID);
    ?>

    	<h2>
        <?php 
            if($courseID != '')
                echo $courseID.' - ';
            echo $pageObj->post_title; 
        ?>
        </h2>
        <?php echo apply_filters('the_content', $pageObj->post_content); ?>

        <div class="course-icons">
        	<div class="course-icons-header">At a glance</div>
        	<ul>
            <?php
                $atAGlance = array('online',
                                   'campus',
                                   'blended',
                                   'tutor',
                                   'government_assisted',
                                   'conference_events',
                                   'payment_options');

                for($g=0; $g<sizeof($atAGlance); $g++) {
                    $label = str_replace('_',' ',$atAGlance[$g]);
                    $slug = str_replace('_','',$atAGlance[$g]);
                    echo '<li ';
                    if($$atAGlance[$g] == 1)
                        echo 'class="active" ';
                    echo 'id="courseicon-'.$slug.'">';
                    if($$atAGlance[$g] == 1)
                        echo '<img src="assets/images/courseicon-'.$slug.'.gif" alt="'.ucwords($label).' Course" />';
                    else echo '&nbsp;';
                    echo '</li>';
                }
            ?>
    	</div>
        
        <?php
            $course_url = get_field('course_link', $pageObj->ID);
            if($course_url != '')
                echo '<a class="button" href="'.$course_url.'" title="Register your interest" target="_blank">Register your interest</a>';
        ?>
    </article>

    <section class="course-info">
        <dl>
        <?php
            $course_type = get_field('course_type', $pageObj->ID);
            $course_study_modes = get_field('course_study_modes', $pageObj->ID);
            $course_start_date = get_field('course_start_date', $pageObj->ID);

            if($course_type != '')
                echo '<dt>Course Type</dt><dd>'.$course_type.'</dd>';
            if($course_study_modes != '')
                echo '<dt>Study Modes</dt><dd>'.$course_study_modes.'</dd>';
            if($course_start_date != '')
                echo '<dt>Start Date</dt><dd>'.$course_start_date.'</dd>';
        ?>
        </dl>

        <p><a class="back" href="javascript:history.go(-1);" title="Back to Courses">&lt; Back to Courses</a></p>
    </section>
</div>

<?php get_footer(); ?> 