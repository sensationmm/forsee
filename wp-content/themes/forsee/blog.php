<?php
/**
 * @package WordPress
 * @subpackage Forsee 2015
 * Template Name: Blog
*/
get_header();  
?>
	
	<div class="banner" style="background-image:url(<?php echo get_field('page_banner', $pageObj->ID); ?>);">
		<div class="body">
			<h1><?php echo ($display_title != '') ? $display_title : $pageObj->post_title; ?></h1>
			<p><?php echo get_field('page_strapline', $pageObj->ID); ?></p>
		</div>
	</div>

	<div class="body outer">
		<?php echo apply_filters('the_content', $pageObj->post_content); ?>

		<section class="col-right">
		<?php
			$categories = get_categories();
			if(sizeof($categories) > 0) {
				echo '<div class="list-header">Categories</div>';
				echo '<ul class="list">';
				echo '<li><a';
				if(!isset($_GET["cat_id"]))
					echo ' class="active"';
				echo ' href="/blog/" title="View all articles">All</a></li>';
				for($c=0; $c<sizeof($categories); $c++) {
					echo '<li><a';
					if($_GET["cat_id"] == $categories[$c]->term_id)
						echo ' class="active"';
					echo ' href="/blog/?cat_id='.$categories[$c]->term_id.'" title="View '.$categories[$c]->name.' articles">'.$categories[$c]->name.'</a></li>';
				}
				echo '</ul>';
			}

			$tags = get_tags();
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

		<div class="col-left">
		<?php

			if ( get_query_var('paged') ) { $paged = get_query_var('paged'); }
			elseif ( get_query_var('page') ) { $paged = get_query_var('page'); }
			else { $paged = 1; }

			$posts_per_page = 10;
			
			$listings = array('post_type' => 'post',
								'posts_per_page' => $posts_per_page,
								'paged' => $paged);

			//apply filters
			if($_GET["cat_id"] != '')
				$listings['cat'] = $_GET["cat_id"];
			else if($_GET["tag_id"] != '')
				$listings['tag'] = $_GET["tag_id"];

			remove_all_filters('posts_orderby');//prevent plugin clashing with custom ordering
			$articles = new WP_Query($listings);

			global $wp_query; 
 			$totalNumResults = ceil(($articles->found_posts));

 			//show number of results
			$resultsText = 'Showing ';
			if($totalNumResults > 0) {
				$resultsText .= $posts_per_page * $paged - $posts_per_page + 1;
				$resultsText .= '-';
				$resultsText .= $posts_per_page * $paged - ($posts_per_page - $articles->post_count);
				$resultsText .= ' of ';
			}
			$resultsText .= $totalNumResults.' results';
			if($_GET["cat_id"] != '' || $_GET["tag_id"] != '')
				$resultsText .= '<div class="results-filtered">Filtered</div>';

			echo '<div class="num-results">'.$resultsText.'</div>';

			if ($articles->have_posts() ) : 
				while ( $articles->have_posts() ) : $articles->the_post();
					echo '<article class="blog">';
					echo '<h2><a href="'.get_permalink(get_the_ID()).'" title="Read '.get_the_title().'">'.get_the_title().'</a></h2>';

					echo '<a href="'.get_permalink(get_the_ID()).'" title="Read '.get_the_title().'">';
					$img = get_the_post_thumbnail(get_the_ID(), array(220,220), array('class' => 'blog-image'));
					echo $img.'</a>';

					$content = get_the_excerpt();
					echo '<div class="blog-content'.(($img == '') ? ' no-image' : '').'">';
					echo '<div class="blog-date">'.get_the_date('j M Y').'</div>';
					echo apply_filters('the_content', $content);
					echo '</div>';
					echo '</article>';
				endwhile;
			endif;

			echo '<div class="num-results">'.$resultsText;

			$big = 999999999; // need an unlikely integer

			$pagination = paginate_links( array(
				'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
				'current' => $paged,
				'total' => $articles->max_num_pages,
				'prev_text' => __('&lt;'),
				'next_text' => __('&gt;'),
			) );

			echo '<div class="pagination-links">'.$pagination.'</div>';

			echo '</div>';
		?>
		</div>

	</div>

<?php get_footer(); ?> 