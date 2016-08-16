<?php
/**
 * @package WordPress
 * @subpackage Forsee 2015
*/
get_header();  

//echo $pageObj->post_type;
$blogPage = get_page(85);
$display_title = get_field('page_display_title', $blogPage->ID); 
$strapline = get_field('page_strapline', $blogPage->ID); 
?>
	<div class="banner" style="background-image:url(<?php echo get_field('page_banner', $blogPage->ID); ?>);">
		<div class="body">
			<h1><?php echo ($display_title != '') ? $display_title : $blogPage->post_title; ?></h1>
			<p><?php echo $strapline; ?></p>
		</div>
	</div>

	<div class="body outer">
		<article class="col-left">
		<p><a href="/blog/" title="Back to blog">&lt; Back to blog</a></p>
		<?php
			echo '<h1>'.$post->post_title.'</h1>';
			echo '<div class="blog-date">'.get_the_date('j M Y').'</div>';

			echo apply_filters('the_content', '[shareaholic app="share_buttons" id="21484445"]');

			echo apply_filters('the_content', $post->post_content);
		?>
		<p><a href="/blog/" title="Back to blog">&lt; Back to blog</a></p>
		</article>

		<section class="col-right">
		<?php
			$categories = get_categories();
			$categories = array_values($categories);
			if(sizeof($categories) > 0) {
				echo '<div class="list-header">Categories</div>';
				echo '<ul class="list">';
				echo '<li><a';
				if(!isset($_GET["cat_id"]))
					echo ' class="active"';
				echo ' href="/blog/" title="View all articles">All</a></li>';
				for($c=0; $c<sizeof($categories); $c++) {
					if($categories[$c]->name != '') {
						echo '<li><a';
						if($_GET["cat_id"] == $categories[$c]->term_id)
							echo ' class="active"';
						echo ' href="/blog/?cat_id='.$categories[$c]->term_id.'" title="View '.$categories[$c]->name.' articles">'.$categories[$c]->name.'</a></li>';
					}
				}
				echo '</ul>';
			}

			$tags = get_tags();
			$tags = array_values($tags);
			if(sizeof($tags) > 0) {
				echo '<div class="list-header">Tags</div>';
				echo '<ul class="list"><li>';
				$tagsList = '';
				for($c=0; $c<sizeof($tags); $c++) {
					$tagsList .= '<a';
					if($_GET["tag_id"] == $tags[$c]->slug)
						$tagsList .= ' class="active"';
					$tagsList .= ' href="/blog/?tag_id='.$tags[$c]->slug.'" title="View '.$tags[$c]->name.' posts">'.$tags[$c]->name.'</a>, ';
				}
				echo substr($tagsList, 0, -2);
				echo '</li></ul>';
			}

		?>
		</section>

	</div>

<?php get_footer(); ?> 