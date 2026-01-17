<?php
/**
 * The main template file
 *
 * @package CXZ_WP_Theme
 */

get_header();
?>

<main id="main" class="site-main">
	<?php
	if ( have_posts() ) :
		while ( have_posts() ) :
			the_post();
			?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<?php the_content(); ?>
			</article>
			<?php
		endwhile;
	endif;
	?>
</main>

<?php
get_footer();
