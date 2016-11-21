<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package storefront
 */

get_header(); ?>

	<main id="main" class="site-main container" role="main">

	<div class="row">
		<div class="col-sm-12 col-md-8">

			<?php if ( have_posts() ) : ?>
		
				<?php get_template_part( 'loop' ); ?>

			<?php else : ?>

				<?php get_template_part( 'content', 'none' ); ?>

			<?php endif; ?>

		</div>

		<div class="col-sm-12 col-md-4">

			<?php do_action( 'storefront_sidebar' ); ?>

		</div>

	</main><!-- #main -->

<?php get_footer(); ?>
