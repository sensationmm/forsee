<?php

if(!is_user_logged_in()) {
	wp_redirect('/login/');
}
/**
* @package WordPress
* @subpackage Forsee 2015
* Template Name: CNS Enjoyment
*/
$user_id = get_current_user_id();
get_header();  
global $isCNS;
$isCNS = true;
global $wpdb;
$Career_Array = array();
$Products_Ordered = WC_What_Products_Have_They_Ordered(311);

$survey_url = get_user_meta( $user_id, 'survey_url', true ); 
$survey_completed = get_user_meta( $user_id, 'survey_completed', true ); 

//Check Search
if( isset($_POST["searchcareers"]) ) {
	$searchcareers = sanitize_text_field( $_POST["searchcareers"] );
} else {
	$searchcareers = false;
}

//Check Filter
if( isset($_POST["filtercareers"]) ) {
	$filtercareers = sanitize_text_field( $_POST["filtercareers"] );
} else {
	$filtercareers = false;
}
//Check to make sure it's a valid one as extra security
if($filtercareers=='year_11_or_below' || $filtercareers=='year_12_or_equivalent' || $filtercareers=='licenses_vet_certificate_i_to_iii' || $filtercareers=='vet_certificate_iv_diploma_advanced_diploma' || $filtercareers=='bachelors' || $filtercareers=='masters' || $filtercareers=='doctorate' ) {
//All good no change change anything
} else {
	$filtercareers = false;
}

//Shortlist filter
if( isset($_POST["shortlist"]) ) {
	$shortlist_filter = true;
} else {
	$shortlist_filter = false;
}



