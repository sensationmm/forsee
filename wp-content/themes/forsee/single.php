<?php
	if($post->post_type == 'reports')
		include 'single-report.php';
	else if($post->post_type == 'course')
		include 'single-course.php';
	else if($post->post_type == 'lp')
		include 'single-landingpage.php';
	else if($post->post_type == 'post')
		include 'single-blog.php';
	else
		header('Location: /not-found/');
?>