<?php
/**
 * The template for displaying the homepage.
 *
 * Template name: Homepage
 *
 * @package storefront
 */

// Get scanner WC_Product Class
$scanner = get_scanner_product();
$currency = get_woocommerce_currency_symbol();

get_header(); ?>

	<main id="main" role="main">
		<?php $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' ); ?>
		<section class="hero" style="background-image: url('<?php echo $image[0]; ?>');">
			<div class="container">
				<div class="row">
					<div class="col-xs-12 col-sm-12">
						<?php
							while ( have_posts() ) : the_post();
								the_content();
							endwhile;
						?>
					</div>
				</div>
			</div>
		</section>


		<div class="home-articles">
			<?php $count = 0; ?>
			<?php if ( have_rows('hp_blocks') ) : ?>
				<?php while ( have_rows('hp_blocks') ) : the_row(); ?>
					<?php $count++; ?>
					<section class="benefits <?php echo ( $count == 1 ? 'box-first' : ''); ?>" style="background-image: url(<?php the_sub_field('section_background'); ?>);">
						<div class="container">
							<div class="row">
								<div class="col-sm-12">
									<article class="box-<?php echo $count; ?>">
										<div class="row">
											<div class="col-sm-12 col-md-6">

												<?php if ( $count == 4 ) : ?>
													<img class="scanify-logo" width="60%" src="<?php echo get_template_directory_uri(); ?>/images/scanify-logo.png" alt="Scanify">
													<p>
														<?php echo $currency . $scanner->get_price(); ?>
														<?php echo $scanner->get_price_suffix(); ?>
													</p>
													<p>
														<a class="btn btn-primary" href="<?php the_sub_field('button_url'); ?>"><?php the_sub_field('button_text'); ?></a>&nbsp;&nbsp;
													</p>
												<?php else : ?>
													<h2 class="h1">
														<?php the_sub_field('headline'); ?>
													</h2>

													<?php the_sub_field('text'); ?>
													<p><a class="btn btn-primary" href="<?php the_sub_field('button_url'); ?>"><?php the_sub_field('button_text'); ?></a></p>
												<?php endif; ?>
											</div>
											<div class="col-sm-12 col-md-6">
												<img src="<?php the_sub_field('image'); ?>" alt="<?php the_sub_field('headline'); ?>">
											</div>
										</div>
									</article>
								</div>
							</div>
						</div>
					</section>
				<?php endwhile; ?>
			<?php endif; ?>
		</div>

	</main><!-- #main -->
<?php get_footer(); ?>
