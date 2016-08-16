<?php
/**
 * @package WordPress
 * @subpackage Forsee 2015
 * Template Name: 404
*/
get_header(); 
$user_id = get_current_user_id();
$survey_url = get_user_meta( $user_id, 'survey_url', true );
?>

<div class="body outer">

    <article>
        <?php echo apply_filters('the_content', $pageObj->post_content); ?>
    </article>

</div>

<?php get_footer(); ?> 