//Get all users courses - ordered by score
if($user_id) {
//$courses = $wpdb->get_results("SELECT * FROM `ha_data` WHERE `user_id` = $user_id ORDER BY `meta_score` DESC");

	$courses = $wpdb->get_results(
		//$wpdb->prepare( "SELECT * FROM table WHERE id = %d", $id );
		$wpdb->prepare( "SELECT * FROM `ha_data` WHERE `user_id` = %s ORDER BY `meta_score` DESC", $user_id )
	);

	$count_courses = count ( $courses );   

//Grab all the results from DB or filter them by education level  year_12_or_equivalent     
	if($filtercareers) {
		$args = array(
			'posts_per_page'  => -1,
			'post_type'   => 'career_desc',
			'meta_key'    => $filtercareers,
			'meta_value'  => 'true',

			);
	} else {
		$args = array(
			'posts_per_page'  => -1,
			'post_type'   => 'career_desc',
			); 
	}

	// query
	$the_query = new WP_Query( $args );       

	?>
	<?php if( $the_query->have_posts() ): 
	$count=0;
	while( $the_query->have_posts() ) : $the_query->the_post(); 
	// the_title(); 
	$Get_id_career = trim(get_field('id_career', $the_query->post->ID));
	//$Career_Array[$count]['id']=$Get_id_career; 
	foreach($courses as $course) {   
		$course_meta_id = trim($course->meta_id);
		if( $Get_id_career == $course_meta_id ) { 
			$Career_Array[$count]['id']=$Get_id_career; 
			$Career_Array[$count]['id_match']=$course_meta_id; 
			$Career_Array[$count]['meta_score']=$course->meta_score;
			$Career_Array[$count]['meta_desc']=$course->meta_desc; 
			$Career_Array[$count]['career_record_id']=$course->id;
			$Career_Array[$count]['favourite']=$course->favourite;
			continue; //skip other records as only need 1 match
		}
	}       

$count++;                
endwhile;
endif;
wp_reset_query();  // Restore global post data stomped by the_post(). 

if($Career_Array) {
	foreach ($Career_Array as $key => $row) {
		$mid[$key]  = $row['meta_score'];
	}

	// Sort the data with mid descending
	// Add $data as the last parameter, to sort by the common key
	array_multisort($mid, SORT_DESC, $Career_Array);  
} 

//echo '<prE>';var_dump($Career_Array);echo ';</pre>';

?>



<?php include 'cns-header.php'; ?>


<div class="body outer">
	<h2><?php echo ($display_title != '') ? $display_title : $pageObj->post_title; ?></h2>
	<?php echo apply_filters('the_content', $pageObj->post_content); ?>

	<div class="courses">
	<?php
		if(!$survey_completed) {
			echo '<p>You still need to complete your survey. (If you have just finished it please refresh this page in a minute as we are still generating your results.</p>
				<div class="button"><a href="'.$survey_url.'" title="Complete Survey">Complete Survey</a></div>
				<script>
				setTimeout(function() {
					window.location.reload(1);
				}, 20000);
				</script>';
		} else {
			?> 

			<div class="career-list">    
			<form class="filter-careers" action="/careers-in-order-of-likely-level-of-enjoyment/" method="post">
				<div class="filter-holder">
					<select id="filtercareers" name="filtercareers" class="form-control input-sm">
						<?php
						if($shortlist_filter) {
							echo '<option value="all">Please select</option>';
						}
						?>                          
						<option value="all">All</option>
						<option <?php if($filtercareers=='year_11_or_below') {echo 'selected';}?> value="year_11_or_below">Year 11 or Below</option>
						<option <?php if($filtercareers=='year_12_or_equivalent') {echo 'selected';}?> value="year_12_or_equivalent">Year 12 or Equivalent</option>
						<option <?php if($filtercareers=='licenses_vet_certificate_i_to_iii') {echo 'selected';}?> value="licenses_vet_certificate_i_to_iii">Licenses / VET Certificate I to III</option>
						<option <?php if($filtercareers=='vet_certificate_iv_diploma_advanced_diploma') {echo 'selected';}?> value="vet_certificate_iv_diploma_advanced_diploma">VET Certificate IV / Diploma / Advanced Diploma</option>
						<option <?php if($filtercareers=='bachelors') {echo 'selected';}?>  value="bachelors">Bachelor's</option>
						<option <?php if($filtercareers=='masters') {echo 'selected';}?> value="masters">Master's</option>
						<option <?php if($filtercareers=='doctorate') {echo 'selected';}?> value="doctorate">Doctorate</option>
					</select>  
				</div>
			</form> 


			<form class="view-shortlist" action="/careers-in-order-of-likely-level-of-enjoyment/" method="post">
				<?php if($shortlist_filter === false) { ?>
					<input type="submit" name="shortlist" id="fav" value="View Shortlist" />
				<?php } else { ?>
					<input type="submit" name="shortlist_off" id="fav" value="View Full list" />
				<?php } ?>
			</form>                         

			<script type="text/javascript"> 
			$(function() {
				$('#filtercareers').change(function() {
					this.form.submit();
				});
			});
			</script> 
<!--[if lte IE 8]>
<script charset="utf-8" type="text/javascript" src="//js.hsforms.net/forms/v2-legacy.js"></script>
<![endif]-->
<script charset="utf-8" type="text/javascript" src="//js.hsforms.net/forms/v2.js"></script>


			<div class="career-list-scroll<?php echo ($searchcareers !== false || $filtercareers !== false || $shortlist_filter !== false) ? ' autoheight' : ''; ?>">    
			<?php
			$Top_Career_ID_Match = false;
			$Normal_loop_count = 0;
			$Fav_Loop_count = 0;
			$Search_Loop_count = 0;

			if($count_courses<11 && $Products_Ordered['all']['309']) {
				echo '<br /><img src="/wp-admin/images/loading.gif" > We are currently generating your results, please check back in 1 minute.<br /><br />
				<script>
				setTimeout(function() {
					window.location.reload(1);
				}, 20000);
				</script>';
			}

			echo '<ul>';

			if($count_courses<11 && !$Products_Ordered['all']['309']) {
				$Normal_loop_count = 10;
				echo 'To unlock all 680+ results please purchase the Career Options Report or the Career Pack.<br /><br />';
				echo '<a class="button" href="/pricing-and-packages/">Purchase complete Report</a><div class="clear"></div><br />';

				for($c=0; $c<10; $c++) {
					echo '<li><div class="list-count">';
					echo str_pad($c+1,2,'0', STR_PAD_LEFT).'.&nbsp;&nbsp;';
					echo '(??%) </div>';
					echo '<span class="fav favhide"></span> ';
					echo '<div class="purchase-to-unlock">Purchase report to unlock</div>';
					echo '</li>';
				}
			}


			foreach($Career_Array as $career_to_show) { 
				$Normal_loop_count++;
				if($career_to_show['favourite']==1) {
				//$getallheaders(oid)_bloginfo('template_directory').'/assets/images/heart-love.jpg" /></a>';
					$fav = 'fav favyes';
				} else {
				//$fav = '<a href="#" class="heart" rel="'.$career_to_show['id'].'" data-rel="'.$career_to_show['favourite'].'"><img id="heart_id" src="'.get_bloginfo('template_directory').'/assets/images/heart-no-love.jpg" /></a>';
					$fav = 'fav favno';
				}

				echo '<li>';
				if($shortlist_filter) {
					if($career_to_show['favourite']==1) {
						$Fav_Loop_count++;

						echo '<div class="list-count">'.str_pad($Normal_loop_count,3,'0', STR_PAD_LEFT).'.&nbsp;&nbsp;('.$career_to_show['meta_score'].'%)</div><span id="'.$career_to_show['id'].'" class="'.$fav .'" rel="'.$career_to_show['id'].'"></span> <a href="#" class="course_id" rel="'.$career_to_show['id'].'">Details</a> <div>'.$career_to_show['meta_desc'].'</div>';
						if($Fav_Loop_count==1) {
							$Top_Career_ID_Match = $career_to_show['id'];
						}
					}
				} else {

					if($searchcareers !== false) {
						if(stripos($career_to_show['meta_desc'], $searchcareers) !== false) {
							$Search_Loop_count++;
							echo '<div class="list-count">'.str_pad($Normal_loop_count,3,'0', STR_PAD_LEFT).'.&nbsp;&nbsp;('.$career_to_show['meta_score'].'%) </div><span id="'.$career_to_show['id'].'" class="'.$fav .'" rel="'.$career_to_show['id'].'"></span> <a href="#" class="course_id" rel="'.$career_to_show['id'].'">Details</a> <div>'.$career_to_show['meta_desc'].'</div>';
							if($Search_Loop_count==1) {
								$Top_Career_ID_Match = $career_to_show['id'];
							}
						}
					} else {
						echo '<div class="list-count">'.str_pad($Normal_loop_count,3,'0', STR_PAD_LEFT).'.&nbsp;&nbsp;('.$career_to_show['meta_score'].'%) </div><span id="'.$career_to_show['id'].'" class="'.$fav .'" rel="'.$career_to_show['id'].'"></span> <a href="#" class="course_id" rel="'.$career_to_show['id'].'">Details</a> <div>'.$career_to_show['meta_desc'].'</div>';
						$Top_Career_ID_Match = $Career_Array[0]['id_match'];
					}

				}
				echo '</li>';
			}
			echo '</ul>';

			if($searchcareers !== false) {
				echo '<div class="back-to-list">';
				echo '<a class="button" href="'.$_SERVER["REQUEST_URI"].'" title="Back to full list">Back to full list</a>';
				echo '</div>';
			}
			echo '</div>';

			echo '<form class="search-careers" action="/careers-in-order-of-likely-level-of-enjoyment/" method="post">';
			echo '<input type="text" placeholder="Search careers..." name="searchcareers" '.(($searchcareers != '') ? 'value="'.$searchcareers.'" ' : '').'/>';
			echo '<input type="submit" name="shortlist_off" id="fav" value="Search" />';
			echo '</form>';

			$Co2_report_Avail_In_PDF = Check_ha_report_generated('Co2');
			if($Co2_report_Avail_In_PDF['ha_report_file']){
				echo '<a class="button-full" target="_blank" href="/download.php?report='.$Co2_report_Avail_In_PDF['ha_report_file'].'" title="Generate report for career list above">Generate report for career list above</a>';
			}
			echo '</div>';

			echo '<a class="button back-to-list">&lt; Back to list</a>';
			echo '<div class="career-details" id="Career_Info_Area">';
			echo '<div class="career-details-loading"><img src="/wp-admin/images/spinner-2x.gif" /></div>';
			echo '</div> ';
			echo '<a class="button back-to-list">&lt; Back to list</a>';
		}
	}


