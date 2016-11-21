<?php
/**
 * Partial for Scanify box
 *
 * Appears on homepage at the bottom
 *
 * @package storefront
 */
?>

<?php
	// Get page ID
	$pageID = get_queried_object_id();

	// Get image Object
	$image = wp_get_attachment_image_src(get_field('image', $pageID), 'full');

	// print_r($image);
?>

<section class="scanify-box cf">

	<div class="col-sm-12 col-md-4 hidden-xs hidden-sm">
		<img src="<?php echo $image[0]; ?>" alt="<?php echo get_the_title(get_field('headline')) ?>">
	</div>
	
	<div class="col-sm-12 col-md-8">
		<h3 class="h1">
			<?php the_field('headline', $pageID); ?>
		</h3>

		<?php the_field('text', $pageID) ?>

		<a class="btn btn-secondary" href="<?php echo home_url( 'process' ); ?>"><?php _e( 'Sign in', 'storefront' ); ?></a>&nbsp;&nbsp;
		<a class="btn btn-secondary" href="<?php echo home_url( 'register' ); ?>"><?php _e( 'Register', 'storefront' ); ?></a>
	</div>

</section>