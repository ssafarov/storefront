<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * Override this template by copying it to yourtheme/woocommerce/content-single-product.php
 *
 * @author        WooThemes
 * @package       WooCommerce/Templates
 * @version       1.6.4
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

$currency = get_woocommerce_currency_symbol();


$tabs = [
    'Description' => get_field('description'),
    'Applications' => get_field('application'),
    'Tech Specs' => get_field('tech_specs'),
    'Customer Reviews' => get_field('customer_reviews'),
    'Shipping' => get_field('shipping'),
    'Compare' => get_field('comparison_table'),
];
foreach ($tabs as $tab_name => $tab_content) {
    if (!$tab_content) {
        unset($tabs[$tab_name]);
    }
}

?>

    <div itemscope itemtype="<?php echo woocommerce_get_product_schema(); ?>"
         id="product-<?php the_ID(); ?>" <?php post_class(); ?>>
        <div class="container">
            <div class="products-category">
                <div class="row">

                    <div class="col-lg-10 col-lg-offset-1" id="back_to_top">
                        <div class="product-main-info">
                            <div class="row">
                                <div class="col-sm-12">
                                    <?php
                                    /**
                                     * woocommerce_before_single_product hook
                                     *
                                     * @hooked wc_print_notices - 10
                                     */
                                    do_action('woocommerce_before_single_product');
                                    // view cart
                                    ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-md-6">

                                    <?php
                                    /**
                                     * woocommerce_before_single_product_summary hook
                                     *
                                     * @hooked woocommerce_show_product_sale_flash - 10
                                     * @hooked woocommerce_show_product_images - 20
                                     */
                                    do_action('woocommerce_before_single_product_summary');
                                    ?>
                                    <script>
                                        jQuery(function ($) {

                                            wc_add_to_cart_params.i18n_view_cart = '<?php _e("Items have been added to your cart"); ?>';

                                            var eStart, eEnd;

                                            $("body").on("touchstart", ".pp_fade", function (e) {
                                                var event = e.originalEvent;
                                                eStart = null;
                                                if (event.targetTouches.length == 1) {
                                                    eStart = event.targetTouches[0];
                                                }
                                            });

                                            $("body").on("touchmove", ".pp_fade", function (e) {
                                                var event = e.originalEvent;
                                                eEnd = null;
                                                if (event.targetTouches.length == 1) {
                                                    eEnd = event.targetTouches[0];
                                                }
                                            });

                                            $("body").on("touchend", ".pp_fade", function (e) {
                                                var dX = eStart.pageX - eEnd.pageX;
                                                if (dX > 60) {
                                                    jQuery('.pp_arrow_next').click();
                                                }
                                                if (dX < -60) {
                                                    jQuery('.pp_arrow_previous').click();
                                                }
                                            });
                                        });
                                    </script>

                                    <?php
                                    $video = get_field('video');
                                    if ($video):
                                        ?>
                                        <a id="videoModal" href="<?= $video ?>"
                                           class="single_add_to_cart_button btn btn-lg btn-primary-empty btn-block"
                                           data-toggle="modal" data-target="#myModal"
                                        >
                                            <i class="fa fa-youtube-play"></i> Watch the video
                                        </a>


                                        <!-- Modal -->
                                        <div class="modal fade" id="myModal" tabindex="-1" role="dialog"
                                             aria-labelledby="myModalLabel">
                                            <div class="modal-dialog" role="document" style="margin-top:120px;">
                                                <div class="modal-content">
                                                    <!--                                                    <div class="modal-header">-->
                                                    <!--                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>-->
                                                    <!--                                                        <h4 class="modal-title" id="myModalLabel">Modal title</h4>-->
                                                    <!--                                                    </div>-->
                                                    <div class="modal-body modalIframe"></div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-primary"
                                                                data-dismiss="modal">Close
                                                        </button>
                                                        <!--                                                        <button type="button" class="btn btn-primary">Save changes</button>-->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <script>
                                            jQuery(function ($) {
                                                $('#myModal').on('shown.bs.modal', function () {
                                                    var href = $("#videoModal").attr('href') + '?&autoplay=1';
                                                    var html = "<iframe frameborder='0' allowfullscreen='' width='100%' height='320px' src='" + href + "'></iframe>";
                                                    $(".modalIframe").html(html);
                                                });
                                                $('#myModal').on('hidden.bs.modal', function (e) {
                                                    var html = "-";
                                                    $(".modalIframe").html(html);
                                                })
                                            });
                                        </script>

                                    <?php endif; ?>
                                </div>

                                <div class="col-xs-12 col-md-6">
                                    <div class="entry-summary">
                                        <?php woocommerce_template_single_title(); ?>

                                        <div class="star__rating star__rating_big">
                                            <?php /*
                                            <span class="active">&#9733;</span><span class="active">&#9733;</span><span class="active">&#9733;</span><span
                                                class="active">&#9733;</span><span class="active">&#9733;</span>
                                            */ ?>
                                        </div>

                                        <!--    <ul class="section-min__list section-min__list_margin">-->
                                        <!--        <li>Capture a 3D image in just 1/10 of second</li>-->
                                        <!--        <li>High resolution & colour (down to 350 microns!)</li>-->
                                        <!--        <li>Cme with FREE audio software, allowing you to crop, manipulate and export file (OBJ, PLY & STI)</li>-->
                                        <!--        <li>Simple to use - point and shoot</li>-->
                                        <!--    </ul>-->
                                        <?php f3d_single_product_content(); ?>

                                        <?php if (count($tabs)): ?>
                                            <div class="btn-wrapper">
                                                <a href="#go_tabs" data-delta="true"
                                                   class="btn btn-block btn-gray btn-min">Learn more <i
                                                        class="fa fa-arrow-down"></i></a>
                                            </div>
                                        <?php endif; ?>

                                        <?php
                                        /**
                                         * @var WC_Product $product
                                         */
                                        global $product;
                                        $stockQuantity = $product->get_stock_quantity();
                                        ?>
                                    <?php if (empty($product->get_children())):?>
                                        <?php if ((($product->get_type() == 'simple') && ($stockQuantity > 0)) || ($product->get_type() != 'simple'))  : ?>

                                            <?php do_action('woocommerce_before_add_to_cart_form'); ?>

                                            <form class="cart has-validation-callback" method="post"
                                                  enctype="multipart/form-data" style="margin-bottom: 1.618em;">

                                                <?php do_action('woocommerce_before_add_to_cart_button'); ?>

                                                <div class="product__price product__price_big">
                                                    <div class="row">
                                                        <?php if (get_current_blog_id() == 4) : ?>
                                                            <div class="col-lg-7 prices__wrapper">
                                                                <div class="price__row price__row_big inc-price">
                                                                    <sup><?= $currency ?></sup>
                                                                    <span class="price__amount">
                                                                        <?= number_format($product->get_price_excluding_tax(),
                                                                            2, '.', '') ?>
                                                                    </span>
                                                                    <small class="price__suffix__us"> exc sales tax
                                                                    </small>
                                                                </div>
                                                            </div>
                                                        <?php else : ?>
                                                            <div class="col-lg-7 prices__wrapper">
                                                                <div class="price__row price__row_big inc-price">
                                                                    <sup><?= $currency ?></sup>
                                                                    <span class="price__amount">
                                                                        <?= number_format($product->get_price_including_tax(),
                                                                            2, '.', '') ?>
                                                                    </span>
                                                                    <small class="price__suffix"> inc VAT</small>
                                                                </div>
                                                                <div class="price__row price__row_big exc-price">
                                                                    <sup><?= $currency ?></sup>
                                                                    <span class="price__amount">
                                                                        <?= number_format($product->get_price_excluding_tax(),
                                                                            2, '.', '') ?>
                                                                    </span>
                                                                    <small class="price__suffix"> exc VAT</small>
                                                                </div>
                                                            </div>
                                                        <?php endif; ?>

                                                        <div class="col-lg-5 counter-input-group__wrapper">
                                                            <div class="input-group counter-input-group quantity">
                                                                <span class="input-group-btn"><button
                                                                        class="btn btn-lg minus" type="button">-
                                                                    </button></span>
                                                                <?php
                                                                if (!$product->is_sold_individually()) {
                                                                    $min_value = apply_filters('woocommerce_quantity_input_min',
                                                                        1, $product);
                                                                    $max_value = apply_filters('woocommerce_quantity_input_max',
                                                                        $product->backorders_allowed() ? '' : $product->get_stock_quantity(),
                                                                        $product);

                                                                    ?>
                                                                    <input type="number"
                                                                           step="<?php echo esc_attr($step); ?>"
                                                                           <?php if (is_numeric($min_value)) : ?>min="<?php echo esc_attr($min_value); ?>"<?php endif; ?>
                                                                           <?php if (is_numeric($max_value)) : ?>max="<?php echo esc_attr($max_value); ?>"<?php endif; ?>
                                                                           name="quantity" value="1"
                                                                           title="<?php echo esc_attr_x('Qty',
                                                                               'Product quantity input tooltip',
                                                                               'woocommerce') ?>"
                                                                           class="form-control input-lg qty" size="4"/>
                                                                    <?php
                                                                }
                                                                ?>
                                                                <span class="input-group-btn"><button
                                                                        class="btn btn-lg plus" type="button">+
                                                                    </button></span>
                                                            </div>
                                                        </div>
                                                        <?php //woocommerce_template_single_add_to_cart(); ?>

                                                    </div>
                                                </div>

                                                <div>
                                                    <input type="hidden" name="add-to-cart"
                                                           value="<?php echo esc_attr($product->id); ?>"/>
                                                    <button type="submit"
                                                            class="single_add_to_cart_button btn btn-lg btn-primary btn-block">
                                                        <?php echo $product->single_add_to_cart_text(); ?>
                                                    </button>
                                                </div>

                                                <?php do_action('woocommerce_after_add_to_cart_button'); ?>

                                            </form>

                                            <script>
                                                (function ($) {
                                                    // anchor
                                                    $(document).ready(function () {
                                                        $('a[href*=#]').bind("click", function (e) {
                                                            var anchor = $(this);
                                                            var delta = 0, scrolltop = 0;
                                                            if ($(this).data('delta')) {
                                                                var header_height = $("header#masthead").height();
                                                                var header_position = $("header#masthead").css('position');

                                                                if (header_height > 100) {
                                                                    if (header_position == 'fixed') {
                                                                        delta = 55;
                                                                    } else {
                                                                        delta = 160;
                                                                    }
                                                                } else {
                                                                    delta = 50;
                                                                }
                                                            }
                                                            if (typeof ($(anchor.attr('href')) != undefined)){
                                                                $('html, body').stop().animate({
                                                                    scrollTop: $(anchor.attr('href')).offset().top - delta;
                                                                }, 1000);
                                                            }
                                                        });
                                                        return false;
                                                    });

                                                }(jQuery));
                                            </script>

                                            <script>
                                                jQuery(function ($) {

                                                    $('[data-toggle="tooltip"]').tooltip();

                                                    $(document).on('change', '.qty', function () {
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
                                        <?php else : ?>
                                            <span class="btn btn-xs btn-block btn-tertiary"><?= __('Out of stock',
                                                    'storefront'); ?></span>
                                        <?php endif; ?>
                                    <?php else : ?>
                                        <?php
                                        wc_get_template( 'single-product/add-to-cart/grouped.php', array(
                                            'grouped_product'    => $product,
                                            'grouped_products'   => $product->get_children(),
                                            'quantites_required' => false
                                        ) );
                                        ?>
                                    <?php endif; ?>

                                        <?php do_action('woocommerce_after_add_to_cart_form'); ?>

                                        <?php
                                        // woocommerce_template_single_meta();
                                        ?>

                                        <div class="row row-min">

                                            <?php
                                            // $is_show_free_delivery = have_rows('free_delivery');// checkbox
                                            $is_show_free_delivery = strip_tags(get_field('free_delivery'));
                                            $is_show_refund_guaranteed = strip_tags(get_field('refund_guaranteed'));
                                            ?>

                                            <?php if ($is_show_free_delivery): ?>
                                                <div class="col-md-6">
                                                    <a class="btn btn-block btn-gray btn_min-padding btn_min-margin"
                                                       title="<?= $is_show_free_delivery ?>" data-toggle="tooltip">
                                                    <span class="btn-content-wr">
                                                        <span class="btn-img-left__wr">
                                                            <img
                                                                src="<?php echo get_site_url(); ?>/wp-content/themes/storefront/images/free-delivery.png"
                                                                class="btn-img-left" alt="" title="">
                                                        </span>
                                                    <span class="btn-label">Free <br> Delivery</span>
                                                    </span>
                                                    </a>
                                                </div>
                                            <?php endif; ?>

                                            <?php if ($is_show_refund_guaranteed): ?>
                                                <div class="col-md-6">
                                                    <a class="btn btn-block btn-gray btn_min-padding btn_min-margin"
                                                       title="<?= $is_show_refund_guaranteed ?>" data-toggle="tooltip">
                                                    <span class="btn-content-wr">
                                                        <span class="btn-img-left__wr">
                                                            <img
                                                                src="<?php echo get_site_url(); ?>/wp-content/themes/storefront/images/refund-guaranteed.png"
                                                                class="btn-img-left" alt="" title="">
                                                        </span>
                                                    <span class="btn-label">Refund <br> Guarantee</span>
                                                    </span>
                                                    </a>
                                                </div>
                                            <?php endif; ?>

                                        </div>
                                    </div>
                                    <!-- .summary -->
                                </div>
                            </div>
                        </div>

                        <div id="go_tabs"></div>

                        <div class="panel-group panel-group_min hidden-md hidden-lg" id="product_panel_group"
                             role="tablist" aria-multiselectable="true">

                            <?php $count = 0;
                            foreach ($tabs as $tab_name => $tab_content):
                                $count ++;
                                ?>

                                <div class="panel panel-default">
                                    <div class="panel-heading" role="tab" id="heading<?= $count ?>">
                                        <h4 class="panel-title">
                                            <a role="button" data-toggle="collapse" data-parent="#product_panel_group"
                                               href="#collapse<?= $count ?>" aria-expanded="true"
                                               aria-controls="collapse<?= $count ?>"><?= $tab_name ?></a>
                                        </h4>
                                    </div>
                                    <div id="collapse<?= $count ?>"
                                         class="panel-collapse collapse <?= ($count == 1) ? 'in' : '' ?>"
                                         role="tabpanel" aria-labelledby="heading<?= $count ?>">
                                        <div class="panel-body">
                                            <?= $tab_content ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                            <!--tabs 1-->

                        </div>

                        <div class="tabe-with-bg hidden-xs hidden-sm">
                            <ul class="custom__nav nav-tabs_custom">
                                <?php $count = 0;
                                foreach ($tabs as $tab_name => $tab_content): $count ++; ?>
                                    <li role="presentation"
                                        class="<?= ($count == 1) ? 'active' : '' ?>"
                                    ><a href="#tab-<?= $count ?>-4" aria-controls="tab-<?= $count ?>-4" role="tab"
                                        data-toggle="tab"><?= $tab_name ?></a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                            <!-- Tab panes -->
                            <div class="tab-content">
                                <?php $count = 0;
                                foreach ($tabs as $tab_name => $tab_content): $count ++; ?>
                                    <div role="tabpanel" class="tab-pane fade <?= ($count == 1) ? 'in active' : '' ?>"
                                         id="tab-<?= $count ?>-4">
                                        <?= $tab_content ?>
                                    </div>
                                <?php endforeach; ?>
                                <!--tabs 2-->

                            </div>
                        </div>

                        <!-- Tab panes -->

                        <?php
                        $crossSell_ids = get_post_meta(get_the_ID(), '_crosssell_ids', true);
                        $args = array(
                            'posts_per_page' => - 1,
                            'post__in' => $crossSell_ids,
                            'post_type' => 'product',
                            'orderby' => 'title,'
                        );

                        $productsTabContent = '<div class="row"><h3 class="section__main-title">Other customers also bought...</h3>';
                        include 'content-product-columns.php';
                        $productsTabContent .= '</div>';
                        echo $productsTabContent;
                        unset ($productsTabContent);

                        ?>

                        <a href="#back_to_top" class="btn btn-block btn-primary-empty btn-back-to-top">
                            <span class="hidden-xs hidden-sm"><i class="fa fa-caret-up"></i></span>
                            <span class="hidden-md hidden-lg">Back to top</span>
                        </a>
                    </div>


                </div>
            </div>
        </div>
    </div>

<?php do_action('woocommerce_after_single_product'); ?>