?>



<script type="text/javascript">

function fsContactCheck(id) {

	var form = $('form#contact'+id);
	var phone = form.find('input[name="phone"]').val();
	var optin = form.find('input[name="career_contact_me_opt_in"]').prop('checked');
	var errorsField = form.find('.form-errors');
	var errors = '';

	if(phone == '') {
		errors += 'You must enter your preferred contact number<br />';
	}
	if(optin == false) {
		errors += 'You must confirm you wish to be contacted about this course<br />';
	}

	if(errors != '') {
		errorsField.html(errors);
		errorsField.fadeIn('fast');
		return false;
	} else {
		return fsContactMe(id);
	}
}

function fsContactMe(id) {
	var form = $('form#contact'+id);
	form.parent().parent().find('.course-contact-overlay').fadeIn('fast');

	var course = form.find('input[name="career_contact_me_about_course"]').val();
	var broker = form.find('input[name="course_broker_name"]').val();
	var email = form.find('input[name="email"]').val();
	var phone = form.find('input[name="phone"]').val();
	var optin = form.find('input[name="career_contact_me_opt_in"]').val();

	var formData = {
            'course'   : course,
            'broker'   : broker,
            'email'    : email,
            'phone'    : phone,
            'optin'    : optin
        };

        // process the form
        $.ajax({
            type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
            url         : '/wp-content/themes/forsee/ajax-course-contact.php', // the url where we want to POST
            data        : formData, // our data object
        }).done(function(data) {

            form.parent().parent().find('.contact-broker-spiel').css('display','none');
			form.parent().parent().find('.course-contact-overlay').fadeOut('fast');
            form.parent().html("<p>Thanks, we will be in touch within 1-3 working days.</p>");

        }).fail(function(data) {

            form.parent().parent().find('.contact-broker-spiel').css('display','none');
			form.parent().parent().find('.course-contact-overlay').fadeOut('fast');
            form.parent().html("<p>Sorry! Something went wrong... Please try again</p>");

        });







	return false;
}

