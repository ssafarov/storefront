<?php
/**
 * The template for displaying full width pages.
 *
 * Template Name: Full width
 *
 * @package storefront
 */

get_header(); ?>

	<main id="main" class="container site-main" role="main">

		<?php while ( have_posts() ) : the_post(); ?>

			<?php
			do_action( 'storefront_page_before' );
			?>

			<?php get_template_part( 'content', 'page' ); ?>

			<?php
			/**
			 * @hooked storefront_display_comments - 10
			 */
			do_action( 'storefront_page_after' );
			?>

		<?php endwhile; // end of the loop. ?>

	</main><!-- #main -->

<?php get_footer(); ?>
