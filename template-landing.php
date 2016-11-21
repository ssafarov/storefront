<?php
/**
 * The template for displaying the product page.
 *
 * Template name: Landing Page Template
 *
 * @package storefront
 */
// Get scanner WC_Product Class
$scanner = get_scanner_product();
$currency = get_woocommerce_currency_symbol();
get_header(); ?>

	<main class="product-page" id="main" role="main">
		<?php $count = 0; ?>
		<?php if ( have_rows( 'page_sections' ) ) : ?>
			<?php while ( have_rows( 'page_sections' ) ) : the_row(); ?>
				<?php $count++; ?>
				<?php $bg_image = get_sub_field( 'section_background' ); ?>
				<?php $bg_color = get_sub_field( 'section_background_color' ); ?>
				<?php $padding_top = get_sub_field( 'section_padding_top' ); ?>
				<?php $padding_bottom = get_sub_field( 'section_padding_bottom' ); ?>
				<?php $section_theme = get_sub_field('section_text_color'); ?>
				<?php $section_image = get_sub_field('section_image'); ?>
				<section class="product-page__section product-page__section--<?php echo $count; ?> <?php echo ( $section_theme == 'light' ? ' product-page--light' : '' ) ?>" style="background-color: <?php echo $bg_color ?>; background-image: url('<?php echo $bg_image; ?>'); padding-top: <?php echo $padding_top; ?>px; padding-bottom: <?php echo $padding_bottom; ?>px">
					<div class="full-container">
						<div class="row">
							<div class="col-sm-12 <?php echo $count == '5' ? '' : 'col-md-6' ?>">
								<h2><?php the_sub_field( 'section_headline' ); ?></h2>
								<?php the_sub_field( 'section_text' ); ?>
								<?php if  ( get_sub_field( 'button_text' ) ) : ?>
									<br>
									<?php if ( $count == '1' ) : ?>
                                        <a class="btn btn-primary" href="/shop-redirect/"><?php the_sub_field( 'button_text' ); ?></a>
									<?php elseif ( $count == '6' ) : ?>
										<a class="btn btn-primary" href="<?php echo home_url( 'portfolio' ); ?>"><?php the_sub_field( 'button_text' ); ?></a>
									<?php else : ?>
									<?php endif; ?>
								<?php endif; ?>

								<?php if ( get_sub_field('section_videos') ) : ?>
									<div class="row">
										<?php foreach ( get_sub_field('section_videos') as $video ) : ?>
											<div class="col-xs-12 col-sm-4">
												<?php echo do_shortcode(' [wp_lightbox_fancybox_youtube_video link="' . $video['video_file'] . '" source="' . $video['video_thumbnail'] . '"] '); ?>
											</div>
										<?php endforeach; ?>
									</div>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</section>
			<?php endwhile; ?>
		<?php endif; ?>



		<div class="product-content">
			<section class="product-content__section">
				<div class="container-full">
					<div class="row">
						<div class="col-sm-12">
							<?php
								while ( have_posts() ) : the_post();
									the_content();
								endwhile;
							?>
						</div>
					</div>
				</div>
			</section>
		</div>

	</main><!-- #main -->
<?php get_footer(); ?>
