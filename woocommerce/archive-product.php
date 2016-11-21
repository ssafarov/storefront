<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive.
 *
 * Override this template by copying it to yourtheme/woocommerce/archive-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

get_header( 'shop' ); ?>

	<?php
		/**
		 * woocommerce_before_main_content hook
		 *
		 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
		 * @hooked woocommerce_breadcrumb - 20
		 */
		do_action( 'woocommerce_before_main_content' );

		do_action( 'woocommerce_archive_description' );

		$needToClarifyRegion = !(isUsaCustomer() == isUsaShop());

		$productsTabContent = '';

		if ( have_posts() ) {
			$args = array(
				'number'     => '',
				'orderby'    => 'id',
				'order'      => 'DESC',
				'hide_empty' => true
			);
			$productCategories = get_terms( 'product_cat', $args );
			$countCategories = count($productCategories);
			$categoriesTabs = '';
			$catNum = 0;
			$prodNum = 0;
			if ( $countCategories > 0 ){
				foreach ( $productCategories as $productCategory ) {
					$catNum++;
					$firstClass = $catNum==1 ? 'active': '';
					$firstInActive = $catNum==1 ? 'in active': '';
					$categoriesTabs .= "<li role='presentation' class='".$firstClass."'><a href='#tab".$catNum."' aria-controls='tab".$catNum."' role='tab' data-toggle='tab'>".$productCategory->name."</a></li>\n";

					$args = array(
						'posts_per_page' => -1,
						'tax_query' => array(
							'relation' => 'AND',
							array(
								'taxonomy' => 'product_cat',
								'field' => 'slug',
								'terms' => $productCategory->slug
							)
						),
						'post_type' => 'product',
						'orderby' => 'title'
					);

					$productsTabContent .= "<div role='tabpanel' class='tab-pane fade ".$firstInActive."' id='tab".$catNum."'><div class='row'>\n";
					include 'content-product-columns.php';
					$productsTabContent .= '</div></div>';

				}
			}
		} elseif ( ! woocommerce_product_subcategories( array( 'before' => woocommerce_product_loop_start( false ), 'after' => woocommerce_product_loop_end( false ) ) ) ) {

			wc_get_template( 'loop/no-products-found.php' );

		}

?>
<div class="container">
	<div class="products-category">
		<div class="row">
			<div class="col-lg-10 col-lg-offset-1">
				<!-- Nav tabs -->
				<div class="select_head">Select a product type:</div>
				<ul class="custom__nav nav-tabs_custom nav-tabs-index-0" role="tablist">
					<?php
					echo $categoriesTabs;
					?>
				</ul>
				<!-- Tab panes -->
				<div class="tab-content">
					<?php
					echo $productsTabContent;
					?>
				</div>
			</div>
		</div>
	</div>
</div>
	<script>
		jQuery(function ($) {

			wc_add_to_cart_params.i18n_view_cart = '<?php _e("Items have been added to your cart"); ?>';

			$(document).on( 'change', '.qty', function() {
				$(this).closest('.product-meta').find('.add_to_cart_button').attr('data-quantity', $(this).val());
			});

			$(document).on('click', '.plus, .minus', function () {
				// Get values
				var $qty = $(this).closest('.counter-input-group').find('.qty'),
					currentVal = parseFloat($qty.val()),
					max = parseFloat($qty.attr('max')),
					min = parseFloat($qty.attr('min')),
					step = $qty.attr('step');
				// Format values
				if (!currentVal || currentVal === '' || currentVal === 'NaN') currentVal = 0;
				if (max === '' || max === 'NaN') max = '';
				if (min === '' || min === 'NaN') min = 0;
				if (step === 'any' || step === '' || step === undefined || parseFloat(step) === 'NaN') step = 1;

				// Change the value
				if ($(this).is('.plus')) {

					if (max && ( max == currentVal || currentVal > max )) {
						$qty.val(max);
					} else {
						$qty.val(currentVal + parseFloat(step));
					}
				} else {
					if (min && ( min == currentVal || currentVal < min )) {
						$qty.val(min);
					} else if (currentVal > 0) {
						$qty.val(currentVal - parseFloat(step));
					}
				}

				// Trigger change event
				$qty.trigger('change');
			});

		});
	</script>

<?php

		/**
		 * woocommerce_after_main_content hook
		 *
		 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
		 */
		do_action( 'woocommerce_after_main_content' );
	?>

	<?php
		/**
		 * woocommerce_sidebar hook
		 *
		 * @hooked woocommerce_get_sidebar - 10
		 */
		do_action( 'woocommerce_sidebar' );
	?>

<?php get_footer( 'shop' ); ?>

<?php wc_get_template ( 'notices/popup-clarify-region.php', ['needToClarifyRegion'=>$needToClarifyRegion] ); ?>
