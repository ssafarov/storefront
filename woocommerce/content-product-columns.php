<?php
if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

$step = 1;

/**
 * @var array $args
 */

$products = new WP_Query($args);

while ($products->have_posts()) {
    // Product custom fields
    $prodNum++;
    $products->the_post();

    /**
     * @var WC_Product $product
     */
    global $product;

    if ($product->visibility == 'hidden') continue;
    if ($product->get_attribute('nalpeiron_profilename_old')) continue;

    $product_subheading = get_post_custom_values('product_subheading')[0];

    $product_description = get_the_excerpt();
    $dots = strlen($product_description) >= 347 ? '...' : '';
    $product_description = substr(get_the_excerpt(), 0, 347) . $dots;

    $price_description = get_post_custom_values('product_price_heading')[0];
    $priceDescription = $price_description ? '<p class="stock">' . __($price_description) . '</p>' : '<p class="stock">&nbsp;</p>';

    $currency = get_woocommerce_currency_symbol();
    $priceInTax = number_format($product->get_price_including_tax(), 2, '.', '');
    $priceExTax = number_format($product->get_price_excluding_tax(), 2, '.', '');

    $learnMoreLink = sprintf('<a href="%s" rel="nofollow" class="btn btn-xs btn-block btn-secondary">%s</a>',
        esc_url(apply_filters('the_permalink', get_permalink())),
        esc_html(__('Learn More', 'storefront'))
    );

    if (!$product->is_sold_individually()) {
        $min_value = apply_filters('woocommerce_quantity_input_min', 1, $product);
        $minValue = (is_numeric($min_value)) ? $minValue = 'min="' . esc_attr($min_value) . '"' : '';

        $max_value = apply_filters('woocommerce_quantity_input_max',
            $product->backorders_allowed() ? '' : $product->get_stock_quantity(), $product);
        $maxValue = (is_numeric($max_value)) ? $maxValue = 'max="' . esc_attr($max_value) . '"' : '';
    } else {
        $minValue = 'min=1';
        $maxValue = '';
    }

    // Availability
    $stockQuantity = $product->get_stock_quantity();
    $availability = $product->get_availability();
    if (($availability['availability']) && ($product->get_type() == 'simple')) {
        $inStockMark = apply_filters('woocommerce_stock_html',
            '<p class="stock ' . esc_attr($availability['class']) . '">' . __($availability['availability']) . '</p>',
            $availability['availability']);
    } else {
        $inStockMark = '';
    }
    //@todo: need to get clear way should we show this mark or not.
    $inStockMark = '';
    if ((($product->get_type() == 'simple') && ($stockQuantity > 0)) || ($product->get_type() != 'simple')) {
        $addToCartLink = sprintf('<a href="%s" rel="nofollow" data-product_id="%s" data-quantity="1" data-product_sku="%s" class="btn btn-xs btn-block btn-primary %s product_type_%s">%s</a>',
            esc_url($product->add_to_cart_url()),
            esc_attr($product->id),
            esc_attr($product->get_sku()),
            $product->is_purchasable() ? 'add_to_cart_button' : '',
            esc_attr($product->product_type),
            $product->add_to_cart_text()
        );
        $qtyCounter = '<div class="col-lg-4 counter-input-group__wrapper">
                            <div class="input-group counter-input-group">';
        if (empty($product->get_children())) {
            if ($price_html = $product->get_price_html()) {
                $qtyCounter .= '<span class="input-group-btn"><button class="btn minus" type="button" value="-">-</button></span>';
                $qtyCounter .= '<input type="number" step="' . esc_attr($step) . '" ' . $minValue . ' ' . $maxValue . ' name="quantity" value="1" title="' . esc_attr_x('Qty',
                        'Product quantity input tooltip', 'woocommerce') . '" class="form-control input-sm qty" size="4"/>';
                $qtyCounter .= '<span class="input-group-btn"><button class="btn plus" type="button" value="+">+</button></span>';
            }
        }
        $qtyCounter .= '</div></div></div></div>';
    } else {
        $addToCartLink = sprintf('<span class="btn btn-xs btn-block btn-tertiary">%s</span>',
            __('Out of stock', 'storefront')
        );
        $qtyCounter = '</div></div>';
    }


    $productsTabContent .= "<div class='col-sm-4'>\n";
    $productsTabContent .= "<div class='products__list-item-well well'>\n";
    $productsTabContent .= "<div class='products__list-item'>\n";

    $productsTabContent .= "<div class='product__thumbnail'>\n<a href='" . esc_url(apply_filters('the_permalink',
            get_permalink())) . "'>\n";
    $productsTabContent .= woocommerce_get_product_thumbnail([280, 280]);
    $productsTabContent .= "</a></div>\n";

    $productsTabContent .= "<div class='product__description'>\n";
    $productsTabContent .= "<h2 class='product-title'><a href='" . esc_url(apply_filters('the_permalink',
            get_permalink())) . "'>" . get_the_title() . "</a></h2>\n";
    $productsTabContent .= "<div itemprop='description'>\n";
    if ($product_subheading) {
        $productsTabContent .= "<h4 class='product-title_min'>" . _x($product_subheading, 'fuel3d') . " </h4>\n";
    }
    $productsTabContent .= "<p>" . $product_description . "</p>\n";
    $productsTabContent .= "</div>\n";
    $productsTabContent .= "</div>\n";

    $productsTabContent .= '<div class="product-meta">';
    $productsTabContent .= $inStockMark;
    //$productsTabContent .= $priceDescription;
    if (get_current_blog_id() == 4) {
        if (!empty($product->get_children())) {
            if ($price_html = $product->get_price_html()) {
                $productsTabContent .= '<div class="product__price">
                                <div class="row">
                                    <div class="col-lg-12 prices__wrapper grouped-product">
                                        <div class="price__row inc-price">
                                            <span class="price__amount">' . $price_html . '</span>
                                            <small class="price__suffix__us"> ' . __('exc tax', 'storefront') . '</small>
                                        </div>
                                    </div>';
            }
        } else {
            $productsTabContent .= '<div class="product__price">
                                <div class="row">
                                    <div class="col-lg-7 prices__wrapper">
                                        <div class="price__row inc-price">
                                            <sup>' . $currency . '</sup>
                                            <span class="price__amount">' . $priceExTax . '</span>
                                            <small class="price__suffix__us"> ' . __('exc sales tax', 'storefront') . '</small>
                                        </div>
                                    </div>';
        }
    } else {
        if (!empty($product->get_children())) {
            if ($price_html = $product->get_price_html()) {
                $productsTabContent .= '<div class="product__price">
                                    <div class="row">
                                        <div class="col-lg-12 prices__wrapper grouped-product">
                                            <div class="price__row inc-price">
                                                <span class="price__amount">' . $price_html . '</span>
                                            </div>
                                            <div class="price__row exc-price">
                                                <small class="price__suffix"> ' . __('exc VAT', 'storefront') . '</small>
                                            </div>
                                        </div>';

            }
        } else {
            $productsTabContent .= '<div class="product__price">
                                    <div class="row">
                                        <div class="col-lg-7 prices__wrapper">
                                            <div class="price__row inc-price">
                                                <sup>' . $currency . '</sup>
                                                <span class="price__amount">' . $priceInTax . '</span>
                                                <small class="price__suffix"> ' . __('inc VAT', 'storefront') . '</small>
                                            </div>
                                            <div class="price__row exc-price">
                                                <sup>' . $currency . '</sup>
                                                <span class="price__amount">' . $priceExTax . '</span>
                                                <small class="price__suffix"> ' . __('exc VAT', 'storefront') . '</small>
                                            </div>
                                        </div>';
        }
    }

    $productsTabContent .= $qtyCounter;
    $productsTabContent .= '<div class="view_cart_hidden product__btn-group">';
    $productsTabContent .= $addToCartLink;
    $productsTabContent .= $learnMoreLink;
    $productsTabContent .= '</div>';

    $productsTabContent .= '</div>';
    $productsTabContent .= '</div></div></div>';
}