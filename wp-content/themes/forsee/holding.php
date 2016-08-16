<?php
/**
 * @package WordPress
 * @subpackage Forsee 2015
 * Template Name: Holding
*/
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<title>Forsee Career Technology</title>
<base href="/wp-content/themes/forsee/" />
<link rel="shortcut icon" type="image/x-icon" href="assets/images/favicon.ico" />
<link rel="stylesheet" href="assets/css/holding.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/3.1.2/css/swiper.min.css">
<link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300' rel='stylesheet' type='text/css'>
<link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet' type='text/css'>
<link href='https://fonts.googleapis.com/css?family=Lato' rel='stylesheet' type='text/css'>
<style type="text/css">
body { 
	padding: 0px; 
	margin: 0px;
	font-family: 'Source Sans Pro', sans-serif; 
	font-weight: 300; 
	color:#ffffff; 
}
.banner {
	width: 100%;
	background-image: url(assets/images/background-cityscape.jpg);
	background-size: cover;
}
.inner {
	width: 50%;
	text-align: center;
	margin: 0 auto;
	padding-top: 15%;
	padding-bottom: 15%;
}
img {
	width: 80%;
	max-width: 340px;
}
</style>
</head>
<?php wp_head(); ?>
<body>


	<div class="banner">
		<div class="inner">
			<img src="assets/images/forsee-career-technology.png" alt="Forsee Career Technology" />
			<h1><?php echo apply_filters('the_content', $post->post_content); ?></h1>
		</div>
	</div>


    <?php wp_footer(); ?>
</body>
</html>