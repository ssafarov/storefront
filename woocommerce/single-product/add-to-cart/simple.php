<?php
/**
 * Simple product add to cart
 *
 * @author        WooThemes
 * @package       WooCommerce/Templates
 * @version       2.1.0
 */

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

global $woocommerce, $product;

if (!$product->is_purchasable()) {
    return;
}
?>

<?php
// Availability
$availability = $product->get_availability();
$stockQuantity = $product->get_stock_quantity();

if ($availability['availability']) {
    echo apply_filters('woocommerce_stock_html',
        '<p class="stock ' . esc_attr($availability['class']) . '">' . __($availability['availability'],
            'storefront') . '</p>', $availability['availability']);
}
?>

<?php if ((($product->get_type() == 'simple') && ($stockQuantity > 0)) || ($product->get_type() != 'simple')) {

    do_action('woocommerce_before_add_to_cart_form'); ?>

    <form class="cart" method="post" enctype='multipart/form-data'>
        <?php do_action('woocommerce_before_add_to_cart_button'); ?>

        <?php
        if (!$product->is_sold_individually()) {
            woocommerce_quantity_input(array(
                'min_value' => apply_filters('woocommerce_quantity_input_min', 1, $product),
                'max_value' => apply_filters('woocommerce_quantity_input_max',
                    $product->backorders_allowed() ? '' : $product->get_stock_quantity(), $product)
            ));
        }
        ?>

        <input type="hidden" name="add-to-cart" value="<?php echo esc_attr($product->id); ?>"/>

        <button type="submit"
                class="single_add_to_cart_button btn btn-primary btn-block"><?php echo $product->single_add_to_cart_text(); ?></button>

        <?php do_action('woocommerce_after_add_to_cart_button'); ?>
    </form>

    <?php do_action('woocommerce_after_add_to_cart_form');
} else {
    $addToCartLink = sprintf('<span class="btn btn-xs btn-block btn-tertiary">%s</span>',
        __('Out of stock', 'storefront'));
    echo $addToCartLink;
}
?>