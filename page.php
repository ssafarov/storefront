<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package storefront
 */

get_header(); ?>
	<div class="container site-main">
		<?php while ( have_posts() ) : the_post(); ?>

			<?php
				do_action( 'storefront_page_before' );
			?>
			<div class="row">
				<div class="col-md-12">
					<?php
					if (!is_checkout()) : ?>
						<h1><?php the_title(); ?></h1>
						<?php echo do_shortcode( '[splitter]' ); ?>
					<?php endif; ?>
				</div>
			</div>

			<div class="row">
				<?php get_template_part( 'content', 'page' ); ?>
			</div>

			<?php
			/**
			 * @hooked storefront_display_comments - 10
			 */
			do_action( 'storefront_page_after' );
			?>

		<?php endwhile; // end of the loop. ?>
	</div>
<?php get_footer(); ?>
