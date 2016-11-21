<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * Override this template by copying it to yourtheme/woocommerce/content-single-product.php
 *
 * @author        WooThemes
 * @package    WooCommerce/Templates
 * @version     1.6.4
 */

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly
?>

<?php

if (post_password_required()) {
    echo get_the_password_form();

    return;
}

$extraProducts = get_extra_products();

$currency = get_woocommerce_currency_symbol();
?>

    <div itemscope itemtype="<?php echo woocommerce_get_product_schema(); ?>" id="product-<?php the_ID(); ?>" <?php post_class(); ?>>

        <section class="product-hero">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <?php
                        /**
                         * woocommerce_before_single_product hook
                         *
                         * @hooked wc_print_notices - 10
                         */
                        do_action('woocommerce_before_single_product');
                        ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-4 col-sm-push-8">
                        <div class="summary entry-summary product-meta">

                            <?php
                            /**
                             * woocommerce_single_product_summary hook
                             *
                             * @hooked woocommerce_template_single_title - 5
                             * @hooked f3d_single_product_subheading - 5
                             * @hooked f3d_template_single_price - 10
                             * @hooked f3d_single_product_content - 20
                             * @hooked woocommerce_template_single_add_to_cart - 40
                             * @hooked woocommerce_template_single_meta - 50
                             */

                            do_action('f3d_single_product_summary');
                            ?>

                        </div>
                        <!-- .summary -->
                    </div>
                    <div class="col-xs-12 col-sm-7 col-sm-pull-4">
                        <?php
                        /**
                         * woocommerce_before_single_product_summary hook
                         *
                         * @hooked woocommerce_show_product_sale_flash - 10
                         * @hooked woocommerce_show_product_images - 20
                         */
                        do_action('woocommerce_before_single_product_summary');
                        ?>
                        <div class="product-hero__short-info">
                            <?php do_action('f3d_item_description'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <div class="container product-content">
            <div class="row">
                <div class="col-sm-12">
                    <?php
                    /**
                     * woocommerce_after_single_product_summary hook
                     *
                     * @hooked woocommerce_output_product_data_tabs - 10
                     * @hooked woocommerce_output_related_products - 20
                     */
                    // do_action( 'woocommerce_after_single_product_summary' );

                    $count = 0;

                    if (have_rows('extra_content')) {
                        while (have_rows('extra_content')) {
                            the_row();

                            include(locate_template('woocommerce/single-product/product-custom-content.php'));
                        }
                    }

                    if (have_rows('product_highlights')) {
                        while (have_rows('product_highlights')) {
                            the_row();
                            $count++;

                            // Can't use get_template_part() because we cannot send the $count variable
                            include(locate_template('woocommerce/single-product/product-custom-highlights.php'));
                        }
                    }
                    ?>

                    <meta itemprop="url" content="<?php the_permalink(); ?>"/>
                </div>
            </div>
        </div>


        <?php
        $nextLine = false;
        foreach ($extraProducts as $extraProduct) { ?>
            <section class="product-extra">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-12 col-md-5">
                            <h2 class="h1"><?php if (!$nextLine) {
                                    _e('Add extra', 'storefront');
                                } ?></h2>
                        </div>
                        <div class="col-sm-12 col-md-3">
                            <?php echo $extraProduct->get_image(); ?>
                        </div>
                        <div class="col-sm-12 col-md-4 product-meta">
                            <h4><?php echo $extraProduct->get_title(); ?></h4>

                            <div class="price">
                                <h6><?php echo get_post_custom_values('product_price_heading', $extraProduct->id)[0]; ?></h6>
                                <sup><?php echo $currency; ?></sup>
                                <span class="amount"><?php echo $extraProduct->get_price(); ?></span>
                            </div>


                            <form class="cart has-validation-callback" method="post" enctype="multipart/form-data">
                                <!--
                                <div class="quantity buttons_added"><input type="button" value="-" class="minus"><input type="number" step="1" min="1" name="quantity" value="1" title="Qty" class="input-text qty text" size="4"><input type="button" value="+" class="plus"></div>-->

                                <input type="hidden" name="add-to-cart" value="<?php echo $extraProduct->id; ?>">

                                <button type="submit" class="single_add_to_cart_button btn btn-primary btn-block"><?php echo $extraProduct->single_add_to_cart_text() ?></button>

                            </form>
                            <!-- <a class="btn btn-primary btn-block" href="<?php echo get_permalink($extraProduct->id) ?>"></a> -->
                        </div>
                    </div>
                </div>
            </section>
            <?php
            $nextLine = true;
        } ?>

        <?php if (have_rows('sample_scans')) : ?>
            <section class="product-samples">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-12">
                            <?php while (have_rows('sample_scans')) : the_row(); ?>
                                <h2><?php the_sub_field('headline'); ?></h2>
                                <?php the_sub_field('content'); ?>

                                <?php if (have_rows('images')) : ?>
                                    <ul class="list-unstyled row">
                                        <?php while (have_rows('images')) : the_row(); ?>
                                            <li class="col-sm-12 col-md-3">
                                                <img src="<?php the_sub_field('image'); ?>" alt="">
                                            </li>
                                        <?php endwhile; ?>
                                    </ul>
                                <?php endif ?>
                            <?php endwhile; ?>
                        </div>
                    </div>
                </div>
            </section>
        <?php endif; ?>

        <?php if (get_field('product_specs') || get_field('suitable') || get_field('not_suitable')) : ?>
            <section class="product-extra">
                <div class="container">
                    <?php if (get_field('product_specs')) : ?>
                        <div class="row">
                            <div class="col-sm-12">
                                <h2><?php _e('Technical specifications', 'storefront'); ?></h2>

                                <?php
                                if (get_field('product_specs')) {
                                    the_field('product_specs');
                                }
                                ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if (get_field('suitable')) : ?>
                        <div class="row">
                            <div class="col-sm-12">
                                <?php if (get_field('suitable')) : ?>
                                    <div class="alert suitable" role="alert">
                                        <?php the_field('suitable') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if (get_field('not_suitable')) : ?>
                        <div class="row">
                            <div class="col-sm-12">
                                <?php if (get_field('not_suitable')) : ?>
                                    <div class="alert not-suitable" role="alert">
                                        <?php the_field('not_suitable') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </section>
        <?php endif; ?>
    </div><!-- #product-<?php the_ID(); ?> -->

<?php do_action('woocommerce_after_single_product'); ?>