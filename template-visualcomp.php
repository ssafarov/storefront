<?php
/**
 * The template for displaying full width pages.
 *
 * Template Name: Visual Composer
 *
 * @package storefront
 */
get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<?php
			// Start the loop.
			while ( have_posts() ) : the_post();


				do_action( 'storefront_page_before' );



				// Include the page content template.
				get_template_part( 'content', 'page' );




				do_action( 'storefront_page_after' );

				// End the loop.
			endwhile;
			?>

		</main><!-- .site-main -->
	</div><!-- #main -->

<?php get_footer(); ?>