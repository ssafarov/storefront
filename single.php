<?php
/**
 * The template for displaying all single posts.
 *
 * @package storefront
 */

get_header(); ?>

	<main id="main" class="container site-main" role="main">
		<div class="row">
			<div class="col-sm-12 col-md-8">
				<?php while ( have_posts() ) : the_post(); ?>

					<?php
						do_action( 'storefront_single_post_before' );

						get_template_part( 'content', 'single' );

						/**
						 * @hooked storefront_post_nav - 10
						 */
						do_action( 'storefront_single_post_after' );
					?>

				<?php endwhile; // end of the loop. ?>
			</div>

			<div class="col-sm-12 col-md-4">
				<?php get_template_part_with_data('page-templates/partials/_newsletter_sign_up'); ?>
				<?php get_template_part_with_data('page-templates/partials/_twitter_feed_blog-rightside'); ?>
				<?php do_action( 'storefront_sidebar' ); ?>

			</div>

		</div>

	</main><!-- #main -->

<?php get_footer(); ?>