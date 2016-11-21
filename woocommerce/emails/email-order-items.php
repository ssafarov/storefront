<?php
/**
 * Email Order Items
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates/Emails
 * @version     2.1.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $woocommerce;

foreach ( $items as $item ) :
	$_product     = apply_filters( 'woocommerce_order_item_product', $order->get_product_from_item( $item ), $item );
	$item_meta    = new WC_Order_Item_Meta( $item['item_meta'], $_product );
	?>
	<tr>
        <td style="text-align:left; border: none; padding-top:10px;"><?php echo $item['qty'] ;?></td>
		<td style="text-align:left; border: none; padding-top:10px; word-wrap:break-word;">
            <?php

                $_tax = new WC_Tax();//looking for appropriate vat for specific product
                $rates = $_tax->get_rates($_product->get_tax_class());
                $rates = array_shift($rates);
                if (isset($rates['rate'])) {	//vat found
                    if ($rates['rate'] == 0) {	//if 0% vat
                        $tax_rate = 'N/G';
                    } else {
                        $tax_rate = round($rates['rate'], 2) . '%';
                    }
                } else {//FailSafe: just in case ;-)
                    //Calculate tax rate value
                    $itemQty = (is_numeric($item['qty'])&&(intval($item['qty'])>0)) ? intval($item['qty']) : 1;
                    $tax_rate = round($order->get_line_tax( $item ) / $order->get_item_total( $item ) * 100 / $itemQty, 2);
                    $tax_rate = ($tax_rate > 0) ? $tax_rate . '%' : 'N/G';
                }

                // Product name
                echo apply_filters( 'woocommerce_order_item_name', $item['name'], $item );
                // SKU
                if ( $show_sku && is_object( $_product ) && $_product->get_sku() ) {
                    echo ' (#' . $_product->get_sku() . ')';
                }

                // File URLs
                if ( $show_download_links && is_object( $_product ) && $_product->exists() && $_product->is_downloadable() ) {
                    $download_files = $order->get_item_downloads( $item );
                    $i              = 0;
                    foreach ( $download_files as $download_id => $file ) {
                        $i++;
                        if ( count( $download_files ) > 1 ) {
                            $prefix = sprintf( __( 'Download %d', 'woocommerce' ), $i );
                        } elseif ( $i == 1 ) {
                            $prefix = __( 'Download', 'storefront' );
                        }
                        echo '<br/><small>' . $prefix . ': <a href="' . esc_url( $file['download_url'] ) . '" target="_blank">' . esc_html( $file['name'] ) . '</a></small>';
                    }
                }
                // Variation
                if ( $item_meta->meta ) {
                    echo '<br/><small>' . nl2br( $item_meta->display( true, true ) ) . '</small>';
                }
		    ?>
        </td>
		<td style="text-align:left; border: none; padding-top:10px;"><nobr><?php echo $order->get_item_total( $item ); ?>&nbsp;</nobr></td>
        <td style="text-align:left; border: none; padding-top:10px;"><nobr><?php echo $tax_rate; ?>&nbsp;</nobr></td>
        <td style="text-align:left; border: none; padding-top:10px;"><nobr><?php echo wc_price($order->get_line_tax( $item ), array('currency' => $order->get_order_currency())); ?>&nbsp;</nobr></td>
        <td style="text-align:left; border: none; padding-top:10px;"><nobr><?php echo $order->get_formatted_line_subtotal( $item ); ?>&nbsp;</nobr></td>
	</tr>

	<?php if ( $show_purchase_note && is_object( $_product ) && $purchase_note = get_post_meta( $_product->id, '_purchase_note', true ) ) : ?>
		<tr>
			<td colspan="3" style="text-align:left; vertical-align:middle; border: 1px solid #eee;"><?php echo apply_filters( 'the_content', $purchase_note ); ?></td>
		</tr>
	<?php endif; ?>

<?php endforeach; ?>
