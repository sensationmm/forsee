<?php

$path = $_SERVER['DOCUMENT_ROOT'];

include_once $path . '/wp-config.php';
include_once $path . '/wp-load.php';
include_once $path . '/wp-includes/wp-db.php';
include_once $path . '/wp-includes/pluggable.php';


// args
$args = array(
	'numberposts'	=> -1,
	'post_type'		=> 'career_desc',
	'meta_key'		=> 'id_career',
	'meta_value'	=> 'HA-624'
);

$args = array(
	'posts_per_page'	=> -1,
	'post_type'		=> 'career_desc',
	'meta_key'		=> 'doctorate',
	'meta_value'	=> 'true'
);


// query
$the_query = new WP_Query( $args );

?>
<?php if( $the_query->have_posts() ): ?>
	<ul>
	<?php while( $the_query->have_posts() ) : $the_query->the_post(); ?>
		<li>
			<a href="<?php the_permalink(); ?>">
				<?php the_title(); ?>
			</a>
		</li>
	<?php endwhile; ?>
	</ul>
<?php endif; ?>

<?php wp_reset_query();	 // Restore global post data stomped by the_post(). ?>


