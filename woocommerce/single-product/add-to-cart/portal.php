<?php
if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

global $woocommerce, $product;

if (!$product->is_purchasable()) {
    return;
}
$stockQuantity = $product->get_stock_quantity();
?>

<?php if ((($product->get_type() == 'simple') && ($stockQuantity > 0)) || ($product->get_type() != 'simple')) {

    do_action('woocommerce_before_add_to_cart_form'); ?>

    <form class="cart" method="post" enctype='multipart/form-data'>

        <input type="hidden" name="add-to-cart" value="<?php echo esc_attr($product->id); ?>"/>

        <button type="submit" class="single_add_to_cart_button btn btn-secondary pull-right"><?php _e('Buy now!',
                'storefront'); ?></button>

        <?php do_action('woocommerce_after_add_to_cart_button'); ?>
    </form>

    <?php do_action('woocommerce_after_add_to_cart_form'); ?>

<?php } else {
    $addToCartLink = sprintf('<span class="btn btn-xs btn-block btn-tertiary">%s</span>',
        __('Out of stock', 'storefront'));
    echo $addToCartLink;
} ?>