var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
var tOut = false; 
//var forcepollingstart = false;

function startpoll(career_id_passed) {
	var carID = career_id_passed;
	(function poll() {
		tOut = setTimeout(function() {
			$.ajax({
				url: ajaxurl,
				type: "POST",
				data: {
					action: 'check_report_generated',
					career_id: carID
				},            
				success: function(data) {                  
					if(data.foundreport) {
						$('#report_request_container').html( data.foundreport );                   
					} else {
						poll();
					}
					console.log( data );

				},
				dataType: "json",
				//complete: poll,
				//timeout: 2000
			})
		}, 5000);
	})();
}


$(document).on('click','[id^=request_report]',function(e) {

	$('#report_request_container').html('<br /><img src="/wp-admin/images/loading.gif" /> Please wait while we request your report...<br /><br />');

	var carID = $(this).attr('rel');
	//console.log(popID);
	jQuery.ajax({
		url: ajaxurl,
		data: {
			action: 'request_report',
			career_id: carID,
			report_type: 'cc'
		},
		success: function(data) {
			//$('#Career_Info_Area').html(data);
			//alert(data);
			$('#report_request_container').html(data);
			startpoll(carID);
		},        
		type: 'POST'
	});

	//return false; // prevent default
	e.preventDefault();
});


$(document).ready(function() {

	/*************************************/
	/** CEA COURSE CONTACT **/
	/*************************************/
	$('form.course-contact').submit(function(event) {

		console.log('submitted');

        event.preventDefault();
	});




	var mobileCheck = {
    Android: function() {
        return navigator.userAgent.match(/Android/i);
    },
    BlackBerry: function() {
        return navigator.userAgent.match(/BlackBerry/i);
    },
    iOS: function() {
        return navigator.userAgent.match(/iPhone|iPad|iPod/i);
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

$careerList = $('.career-list');
$careerDetails = $('.career-details');
$backToList = $('a.back-to-list');

	<?php
	if($Top_Career_ID_Match) {
		?>
		//Load top match as first for detail box
		var topid = "<?php echo $Top_Career_ID_Match;?>";
		jQuery.ajax({
			url: ajaxurl,
			data: {
				action: 'get_career_details',
				career_id: topid
			},
			success: function(data) {
				data = data.replace(new RegExp('h2', 'g'), 'h4');
				data = data.replace(new RegExp('h1', 'g'), 'h3');
				$('#Career_Info_Area').html(data);
				//alert('Load was performed.');
			},        
			type: 'POST'
		});
<?php
	} else {
	?>
		$('#Career_Info_Area').html('<div style="text-align:center;"><br />Select a career option from the list<br /><br /></div>');
	<?php
	}
?>

	//Load Career info on click
	$('a.course_id').click(function(e) { 

		var carID = $(this).attr('rel');
		//console.log(popID);

		if(isMobile) {
			$careerList.fadeOut('fast');
		}

		$('#Career_Info_Area').html('<div class="career-details-loading"><img src="/wp-admin/images/spinner-2x.gif" /></div>');

		jQuery.ajax({
			url: ajaxurl,
			data: {
				action: 'get_career_details',
				career_id: carID
			},
			success: function(data) {        
				data = data.replace(new RegExp('h2', 'g'), 'h4');
				data = data.replace(new RegExp('h1', 'g'), 'h3');
				clearTimeout(tOut);

				$('#Career_Info_Area').html(data);
				//alert('Load was performed.');
				if(isMobile) {
					$careerDetails.fadeIn('fast');
					$('body').scrollTop($('#Career_Info_Area').offset().top);
					$backToList.fadeIn('fast').css('display','block').attr('rel',carID);
				}
			},        
			type: 'POST'
		});

		//return false; // prevent default
		e.preventDefault();
	});

	$('a.back-to-list').click(function(e) { 
		$careerDetails.fadeOut('fast');
		$careerList.fadeIn('fast', function() {
			target = '#'+$backToList.attr('rel');
			console.log(target);
			$('body').scrollTop($(target).offset().top);
		});
		$backToList.fadeOut('fast');

		
	});

	//Favourite action
	//heartload(); //Re-initialise the javascript
	//$('a.heart').click(function(e) { 
	$('span.fav').click(function(e) { 
		var carID = $(this).attr('rel');
		var favstatus = this.className;
		//console.log(favstatus);
		//var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
		jQuery.ajax({
			url: ajaxurl,
			data: {
				action: 'heart_fav',
				career_id: carID,
				favstatus: favstatus
			},
			success: function(data) {
				//$('#'+carID).html(data);
				//Change the fav status
				if(favstatus=='fav favyes') {             
					$('#'+carID).removeClass( "favyes" );
					$('#'+carID).addClass( "favno" );
				}      

				if(favstatus=='fav favno') {
					$('#'+carID).removeClass( "favno" );
					$('#'+carID).addClass( "favyes" );
				}
			},        
			type: 'POST'
		});

		//return false; // prevent default
		e.preventDefault();
	}); 

	$('.back-to-top').click(function() {
		$('html,body').animate({
            scrollTop: 0
        }, 500);
	});

	if(isMobile) {
		$(window).bind('scroll', function() {
			if(window.pageYOffset > 500) {
				$('.back-to-top').fadeIn('fast');
			} else {
				$('.back-to-top').fadeOut('fast');
			}
		});
	}

});

function heartload() {
	//Favourite action
	$('a.heart').click(function(e) { 
		var carID = $(this).attr('rel');
		var favstatus = $(this).attr('data-rel');
		//console.log(favstatus);
		var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
		jQuery.ajax({
			url: ajaxurl,
			data: {
				action: 'heart_fav',
				career_id: carID,
				favstatus: favstatus
			},
			success: function(data) {
				$('#'+carID).html(data);
				heartload();
			//alert('Load was performed.');
			},        
			type: 'POST'
		});

		//return false; // prevent default
		e.preventDefault();
	});  
}

function setModalDisplay(target) {
	$modal = $('.contact-overlay');
	if(target === 0)
		$modal.fadeOut('fast');
	else
		$modal.fadeIn('fast');
}
</script>

<!--script type="text/javascript">
var hsform = hbspt.forms.create({ 
	portalId: '493485',
	formId: '959e731e-1421-40ed-83eb-e3dc4992ddba',
	onFormReady: function($form) {
		$('input[name="career_contact_me_about_course"]').val('<?php echo get_the_title(); ?>').change();
		$('input[name="email"]').val('ralph@ec8.co').change();
		$('input[name="course_broker_name"]').val('<?php echo $broker->post_title; ?> ').change();
	}
});
document.write(hsform);
</script-->


</div>

</div>
<div class="back-to-top">&#8679;</div>

<?php get_footer(); ?> 