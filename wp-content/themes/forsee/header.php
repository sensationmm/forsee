<?php
	global $headerInclude, $post, $pageObj, $template, $isCorporate;
	$pageObj = $post;
	$template = get_current_template();


	$pageURL = 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . "{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
	$pageURL = strtok($pageURL, '?');
	$shopPages = array('archive-product.php','single-product.php');
	if(in_array($template, $shopPages) || $template == 'woocommerce-page.php') {
		$template = 'woocommerce';
		if(in_array($pageObj->ID, array(1953)) && !is_user_logged_in() && $pageURL != wp_lostpassword_url())
			header('Location: /login/');
	} else
		$template = substr($template, 0, stripos($template, '.'));

	if($template == 'taxonomy-courses') {
		$term = $wp_query->get_queried_object();
		$pageTitle = $term->name.' Courses :: ';
	} else if($pageObj->ID != 8)
		$pageTitle = $pageObj->post_title.' :: ';
	else 
		$pageTitle = '';

	if($template != 'home') {
		$top = $pageObj; 
		while($top->post_parent != 0) {
			$parentPage = get_page($top->post_parent);
			$top = $parentPage;
		}
		$corporateFlag = get_field('home_corporate', $top->ID);
	} else {
		$corporateFlag = get_field('home_corporate', $pageObj->ID);
	}
	$isCorporate = ($corporateFlag == '1') ? true : false;
	$corporateHome = 4133;
	// echo ($isCorporate) ? 'corporate' : 'regular';
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<title><?php echo $pageTitle; ?>Forsee Career Technology</title>
<base href="/wp-content/themes/forsee/" />
<link rel="shortcut icon" type="image/x-icon" href="assets/images/favicon.ico" />
<link rel="stylesheet" href="assets/css/style.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/3.1.2/css/swiper.min.css">
<link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,200' rel='stylesheet' type='text/css'>
<link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet' type='text/css'>
<link href='https://fonts.googleapis.com/css?family=Lato' rel='stylesheet' type='text/css'>
<?php echo $headerInclude; ?>
<?php wp_head(); ?>
</head>
<body id="<?php echo $template; ?>">
<?php if ( function_exists( 'gtm4wp_the_gtm_tag' ) ) { gtm4wp_the_gtm_tag(); } ?>
	<header>
  		<div class="body">
			<div class="logo">
			<?php if ($isCorporate) { ?>
				<a href="<?php echo get_permalink($corporateHome); ?>" title="View homepage"><img src="assets/images/forsee-career-technology.png" /></a>
			<?php } else { ?>
				<a href="/" title="View homepage"><img src="assets/images/forsee-career-technology.png" /></a>
			<?php } ?>
			</div>

			<?php if($template != 'splash-landing') { ?>
			<div class="navi">      
                
				<nav class="main">
					<div class="dropdown-label">Menu</div>
					<div class="dropdown">
						<?php
							if($isCorporate) {
			    				$nav = wp_get_nav_menu_items('header-corporate');
			    			} else {
				    			$nav = wp_get_nav_menu_items('header');
				    		}
			    			if(sizeof($nav) > 0) {
			                    echo '<div class="mobile-nav-close"></div>';

			    				echo '<ul>';
			    				for($i=0; $i<sizeof($nav); $i++) {
			    					$navPage = get_field('_menu_item_object_id', $nav[$i]->ID);
			    					$navPage = get_post($navPage);
			    					echo '<li>';

			    					echo '<a ';
			    					if($pageObj->ID == $navPage->ID)
			    						echo 'class="active" ';
			    					echo 'href="'.get_permalink($navPage->ID).'" title="Go to '.$navPage->post_title.'">'.$nav[$i]->title.'</a>';
			    					echo '</li>';
				    			}
			    				echo '</ul>';
				    		}
			    		?>
					</div>
				</nav>
				<?php

				if($isCorporate) {

	                echo '<div class="free-trial">';
						echo '<a class="cta_button " href="/" title="View Individual Site">Individual</a>';
					echo '</div>';

				} else {

					global $user_login;
					get_currentuserinfo();
					if(is_user_logged_in()) {
						echo '<div class="login logged-in"><a href="/welcome/" title="Go to My Account">My Account</a></div>';
					} else {
					?>
	                <!--div class="free-trial">
						<a class="cta_button " href="<?php echo get_permalink($corporateHome); ?>" title="View Corporate Site">Corporate</a>
					</div-->
	                <div class="free-trial">
						<a class="cta_button " href="/free-trial/" title="Start your Free Trial">Free Trial</a>
					</div>
					<?php
						echo '<div class="login"><a href="/login/" title="Login to My Account">Login</a></div>';
					}
				}
				?>          
			</div>
			<?php } ?>
		</div>
	</header>